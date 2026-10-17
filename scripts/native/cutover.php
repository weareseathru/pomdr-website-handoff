<?php
/**
 * Same-record cutover: copies a validated native staging page onto the
 * LIVE page record and arms the _pomdr_native gate. The only script that
 * ever writes to live page records.
 *
 * There is no real transaction in WordPress, so the honest mechanism is:
 * dump, write, read-back verify, AUTO-RESTORE on any mismatch. The dump
 * (post_content + every _et_pb_* meta + _pomdr_native) is written to
 * scripts/native/backups/cutover-{slug}-{stamp}.json BEFORE anything
 * changes, and doubles as the manual rollback artifact.
 *
 * Usage:
 *   wp eval-file scripts/native/cutover.php <slug>          --user=<admin>
 *   wp eval-file scripts/native/cutover.php restore <slug>  --user=<admin>
 *
 * Rollback restores content + metas and clears _pomdr_native, so the
 * sidecar template resumes on the next request.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$pom_args = isset( $args ) && is_array( $args ) ? array_values( $args ) : array();
$mode     = 'cutover';
if ( isset( $pom_args[0] ) && 'restore' === $pom_args[0] ) {
	$mode = 'restore';
	array_shift( $pom_args );
}
$slug = $pom_args[0] ?? '';
if ( '' === $slug ) {
	echo "usage: cutover.php [restore] <slug>\n";
	exit( 1 );
}
if ( ! current_user_can( 'unfiltered_html' ) ) {
	echo "FATAL: current user lacks unfiltered_html\n";
	exit( 1 );
}

$backup_dir = __DIR__ . '/backups';
if ( ! is_dir( $backup_dir ) ) { mkdir( $backup_dir, 0755, true ); }

$live = get_page_by_path( $slug, OBJECT, 'page' );
if ( ! $live ) {
	// Nested pages (about/culture) miss the flat path; find by name.
	$found = get_posts( array( 'name' => $slug, 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1 ) );
	$live  = $found ? $found[0] : null;
}
if ( ! $live ) {
	echo "FATAL: no live page for slug '$slug'\n";
	exit( 1 );
}

/* Snapshot helper: content + all builder-relevant metas. */
$snapshot = function ( $post_id ) {
	$metas = array();
	foreach ( get_post_meta( $post_id ) as $key => $values ) {
		if ( 0 === strpos( $key, '_et_pb_' ) || '_pomdr_native' === $key ) {
			$metas[ $key ] = $values[0];
		}
	}
	return array(
		'post_id'      => $post_id,
		'post_content' => get_post( $post_id )->post_content,
		'metas'        => $metas,
		'taken'        => gmdate( 'c' ),
	);
};

$restore_from = function ( array $snap ) {
	wp_update_post( array( 'ID' => $snap['post_id'], 'post_content' => wp_slash( $snap['post_content'] ) ) );
	// Remove builder metas not present in the snapshot, restore the rest.
	foreach ( get_post_meta( $snap['post_id'] ) as $key => $v ) {
		if ( ( 0 === strpos( $key, '_et_pb_' ) || '_pomdr_native' === $key ) && ! isset( $snap['metas'][ $key ] ) ) {
			delete_post_meta( $snap['post_id'], $key );
		}
	}
	foreach ( $snap['metas'] as $key => $value ) {
		update_post_meta( $snap['post_id'], $key, maybe_unserialize( $value ) );
	}
	clean_post_cache( $snap['post_id'] );
};

if ( 'restore' === $mode ) {
	$dumps = glob( "$backup_dir/cutover-$slug-*.json" );
	if ( empty( $dumps ) ) {
		echo "FATAL: no cutover dump found for '$slug'\n";
		exit( 1 );
	}
	rsort( $dumps );
	$snap = json_decode( file_get_contents( $dumps[0] ), true );
	if ( ! $snap || (int) $snap['post_id'] !== (int) $live->ID ) {
		echo "FATAL: dump invalid or post id mismatch\n";
		exit( 1 );
	}
	$restore_from( $snap );
	$ok = get_post( $live->ID )->post_content === $snap['post_content']
		&& 'on' !== get_post_meta( $live->ID, '_pomdr_native', true );
	echo $ok ? "RESTORED $slug from " . basename( $dumps[0] ) . " (sidecar resumes)\n" : "RESTORE VERIFY FAILED, inspect manually\n";
	exit( $ok ? 0 : 1 );
}

/* ---- Cutover ---- */

$staging = get_page_by_path( "native-staging-$slug", OBJECT, 'page' );
if ( ! $staging ) {
	echo "FATAL: no staging page native-staging-$slug\n";
	exit( 1 );
}
$native_content = $staging->post_content;
if ( false === strpos( $native_content, 'wp:divi/placeholder' ) ) {
	echo "FATAL: staging content is not converted D5 (no placeholder)\n";
	exit( 1 );
}

/* 1. Dump the live record. */
$stamp = gmdate( 'Ymd-His' );
$snap  = $snapshot( $live->ID );
$file  = "$backup_dir/cutover-$slug-$stamp.json";
file_put_contents( $file, wp_json_encode( $snap ) );
if ( ! file_exists( $file ) || ! json_decode( file_get_contents( $file ), true ) ) {
	echo "FATAL: dump not written/readable, aborting before any write\n";
	exit( 1 );
}
echo 'dump: ' . basename( $file ) . "\n";

/* 2. Write content + builder metas + the gate meta. */
wp_update_post( array( 'ID' => $live->ID, 'post_content' => wp_slash( $native_content ) ) );
update_post_meta( $live->ID, '_et_pb_use_divi_5', 'on' );
update_post_meta( $live->ID, '_et_pb_use_builder', 'on' );
update_post_meta( $live->ID, '_et_pb_show_page_creation', 'off' );
update_post_meta( $live->ID, '_pomdr_native', 'on' );
clean_post_cache( $live->ID );

/* 3. Read back and verify; auto-restore on ANY mismatch. */
$saved = get_post( $live->ID )->post_content;
$ok    = ( $saved === $native_content )
	&& 'on' === get_post_meta( $live->ID, '_et_pb_use_divi_5', true )
	&& 'on' === get_post_meta( $live->ID, '_pomdr_native', true );

if ( ! $ok ) {
	echo "VERIFY FAILED: auto-restoring from dump\n";
	$restore_from( $snap );
	$back = get_post( $live->ID )->post_content === $snap['post_content'];
	echo $back ? "auto-restore verified, live page unchanged\n" : "AUTO-RESTORE VERIFY FAILED, restore manually from $file\n";
	exit( 1 );
}

if ( class_exists( 'ET_Core_PageResource' ) ) {
	ET_Core_PageResource::remove_static_resources( 'all', 'all', true );
}

echo "CUTOVER COMPLETE: /$slug/ now renders natively (gate armed). Validate at the REAL slug now; rollback: cutover.php restore $slug\n";
