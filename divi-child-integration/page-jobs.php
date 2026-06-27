<?php
/** Template for the Jobs page (WP slug: jobs). Ports prototype jobs.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">

<main id="main">

<header class="page-header">
  <div class="container">
    <span class="eyebrow">Careers</span>
    <h1 class="page-title">Work <em>with us</em>.</h1>
    <p class="page-lead">POMDR is a small organization with a big mission. When we hire, this is where the openings appear.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:760px">
    <div class="card" style="padding:32px;text-align:center;background:var(--cream-2)">
      <div class="eyebrow">Right now</div>
      <h2 style="font-family:var(--font-serif);font-size:32px;font-weight:500;margin:12px 0 16px">No open roles.</h2>
      <p style="color:var(--ink-3);font-size:17px;margin:0 0 24px">We are not hiring at the moment. The fastest way in is to volunteer. We hire most of our staff from the volunteer pool.</p>
      <a href="/volunteer/" class="btn btn-primary">Volunteer with POMDR</a>
    </div>

    <h2 class="section-title" style="margin:64px 0 16px">What it is like to work here</h2>
    <p style="font-size:17px;line-height:1.7">Small team, high autonomy, deep care for the dogs. We are based in Pacific Grove with three working locations: the Bauer Center, the Boand Veterinary Clinic in Monterey, and the Benefit Shop in Pacific Grove. Most roles are part time and onsite.</p>

    <h2 class="section-title" style="margin:48px 0 16px">Reach out</h2>
    <p style="font-size:17px;line-height:1.7">If you have a skill we should know about (development, communications, animal care, retail), email <a href="mailto:info@pomdr.org" style="color:var(--blue)">info@pomdr.org</a>. We keep an informal bench.</p>
  </div>
</section>

</main>

</main>
<?php get_footer();
