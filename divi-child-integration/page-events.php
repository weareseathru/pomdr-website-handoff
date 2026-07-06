<?php
/**
 * Template for the Events page (WP slug: events).
 *
 * The page's Divi builder content produced no H1 (only the [events] shortcode's
 * event-card headings), leaving the page without a title heading and failing the
 * one-H1 outline rule. This template gives the page a single H1 (its own title)
 * and lists events via the same [events] shortcode, matching the other sub-page
 * templates. No copy is added; the H1 is the page's existing title.
 */
get_header();
?>
<main id="main-content">
<header class="page-header">
  <div class="container">
    <h1 class="page-headline"><?php echo esc_html( get_the_title() ); ?></h1>
  </div>
</header>
<section class="section">
  <div class="container">
    <?php echo do_shortcode( '[events hlevel="h2"]' ); ?>
  </div>
</section>
</main>
<?php get_footer();
