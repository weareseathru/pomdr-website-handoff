<?php
/**
 * Enqueue POMDR design system styles and scripts.
 * Loads after Divi's stylesheet so our tokens and overrides win.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {

    // Design system CSS (tokens + components). Loaded after parent Divi stylesheet.
    wp_enqueue_style(
        'pomdr-design',
        get_stylesheet_directory_uri() . '/assets/css/pomdr-design.css',
        array( 'divi-style' ),
        filemtime( get_stylesheet_directory() . '/assets/css/pomdr-design.css' )
    );

    // Gallery lightbox - only on single pet pages.
    if ( is_singular( 'pets' ) ) {
        wp_enqueue_script(
            'pomdr-gallery',
            get_stylesheet_directory_uri() . '/assets/js/gallery.js',
            array(),
            filemtime( get_stylesheet_directory() . '/assets/js/gallery.js' ),
            true
        );
    }

    // Mobile nav supplement - all pages.
    wp_enqueue_script(
        'pomdr-nav',
        get_stylesheet_directory_uri() . '/assets/js/pomdr-nav.js',
        array(),
        filemtime( get_stylesheet_directory() . '/assets/js/pomdr-nav.js' ),
        true
    );

    // Pass site data to JS for dynamic features (dog name prefill on LGL forms, etc.).
    wp_localize_script( 'pomdr-nav', 'POMDR', array(
        'siteUrl'       => home_url(),
        'adoptFormBase' => 'https://new.pomdr.org/adoption-questionnaire/',
        'phone'         => '(831) 718-9122',
        'email'         => 'info@peaceofminddogrescue.org',
    ) );

}, 20 ); // priority 20 = after Divi's own enqueue at 10
