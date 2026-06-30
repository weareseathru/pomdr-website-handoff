<?php
/**
 * Template for the Foster page (WP slug: foster-needs). Ports prototype
 * foster.html. Chrome + footer from the theme; shared look from pomdr.css.
 */
get_header();
?>
<main id="main-content">
<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Foster</h1>
    <p class="page-narrative">Open your home. Save a <em>life</em>.</p>
    <p class="page-lead">Foster homes are the heart of our rescue. When you foster a senior dog, you give them comfort, safety, and routine while we find their forever person. We cover the costs. You give the love.</p>
    <div class="page-cta">
      <a href="/volunteer-application/" class="btn btn-primary">Apply to Foster</a>
      <a href="#how" class="btn btn-outline">How it works</a>
    </div>
  </div>
</header>

<section class="section" id="how">
  <div class="container">
    <span class="eyebrow purple">How fostering works</span>
    <h2 class="section-title">Four simple steps from <em>application</em> to homecoming.</h2>
    <div class="step-row"><span class="step-num">01</span><div><h3 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">Apply</h3><p style="color:var(--ink-2);margin:0">Tell us about your home, your schedule, and the kind of dog you can welcome. We read every application personally.</p></div></div>
    <div class="step-row"><span class="step-num">02</span><div><h3 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">Match</h3><p style="color:var(--ink-2);margin:0">We pair you with a dog whose needs and energy fit your life. Senior, hospice, medical recovery, or short-term respite.</p></div></div>
    <div class="step-row"><span class="step-num">03</span><div><h3 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">We cover everything</h3><p style="color:var(--ink-2);margin:0">Vet care, medication, food, supplies. You provide the couch and the love. We pay every bill.</p></div></div>
    <div class="step-row"><span class="step-num">04</span><div><h3 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">Goodbye is part of the gift</h3><p style="color:var(--ink-2);margin:0">When the right adopter arrives, we handle the transition. You can foster again, or foster-to-adopt if your heart says yes.</p></div></div>
  </div>
</section>

<section class="section" id="provides">
  <div class="container">
    <span class="eyebrow purple">What we provide, what we ask</span>
    <h2 class="section-title">We have no shelter. Our dogs live in <em>foster homes</em>.</h2>
    <p class="page-lead">Every POMDR dog lives in a foster home as a member of the family until they find their permanent home. Foster parents are the bridge that helps a dog transition from their past, whether that was a loving home, a shelter, or a hard situation, into a calm new routine. We typically have about 80 dogs in our care at any time, with up to 15 more waiting for a foster home to open up.</p>
    <div class="step-row"><span class="step-num">01</span><div><h3 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">POMDR supplies the gear</h3><p style="color:var(--ink-2);margin:0">We provide a crate, bed, collar, harness, leash, ID tag, bowls, toys, and flea prevention. If we have it, you get it.</p></div></div>
    <div class="step-row"><span class="step-num">02</span><div><h3 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">POMDR covers the vet care</h3><p style="color:var(--ink-2);margin:0">We cover all medical expenses for your foster dog. You never pay a vet bill.</p></div></div>
    <div class="step-row"><span class="step-num">03</span><div><h3 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">You provide food and a safe home</h3><p style="color:var(--ink-2);margin:0">We ask foster homes to provide the food and a loving, safe place to land until the dog is adopted.</p></div></div>
    <div class="step-row"><span class="step-num">04</span><div><h3 style="font-family:var(--font-serif);font-size:24px;font-weight:500;margin:0 0 8px">We tell you what we know</h3><p style="color:var(--ink-2);margin:0">Some dogs have medical or behavioral needs. We disclose anything we know when you inquire, so you can choose a match that fits your home.</p></div></div>
  </div>
</section>

<section class="cta-strip" style="background:var(--blue-50)">
  <div class="container">
    <h2 class="serif">Ready to foster? <em>We need you.</em></h2>
    <div class="ctas">
      <a href="/volunteer-application/" class="btn btn-primary">Apply Now</a>
      <a href="mailto:info@pomdr.org" class="btn btn-outline">Ask a Question</a>
    </div>
  </div>
</section>
</main>
<?php get_footer();
