<?php
/** Template for the Thank You page (WP slug: thanks). Ports prototype thank-you.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">
<header class="page-header" style="text-align:center">
  <div class="container">
    <span class="eyebrow">Thank You</span>
    <h1 class="page-title" id="thanks-headline">We got it.</h1>
    <p class="page-lead" id="thanks-lead" style="margin-left:auto;margin-right:auto;max-width:600px">Your message is on its way to a real human at POMDR. We will follow up within a few days.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:760px;text-align:center">
    <h2 class="section-title" style="margin-bottom:24px">While you wait</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;text-align:left">
      <article class="card" style="padding:24px"><div class="eyebrow">Browse</div><h3 style="font-family:var(--font-serif);font-size:20px;margin:8px 0 8px"><a href="/adopt/" style="color:var(--ink)">Adoptable Dogs</a></h3><p style="margin:0;color:var(--ink-3);font-size:16px">See who is available right now.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">Stories</div><h3 style="font-family:var(--font-serif);font-size:20px;margin:8px 0 8px"><a href="/adopted/" style="color:var(--ink)">Recently Adopted</a></h3><p style="margin:0;color:var(--ink-3);font-size:16px">Dogs who recently went home.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">Support</div><h3 style="font-family:var(--font-serif);font-size:20px;margin:8px 0 8px"><a href="/donate/" style="color:var(--ink)">Donate</a></h3><p style="margin:0;color:var(--ink-3);font-size:16px">One dollar feeds a senior dog for a day.</p></article>
    </div>
  </div>
</section>

<script>
  // Adjust headline based on ?source= query param.
  (function () {
    const params = new URLSearchParams(location.search);
    const source = params.get("source");
    const headline = document.getElementById("thanks-headline");
    const lead = document.getElementById("thanks-lead");
    const map = {
      newsletter: { h: "You are on the list.", l: "Welcome. We send a friendly update once a month with new dogs and program news." },
      adoption:   { h: "Your application is in.", l: "An adoption coordinator will reach out within a few days. If it is urgent, call (831) 718-9122." },
      foster:     { h: "Foster inquiry received.", l: "Our foster coordinator will be in touch shortly. Thank you for offering a home." },
      donate:     { h: "Thank you, truly.", l: "Your gift goes directly to senior dog care. A receipt is on its way to your inbox." },
      contact:    { h: "We got it.", l: "Your message reached a real human at POMDR. We will reply within a few days." },
    };
    if (source && map[source] && headline && lead) {
      headline.innerHTML = map[source].h;
      lead.textContent = map[source].l;
    }
  })();
</script>
</main>
<?php get_footer();
