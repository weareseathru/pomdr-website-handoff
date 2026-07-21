<?php
/**
 * Template for the Testimonials page (WP slug: testimonials).
 * Renders every real testimonial from the live site (synced 2026-07-21 into
 * the theme's data/content/testimonials.json; 170 quotes, verbatim, with the
 * live "Name, Role" attributions). No invented content.
 */
get_header();

$file = get_stylesheet_directory() . '/data/content/testimonials.json';
$testimonials = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : array();
if ( ! is_array( $testimonials ) ) { $testimonials = array(); }
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Testimonials</h1>
    <p class="page-narrative">In their <em>own words.</em></p>
    <p class="page-lead"><?php echo esc_html( count( $testimonials ) ); ?> notes from adopters, fosters, volunteers, and Helping Paw clients, exactly as they wrote them.</p>
  </div>
</header>

<section class="section">
  <div class="container">
    <div class="tmn-wall">
      <?php foreach ( $testimonials as $t ) : if ( empty( $t['quote'] ) ) { continue; } ?>
      <figure class="tmn-card">
        <blockquote><?php echo esc_html( $t['quote'] ); ?></blockquote>
        <figcaption>
          <?php echo esc_html( $t['attribution'] ?? '' ); ?>
          <?php if ( ! empty( $t['role'] ) ) : ?><span class="tmn-role"><?php echo esc_html( $t['role'] ); ?></span><?php endif; ?>
        </figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

</main>
<style>
.tmn-wall { columns: 3 340px; column-gap: 24px; }
.tmn-card {
  break-inside: avoid; margin: 0 0 24px; padding: 26px 28px;
  background: #fff; border: 1px solid var(--line); border-radius: var(--radius);
}
.tmn-card blockquote { margin: 0 0 14px; font-size: 17px; line-height: 1.65; color: var(--ink-2); }
.tmn-card figcaption { font-weight: 700; color: var(--ink); font-size: 16px; }
.tmn-card .tmn-role { display: block; font-weight: 600; color: var(--blue-text); font-size: 14px; margin-top: 2px; }
</style>
<?php get_footer();
