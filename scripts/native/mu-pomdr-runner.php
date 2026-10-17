<?php
/**
 * Plugin Name: POMDR Deploy Runner (temporary)
 * Description: Fallback executor for the deploy scripts on hosts WITHOUT wp-cli. Copy this file to wp-content/mu-plugins/, define the two constants in wp-config.php, run the scripts from the browser as an admin, then DELETE this file. It refuses to run without the constants, outside the whitelisted directory, or for anyone lacking manage_options + unfiltered_html.
 *
 * wp-config.php additions (remove after the deploy window):
 *   define( 'POMDR_RUNNER_TOKEN', '<long random string>' );
 *   define( 'POMDR_RUNNER_DIR', WP_CONTENT_DIR . '/pomdr-deploy' );
 *
 * Upload the scripts (seed-divi-options.php, port.php, cutover.php, ...)
 * into that directory, then visit as a logged-in administrator:
 *   /wp-admin/?pomdr_run=seed-divi-options.php&pomdr_token=<token>
 *   /wp-admin/?pomdr_run=cutover.php&pomdr_args=culture&pomdr_token=<token>
 *
 * This is the fallback path for DEPLOY-RUNBOOK.md steps 6 to 9 when the
 * host toolset has no wp-cli (council round 3, correction 3). The scripts
 * themselves are identical either way; $args is populated from pomdr_args.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', function () {
	if ( ! isset( $_GET['pomdr_run'] ) ) {
		return;
	}

	if ( ! defined( 'POMDR_RUNNER_TOKEN' ) || ! defined( 'POMDR_RUNNER_DIR' ) ) {
		wp_die( 'POMDR runner: constants not defined in wp-config.php.' );
	}
	if ( ! isset( $_GET['pomdr_token'] ) || ! hash_equals( POMDR_RUNNER_TOKEN, (string) $_GET['pomdr_token'] ) ) {
		wp_die( 'POMDR runner: bad token.' );
	}
	if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'unfiltered_html' ) ) {
		wp_die( 'POMDR runner: this account lacks manage_options + unfiltered_html.' );
	}

	$name = basename( (string) $_GET['pomdr_run'] ); // strip any path tricks
	$file = trailingslashit( POMDR_RUNNER_DIR ) . $name;
	$real = realpath( $file );
	if ( ! $real || 0 !== strpos( $real, (string) realpath( POMDR_RUNNER_DIR ) ) || '.php' !== substr( $real, -4 ) ) {
		wp_die( 'POMDR runner: script not found in the whitelisted directory.' );
	}

	// The deploy scripts read wp-cli style positional args from $args.
	$args = array();
	if ( isset( $_GET['pomdr_args'] ) ) {
		$args = array_map( 'sanitize_text_field', explode( ',', (string) $_GET['pomdr_args'] ) );
	}

	header( 'Content-Type: text/plain; charset=utf-8' );
	require $real;
	exit;
} );
