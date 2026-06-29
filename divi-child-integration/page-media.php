<?php
/** Template for the Media page (WP slug: media). Ports prototype media.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">
<header class="page-header">
  <div class="container">
    <h1 class="page-headline">In the Media <span class="page-headline-sub">Press and Features</span></h1>
    <p class="page-narrative">Press, features, and <em>print ads</em>.</p>
    <p class="page-lead">Highlights of POMDR coverage, plus our weekly Monterey Herald ads for adoptable dogs.</p>
  </div>
</header>

<section class="section">
  <div class="container">
    <h2 class="section-title" style="margin-bottom:32px">Recent coverage</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:28px">
      <article class="card" style="padding:24px"><div class="eyebrow">Monterey Herald</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0">Adoptable seniors of the week</h3><p style="color:var(--ink-3);font-size:15px;margin:0">Weekly print feature spotlighting available dogs.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">KSBW</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0">Senior dogs find peace of mind</h3><p style="color:var(--ink-3);font-size:15px;margin:0">Feature on the founding mission and recent rescues.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">Carmel Pine Cone</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0">Helping Paw at work</h3><p style="color:var(--ink-3);font-size:15px;margin:0">Behind the scenes of the support-in-place program.</p></article>
    </div>
    <p style="margin-top:48px;color:var(--ink-3);font-size:16px"><em>Full archive moves here on WordPress launch. Press inquiries: <a href="mailto:info@pomdr.org" style="color:var(--blue)">info@pomdr.org</a>.</em></p>
  </div>
</section>

<section class="cta-strip">
  <div class="container">
    <h2 class="serif">Want to write about <em>POMDR</em>?</h2>
    <div class="ctas">
      <a href="mailto:info@pomdr.org" class="btn btn-primary">Press Contact</a>
      <a href="/about/" class="btn btn-outline">Our Story</a>
    </div>
  </div>
</section>
</main>
<?php get_footer();
