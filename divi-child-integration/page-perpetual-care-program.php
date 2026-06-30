<?php
/** Template for the Perpetual Care Program page (WP slug: perpetual-care-program). Ports prototype perpetual-care-program.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">

<main id="main">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Perpetual Care</h1>
    <p class="page-narrative">A plan for <em>after you</em>.</p>
    <p class="page-lead">If something happens to you, POMDR can take your dog. The Lifetime Care Program is for senior people who want to know their senior dog has a soft landing.</p>
  </div>
</header>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start">
      <div>
        <h2 class="section-title" style="margin-bottom:16px">How it works</h2>
        <ol style="font-size:17px;line-height:1.8;padding-left:24px;margin:0">
          <li>You enroll your dog while you can. We meet them, learn their routine, and keep their records.</li>
          <li>If you can no longer care for your dog (hospitalization, move to assisted living, passing), POMDR takes responsibility for placement.</li>
          <li>We honor any specific requests on file: medical, behavioral, and lifestyle preferences for the dog's next home.</li>
        </ol>

        <h2 class="section-title" style="margin:48px 0 16px">What it costs</h2>
        <p style="font-size:17px;line-height:1.7">A one-time enrollment gift covers ongoing costs of the program. We share the current amount when we meet you in person.</p>
      </div>
      <div>
        <h2 class="section-title" style="margin-bottom:16px">Who it is for</h2>
        <p style="font-size:17px;line-height:1.7">Owners over 65 with a senior dog, especially those without family who could step in. Many of our Lifetime Care members also receive Helping Paw support during their lifetime.</p>
        <h2 class="section-title" style="margin:48px 0 16px">Get started</h2>
        <p style="font-size:17px;line-height:1.7">Call us first. We sit down with you, meet your dog, and walk through paperwork together.</p>
        <a href="mailto:info@pomdr.org" class="btn btn-primary" style="margin-top:16px">Schedule a Conversation</a>
      </div>
    </div>
  </div>
</section>

<section class="cta-strip" style="background:var(--cream-2)">
  <div class="container">
    <h2 class="serif">Also see <em>Helping Paw</em>.</h2>
    <div class="ctas">
      <a href="/helping-paw/" class="btn btn-primary">Helping Paw Program</a>
      <a href="/surrender/" class="btn btn-outline">Placing Your Dog</a>
    </div>
  </div>
</section>

</main>

</main>
<?php get_footer();
