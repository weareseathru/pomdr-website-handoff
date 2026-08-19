<?php
/**
 * Native-Divi cutover gate.
 *
 * A page cuts over from its sidecar template (page-{slug}.php) to native
 * Divi rendering when it carries the dedicated meta `_pomdr_native = on`,
 * written only by the conversion cutover script. While no page carries the
 * meta this filter changes nothing.
 *
 * The gate must NOT key on Divi's own `_et_pb_use_divi_5`: 27 of the 40
 * sidecar-covered page records still carry that meta with stale base-build
 * Divi content (verified 2026-08-18), and keying on it would cut them all
 * over to stale content at once. See docs/DEPLOY-RUNBOOK.md section 1.
 *
 * Rollback per page: restore the pre-conversion dump (clears the meta) and
 * the sidecar template resumes on the next request.
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'template_include', function ( $template ) {
	if ( ! is_page() ) {
		return $template;
	}

	if ( get_post_meta( get_queried_object_id(), '_pomdr_native', true ) !== 'on' ) {
		return $template;
	}

	// Only bypass the child theme's page-{slug}.php sidecars. Anything else
	// (parent templates, the generic hierarchy) is already the native path.
	// DELIBERATE exclusions (council round 3): front-page.php and
	// single-pets.php never match this prefix, so the home page and dog
	// pages CANNOT cut over through this gate; they keep their sidecars
	// until their own dedicated cutover plans (runbook section 7).
	if ( 0 !== strpos( basename( $template ), 'page-' ) ) {
		return $template;
	}

	$native = get_stylesheet_directory() . '/page-native.php';

	return file_exists( $native ) ? $native : $template;
}, 99 );

/**
 * Staging pages (native-staging-{slug}) have no sidecar template and no
 * _pomdr_native meta, so WordPress falls through to the PARENT page.php,
 * which lacks the <main> landmark. Route every builder-built page without
 * a sidecar match through page-native.php as well.
 */
add_filter( 'template_include', function ( $template ) {
	if ( ! is_page() ) {
		return $template;
	}
	if ( basename( $template ) !== 'page.php' ) {
		return $template;
	}
	if ( 'on' !== get_post_meta( get_queried_object_id(), '_et_pb_use_divi_5', true ) ) {
		return $template;
	}
	$native = get_stylesheet_directory() . '/page-native.php';
	return file_exists( $native ) ? $native : $template;
}, 100 );
