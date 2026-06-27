<?php
/**
 * Enqueue POMDR design system styles and scripts.
 * Loads after Divi's stylesheet so our tokens and overrides win.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {

    // Design tokens (full set, mirrors the prototype tokens.css). First, so
    // every later stylesheet resolves its custom properties.
    wp_enqueue_style(
        'pomdr-tokens',
        get_stylesheet_directory_uri() . '/assets/css/tokens.css',
        array( 'divi-style' ),
        filemtime( get_stylesheet_directory() . '/assets/css/tokens.css' )
    );

    // Design system CSS (tokens + components). Loaded after parent Divi stylesheet.
    wp_enqueue_style(
        'pomdr-design',
        get_stylesheet_directory_uri() . '/assets/css/pomdr-design.css',
        array( 'pomdr-tokens' ),
        filemtime( get_stylesheet_directory() . '/assets/css/pomdr-design.css' )
    );

    // Full shared component layer (the prototype pomdr.css): page-header,
    // sections, buttons, step rows, cta strips, cards. Gives every custom page
    // template the exact prototype look. Loaded after the design layer.
    wp_enqueue_style(
        'pomdr-shared',
        get_stylesheet_directory_uri() . '/assets/css/pomdr.css',
        array( 'pomdr-design' ),
        filemtime( get_stylesheet_directory() . '/assets/css/pomdr.css' )
    );

    // Site chrome (action bar, two-row nav, tagline, logo, mobile drawer).
    // Loaded after pomdr-design; hides Divi's Theme Builder header.
    wp_enqueue_style(
        'pomdr-chrome',
        get_stylesheet_directory_uri() . '/assets/css/pomdr-chrome.css',
        array( 'pomdr-shared' ),
        filemtime( get_stylesheet_directory() . '/assets/css/pomdr-chrome.css' )
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

    // Homepage redesign layout + animations. Front page only.
    if ( is_front_page() || is_page( 'home' ) ) {
        $dir = get_stylesheet_directory();
        $uri = get_stylesheet_directory_uri();
        wp_enqueue_style(
            'pomdr-home',
            $uri . '/assets/css/pomdr-home.css',
            array( 'pomdr-design', 'pomdr-chrome' ),
            filemtime( $dir . '/assets/css/pomdr-home.css' )
        );
        wp_enqueue_style(
            'pomdr-paw-trail',
            $uri . '/assets/css/paw-trail.css',
            array( 'pomdr-home' ),
            filemtime( $dir . '/assets/css/paw-trail.css' )
        );
        // GSAP + ScrollTrigger power the scroll reveals and the paw trail.
        wp_enqueue_script( 'gsap', $uri . '/assets/vendor/gsap.min.js', array(), '3', true );
        wp_enqueue_script( 'gsap-scrolltrigger', $uri . '/assets/vendor/ScrollTrigger.min.js', array( 'gsap' ), '3', true );
        wp_enqueue_script(
            'pomdr-paw-trail',
            $uri . '/assets/js/paw-trail.js',
            array( 'gsap', 'gsap-scrolltrigger' ),
            filemtime( $dir . '/assets/js/paw-trail.js' ),
            true
        );
        wp_enqueue_script(
            'pomdr-home',
            $uri . '/assets/js/pomdr-home.js',
            array( 'gsap', 'gsap-scrolltrigger' ),
            filemtime( $dir . '/assets/js/pomdr-home.js' ),
            true
        );
    }

    // Adopt page search, filter, and sort over the server-rendered CPT grid.
    // Adopt page only; progressive enhancement, deferred to the footer.
    if ( is_page( 'adopt' ) ) {
        wp_enqueue_script(
            'pomdr-adopt-filter',
            get_stylesheet_directory_uri() . '/assets/js/adopt-filter.js',
            array(),
            filemtime( get_stylesheet_directory() . '/assets/js/adopt-filter.js' ),
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
        'email'         => 'info@pomdr.org',
    ) );

}, 20 ); // priority 20 = after Divi's own enqueue at 10
