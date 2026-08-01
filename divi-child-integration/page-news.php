<?php
/**
 * Template for the What's Happening page (WP slug: news). The live news.html
 * items (synced 2026-07-21, data/content/news.json): fundraisers, partner
 * promotions, seminars, and other happenings that are not calendar events.
 */
get_header();

$file  = get_stylesheet_directory() . '/data/content/news.json';
$items = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : array();
if ( ! is_array( $items ) ) { $items = array(); }
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <div class="ph-split">
      <div class="ph-text">
    <h1 class="page-headline">What&rsquo;s Happening</h1>
    <p class="page-narrative">News, fundraisers, and <em>good causes.</em></p>
    <p class="page-lead">Upcoming happenings and ongoing ways to support the dogs. For dated events, see the <a href="/events/" style="color:var(--blue-text);text-decoration:underline">events calendar</a>.</p>
      </div>
      <div class="ph-media">
        <picture>
          <source type="image/webp" srcset="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dog3.webp">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dog3.jpeg" alt="A POMDR senior dog out in the community">
        </picture>
      </div>
    </div>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:900px">
    <?php foreach ( $items as $it ) : ?>
    <article class="news-item">
      <h2><?php echo esc_html( $it['title'] ?? '' ); ?></h2>
      <p><?php echo esc_html( $it['body'] ?? '' ); ?></p>
      <?php if ( ! empty( $it['links'] ) ) : ?>
      <p class="news-links">
        <?php foreach ( $it['links'] as $l ) : ?>
          <a href="<?php echo esc_url( $l['url'] ?? '#' ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $l['text'] ?? 'Learn more' ); ?></a>
        <?php endforeach; ?>
      </p>
      <?php endif; ?>
    </article>
    <?php endforeach; ?>
  </div>
</section>

</main>
<style>
.news-item { padding: 30px 0; border-bottom: 1px solid var(--line); }
.news-item h2 { font-family: var(--font-serif); font-size: 27px; font-weight: 500; margin: 0 0 10px; }
.news-item p { font-size: 17px; line-height: 1.7; color: var(--ink-2); margin: 0 0 10px; max-width: 72ch; }
.news-links a { display: inline-flex; align-items: center; min-height: 44px; margin-right: 22px; color: var(--blue-text); font-weight: 700; text-decoration: underline; text-underline-offset: 3px; }
</style>
<?php get_footer();
