<?php
/**
 * Template for the Fostering landing page (WP slug: fostering). Replaces the
 * dated Divi content (a plain title, a wall of text with the application buried
 * as a link, then the foster-parent stories) with the redesign: a hero that
 * surfaces the primary actions as buttons, a plain-language intro, and the real
 * foster-parent stories. Story text and photo filenames live in
 * data/foster-stories.json (ported from the live page); the photos themselves
 * are in the media library at uploads/2025/06/.
 */
get_header();
$img    = get_stylesheet_directory_uri() . '/assets/images';
$upload = wp_get_upload_dir();

$stories_file = get_stylesheet_directory() . '/data/foster-stories.json';
$stories = file_exists( $stories_file ) ? json_decode( (string) file_get_contents( $stories_file ), true ) : array();
if ( ! is_array( $stories ) ) { $stories = array(); }
?>
<style>
.foster-stories { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px 28px; }
@media (max-width: 980px) { .foster-stories { grid-template-columns: 1fr 1fr; } }
@media (max-width: 620px) { .foster-stories { grid-template-columns: 1fr; } }
.foster-story { background: #fff; border: 1px solid var(--line); border-radius: var(--radius-lg); overflow: hidden; display: flex; flex-direction: column; }
.foster-story img { width: 100%; aspect-ratio: 4 / 3; object-fit: cover; display: block; }
.foster-story__body { padding: 20px 22px 24px; }
.foster-story__name { font-family: var(--font-serif); font-size: 21px; font-weight: 500; margin: 0 0 8px; color: var(--ink); }
.foster-story__quote { font-size: 16px; line-height: 1.62; color: var(--ink-2); margin: 0; }
.foster-lede { max-width: 74ch; font-size: 18px; line-height: 1.7; color: var(--ink-2); }
.foster-lede p { margin: 0 0 18px; }
</style>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <div class="ph-split">
      <div class="ph-text">
        <h1 class="page-headline">Fostering</h1>
        <p class="page-narrative">One of the most rewarding jobs <em>there is</em>.</p>
        <p class="page-lead">POMDR has no shelter. Every one of our dogs lives in a foster home as part of the family until they find their forever person. Foster parents are the bridge, and we cover the costs. You give the love.</p>
        <div class="page-cta">
          <a href="/volunteer-application/" class="btn btn-primary">Apply to Foster</a>
          <a href="/foster-needs/" class="btn btn-outline">See dogs who need a foster</a>
        </div>
      </div>
      <div class="ph-media">
        <picture>
          <source type="image/webp" srcset="<?php echo $img; ?>/hero-helping-paw.webp">
          <img src="<?php echo $img; ?>/hero-helping-paw.jpeg" alt="A POMDR foster volunteer holding a senior dog">
        </picture>
      </div>
    </div>
  </div>
</header>

<section class="section" id="what">
  <div class="container">
    <span class="eyebrow purple">What fostering means</span>
    <h2 class="section-title">A safe place to land, for however long it <em>takes</em>.</h2>
    <div class="foster-lede">
      <p>Foster parents help a dog move from wherever they have been, a beloved home, a shelter, or a hard situation, into a calm new routine. For a dog who has lost their family, that change can be stressful, and a patient foster home makes all the difference.</p>
      <p>Many of our foster parents say they receive as much as they give. We provide the crate, bed, supplies, and every bit of vet care. You provide the food, the couch, and the love, until the right adopter comes along.</p>
    </div>
    <div class="page-cta" style="margin-top:26px">
      <a href="/volunteer-application/" class="btn btn-primary">Become a Foster</a>
      <a href="/foster-needs/" class="btn btn-outline">Dogs needing foster now</a>
    </div>
  </div>
</section>

<section class="section" id="stories" style="background:var(--cream-2)">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow purple">In their own words</span>
      <h2 class="section-title">Meet some of our <em>foster parents</em>.</h2>
    </div>
    <?php if ( $stories ) : ?>
    <div class="foster-stories">
      <?php foreach ( $stories as $s ) :
          $photo = isset( $s['photo'] ) ? $s['photo'] : '';
          $src   = $photo ? trailingslashit( $upload['baseurl'] ) . '2025/06/' . $photo : '';
          $name  = isset( $s['name'] ) ? $s['name'] : '';
      ?>
      <article class="foster-story">
        <?php if ( $src ) : ?><img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $name ); ?> and their foster dog" loading="lazy"><?php endif; ?>
        <div class="foster-story__body">
          <h3 class="foster-story__name"><?php echo esc_html( $name ); ?></h3>
          <p class="foster-story__quote"><?php echo esc_html( isset( $s['quote'] ) ? $s['quote'] : '' ); ?></p>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<section class="cta-strip" style="background:var(--blue-50)">
  <div class="container">
    <h2 class="serif">Open your home to a senior who <em>needs one</em>.</h2>
    <div class="ctas">
      <a href="/volunteer-application/" class="btn btn-primary">Apply to Foster</a>
      <a href="mailto:info@pomdr.org" class="btn btn-outline">Ask a Question</a>
    </div>
  </div>
</section>
</main>
<?php get_footer();
