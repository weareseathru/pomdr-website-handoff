<?php
/**
 * Template for the donor recognition page (WP slug: thank-you; /thanks/ is the
 * form-confirmation page). The full keystone tiers and category sections from
 * the live thankyou.html (synced 2026-07-21, data/content/thankyou.json).
 */
get_header();

$file = get_stylesheet_directory() . '/data/content/thankyou.json';
$d    = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : array();
$keystone   = $d['keystone']   ?? array();
$categories = $d['categories'] ?? array();
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Thank You</h1>
    <p class="page-narrative">The people behind <em>every rescue.</em></p>
    <?php if ( ! empty( $d['intro'] ) ) : ?><p class="page-lead"><?php echo esc_html( $d['intro'] ); ?></p><?php endif; ?>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:980px">
    <?php if ( $keystone ) : ?>
    <div class="ty-keystone">
      <h2 class="section-title">Keystone <em>Donors.</em></h2>
      <?php if ( ! empty( $keystone['intro'] ) ) : ?><p class="ty-intro"><?php echo esc_html( $keystone['intro'] ); ?></p><?php endif; ?>
      <?php foreach ( ( $keystone['tiers'] ?? array() ) as $tier ) : ?>
      <div class="ty-tier">
        <h3><?php echo esc_html( $tier['label'] ?? '' ); ?></h3>
        <p><?php echo esc_html( implode( ' · ', $tier['names'] ?? array() ) ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php foreach ( $categories as $cat ) : ?>
    <div class="ty-cat">
      <h3><?php echo esc_html( $cat['name'] ?? '' ); ?></h3>
      <?php if ( ! empty( $cat['subheading'] ) ) : ?><h4><?php echo esc_html( $cat['subheading'] ); ?></h4><?php endif; ?>
      <p><?php echo esc_html( implode( ' · ', $cat['entries'] ?? array() ) ); ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</section>

</main>
<style>
.ty-intro { font-size: 17px; color: var(--ink-2); max-width: 70ch; }
.ty-tier { margin: 22px 0; }
.ty-tier h3, .ty-cat h3 { font-family: var(--font-serif); font-size: 24px; font-weight: 500; margin: 0 0 8px; padding-bottom: 6px; border-bottom: 1px solid var(--line); }
.ty-cat h4 { font-size: 16px; font-weight: 700; color: var(--blue-text); margin: 10px 0 6px; }
.ty-tier p, .ty-cat p { font-size: 16px; line-height: 1.9; color: var(--ink-2); margin: 0; }
.ty-cat { margin: 34px 0; }
</style>
<?php get_footer();
