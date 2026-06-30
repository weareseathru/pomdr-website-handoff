<?php
/** Template for the Videos page (WP slug: videos). Ports prototype videos.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">
<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Videos</h1>
    <p class="page-narrative">See <em>POMDR</em> at work.</p>
    <p class="page-lead">Short clips: dog introductions, adoption stories, behind-the-scenes at the Bauer Center.</p>
  </div>
</header>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:28px">
      <article class="card" style="padding:0;overflow:hidden">
        <div style="aspect-ratio:16/9;background:var(--cream-2);display:grid;place-items:center;color:var(--ink-3)">video placeholder</div>
        <div style="padding:20px"><div class="eyebrow">Adoption story</div><h3 style="font-family:var(--font-serif);font-size:20px;margin:6px 0 0">Pebble's first week home</h3></div>
      </article>
      <article class="card" style="padding:0;overflow:hidden">
        <div style="aspect-ratio:16/9;background:var(--cream-2);display:grid;place-items:center;color:var(--ink-3)">video placeholder</div>
        <div style="padding:20px"><div class="eyebrow">Behind the scenes</div><h3 style="font-family:var(--font-serif);font-size:20px;margin:6px 0 0">A morning at the Bauer Center</h3></div>
      </article>
      <article class="card" style="padding:0;overflow:hidden">
        <div style="aspect-ratio:16/9;background:var(--cream-2);display:grid;place-items:center;color:var(--ink-3)">video placeholder</div>
        <div style="padding:20px"><div class="eyebrow">Helping Paw</div><h3 style="font-family:var(--font-serif);font-size:20px;margin:6px 0 0">Walking brigade at work</h3></div>
      </article>
    </div>
    <p style="margin-top:48px;color:var(--ink-3);font-size:16px"><em>Embeds pull from our YouTube channel at <a href="https://www.youtube.com/@peaceofminddogrescue" target="_blank" rel="noopener" style="color:var(--blue)">youtube.com/@peaceofminddogrescue</a>.</em></p>
  </div>
</section>
</main>
<?php get_footer();
