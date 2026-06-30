<?php
/** Template for the Privacy Policy page (WP slug: privacy). Ports prototype privacy.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">

<main id="main">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Privacy</h1>
    <p class="page-narrative">Our <em>privacy</em> commitments.</p>
    <p class="page-lead">We protect the information you trust us with. This page explains what we collect and why.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:800px">
    <h2 class="section-title" style="margin-bottom:16px">What we collect</h2>
    <p>When you adopt, foster, volunteer, donate, or sign up for our newsletter, we collect the information you give us (name, contact details, payment information for donations). We use it only to do the thing you came here to do.</p>

    <h2 class="section-title" style="margin:48px 0 16px">How we use it</h2>
    <p>Adoption applications go to our adoption coordinators. Donations go through a secure processor. Newsletter signups go to Mailchimp. We do not sell your information, and we do not share it with anyone outside of POMDR or our regulated processors.</p>

    <h2 class="section-title" style="margin:48px 0 16px">Cookies</h2>
    <p>Our site uses a small number of analytics cookies so we can see what pages people read most. You can disable cookies in your browser without losing access to anything on this site.</p>

    <h2 class="section-title" style="margin:48px 0 16px">Questions</h2>
    <p>Reach us at <a href="mailto:info@pomdr.org" style="color:var(--blue)">info@pomdr.org</a> or (831) 718-9122.</p>

    <p style="margin-top:48px;color:var(--ink-3);font-size:16px"><em>This page is a placeholder. The final policy will be reviewed by counsel before the redesign launches.</em></p>
  </div>
</section>

</main>

</main>
<?php get_footer();
