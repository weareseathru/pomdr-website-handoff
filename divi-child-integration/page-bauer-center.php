<?php
/** Template for the Bauer Center page (WP slug: bauer-center). Ports prototype bauer-center.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<style>
.page-header .page-cta{display:flex;gap:14px;flex-wrap:wrap;margin-top:8px;}
/* INFO BAND */
.info-band{background:var(--purple-50);padding:64px 0;border-top:1px solid var(--line);border-bottom:1px solid var(--line);}
.info-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;}
.info-card{background:#fff;border-radius:var(--radius-lg);padding:28px;border:1px solid var(--line);}
.info-card .info-icon{width:48px;height:48px;border-radius:15px;background:var(--purple-50);display:grid;place-items:center;color:var(--purple);margin-bottom:16px;}
.info-card .info-icon svg{width:22px;height:22px;stroke:currentColor;fill:none;stroke-width:1.5;}
.info-card h3{font-family:var(--font-serif);font-size:22px;font-weight:500;margin:0 0 8px;}
.info-card p{font-size:16px;color:var(--ink-2);margin:0;line-height:1.6;}
.info-card a{color:var(--purple);font-weight:600;}
@media(max-width:820px){.info-grid{grid-template-columns:1fr;}}
/* HAPPENS HERE */
.here-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:24px;}
.here-card{background:#fff;border-radius:var(--radius-lg);padding:26px;border:1px solid var(--line);transition:all .35s var(--ease);}
.here-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
.here-card h3{font-family:var(--font-serif);font-size:20px;font-weight:500;margin:0 0 8px;}
.here-card p{font-size:16px;color:var(--ink-2);margin:0;line-height:1.6;}
@media(max-width:700px){.here-grid{grid-template-columns:1fr;}}
/* VISIT BAND */
.visit-band{background:var(--blue-50);padding:88px 0;}
.visit-inner{max-width:760px;}
.visit-band h2{font-family:var(--font-serif);font-size:clamp(28px,3.5vw,46px);font-weight:300;letter-spacing:-.02em;margin:10px 0 18px;color:var(--blue-700);}
.visit-band h2 em{font-style:italic;color:var(--blue);}
.visit-band p{font-size:18px;color:var(--ink-2);margin:0 0 18px;line-height:1.7;}
.cta-strip{padding:72px 0;background:var(--purple-50);}
</style>
<main id="main-content">
<header class="page-header">
  <div class="container">
    <h1 class="page-headline">The Bauer Center <span class="page-headline-sub">Our home base</span></h1>
    <p class="page-narrative">Where dogs and people <em>find each other</em>.</p>
    <p class="page-lead">The Patricia J. Bauer Center in Pacific Grove is the home of Peace of Mind Dog Rescue. It is our office and our adoption center, the place where staff, volunteers, and visiting families come together to help senior dogs find their people.</p>
    <div class="page-cta">
      <a href="#visit" class="btn btn-purple">Plan your visit</a>
      <a href="/adopt/" class="btn btn-outline">Meet our dogs</a>
    </div>
  </div>
</header>

<section class="info-band">
  <div class="container">
    <div class="info-grid">
      <div class="info-card">
        <div class="info-icon"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
        <h3>Visit us</h3>
        <p>615 Forest Ave<br/>Pacific Grove, CA 93950</p>
      </div>
      <div class="info-card">
        <div class="info-icon"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
        <h3>Call us</h3>
        <p>Reach the team at <a href="tel:8317189122">(831) 718-9122</a> with any question.</p>
      </div>
      <div class="info-card">
        <div class="info-icon"><svg viewBox="0 0 24 24"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
        <h3>Email us</h3>
        <p>Write to <a href="mailto:info@pomdr.org">info@pomdr.org</a> and we will get back to you.</p>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <span class="eyebrow purple">What happens here</span>
    <h2 class="section-title">One building, <em>many good things</em>.</h2>
    <p class="section-lead">The Bauer Center is more than an office. It is where adoptions begin, where volunteers gather, and where the day-to-day work of caring for our dogs gets done.</p>
    <div class="here-grid">
      <div class="here-card">
        <h3>Adoption center</h3>
        <p>Come spend time with our dogs and talk with the people who know them best. We take adoptions slowly and thoughtfully, so every match is the right one.</p>
      </div>
      <div class="here-card">
        <h3>Our office</h3>
        <p>The staff who keep the rescue running work from the Bauer Center. It is the first point of contact for adopters, fosters, and supporters.</p>
      </div>
      <div class="here-card">
        <h3>Volunteer home base</h3>
        <p>Volunteers check in here before walks, events, and shifts. If you want to get involved, this is where it starts.</p>
      </div>
      <div class="here-card">
        <h3>Programs and support</h3>
        <p>Helping Paw, fostering, and donor support are all coordinated from here, keeping senior dogs and senior people connected.</p>
      </div>
    </div>
  </div>
</section>

<section class="visit-band" id="visit">
  <div class="container">
    <div class="visit-inner">
      <span class="eyebrow light">Planning a visit</span>
      <h2>Stop by and say <em>hello</em>.</h2>
      <p>You are welcome at the Bauer Center, whether you are ready to adopt, thinking about fostering, or simply want to learn more about what we do. We recommend calling ahead so we can make sure the right person is available to greet you and introduce you to our dogs.</p>
      <p>Peace of Mind Dog Rescue was founded in 2009 and serves senior dogs and senior people across California's Central Coast. The Patricia J. Bauer Center is the heart of that work, and the door is open.</p>
      <div class="page-cta" style="margin-top:24px;">
        <a href="tel:8317189122" class="btn btn-purple">Call to plan a visit</a>
        <a href="/adopt/" class="btn btn-outline">See dogs available now</a>
      </div>
    </div>
  </div>
</section>

<section class="cta-strip">
  <div class="container">
    <h2 class="serif">Ready to meet a dog in person? <em>We would love that.</em></h2>
    <div class="ctas">
      <a href="/adopt/" class="btn btn-purple">Meet our dogs</a>
      <a href="tel:8317189122" class="btn btn-outline">Call (831) 718-9122</a>
    </div>
  </div>
</section>
</main>
<?php get_footer();
