<?php
/** Template for the Testimonials page (WP slug: testimonials). Ports prototype testimonials.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">
<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Testimonials <span class="page-headline-sub">In Their Words</span></h1>
    <p class="page-narrative">What <em>adopters</em> say.</p>
    <p class="page-lead">A small sample of notes we receive from the people who take our dogs home.</p>
  </div>
</header>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:32px">
      <blockquote class="card" style="padding:32px;font-family:var(--font-serif);font-size:20px;line-height:1.5;color:var(--ink);margin:0">
        <p style="margin:0 0 16px">&ldquo;She walked into the house, found the sunniest spot, and never left. Eight months later, she still rules it.&rdquo;</p>
        <footer style="font-family:var(--font-sans);font-size:16px;color:var(--ink-3);font-style:normal">Lily &amp; Pebble, adopted 2025</footer>
      </blockquote>
      <blockquote class="card" style="padding:32px;font-family:var(--font-serif);font-size:20px;line-height:1.5;color:var(--ink);margin:0">
        <p style="margin:0 0 16px">&ldquo;POMDR handles the hard parts. Vet records, follow-ups, the questions you do not know to ask. I felt like I was adopting with a team behind me.&rdquo;</p>
        <footer style="font-family:var(--font-sans);font-size:16px;color:var(--ink-3);font-style:normal">Marco &amp; Watson, adopted 2024</footer>
      </blockquote>
      <blockquote class="card" style="padding:32px;font-family:var(--font-serif);font-size:20px;line-height:1.5;color:var(--ink);margin:0">
        <p style="margin:0 0 16px">&ldquo;We lost our previous dog in winter. POMDR called the day a senior corgi mix arrived who needed a quiet home. They listened.&rdquo;</p>
        <footer style="font-family:var(--font-sans);font-size:16px;color:var(--ink-3);font-style:normal">Andrea &amp; Honeybee, adopted 2025</footer>
      </blockquote>
    </div>

    <p style="margin-top:48px;color:var(--ink-3);font-size:16px"><em>Full testimonial archive ships with the WordPress build.</em></p>
  </div>
</section>

<section class="cta-strip" style="background:var(--cream-2)">
  <div class="container">
    <h2 class="serif">Read more <em>happy tails</em>.</h2>
    <div class="ctas">
      <a href="/adopted/" class="btn btn-primary">Recently Adopted</a>
      <a href="/adopt/" class="btn btn-outline">Available Dogs</a>
    </div>
  </div>
</section>
</main>
<?php get_footer();
