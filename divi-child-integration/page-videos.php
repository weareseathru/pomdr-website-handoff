<?php
/**
 * Template for the Videos page (WP slug: videos). Every video from the live
 * site (peaceofminddogrescue.org/videos.html), captured 2026-07-08 with their
 * real YouTube ids, captions, and credits. Embeds load only on click
 * (youtube-nocookie facade); two entries are external features (BYUtv, CNN)
 * and open in a new tab.
 */
get_header();

// Videos are now staff-editable: they come from the Videos post type
// (wp-admin > Videos), migrated from the old hardcoded list by
// scripts/seed-videos.php. pomdr_get_videos() lives in inc/videos.php.
$pomdr_videos = function_exists( 'pomdr_get_videos' ) ? pomdr_get_videos() : array();
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <div class="ph-split">
      <div class="ph-text">
    <h1 class="page-headline">Videos</h1>
    <p class="page-narrative">Our dogs and our people, <em>in their own words.</em></p>
      </div>
      <div class="ph-media">
        <picture>
          <source type="image/webp" srcset="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dog16.webp">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dog16.jpeg" alt="A POMDR senior dog on camera">
        </picture>
      </div>
    </div>
  </div>
</header>

<section class="section">
  <div class="container">
    <div class="vids-grid">
      <?php if ( ! $pomdr_videos ) : ?>
        <p>No videos yet. Add them in the WordPress admin under Videos.</p>
      <?php endif; ?>
      <?php foreach ( $pomdr_videos as $v ) : ?>
        <?php if ( ! empty( $v['youtube_id'] ) ) : ?>
        <article class="vid-item">
          <div class="vid-frame" role="button" tabindex="0" data-youtube-id="<?php echo esc_attr( $v['youtube_id'] ); ?>" data-title="<?php echo esc_attr( $v['title'] ); ?>">
            <img src="<?php echo esc_url( 'https://i.ytimg.com/vi/' . $v['youtube_id'] . '/hqdefault.jpg' ); ?>" alt="<?php echo esc_attr( $v['title'] ); ?>" loading="lazy">
            <span class="vid-play" aria-hidden="true"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
          </div>
          <h2 class="vid-title"><?php echo esc_html( $v['title'] ); ?> <span class="vid-year"><?php echo esc_html( $v['year'] ); ?></span></h2>
          <p class="vid-caption"><?php echo esc_html( $v['caption'] ); ?></p>
          <p class="vid-credits"><?php echo esc_html( $v['credits'] ); ?></p>
        </article>
        <?php else : ?>
        <article class="vid-item">
          <a class="vid-frame vid-frame--ext" href="<?php echo esc_url( $v['url'] ); ?>" target="_blank" rel="noopener">
            <span class="vid-ext-label">Watch on their site</span>
            <span class="vid-play" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6"/><path d="M10 14L21 3"/></svg></span>
          </a>
          <h2 class="vid-title"><?php echo esc_html( $v['title'] ); ?> <span class="vid-year"><?php echo esc_html( $v['year'] ); ?></span></h2>
          <p class="vid-caption"><?php echo esc_html( $v['caption'] ); ?></p>
          <p class="vid-credits"><?php echo esc_html( $v['credits'] ); ?></p>
        </article>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

</main>
<style>
.vids-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px 28px; }
@media (max-width: 980px) { .vids-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 640px) { .vids-grid { grid-template-columns: 1fr; } }
.vid-frame { position: relative; aspect-ratio: 16/9; border-radius: var(--radius); overflow: hidden; background: var(--ink); cursor: pointer; display: block; }
.vid-frame img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .4s var(--ease); }
.vid-frame:hover img { transform: scale(1.04); }
.vid-frame iframe { width: 100%; height: 100%; border: 0; display: block; }
.vid-frame:focus-visible { outline: 3px solid var(--focus-color); outline-offset: 3px; }
.vid-play { position: absolute; inset: 0; display: grid; place-items: center; }
.vid-play svg { width: 58px; height: 58px; color: #fff; filter: drop-shadow(0 4px 14px rgba(0,0,0,.5)); }
.vid-frame--ext { display: grid; place-items: center; background: var(--blue-900); color: #fff; text-decoration: none; }
.vid-frame--ext .vid-ext-label { font-weight: 700; font-size: 23px; z-index: 1; }
.vid-frame--ext .vid-play svg { width: 34px; height: 34px; opacity: .6; }
.vid-title { font-family: var(--font-serif); font-size: 29px; font-weight: 500; margin: 14px 0 6px; }
.vid-year { font-family: var(--font-sans); font-size: 21px; font-weight: 600; color: var(--blue-text); margin-left: 8px; }
.vid-caption { font-size: 21px; line-height: 1.6; color: var(--ink-2); margin: 0 0 6px; }
.vid-credits { font-size: 20px; color: var(--ink-3); margin: 0; }
</style>
<script>
(function () {
  document.querySelectorAll('.vid-frame[data-youtube-id]').forEach(function (f) {
    var play = function () {
      var id = f.getAttribute('data-youtube-id');
      f.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0"' +
        ' allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen title="' +
        (f.getAttribute('data-title') || 'POMDR video') + '"></iframe>';
      f.removeAttribute('role'); f.removeAttribute('tabindex'); f.style.cursor = 'default';
    };
    f.addEventListener('click', play, { once: true });
    f.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); play(); } });
  });
})();
</script>
<?php get_footer();
