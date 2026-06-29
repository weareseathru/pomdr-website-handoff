<?php
/** Template for the Resources page (WP slug: recources). Ports prototype resources.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">

<main id="main">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Resources <span class="page-headline-sub">Caring for Seniors</span></h1>
    <p class="page-narrative">Caring for a <em>senior dog</em>.</p>
    <p class="page-lead">Practical guides for the questions we hear most often. Mobility, vet care, end-of-life choices, and how to keep a senior dog comfortable.</p>
  </div>
</header>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:28px">
      <article class="card" style="padding:24px"><div class="eyebrow">Health</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Vet care for senior dogs</h3><p style="color:var(--ink-3);font-size:15px">Twice-a-year exams, blood panels, dental, pain management. What to ask your vet.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">Mobility</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Stairs, rugs, slippery floors</h3><p style="color:var(--ink-3);font-size:15px">Small home tweaks that make a huge difference for a dog with arthritis or hind-end weakness.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">Behavior</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Cognitive changes</h3><p style="color:var(--ink-3);font-size:15px">When a senior dog seems confused at night, or stops settling on a familiar bed.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">Nutrition</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Feeding a senior dog</h3><p style="color:var(--ink-3);font-size:15px">Calorie counts, joint supplements, and what to do when a dog gets picky.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">End of life</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Quality of life</h3><p style="color:var(--ink-3);font-size:15px">A framework for the hardest conversations. Hospice options, in-home services, grief support.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">Finance</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Help paying for vet care</h3><p style="color:var(--ink-3);font-size:15px">Local and national funds for medical bills, including our own Helping Paw program.</p></article>
    </div>
    <p style="margin-top:48px;color:var(--ink-3);font-size:16px"><em>Full articles ship with the WordPress build. This page lists the topics we plan to cover.</em></p>
  </div>
</section>

<section class="cta-strip">
  <div class="container">
    <h2 class="serif">Need help <em>now</em>?</h2>
    <div class="ctas">
      <a href="/helping-paw/" class="btn btn-primary">Helping Paw Program</a>
      <a href="mailto:info@pomdr.org" class="btn btn-outline">Call Us</a>
    </div>
  </div>
</section>

</main>

</main>
<?php get_footer();
