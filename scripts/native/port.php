<?php
/**
 * Auto-port: sidecar page -> native Divi staging page, 1:1 by construction.
 *
 * Fetches the page as the sidecar template renders it TODAY, takes each
 * top-level element inside <main> (header.page-header, section.*, ...) and
 * rebuilds it as a native Divi section whose row holds one text module
 * carrying the section's original inner markup. Because the markup and the
 * stylesheet are unchanged, the native page renders 1:1; the adapter block
 * in pomdr.css makes Divi's section/row wrappers behave as the container.
 *
 * Page-local <style> blocks are harvested into
 * divi-child-integration/assets/css/native-pages.css (versioned, enqueued
 * globally) so those styles travel with the theme instead of dying with
 * the template.
 *
 * Every staged page then goes through Divi's converter and the same static
 * gate as generate.php. Staging drafts only; cutover is separate.
 *
 * Usage: wp eval-file scripts/native/port.php <slug> [<slug> ...] --user=<admin>
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$pom_args = isset( $args ) && is_array( $args ) ? array_values( $args ) : array();
if ( empty( $pom_args ) ) {
	echo "usage: port.php <slug> [...]\n";
	exit( 1 );
}
if ( ! current_user_can( 'unfiltered_html' ) ) {
	echo "FATAL: user lacks unfiltered_html\n";
	exit( 1 );
}

require_once __DIR__ . '/emit.php';

if ( ! class_exists( 'Divi\\D5_Readiness\\Server\\Conversion' ) ) {
	if ( ! class_exists( 'ET_D5_Readiness' ) ) {
		require_once get_template_directory() . '/d5-readiness/d5-readiness.php';
	}
	ET_D5_Readiness::includes();
}
\ET\Builder\Packages\Conversion\Conversion::initialize_shortcode_framework();

/* Harvested page-local styles land in per-page raw files; the node script
   gen-pages-css.mjs compiles them into native-pages.css with a .pg-{slug}
   scope prefix. Unscoped harvesting once leaked one page's dark CTA band
   onto every page INCLUDING the reference standard (found 2026-08-19). */
$css_raw_dir = dirname( __FILE__ ) . '/fidelity/pages-css';
if ( ! is_dir( $css_raw_dir ) ) { mkdir( $css_raw_dir, 0755, true ); }

$backup_dir = __DIR__ . '/backups';
if ( ! is_dir( $backup_dir ) ) { mkdir( $backup_dir, 0755, true ); }

$home_hosts = array( home_url(), 'http://newpomdr-local.local', 'https://newpomdr-local.local' );

/**
 * Dynamic islands: elements in the captured HTML that must stay LIVE data,
 * replaced with the shortcode that renders them. Keyed by slug; 'find' is
 * a class or id the element carries; the whole element is swapped for the
 * shortcode text (shortcodes execute inside D5 text modules; verified).
 */
$islands = array(
	'foster-needs' => array(
		array( 'find' => 'dogs-grid', 'with' => '[foster_needed_dogs]' ),
	),
	'videos'       => array(
		array( 'find' => 'vids-grid', 'with' => '[pomdr_videos]' ),
	),
	'events'       => array(
		// The events renderer has no single wrapper, so: inside the named
		// section keep the heading and intro (first 2 element children of
		// its .container) and replace the rest with the live shortcode.
		array( 'section_id' => 'whats-happening', 'keep_first' => 2, 'append' => '[events types="Special Event,Perpetual Event"]' ),
		array( 'section_id' => 'adoption-events', 'keep_first' => 2, 'append' => '[events types="Adoption Event"]' ),
	),
);

$summary = array();

