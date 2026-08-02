<?php
/** Template for the Adoption Process page (WP slug: process). Ports prototype process.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">

<main id="main">

<header class="page-header">
  <div class="container">
    <div class="ph-split">
      <div class="ph-text">
    <h1 class="page-headline">Adoption</h1>
    <p class="page-narrative">How <em>adoption</em> works.</p>
    <p class="page-lead">Four steps. A real person at every one of them.</p>
    <div class="page-cta">
      <a href="/adopt/" class="btn btn-primary">See Adoptable Dogs</a>
      <a href="#steps" class="btn btn-outline">See the four steps</a>
    </div>
      </div>
      <div class="ph-media">
        <picture>
          <source type="image/webp" srcset="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dog6.webp">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/dog6.jpeg" alt="A senior dog looking up, ready to meet their person">
        </picture>
      </div>
    </div>
  </div>
</header>

<section class="section" id="steps">
  <div class="container">
    <ol style="list-style:none;padding:0;margin:0;display:grid;gap:24px;counter-reset:step;max-width:840px">
      <li class="card" style="padding:28px;display:grid;grid-template-columns:64px 1fr;gap:24px;align-items:start">
        <div style="font-family:var(--font-serif);font-size:48px;color:var(--blue);line-height:1">1</div>
        <div>
          <h3 style="font-family:var(--font-serif);font-size: 29px;font-weight:500;margin:0 0 8px">Apply online</h3>
          <p style="margin:0 0 14px;color:var(--ink-3);font-size: 21px;line-height:1.6">Fill out our application. It is short and asks the things we need to make a good match.</p>
          <a href="/adoption-questionnaire/" class="btn btn-primary" style="min-height:44px">Start the Application</a>
          <p style="margin:12px 0 0;color:var(--ink-3);font-size: 21px">Responses to our adoption questionnaires are typically sent by email. Please check your spam or junk folder.</p>
        </div>
      </li>
      <li class="card" style="padding:28px;display:grid;grid-template-columns:64px 1fr;gap:24px;align-items:start">
        <div style="font-family:var(--font-serif);font-size:48px;color:var(--blue);line-height:1">2</div>
        <div>
          <h3 style="font-family:var(--font-serif);font-size: 29px;font-weight:500;margin:0 0 8px">Meet the dog</h3>
          <p style="margin:0;color:var(--ink-3);font-size: 21px;line-height:1.6">Talk with the dog's foster family first, then spend real time together, at the Bauer Center or in the foster home, before deciding.</p>
        </div>
      </li>
      <li class="card" style="padding:28px;display:grid;grid-template-columns:64px 1fr;gap:24px;align-items:start">
        <div style="font-family:var(--font-serif);font-size:48px;color:var(--blue);line-height:1">3</div>
        <div>
          <h3 style="font-family:var(--font-serif);font-size: 29px;font-weight:500;margin:0 0 8px">Home visit</h3>
          <p style="margin:0;color:var(--ink-3);font-size: 21px;line-height:1.6">A volunteer visits your home. We want to be sure the environment fits the dog.</p>
        </div>
      </li>
      <li class="card" style="padding:28px;display:grid;grid-template-columns:64px 1fr;gap:24px;align-items:start">
        <div style="font-family:var(--font-serif);font-size:48px;color:var(--blue);line-height:1">4</div>
        <div>
          <h3 style="font-family:var(--font-serif);font-size: 29px;font-weight:500;margin:0 0 8px">Welcome home</h3>
          <p style="margin:0;color:var(--ink-3);font-size: 21px;line-height:1.6">Sign the contract, pay the adoption fee, and head home with vet records, meds, food notes, and our phone number.</p>
        </div>
      </li>
    </ol>

    <div class="card" style="max-width:840px;margin:32px 0 0;padding:28px;background:var(--blue-50);border-radius:var(--radius-lg)">
      <h3 style="font-family:var(--font-serif);font-size: 29px;font-weight:500;margin:0 0 10px">Fees and what is included</h3>
      <p style="margin:0 0 10px;color:var(--ink-2);font-size: 22px;line-height:1.7">Adoption fees range from $155 to $305, depending on the age and health of the dog and the expected future medical expenses the adopter takes on. Every dog is altered, microchipped, and vaccinated unless our veterinarian advises against it for health reasons. Senior dogs also get a senior blood panel, and in many cases a dental cleaning with any needed extractions.</p>
      <p style="margin:0;color:var(--ink-2);font-size: 22px;line-height:1.7">After adoption, we check in with an annual follow-up call for the life of the dog, and our phone number is always yours to use.</p>
    </div>
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
