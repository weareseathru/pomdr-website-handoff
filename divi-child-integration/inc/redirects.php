<?php
/**
 * Legacy URL redirect handler.
 *
 * Handles two legacy patterns from the old peaceofminddogrescue.org PHP site:
 *   1. /dog.php?id=N  - redirects to /pets/{id}/
 *   2. /recources/    - redirects to /resources/ (misspelled URL in old nav)
 *
 * These run at init so they fire before any page content is generated.
 * The Redirection plugin handles all other URL rules (set those up via
 * WordPress Admin > Tools > Redirection using redirects.csv as the source).
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {

    // Pattern 1: /dog.php?id=N -> /pets/{id}/
    // The old peaceofminddogrescue.org site used integer IDs (e.g. dog.php?id=4896).
    // The WP site uses the same integer as the post ID on the pets CPT.
    if (
        isset( $_SERVER['REQUEST_URI'] ) &&
        strpos( $_SERVER['REQUEST_URI'], 'dog.php' ) !== false &&
        isset( $_GET['id'] ) &&
        is_numeric( $_GET['id'] )
    ) {
        $id  = absint( $_GET['id'] );
        $url = home_url( '/pets/' . $id . '/' );
        wp_redirect( $url, 301 );
        exit;
    }

    // Pattern 2: /recources/ -> /resources/
    // The old staging nav had a typo. Preserve as a 301 source.
    if (
        isset( $_SERVER['REQUEST_URI'] ) &&
        strpos( $_SERVER['REQUEST_URI'], '/recources' ) === 0
    ) {
        $url = home_url( str_replace( '/recources', '/resources', $_SERVER['REQUEST_URI'] ) );
        wp_redirect( $url, 301 );
        exit;
    }

} );
