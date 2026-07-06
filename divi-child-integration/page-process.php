<?php
/** Template for the Adoption Process page (WP slug: process). Ports prototype process.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">

<main id="main">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Adoption</h1>
    <p class="page-narrative">How <em>adoption</em> works.</p>
    <p class="page-lead">Four steps. A real person at every one of them. Most placements finish inside two weeks.</p>
    <div class="page-cta">
      <a href="/adopt/" class="btn btn-primary">See Adoptable Dogs</a>
      <a href="#steps" class="btn btn-outline">See the four steps</a>
    </div>
  </div>
</header>

<section class="section" id="steps">
  <div class="container">
    <ol style="list-style:none;padding:0;margin:0;display:grid;gap:24px;counter-reset:step;max-width:840px">
      <li class="card" style="padding:28px;display:grid;grid-template-columns:64px 1fr;gap:24px;align-items:start">
        <div style="font-family:var(--font-serif);font-size:48px;color:var(--blue);line-height:1">1</div>
        <div>
          <h2 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">Apply online</h2>
          <p style="margin:0;color:var(--ink-3);font-size:16px;line-height:1.6">Fill out our application. It is short and asks the things we need to make a good match. A coordinator follows up within a few days.</p>
        </div>
      </li>
      <li class="card" style="padding:28px;display:grid;grid-template-columns:64px 1fr;gap:24px;align-items:start">
        <div style="font-family:var(--font-serif);font-size:48px;color:var(--blue);line-height:1">2</div>
        <div>
          <h2 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">Meet the dog</h2>
          <p style="margin:0;color:var(--ink-3);font-size:16px;line-height:1.6">In person at the Bauer Center, or with the foster family. We want you to spend real time together before deciding.</p>
        </div>
      </li>
      <li class="card" style="padding:28px;display:grid;grid-template-columns:64px 1fr;gap:24px;align-items:start">
        <div style="font-family:var(--font-serif);font-size:48px;color:var(--blue);line-height:1">3</div>
        <div>
          <h2 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">Home visit</h2>
          <p style="margin:0;color:var(--ink-3);font-size:16px;line-height:1.6">A volunteer comes to your home or meets via video. We want to be sure the environment fits the dog.</p>
        </div>
      </li>
      <li class="card" style="padding:28px;display:grid;grid-template-columns:64px 1fr;gap:24px;align-items:start">
        <div style="font-family:var(--font-serif);font-size:48px;color:var(--blue);line-height:1">4</div>
        <div>
          <h2 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">Welcome home</h2>
          <p style="margin:0;color:var(--ink-3);font-size:16px;line-height:1.6">Sign the contract, pay the adoption fee, and head home with vet records, meds, food notes, and our phone number.</p>
        </div>
      </li>
    </ol>
  </div>
</section>

<section class="cta-strip" style="background:var(--cream-2)">
  <div class="container">
    <h2 class="serif">Ready when <em>you</em> are.</h2>
    <div class="ctas">
      <a href="/adopt/" class="btn btn-primary">See Adoptable Dogs</a>
      <a href="mailto:info@pomdr.org" class="btn btn-outline">Ask a Question</a>
    </div>
  </div>
</section>

</main>

</main>
<?php get_footer();
