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

    // Accessibility layer (always-on baseline + opt-in senior mode). Loaded
    // after pomdr-design so it reads its tokens and wins on the cascade.
    wp_enqueue_style(
        'pomdr-a11y',
        get_stylesheet_directory_uri() . '/assets/css/a11y.css',
        array( 'pomdr-design' ),
        filemtime( get_stylesheet_directory() . '/assets/css/a11y.css' )
    );

    // Accessibility behavior: floating toggle + single scroll-reveal observer.
    // All pages, footer, deferred. Core content works without it.
    wp_enqueue_script(
        'pomdr-a11y',
        get_stylesheet_directory_uri() . '/assets/js/a11y.js',
        array(),
        filemtime( get_stylesheet_directory() . '/assets/js/a11y.js' ),
        true
    );

    // Homepage redesign layout (scoped under .pomdr-home). Front page only.
    if ( is_front_page() || is_page( 'home' ) ) {
        wp_enqueue_style(
            'pomdr-home',
            get_stylesheet_directory_uri() . '/assets/css/pomdr-home.css',
            array( 'pomdr-design' ),
            filemtime( get_stylesheet_directory() . '/assets/css/pomdr-home.css' )
        );
        wp_enqueue_script(
            'pomdr-home',
            get_stylesheet_directory_uri() . '/assets/js/pomdr-home.js',
            array(),
            filemtime( get_stylesheet_directory() . '/assets/js/pomdr-home.js' ),
            true
        );
    }

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
    // adoptFormBase uses home_url() so it tracks the current environment instead
    // of a hardcoded staging host. NOTE: the adoption-inquiry prefill mechanism
    // still needs reconciling end to end (this internal /adoption-questionnaire/
    // path vs the confirmed LGL iframe form `utzjcNEZaqAcJk3QURlQmw` prefilled by
    // `field_21`). See docs/RISK-REGISTER.md item B1 before relying on prefill.
    wp_localize_script( 'pomdr-nav', 'POMDR', array(
        'siteUrl'       => home_url(),
        'adoptFormBase' => home_url( '/adoption-questionnaire/' ),
        'phone'         => '(831) 718-9122',
        'email'         => 'info@peaceofminddogrescue.org',
    ) );

}, 20 ); // priority 20 = after Divi's own enqueue at 10