foreach ( $pom_args as $slug ) {

	$page = get_page_by_path( $slug, OBJECT, 'page' );
	if ( ! $page ) {
		// Nested pages (about/culture) miss the flat path; find by name.
		$found = get_posts( array( 'name' => $slug, 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1 ) );
		$page  = $found ? $found[0] : null;
	}
	if ( ! $page ) { $summary[ $slug ] = 'NO PAGE RECORD'; continue; }

	$url  = get_permalink( $page->ID );
	$resp = wp_remote_get( $url, array( 'timeout' => 30, 'sslverify' => false ) );
	if ( is_wp_error( $resp ) || 200 !== wp_remote_retrieve_response_code( $resp ) ) {
		$summary[ $slug ] = 'FETCH FAILED ' . ( is_wp_error( $resp ) ? $resp->get_error_message() : wp_remote_retrieve_response_code( $resp ) );
		continue;
	}
	$html = wp_remote_retrieve_body( $resp );

	/* Extract the SIDECAR's <main id="main-content"> specifically: Divi's
	   Theme Builder wraps the page in its own outer <main>, so an unanchored
	   match swallows the wrapper. The sidecar main nests no other main, so
	   non-greedy to the first close is exact. */
	if ( ! preg_match( '~<main[^>]*id="main-content"[^>]*>(.*?)</main>~s', $html, $mm )
		&& ! preg_match( '~<main\b[^>]*>(.*?)</main>~s', $html, $mm ) ) {
		$summary[ $slug ] = 'NO <main>';
		continue;
	}
	$main_html = $mm[1];

	/* Harvest page-local <style> blocks into this page's raw css file. */
	$style_count = 0;
	$page_css    = '';
	$main_html   = preg_replace_callback( '~<style\b[^>]*>(.*?)</style>~s', function ( $m ) use ( &$page_css, &$style_count ) {
		$css = trim( $m[1] );
		if ( '' !== $css && false === strpos( $page_css, $css ) ) {
			$page_css .= $css . "\n";
			$style_count++;
		}
		return '';
	}, $main_html );

	/* Also harvest template style blocks between the chrome and <main>.
	   Anchoring past </head> excludes WordPress/Divi head styles, so no
	   class whitelist is needed (the old whitelist silently dropped why's
	   .twocol block, found via cascade trace 2026-08-20). */
	$body_start = strpos( $html, '</head>' );
	$main_start = strpos( $html, '<main' );
	$body_chunk = ( false !== $body_start && false !== $main_start && $main_start > $body_start )
		? substr( $html, $body_start, $main_start - $body_start )
		: '';
	preg_match_all( '~<style\b(?![^>]*id=)[^>]*>(.*?)</style>~s', $body_chunk, $head_styles );
	foreach ( $head_styles[1] as $css ) {
		$css = trim( $css );
		if ( '' !== $css && false === strpos( $page_css, $css )
			&& ! preg_match( '~^(img|\.wp-|:root\{--wp|\.et[-_])~', $css ) ) {
			$page_css .= $css . "\n";
			$style_count++;
		}
	}
	if ( '' !== $page_css ) {
		file_put_contents( "$css_raw_dir/$slug.css", $page_css );
	}

	/* Collapse whitespace runs: wpautop turns raw newlines inside captured
	   markup into <br> tags, silently rewrapping paragraphs (fidelity probe
	   2026-08-19). No <pre> content exists in these pages. */
	$main_html = preg_replace( '/\s+/', ' ', $main_html );

	/* Localize URLs: absolute internal -> root-relative. */
	foreach ( $home_hosts as $h ) {
		$main_html = str_replace( $h, '', $main_html );
	}

	/* PHP's libxml HTML parser predates <picture>/<source> and mangles them
	   (found: culture hero lost its WebP source). Shield them as paired
	   custom tags around the parse; restored after serialization. */
	$main_html = str_replace( array( '<picture', '</picture>' ), array( '<pom-picture', '</pom-picture>' ), $main_html );
	$main_html = preg_replace( '~<source\b([^>]*)>~i', '<pom-source$1></pom-source>', $main_html );

	/* Split into top-level elements with DOM. */
	$doc = new DOMDocument();
	libxml_use_internal_errors( true );
	$doc->loadHTML( '<?xml encoding="utf-8"?><div id="pom-root">' . $main_html . '</div>' );
	libxml_clear_errors();
	$root = $doc->getElementById( 'pom-root' );
	if ( ! $root ) { $summary[ $slug ] = 'DOM PARSE FAILED'; continue; }

	/* Descend through sole classless wrappers (process nests a second
	   <main id="main"> around its sections): otherwise the whole page ports
	   as ONE section and full-bleed backgrounds paint inset inside the 80%
	   row (measured: white side bands at 390, 2026-08-20). */
	for ( $depth = 0; $depth < 3; $depth++ ) {
		$only = null;
		foreach ( $root->childNodes as $n ) {
			if ( XML_ELEMENT_NODE !== $n->nodeType ) { continue; }
			if ( null !== $only ) { $only = false; break; }
			$only = $n;
		}
		if ( ! $only ) { break; }
		$tag = strtolower( $only->nodeName );
		if ( ! in_array( $tag, array( 'main', 'div' ), true ) || trim( $only->getAttribute( 'class' ) ) !== '' ) { break; }
		$root = $only;
	}

	/* Apply dynamic-island swaps before serialization. */
	if ( isset( $islands[ $slug ] ) ) {
		$xp = new DOMXPath( $doc );
		foreach ( $islands[ $slug ] as $op ) {
			if ( isset( $op['find'] ) ) {
				$hits = $xp->query( "//*[contains(concat(' ', normalize-space(@class), ' '), ' {$op['find']} ')]" );
				if ( $hits->length ) {
					$el = $hits->item( 0 );
					$el->parentNode->replaceChild( $doc->createTextNode( "\n" . $op['with'] . "\n" ), $el );
				} else {
					echo "island WARN [$slug]: .{$op['find']} not found\n";
				}
			} elseif ( isset( $op['section_id'] ) ) {
				$sec = $doc->getElementById( $op['section_id'] );
				if ( $sec ) {
					// Work inside the .container child when present.
					$scope = $sec;
					foreach ( $sec->childNodes as $c ) {
						if ( XML_ELEMENT_NODE === $c->nodeType && false !== strpos( ' ' . $c->getAttribute( 'class' ) . ' ', ' container ' ) ) {
							$scope = $c;
							break;
						}
					}
					$kept = 0;
					$doomed = array();
					foreach ( $scope->childNodes as $c ) {
						if ( XML_ELEMENT_NODE !== $c->nodeType ) { continue; }
						$kept++;
						if ( $kept > $op['keep_first'] ) { $doomed[] = $c; }
					}
					foreach ( $doomed as $c ) { $scope->removeChild( $c ); }
					$scope->appendChild( $doc->createTextNode( "\n" . $op['append'] . "\n" ) );
				} else {
					echo "island WARN [$slug]: #{$op['section_id']} not found\n";
				}
			}
		}
	}

	/* Drop whitespace-only text nodes inside block containers: wpautop
	   wraps them into empty <p> elements on the native render, which steal
	   :last-of-type from real paragraphs and break Divi's own padding
	   reset (found 2026-08-20: phantom P after div.page-cta). Spaces
	   inside inline/text contexts (p, headings, a, li) are untouched. */
	$xp_ws  = new DOMXPath( $doc );
	$blocks = array( 'div', 'section', 'header', 'footer', 'main', 'article', 'aside', 'ul', 'ol', 'figure', 'picture' );
	foreach ( $xp_ws->query( '//text()' ) as $tn ) {
		if ( '' === trim( $tn->nodeValue ) && $tn->parentNode
			&& in_array( strtolower( $tn->parentNode->nodeName ), $blocks, true ) ) {
			$tn->parentNode->removeChild( $tn );
		}
	}

	/* wpautop wraps standalone inline elements (a button <a> between two
	   paragraphs) into phantom <p> wrappers carrying paragraph padding.
	   Shield: wrap orphan inline runs inside block containers in a plain
	   <div> (unstyled, wpautop leaves divs alone). */
	$inline_tags = array( 'a', 'span', 'em', 'strong', 'small', 'img', 'picture', 'button', 'svg' );
	foreach ( $xp_ws->query( '//*' ) as $blk ) {
		if ( ! in_array( strtolower( $blk->nodeName ), $blocks, true ) ) { continue; }
		$children = iterator_to_array( $blk->childNodes );
		$run = array();
		$flush = function () use ( &$run, $doc, $blk ) {
			if ( ! $run ) { return; }
			$has_el = false;
			foreach ( $run as $n ) { if ( XML_ELEMENT_NODE === $n->nodeType ) { $has_el = true; } }
			if ( $has_el ) {
				$wrap = $doc->createElement( 'div' );
				// The shield must be layout-invisible: inside flex/grid parents
				// (.page-cta) a plain div becomes the sole flex item and eats
				// the gap (measured: buttons stacked gapless, 2026-08-20).
				$wrap->setAttribute( 'style', 'display:contents' );
				$blk->insertBefore( $wrap, $run[0] );
				foreach ( $run as $n ) { $wrap->appendChild( $n ); }
			}
			$run = array();
		};
		foreach ( $children as $child ) {
			$is_inline = ( XML_TEXT_NODE === $child->nodeType && '' !== trim( $child->nodeValue ) )
				|| ( XML_ELEMENT_NODE === $child->nodeType && in_array( strtolower( $child->nodeName ), $inline_tags, true ) );
			if ( $is_inline ) { $run[] = $child; } else { $flush(); }
		}
		$flush();
	}

	$d4       = '';
	$sections = 0;
	foreach ( $root->childNodes as $node ) {
		if ( XML_ELEMENT_NODE !== $node->nodeType ) { continue; }

		$classes = trim( $node->getAttribute( 'class' ) );
		$inner   = '';
		foreach ( $node->childNodes as $child ) {
			$inner .= $doc->saveHTML( $child );
		}

		/* The sole-.container unwrap is gone: .et_pb_text .container in the
		   adapter neutralizes the double width constraint while PRESERVING
		   layout containers like .cta-strip .container (flex; its removal
		   made CTA headings span full width, measured 2026-08-20). */

		if ( '' === trim( $inner ) ) { continue; }

		$inline_style = trim( $node->getAttribute( 'style' ) );
		if ( '' !== $inline_style ) {
			$gen = 'pgi-' . $slug . '-' . $sections;
			$classes = trim( $classes . ' ' . $gen );
			$page_css_extra = ( $page_css_extra ?? '' ) . '.' . $gen . '{' . rtrim( $inline_style, ';' ) . ";}\n";
		}
		$label = ucwords( str_replace( array( '-', '_' ), ' ', $classes ? preg_split( '/\s+/', $classes )[0] : $node->nodeName ) );
		$d4   .= pom_d4_section(
			array( 'module_class' => trim( $classes . " pg-$slug" ), 'admin_label' => $label ?: 'Section' ),
			pom_d4_row(
				array( 'admin_label' => $label . ' row' ),
				pom_d4_column( '4_4', array(), pom_d4_text( array( 'admin_label' => $label . ' content' ), trim( $inner ) ) )
			)
		);
		$sections++;
	}

	if ( 0 === $sections ) { $summary[ $slug ] = 'NO SECTIONS'; continue; }

	if ( ! empty( $page_css_extra ) ) {
		file_put_contents( "$css_raw_dir/$slug.css", ( file_exists( "$css_raw_dir/$slug.css" ) ? file_get_contents( "$css_raw_dir/$slug.css" ) : '' ) . $page_css_extra );
		$page_css_extra = '';
	}

	/* Restore the shielded picture/source markup. */
	$d4 = str_replace( array( '<pom-picture', '</pom-picture>' ), array( '<picture', '</picture>' ), $d4 );
	$d4 = preg_replace( '~<pom-source\b([^>]*)>\s*</pom-source>~i', '<source$1>', $d4 );

	/* Stage, convert, check (same gate as generate.php, compact). */
	$staging_slug = "native-staging-$slug";
	$existing     = get_page_by_path( $staging_slug, OBJECT, 'page' );
	if ( $existing ) {
		$post_id = $existing->ID;
		// Preserve publish state: re-drafting a published staging page makes
		// anonymous fidelity captures hit a 404 and poisons every measurement.
		wp_update_post( array( 'ID' => $post_id, 'post_content' => $d4, 'post_status' => $existing->post_status ) );
		delete_post_meta( $post_id, '_et_pb_use_divi_5' );
	} else {
		$post_id = wp_insert_post( array(
			'post_title'   => get_the_title( $page->ID ) . ' (native)',
			'post_name'    => $staging_slug,
			'post_status'  => 'draft',
			'post_type'    => 'page',
			'post_content' => $d4,
		) );
	}
	if ( is_wp_error( $post_id ) || ! $post_id ) { $summary[ $slug ] = 'STAGE FAILED'; continue; }
	update_post_meta( $post_id, '_et_pb_use_builder', 'on' );
	update_post_meta( $post_id, '_et_pb_show_page_creation', 'off' );
	update_post_meta( $post_id, '_pomdr_ported_from', $page->ID );

	file_put_contents( "$backup_dir/$slug-port-d4.txt", $d4 );

	Divi\D5_Readiness\Server\Conversion::convert_single_post( $post_id );
	clean_post_cache( $post_id );
	$saved = get_post( $post_id )->post_content;

	/* Divi's D5-to-D5 migration filter (applied inside convert_single_post)
	   strips the wp:divi/placeholder root on some content. Canonical D5
	   pages carry it (verified on VB-authored pages), so restore it; a
	   plain wp_update_post does not re-run that filter. */
	if ( false === strpos( $saved, 'wp:divi/placeholder' ) && 0 === strpos( ltrim( $saved ), '<!-- wp:divi/' ) ) {
		$saved = "<!-- wp:divi/placeholder -->{$saved}<!-- /wp:divi/placeholder -->";
		wp_update_post( array( 'ID' => $post_id, 'post_content' => wp_slash( $saved ) ) );
		clean_post_cache( $post_id );
		$saved = get_post( $post_id )->post_content;
	}
	file_put_contents( "$backup_dir/$slug-port-d5.txt", $saved );

	$errs = array();
	if ( false === strpos( $saved, 'wp:divi/placeholder' ) ) { $errs[] = 'no-placeholder'; }
	if ( substr_count( $saved, 'divi/shortcode-module' ) > 0 ) { $errs[] = 'shortcode-module-fallback'; }
	if ( 'on' !== get_post_meta( $post_id, '_et_pb_use_divi_5', true ) ) { $errs[] = 'meta-missing'; }
	if ( serialize_blocks( parse_blocks( $saved ) ) !== $saved ) { $errs[] = 'roundtrip-unstable'; }
	$want = $sections;
	$got  = substr_count( $saved, '<!-- wp:divi/section ' );
	if ( $want !== $got ) { $errs[] = "census $want!=$got"; }
	foreach ( $home_hosts as $h ) {
		$hst = preg_replace( '~^https?://~', '', $h );
		if ( $hst && false !== strpos( $saved, $hst ) ) { $errs[] = 'local-host-ref'; break; }
	}

	$summary[ $slug ] = ( empty( $errs ) ? 'PASS' : 'FAIL ' . implode( ',', $errs ) )
		. " (sections=$sections, styles+$style_count, post=$post_id)";
}

echo "\n==== PORT SUMMARY ====\n";
foreach ( $summary as $slug => $line ) {
	echo str_pad( $slug, 28 ) . $line . "\n";
}
echo "raw page css in scripts/native/fidelity/pages-css; run gen-pages-css.mjs then gen-boost.mjs\n";
