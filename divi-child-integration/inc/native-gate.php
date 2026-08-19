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

	$divi_page = get_template_directory() . '/page.php';

	return file_exists( $divi_page ) ? $divi_page : $template;
}, 99 );
