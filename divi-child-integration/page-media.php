<?php
/**
 * Template for the In the Media page (WP slug: media). Renders the complete
 * press archive from the live site (synced 2026-07-21 into
 * data/content/media.json; 37 dated items, newest first).
 */
get_header();

$file  = get_stylesheet_directory() . '/data/content/media.json';
$items = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : array();
if ( ! is_array( $items ) ) { $items = array(); }
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <div class="ph-split">
      <div class="ph-text">
    <h1 class="page-headline">In the Media</h1>
    <p class="page-narrative">The press has <em>noticed.</em></p>
    <p class="page-lead">Coverage of POMDR and our dogs, from local papers to CNN, newest first.</p>
      </div>
      <div class="ph-media">
        <picture>
          <source type="image/webp" srcset="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dog14.webp">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dog14.jpeg" alt="A POMDR senior dog">
        </picture>
      </div>
    </div>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:900px">
    <ul class="press-list">
      <?php foreach ( $items as $it ) : ?>
      <li class="press-item">
        <a href="<?php echo esc_url( $it['url'] ?? '#' ); ?>" target="_blank" rel="noopener">
          <span class="press-title"><?php echo esc_html( $it['title'] ?? '' ); ?></span>
          <span class="press-meta"><?php echo esc_html( trim( ( $it['outlet'] ?? '' ) . ( ! empty( $it['date'] ) ? ' · ' . $it['date'] : '' ), ' ·' ) ); ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

</main>
<style>
.press-list { list-style: none; margin: 0; padding: 0; }
.press-item { border-bottom: 1px solid var(--line); }
.press-item a { display: flex; justify-content: space-between; gap: 24px; align-items: baseline; padding: 18px 4px; min-height: 44px; color: inherit; text-decoration: none; }
.press-item a:hover .press-title { color: var(--blue-text); text-decoration: underline; text-underline-offset: 3px; }
.press-title { font-size: 18px; font-weight: 600; }
.press-meta { font-size: 16px; color: var(--ink-3); white-space: nowrap; }
@media (max-width: 640px) { .press-item a { flex-direction: column; gap: 4px; } .press-meta { white-space: normal; } }
</style>
<?php get_footer();
