<?php
/** Template for the Jobs page (WP slug: jobs). Ports prototype jobs.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">

<main id="main">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Careers</h1>
    <p class="page-narrative">Work <em>with us</em>.</p>
    <p class="page-lead">POMDR is a small organization with a big mission. When we hire, this is where the openings appear.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:760px">
    <div class="card" style="padding:32px;background:#fff;border:1px solid var(--line);border-radius:var(--radius-lg)">
      <div class="eyebrow">Now hiring</div>
      <h2 style="font-family:var(--font-serif);font-size:32px;font-weight:500;margin:12px 0 10px">Relief Veterinarian and Sunday Veterinarian</h2>
      <p style="font-weight:600;color:var(--blue-text);font-size:18px;margin:0 0 16px">$800 to $1,100 per day (negotiable depending on experience and surgical skills)</p>
      <p style="font-size:17px;line-height:1.7;margin:0 0 14px">We are looking for temporary relief vets while one of our vets is on maternity leave. We need someone Mondays and every other Thursday, starting May 2026, at our Boand Veterinary Clinic in Monterey.</p>
      <p style="font-size:17px;line-height:1.7;margin:0 0 20px"><strong>Qualifications:</strong> dental and surgical skills, strong organizational and communication skills, comfort in a small clinic with volunteers, self-motivated, and a passion for helping senior dogs and senior people. Rescue or shelter experience is a plus.</p>
      <a href="mailto:carie@pomdr.org?subject=Relief%20Veterinarian" class="btn btn-primary" style="margin-right:12px">Email your resume to Carie</a>
      <span style="color:var(--ink-3);font-size:16px;display:inline-block;margin-top:10px">Deadline: open until filled.</span>
    </div>

    <div class="card" style="padding:28px;text-align:center;background:var(--cream-2);margin-top:24px;border-radius:var(--radius-lg)">
      <p style="color:var(--ink-2);font-size:17px;margin:0 0 16px">Not a vet? The fastest way in is to volunteer. We hire most of our staff from the volunteer pool.</p>
      <a href="/volunteer/" class="btn btn-outline">Volunteer with POMDR</a>
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
