<?php
/**
 * D4 shortcode emitters for the native-Divi generator.
 *
 * We emit simple Divi 4 shortcodes and let Divi's own converter
 * (convert_single_post) author the Divi 5 blocks; we never hand-write D5
 * JSON. Styling rides on the design-system classes (module_class) that the
 * shared stylesheet already styles; named global presets layer on in a
 * later pass. Link URLs are root-relative; theme-asset image URLs are
 * root-relative too, so they survive the move between hosts untouched.
 *
 * Dev tooling only (scripts/, not theme code). Loaded by generate.php.
 */

defined( 'ABSPATH' ) || exit;

/** Escape a value for use inside a D4 shortcode attribute. */
function pom_d4_attr( $value ) {
	// Divi stores attrs with double quotes; %22 style escaping is not used
	// in hand-authored simple attrs, so forbid double quotes outright.
	return str_replace( '"', '&quot;', (string) $value );
}

/** Build an attribute string from an array, skipping empty values. */
function pom_d4_attrs( array $attrs ) {
	$out = '';
	foreach ( $attrs as $k => $v ) {
		if ( '' === $v || null === $v ) {
			continue;
		}
		$out .= ' ' . $k . '="' . pom_d4_attr( $v ) . '"';
	}
	return $out;
}

function pom_d4_section( array $attrs, $inner ) {
	$attrs = array_merge( array( 'fb_built' => '1' ), $attrs );
	return '[et_pb_section' . pom_d4_attrs( $attrs ) . ']' . $inner . '[/et_pb_section]';
}

function pom_d4_row( array $attrs, $inner ) {
	return '[et_pb_row' . pom_d4_attrs( $attrs ) . ']' . $inner . '[/et_pb_row]';
}

function pom_d4_column( $type, array $attrs, $inner ) {
	$attrs = array_merge( array( 'type' => $type ), $attrs );
	return '[et_pb_column' . pom_d4_attrs( $attrs ) . ']' . $inner . '[/et_pb_column]';
}

function pom_d4_text( array $attrs, $html ) {
	return '[et_pb_text' . pom_d4_attrs( $attrs ) . ']' . $html . '[/et_pb_text]';
}

function pom_d4_button( $text, $url, array $attrs = array() ) {
	$attrs = array_merge( array( 'button_text' => $text, 'button_url' => $url ), $attrs );
	return '[et_pb_button' . pom_d4_attrs( $attrs ) . '][/et_pb_button]';
}

function pom_d4_image( $src, $alt, array $attrs = array() ) {
	$attrs = array_merge( array( 'src' => $src, 'alt' => $alt ), $attrs );
	return '[et_pb_image' . pom_d4_attrs( $attrs ) . '][/et_pb_image]';
}

function pom_d4_code( array $attrs, $raw ) {
	return '[et_pb_code' . pom_d4_attrs( $attrs ) . ']' . $raw . '[/et_pb_code]';
}

/** Root-relative URL for a child-theme asset (portable across hosts). */
function pom_theme_asset( $rel ) {
	$uri  = get_stylesheet_directory_uri() . '/' . ltrim( $rel, '/' );
	$path = wp_parse_url( $uri, PHP_URL_PATH );
	return $path ? $path : $uri;
}

/**
 * The shared sub-page header pattern (h1 + serif narrative + lead, optional
 * CTA buttons, optional split media image). One emitter for every page.
 */
function pom_emit_page_header( array $m ) {
	$text_html = '<h1 class="page-headline">' . $m['headline'] . '</h1>';
	if ( ! empty( $m['narrative'] ) ) {
		$text_html .= '<p class="page-narrative">' . $m['narrative'] . '</p>';
	}
	if ( ! empty( $m['lead'] ) ) {
		$text_html .= '<p class="page-lead">' . $m['lead'] . '</p>';
	}

	$left = pom_d4_text( array( 'admin_label' => 'Page title' ), $text_html );

	if ( ! empty( $m['ctas'] ) ) {
		$btns = '';
		foreach ( $m['ctas'] as $cta ) {
			$btns .= pom_d4_button( $cta['text'], $cta['url'], array(
				'module_class' => 'btn ' . $cta['class'],
				'admin_label'  => 'Button: ' . $cta['text'],
			) );
		}
		$left .= $btns;
	}

	if ( ! empty( $m['media'] ) ) {
		// Theme-asset hero images keep their <picture>/WebP markup (an image
		// module would drop the WebP source; semantic parity gate).
		$pic = '<picture>';
		if ( ! empty( $m['media']['webp'] ) ) {
			$pic .= '<source type="image/webp" srcset="' . $m['media']['webp'] . '">';
		}
		$pic  .= '<img src="' . $m['media']['src'] . '" alt="' . esc_attr( $m['media']['alt'] ) . '"></picture>';
		$right = pom_d4_text( array( 'admin_label' => 'Header photo' ), $pic );
		$inner = pom_d4_column( '1_2', array( 'module_class' => 'ph-text' ), $left )
			. pom_d4_column( '1_2', array( 'module_class' => 'ph-media' ), $right );
		$row   = pom_d4_row( array( 'module_class' => 'ph-split', 'admin_label' => 'Header split' ), $inner );
	} else {
		$row = pom_d4_row(
			array( 'admin_label' => 'Header' ),
			pom_d4_column( '4_4', array(), $left )
		);
	}

	return pom_d4_section(
		array(
			'module_class' => trim( 'page-header ' . ( $m['section_class'] ?? '' ) ),
			'admin_label'  => 'Page header',
		),
		$row
	);
}
