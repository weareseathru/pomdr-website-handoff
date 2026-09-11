<?php
/**
 * Template for the Privacy Policy page (WP slug: privacy).
 * The REAL policy text from the live termsandprivacy.html (synced 2026-07-21,
 * data/content/privacy.md). Flagged for counsel review before launch, but no
 * longer a placeholder.
 */
get_header();

$file  = get_stylesheet_directory() . '/data/content/privacy.md';
$raw   = is_readable( $file ) ? (string) file_get_contents( $file ) : '';
$lines = preg_split( '/\r\n|\r|\n/', $raw );
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Privacy Policy</h1>
    <p class="page-lead">Ported from the current site; under counsel review before launch.</p>
  </div>
</header>

<section class="section">
  <div class="container legal-copy" style="max-width:800px">
    <?php
    $para = array();
    $flush = function () use ( &$para ) {
        if ( $para ) { echo '<p>' . esc_html( implode( ' ', $para ) ) . '</p>'; $para = array(); }
    };
    foreach ( $lines as $line ) {
        $line = trim( $line );
        if ( '' === $line ) { $flush(); continue; }
        if ( 0 === strpos( $line, 'Source:' ) || 0 === strpos( $line, '# ' ) ) { continue; }
        if ( 0 === strpos( $line, '### ' ) ) { $flush(); echo '<h3>' . esc_html( substr( $line, 4 ) ) . '</h3>'; continue; }
        if ( 0 === strpos( $line, '## ' ) )  { $flush(); echo '<h2>' . esc_html( substr( $line, 3 ) ) . '</h2>'; continue; }
        if ( 0 === strpos( $line, '- ' ) )   { $flush(); echo '<p class="legal-li">' . esc_html( substr( $line, 2 ) ) . '</p>'; continue; }
        $para[] = $line;
    }
    $flush();
    ?>
  </div>
</section>

</main>
<style>
.legal-copy h2 { font-family: var(--font-serif); font-size: 35px; font-weight: 500; margin: 40px 0 14px; }
.legal-copy h3 { font-size: 23px; font-weight: 700; letter-spacing: 0.04em; margin: 28px 0 10px; }
.legal-copy p { font-size: 22px; line-height: 1.75; color: var(--ink-2); margin: 0 0 14px; }
.legal-copy .legal-li { padding-left: 22px; position: relative; }
.legal-copy .legal-li::before { content: "\2022"; position: absolute; left: 6px; color: var(--blue-text); }
</style>
<?php get_footer();
