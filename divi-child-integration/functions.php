<?php
/**
 * POMDR 2026 Divi Child Theme - functions.php
 *
 * Entry point. Loads sub-files from inc/ to keep this file short.
 * All heavy lifting (ACF fields, redirects, enqueue) lives in inc/.
 */

defined( 'ABSPATH' ) || exit;

// Enqueue POMDR styles and scripts on top of Divi.
require_once get_stylesheet_directory() . '/inc/enqueue.php';

// ACF Pro field group definitions for all CPTs.
require_once get_stylesheet_directory() . '/inc/acf-fields.php';

// Legacy URL redirect handler (/dog.php?id=N -> /pets/{id}/).
require_once get_stylesheet_directory() . '/inc/redirects.php';

/**
 * Confirm parent Divi theme is active.
 * If someone accidentally activates the child without Divi, show a clear error.
 */
add_action( 'after_setup_theme', function () {
    if ( 'Divi' !== wp_get_theme()->parent()->get( 'Name' ) ) {
        wp_die(
            'POMDR 2026 requires Divi as the parent theme. Please activate Divi first.',
            'Theme dependency error'
        );
    }
} );
