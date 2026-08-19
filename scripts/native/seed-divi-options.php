<?php
/**
 * Seed every Divi-level design option the native pages depend on. This is
 * the DEPLOYABLE form of the Phase 1 token work plus the default
 * neutralization: run it on any environment (local, rehearsal, target) and
 * it converges that site's Divi options to the design system. Idempotent;
 * verifies by read-back; never exports or imports the whole options blob.
 *
 * Covers:
 *  1. Global colors (gcid-pomdr-*) + the four Customizer-routed ids.
 *  2. Design Variables (gvid-pomdr-* fonts + numbers), each item carrying
 *     its own `id` field, which Divi's Style.php requires at render time.
 *  3. Customizer neutralization: content_width matches the design container
 *     (1320px), button hover icon off.
 *
 * Run: wp eval-file scripts/native/seed-divi-options.php --user=<admin>
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

use ET\Builder\Packages\GlobalData\GlobalData;

if ( ! class_exists( 'ET\\Builder\\Packages\\GlobalData\\GlobalData' ) ) {
	echo "FATAL: GlobalData not loaded\n";
	exit( 1 );
}
if ( ! current_user_can( 'edit_theme_options' ) ) {
	echo "FATAL: user lacks edit_theme_options (set_global_variables silently no-ops)\n";
	exit( 1 );
}

$fail = 0;
$check = function ( $label, $ok ) use ( &$fail ) {
	echo ( $ok ? 'OK   ' : 'FAIL ' ) . $label . "\n";
	if ( ! $ok ) { $fail++; }
};

$now = gmdate( 'Y-m-d\TH:i:s.000\Z' );

/* ---- 1. Global colors --------------------------------------------------- */

$brand_colors = array(
	'gcid-primary-color'   => '#008bb0',
	'gcid-secondary-color' => '#632F88',
	'gcid-heading-color'   => '#16202b',
	'gcid-body-color'      => '#16202b',
	'gcid-pomdr-blue'      => '#008bb0',
	'gcid-pomdr-blue-700'  => '#006c8a',
	'gcid-pomdr-blue-900'  => '#004e63',
	'gcid-pomdr-blue-50'   => '#E8F2F6',
	'gcid-pomdr-blue-100'  => '#BCD7E4',
	'gcid-pomdr-purple'    => '#632F88',
	'gcid-pomdr-purple-50' => '#F1EBF5',
	'gcid-pomdr-ink'       => '#16202b',
	'gcid-pomdr-ink-2'     => '#3c4a57',
	'gcid-pomdr-white'     => '#ffffff',
	'gcid-pomdr-line'      => '#cfe0e9',
);

$colors = GlobalData::get_global_colors();
foreach ( $brand_colors as $id => $hex ) {
	$prev          = isset( $colors[ $id ] ) && is_array( $colors[ $id ] ) ? $colors[ $id ] : array();
	$colors[ $id ] = array_merge(
		array( 'status' => 'active', 'usedInPosts' => array() ),
		$prev,
		array( 'color' => $hex, 'lastUpdated' => $now )
	);
}
GlobalData::set_global_colors( $colors );

$colors_after = GlobalData::get_global_colors();
foreach ( $brand_colors as $id => $hex ) {
	if ( isset( GlobalData::$customizer_colors[ $id ] ) ) {
		$opt = GlobalData::$customizer_colors[ $id ]['option_name'];
		$check( "color $id -> option $opt", strtolower( (string) et_get_option( $opt ) ) === strtolower( $hex ) );
	} else {
		$check( "color $id", strtolower( $colors_after[ $id ]['color'] ?? '' ) === strtolower( $hex ) );
	}
}

/* ---- 2. Design Variables (each item MUST carry its own id) -------------- */

$brand_variables = array(
	'fonts'   => array(
		'gvid-pomdr-font-sans'  => array( 'label' => 'POMDR Sans (Source Sans 3)', 'value' => 'Source Sans 3' ),
		'gvid-pomdr-font-serif' => array( 'label' => 'POMDR Serif (Source Serif 4)', 'value' => 'Source Serif 4' ),
	),
	'numbers' => array(
		'gvid-pomdr-section-pad'   => array( 'label' => 'Section padding', 'value' => '80px' ),
		'gvid-pomdr-section-pad-m' => array( 'label' => 'Section padding (mobile)', 'value' => '56px' ),
		'gvid-pomdr-fs-base'       => array( 'label' => 'Body text size', 'value' => '24px' ),
		'gvid-pomdr-fs-lead'       => array( 'label' => 'Lead text size', 'value' => '26px' ),
		'gvid-pomdr-fs-small'      => array( 'label' => 'Small text size', 'value' => '20px' ),
		'gvid-pomdr-radius'        => array( 'label' => 'Card radius', 'value' => '22px' ),
		'gvid-pomdr-radius-lg'     => array( 'label' => 'Large radius', 'value' => '32px' ),
	),
);

$raw = maybe_unserialize( et_get_option( 'global_variables', array(), '', true, false, '', '', true ) );
$raw = json_decode( wp_json_encode( $raw ), true );
if ( ! is_array( $raw ) ) { $raw = array(); }

$order = 0;
foreach ( $brand_variables as $bucket => $items ) {
	if ( ! isset( $raw[ $bucket ] ) || ! is_array( $raw[ $bucket ] ) ) {
		$raw[ $bucket ] = array();
	}
	$order = 0;
	foreach ( $items as $id => $item ) {
		$order++;
		$raw[ $bucket ][ $id ] = array(
			'id'     => $id,
			'label'  => $item['label'],
			'value'  => $item['value'],
			'order'  => $order,
			'status' => 'active',
		);
	}
}
GlobalData::set_global_variables( $raw );

$after = maybe_unserialize( et_get_option( 'global_variables', array(), '', true, false, '', '', true ) );
$after = json_decode( wp_json_encode( $after ), true );
foreach ( $brand_variables as $bucket => $items ) {
	foreach ( $items as $id => $item ) {
		$got = $after[ $bucket ][ $id ] ?? array();
		$check( "variable $bucket/$id (value + id field)", ( $got['value'] ?? '' ) === $item['value'] && ( $got['id'] ?? '' ) === $id );
	}
}

/* ---- 3. Customizer neutralization --------------------------------------- */

et_update_option( 'content_width', '1320' );
$check( 'content_width = 1320 (design container)', '1320' === (string) et_get_option( 'content_width' ) );

et_update_option( 'all_buttons_icon', 'no' );
$check( 'all_buttons_icon = no', 'no' === (string) et_get_option( 'all_buttons_icon' ) );

/* Static CSS caches must regenerate after option changes. */
if ( class_exists( 'ET_Core_PageResource' ) ) {
	ET_Core_PageResource::remove_static_resources( 'all', 'all', true );
	echo "static CSS caches purged\n";
}

echo $fail === 0 ? "ALL OPTION SEEDS VERIFIED\n" : "$fail FAILURES\n";
exit( $fail === 0 ? 0 : 1 );
