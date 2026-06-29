<?php
/** Template for the Volunteer Application page (WP slug: volunteer-application). Ports prototype volunteer-application.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">

<main id="main">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Volunteer <span class="page-headline-sub">Apply to Help</span></h1>
    <p class="page-narrative">Apply to <em>volunteer</em>.</p>
    <p class="page-lead">Tell us a little about you and how you would like to help. A volunteer coordinator follows up within a week.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:760px">
    <div class="card" style="padding:32px">
      <p style="margin:0 0 24px;color:var(--ink-3);font-size:15px">The volunteer application is hosted by Little Green Light. The embedded form below collects your details directly into our volunteer database.</p>
      <div style="aspect-ratio:3/4;background:var(--cream-2);border-radius:var(--radius);display:grid;place-items:center;color:var(--ink-3);text-align:center;padding:24px">
        <div>
          <p style="margin:0 0 12px;font-family:var(--font-serif);font-size:24px;color:var(--ink)">LGL volunteer form embed</p>
          <p style="margin:0;font-size:16px">Wired live with the WordPress build via the LGL Forms iframe. Form ID: utzjcNEZaqAcJk3QURlQmw.</p>
        </div>
      </div>
    </div>

    <p style="margin-top:32px;color:var(--ink-3);font-size:15px">Trouble with the form? Email <a href="mailto:info@pomdr.org" style="color:var(--blue)">info@pomdr.org</a> or call (831) 718-9122.</p>
  </div>
</section>

</main>

</main>
<?php get_footer();
