<?php
/**
 * Native-Divi page generator (Phase 2).
 *
 * Stages a page's native Divi version on a DRAFT page named
 * native-staging-{slug}, converts it to Divi 5 through Divi's own pipeline,
 * and runs the static validator checks. The live page record and the
 * sidecar template are untouched; cutover is a separate, later step
 * (scripts/native/cutover.php) once the full validator passes.
 *
 * Usage:
 *   wp eval-file scripts/native/generate.php <slug> --user=<admin>
 * The user MUST have unfiltered_html (kses otherwise silently strips all
 * D5 block markup; proven in G1).
 *
 * Artifacts land in scripts/native/backups/: the emitted D4 source, the
 * converted D5 content, and the staging page id, per slug per run.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$pom_args = isset( $args ) && is_array( $args ) ? $args : array();
$slug     = $pom_args[0] ?? '';

$models = array(
	'culture' => 'pom_native_culture',
);

require_once __DIR__ . '/emit.php';
require_once __DIR__ . '/model-culture.php';

if ( ! isset( $models[ $slug ] ) ) {
	echo "Unknown slug '$slug'. Known: " . implode( ', ', array_keys( $models ) ) . "\n";
	exit( 1 );
}

if ( ! current_user_can( 'unfiltered_html' ) ) {
	echo "FATAL: current user lacks unfiltered_html; conversion would be silently destroyed by kses.\n";
	exit( 1 );
}

/* Load Divi's conversion machinery (admin-only includes under wp-cli). */
if ( ! class_exists( 'Divi\\D5_Readiness\\Server\\Conversion' ) ) {
	if ( ! class_exists( 'ET_D5_Readiness' ) ) {
		require_once get_template_directory() . '/d5-readiness/d5-readiness.php';
	}
	ET_D5_Readiness::includes();
}
\ET\Builder\Packages\Conversion\Conversion::initialize_shortcode_framework();

/* 1. Emit D4 from the content model. */
$d4 = call_user_func( $models[ $slug ] );
echo 'd4_len=' . strlen( $d4 ) . "\n";

$backup_dir = __DIR__ . '/backups';
if ( ! is_dir( $backup_dir ) ) {
	mkdir( $backup_dir, 0755, true );
}
$stamp = gmdate( 'Ymd-His' );
file_put_contents( "$backup_dir/$slug-$stamp-d4.txt", $d4 );

/* 2. Create or reuse the staging draft. */
$staging_slug = "native-staging-$slug";
$existing     = get_page_by_path( $staging_slug, OBJECT, 'page' );

if ( $existing ) {
	$post_id = $existing->ID;
	// Preserve publish state (a re-draft makes anonymous fidelity captures 404).
	wp_update_post( array( 'ID' => $post_id, 'post_content' => $d4, 'post_status' => $existing->post_status ) );
	delete_post_meta( $post_id, '_et_pb_use_divi_5' );
} else {
	$post_id = wp_insert_post( array(
		'post_title'   => "Native staging: $slug",
		'post_name'    => $staging_slug,
		'post_status'  => 'draft',
		'post_type'    => 'page',
		'post_content' => $d4,
	) );
}
if ( is_wp_error( $post_id ) || ! $post_id ) {
	echo "FATAL: staging page create failed\n";
	exit( 1 );
}
update_post_meta( $post_id, '_et_pb_use_builder', 'on' );
update_post_meta( $post_id, '_et_pb_show_page_creation', 'off' );
echo "staging_post_id=$post_id\n";

/* 3. Convert through Divi's own pipeline. */
Divi\D5_Readiness\Server\Conversion::convert_single_post( $post_id );

clean_post_cache( $post_id );
$saved = get_post( $post_id )->post_content;
file_put_contents( "$backup_dir/$slug-$stamp-d5.txt", $saved );

/* 4. Static validator checks (the browser checks run separately). */
$fail = 0;

$check = function ( $label, $ok ) use ( &$fail ) {
	echo ( $ok ? 'OK   ' : 'FAIL ' ) . $label . "\n";
	if ( ! $ok ) { $fail++; }
};

$check( 'placeholder wrapper present', false !== strpos( $saved, 'wp:divi/placeholder' ) );
$check( 'zero divi/shortcode-module fallbacks', 0 === substr_count( $saved, 'divi/shortcode-module' ) );
$check( 'builder meta _et_pb_use_divi_5=on', 'on' === get_post_meta( $post_id, '_et_pb_use_divi_5', true ) );
$check( 'D4 rollback meta stored', (bool) get_post_meta( $post_id, '_et_pb_divi_4_content', true ) );

$blocks = parse_blocks( $saved );
$check( 'parse round-trip byte-stable', serialize_blocks( $blocks ) === $saved );

/* Expected module census from the emitted D4 (sections/rows/buttons/text). */
foreach ( array(
	'et_pb_section' => 'divi/section',
	'et_pb_row'     => 'divi/row',
	'et_pb_text'    => 'divi/text',
	'et_pb_button'  => 'divi/button',
	'et_pb_image'   => 'divi/image',
) as $d4_tag => $d5_block ) {
	$want = substr_count( $d4, '[' . $d4_tag . ' ' ) + substr_count( $d4, '[' . $d4_tag . ']' );
	// Openers only: '<!-- wp:divi/x ' or '<!-- wp:divi/x -->'; closers start
	// with '<!-- /wp:' and must not count.
	$got = substr_count( $saved, '<!-- wp:' . $d5_block . ' ' ) + substr_count( $saved, '<!-- wp:' . $d5_block . ' -->' );
	$check( "module census $d4_tag ($want) -> $d5_block ($got)", $want === $got );
}

/* Design-system class census: every module_class we emitted must survive. */
preg_match_all( '/module_class="([^"]+)"/', $d4, $m );
$emitted_classes = array_unique( array_filter( array_map( 'trim', preg_split( '/\s+/', implode( ' ', $m[1] ) ) ) ) );
$missing = array();
foreach ( $emitted_classes as $cls ) {
	// Token-boundary match: 'btn' must not be satisfied by 'btn-purple',
	// 'section' not by 'section-cream' (council round 3, correction 6).
	if ( ! preg_match( '/[^A-Za-z0-9_-]' . preg_quote( $cls, '/' ) . '[^A-Za-z0-9_-]/', ' ' . $saved . ' ' ) ) {
		$missing[] = $cls;
	}
}
$check( 'class census (' . count( $emitted_classes ) . ' classes, missing: ' . ( $missing ? implode( ',', $missing ) : 'none' ) . ')', empty( $missing ) );

/* No absolute local URLs baked into the content. */
$bad_hosts = 0;
foreach ( array( 'newpomdr-local.local', 'pomdrsite.local' ) as $host ) {
	$bad_hosts += substr_count( $saved, $host );
}
$check( 'zero local-host references', 0 === $bad_hosts );

echo "artifacts: backups/$slug-$stamp-d4.txt, backups/$slug-$stamp-d5.txt\n";
echo $fail === 0 ? "STATIC CHECKS PASSED for $slug\n" : "$fail STATIC FAILURES for $slug\n";
exit( $fail === 0 ? 0 : 1 );
