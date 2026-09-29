<?php
/** Template for the Mailing List page (WP slug: mailing-list). Ports prototype mailing-list.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<style>
.signup-grid{display:grid;grid-template-columns:1.1fr 1fr;gap:56px;align-items:start;}
@media(max-width:800px){.signup-grid{grid-template-columns:1fr;gap:36px;}}
.signup-card{background:#fff;padding:32px;border-radius:var(--radius-lg);border:1px solid var(--line);box-shadow:var(--shadow-sm);}
.signup-card label{display:block;font-size: 21px;font-weight:600;margin-bottom:6px;}
.signup-card input{width:100%;padding:13px 15px;border:1px solid var(--line);border-radius:12px;font:inherit;font-size: 21px;margin-bottom:18px;min-height:44px;}
.signup-card input:focus-visible{outline:3px solid var(--blue-900);outline-offset:2px;}
.signup-note{font-size: 21px;color:var(--ink-2);margin:16px 0 0;}
.benefit-list{display:flex;flex-direction:column;gap:14px;margin:24px 0 0;}
.benefit-list li{display:flex;align-items:flex-start;gap:12px;font-size: 22px;color:var(--ink-2);}
.benefit-list .ck{width:28px;height:28px;border-radius:8px;background:var(--blue-50);color:var(--blue);display:grid;place-items:center;flex-shrink:0;}
.benefit-list .ck svg{width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2.5;}
</style>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Mailing List</h1>
    <p class="page-narrative">Stay close to the <em>dogs you love</em>.</p>
    <p class="page-lead">Join our mailing list for adoptable dogs, happy tails, events, and the occasional senior pup who needs a little extra help finding home.</p>
    <div class="page-cta">
      <a href="#signup" class="btn btn-primary">Join the mailing list</a>
    </div>
  </div>
</header>

<section class="section">
  <div class="container">
    <div class="signup-grid">
      <div>
        <span class="eyebrow purple">What you will get</span>
        <h2 class="section-title">A few good emails a <em>month</em>.</h2>
        <p class="lead" style="margin-bottom:0">We respect your inbox. No daily blasts, no selling your information, ever. Unsubscribe in one click anytime.</p>
        <ul class="benefit-list">
          <li><span class="ck"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>New senior dogs looking for homes and fosters</li>
          <li><span class="ck"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>Happy tails from dogs who found their people</li>
          <li><span class="ck"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>Adoption events and volunteer opportunities</li>
          <li><span class="ck"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>Ways to help, only when it truly matters</li>
        </ul>
      </div>

      <!-- The real newsletter signup: the same LGL form the live site uses.
           Form ID verified 2026-07-07 from the iframe on the live page
           (peaceofminddogrescue.org/POMDRMailingList.php). Signups land in the
           LGL constituent database, which feeds Mailchimp. -->
      <div id="signup" class="signup-card">
        <h3 style="font-family:var(--font-serif);font-size: 29px;font-weight:500;margin:0 0 20px">Join the mailing list</h3>
        <iframe src="https://secure.lglforms.com/form_engine/s/gTxA6GdJUmSjS2J4hXvcKg"
                title="Join the POMDR mailing list"
                width="100%" height="620"
                style="border:0;display:block;background:#fff;border-radius:12px;"></iframe>
        <noscript><p>To sign up, visit <a href="https://secure.lglforms.com/form_engine/s/gTxA6GdJUmSjS2J4hXvcKg">our mailing list form</a>.</p></noscript>
        <p class="signup-note">You can unsubscribe at any time. We will never share your email.</p>
      </div>
    </div>
  </div>
</section>

<section class="cta-strip" style="background:var(--blue-50)">
  <div class="container">
    <h2 class="serif">Rather talk to a <em>real person</em>.</h2>
    <div class="ctas">
      <a href="tel:8317189122" class="btn btn-primary">Call (831) 718-9122</a>
      <a href="mailto:info@pomdr.org" class="btn btn-outline">Contact us</a>
    </div>
  </div>
</section>

</main>
<script src="https://secure.lglforms.com/form_engine/s/tfs_iframe.js"></script>
<?php get_footer();
