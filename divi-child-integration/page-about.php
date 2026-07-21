<?php
/** Template for the About page (WP slug: about). Ports prototype about.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<style>
/* PAGE HERO */
.page-hero{padding:160px 0 80px;background:linear-gradient(135deg,rgba(0,139,176,.06) 0%,transparent 60%),var(--cream);border-bottom:1px solid var(--line);}
.page-hero .container{display:grid;grid-template-columns:1.1fr .9fr;gap:56px;align-items:center;}
/* Title uses the same serif as the other titles, with italic-blue emphasis. */
.page-hero h1{font-family:var(--font-serif);font-size:clamp(22px,2.9vw,40px);font-weight:400;line-height:1.1;letter-spacing:-.02em;margin:0 0 16px;max-width:24ch;}
.page-hero h1 em{font-style:italic;font-weight:300;color:var(--blue);}
/* Placeholder slots for transparent dog cutout PNGs (added later). */
.hero-cutouts{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;align-items:end;}
.cutout-slot{aspect-ratio:3/4;border-radius:var(--radius-lg);border:2px dashed var(--blue-200);background:linear-gradient(180deg,var(--blue-50),rgba(232,242,246,.25));display:grid;place-items:center;color:var(--blue-200);}
.cutout-slot:nth-child(2){aspect-ratio:3/4.5;}
.cutout-slot svg{width:40px;height:40px;opacity:.8;}
@media(max-width:860px){.page-hero .container{grid-template-columns:1fr;gap:36px;}.hero-cutouts{max-width:440px;}}
/* MISSION BAND */
.mission-band{background:var(--blue-900);color:#fff;padding:72px 0;}
/* Top-align so the large title and the smaller body text start on the same line. */
.mission-band .container{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:start;}
.mission-band h2{font-family:var(--font-serif);font-size:clamp(28px,3.5vw,48px);font-weight:400;line-height:1.1;letter-spacing:-.02em;margin:0;}
.mission-band h2 em{font-style:italic;color:var(--blue-200);}
.mission-band p{font-size:16px;line-height:1.65;color:rgba(255,255,255,.88);margin:0 0 14px;}
.mission-band p:last-child{margin-bottom:0;}
@media(max-width:800px){.mission-band .container{grid-template-columns:1fr;gap:24px;}}
/* STATS */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:32px;padding:48px;background:var(--blue-50);border-radius:var(--radius-xl);margin-top:48px;}
.stat-item .num{font-family:var(--font-serif);font-size:clamp(36px,4.5vw,64px);font-weight:300;line-height:1;color:var(--blue-700);}
.stat-item .lbl{font-size:14px;letter-spacing:.14em;text-transform:uppercase;font-weight:600;color:var(--ink-3);margin-top:8px;}
@media(max-width:700px){.stats-row{grid-template-columns:1fr 1fr;padding:28px;}}
/* FOUNDERS QUOTE */
.founders-band{background:var(--purple);color:#fff;padding:72px 0;text-align:center;}
.founders-band blockquote{font-family:var(--font-serif);font-size:clamp(24px,3.5vw,42px);font-weight:300;font-style:italic;line-height:1.15;letter-spacing:-.02em;max-width:860px;margin:0 auto 20px;}
.founders-band cite{font-size:15px;letter-spacing:.18em;text-transform:uppercase;opacity:.75;}
/* TIMELINE */
.timeline{margin-top:40px;display:flex;flex-direction:column;}
.tl-item{display:grid;grid-template-columns:90px 1fr;gap:28px;padding:28px 0;border-top:1px solid var(--line);}
.tl-item:last-child{border-bottom:1px solid var(--line);}
.tl-year{font-family:var(--font-serif);font-size:32px;font-weight:300;color:var(--blue);line-height:1;}
.tl-content h4{font-family:var(--font-serif);font-size:20px;font-weight:500;margin:0 0 6px;}
.tl-content p{font-size:16px;color:var(--ink-2);margin:0;}
/* TEAM */
.team-section{background:var(--cream-2);padding:100px 0;}
.team-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:40px;}
.person-card{background:#fff;border-radius:var(--radius);padding:22px;text-align:center;border:1px solid var(--line);}
.person-card .avatar{width:72px;height:72px;border-radius:50%;background:var(--blue-100);margin:0 auto 14px;display:flex;align-items:center;justify-content:center;font-family:var(--font-serif);font-size:24px;color:var(--blue-700);}
.person-card h4{font-family:var(--font-serif);font-size:18px;font-weight:500;margin:0 0 5px;}
.person-card .role{font-size:14px;letter-spacing:.1em;text-transform:uppercase;font-weight:600;color:var(--blue);}
@media(max-width:1000px){.team-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:700px){.team-grid{grid-template-columns:repeat(2,1fr);}}
/* BOARD */
.board-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:28px;}
.board-card{background:var(--ink);color:#fff;border-radius:var(--radius);padding:26px;}
.board-card .bname{font-family:var(--font-serif);font-size:20px;font-weight:400;margin:0 0 6px;}
.board-card .brole{font-size:14px;letter-spacing:.14em;text-transform:uppercase;color:var(--blue-200);font-weight:600;}
@media(max-width:700px){.board-grid{grid-template-columns:1fr 1fr;}.team-grid{grid-template-columns:1fr 1fr;}}
/* LOCATIONS */
.locations-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:40px;}
.location-card{background:#fff;border-radius:var(--radius-lg);padding:28px;border:1px solid var(--line);transition:all .3s var(--ease);}
.location-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
.location-card .loc-icon{width:48px;height:48px;border-radius:14px;background:var(--blue-50);display:grid;place-items:center;color:var(--blue);margin-bottom:18px;}
.location-card .loc-icon svg{width:22px;height:22px;stroke:currentColor;fill:none;stroke-width:1.5;}
.location-card h3{font-family:var(--font-serif);font-size:20px;font-weight:500;margin:0 0 8px;}
.location-card p{font-size:16px;color:var(--ink-2);margin:0 0 5px;}
.location-card .hours{font-size:15px;color:var(--ink-3);margin-top:12px;padding-top:12px;border-top:1px dashed var(--line);}
@media(max-width:800px){.locations-grid{grid-template-columns:1fr;}}
/* CTA STRIP */
.cta-strip{padding:72px 0;background:var(--blue-50);}
/* WHAT WE DO: text + photo lead, then the cards read across the width in 2 cols. */
.whatwedo-lead{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;margin-top:8px;}
.whatwedo-lead__media{width:100%;height:100%;min-height:260px;max-height:380px;object-fit:cover;border-radius:var(--radius-lg);display:block;}
.cards-2col{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:32px;}
@media(max-width:860px){.whatwedo-lead{grid-template-columns:1fr;gap:26px;}.cards-2col{grid-template-columns:1fr;}}
/* RECOGNITION: award logos sit side by side, not stacked. */
.recognition-row{display:grid;grid-template-columns:repeat(2,auto);gap:40px;align-items:center;justify-content:start;margin-top:32px;}
.recognition-row img{height:96px;width:auto;display:block;}
@media(max-width:560px){.recognition-row{grid-template-columns:1fr;gap:24px;justify-items:start;}}
</style>
<main id="main-content">


<script>
  // Scroll effect
  window.addEventListener('scroll', () => {
    document.getElementById('site-nav').classList.toggle('scrolled', window.scrollY > 40);
  });
</script>
<style>
/* About-page hero: the building photo shows whole (no crop) and gets more
   width. The image renders at its natural 3:2 ratio inside rounded corners. */
.page-hero > .container { display: block; } /* the shared hero container is a 2-col grid; this page lays out with ph-split instead */
.page-hero .ph-split { width: 100%; grid-template-columns: 0.92fr 1.08fr; align-items: center; } /* width:100%: the page-hero container is a centering grid that otherwise shrink-fits this */
.page-hero .ph-media { min-height: 0; background: none; border-radius: 0; }
.page-hero .ph-media picture { height: auto; }
.page-hero .ph-media img { width: 100%; height: auto; object-fit: contain; border-radius: var(--radius-xl); }

/* What we do: editorial rows instead of small text cards. Big serif statements
   (the site's hero word treatment), a number rail, hairline separators, photos
   on alternating sides, and a soft brand paw watermark on the text-only rows. */
.wwd-rows { margin-top: 24px; }
.wwd-row {
  display: grid; grid-template-columns: 88px 1.15fr 0.85fr;
  gap: 36px; align-items: center;
  padding: 52px 0; border-top: 1px solid var(--line);
  position: relative;
}
.wwd-row:last-child { border-bottom: 1px solid var(--line); }
.wwd-num {
  font-family: var(--font-serif); font-size: 22px; color: var(--blue-text);
  letter-spacing: 0.06em; align-self: start; padding-top: 14px;
}
.wwd-title {
  font-family: var(--font-serif); font-weight: 400;
  font-size: clamp(30px, 3.4vw, 46px); line-height: 1.04; letter-spacing: -0.02em;
  margin: 0 0 16px; color: var(--ink);
}
.wwd-title em { font-style: italic; color: var(--blue); }
.wwd-row p { font-size: 17px; line-height: 1.7; color: var(--ink-2); max-width: 54ch; margin: 0 0 20px; }
.wwd-media { border-radius: var(--radius-lg); overflow: hidden; aspect-ratio: 4/3; }
.wwd-media img { width: 100%; height: 100%; object-fit: cover; display: block; }
.wwd-row--flip { grid-template-columns: 88px 0.85fr 1.15fr; }
.wwd-row--flip .wwd-media { order: 2; }
.wwd-row--flip .wwd-body { order: 3; }
.wwd-row--text { grid-template-columns: 88px 1fr; }
.wwd-row--text .wwd-paw {
  position: absolute; right: 2%; top: 50%; transform: translateY(-50%) rotate(12deg);
  width: 150px; height: 150px; opacity: 0.1; pointer-events: none;
}
@media (max-width: 860px) {
  .wwd-row, .wwd-row--flip { grid-template-columns: 1fr; gap: 20px; padding: 40px 0; }
  .wwd-num { padding-top: 0; }
  .wwd-row--flip .wwd-media, .wwd-row--flip .wwd-body { order: 0; }
  .wwd-row--text .wwd-paw { width: 100px; height: 100px; opacity: 0.07; }
}

.section-sm ul a { color: var(--blue-text); font-weight: 600; text-decoration: underline; text-underline-offset: 3px; display: inline-flex; min-height: 40px; align-items: center; }
</style>
<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container">
    <div class="ph-split">
      <div class="ph-text">
        <h1 class="page-headline">Our Story</h1>
        <p class="page-narrative">Advocating for seniors. <em>Dogs and people</em> alike.</p>
        <p>Peace of Mind Dog Rescue is a 501(c)(3) nonprofit based on California's Central Coast, dedicated to senior dogs and the senior people who love them.</p>
        <div style="display:flex;gap:14px;flex-wrap:wrap;margin-top:32px;">
          <a href="/adopt/" class="btn btn-primary">Meet our dogs</a>
          <a href="/volunteer/" class="btn btn-outline">Volunteer with us</a>
        </div>
      </div>
      <div class="ph-media">
        <picture>          <img src="<?php echo $img; ?>/pages/pomdr-house.jpg" alt="The POMDR Patricia J. Bauer Center in Pacific Grove" loading="lazy">
        </picture>
      </div>
    </div>
  </div>
</section>

<!-- MISSION BAND -->
<div class="mission-band">
  <div class="container">
    <div>
      <h2>Our mission is to be a resource and advocate for <em>senior dogs and senior people</em> on California's Central Coast.</h2>
    </div>
    <div>
      <p>We focus on helping dogs and people from Monterey, Santa Cruz and San Benito counties.</p>
      <p>Our vision is to model lifetime care for dogs and all companion animals, to help bring about a positive change in the way society thinks about and treats senior dogs, and to create better lives for them through rescue, foster, adoption, hospice, and education.</p>
    </div>
  </div>
</div>

<!-- STATS -->
<section class="section">
  <div class="container">
    <div class="eyebrow">By the Numbers</div>
    <h2 class="section-title">16 years of <em>quiet, steady love.</em></h2>
    <div class="stats-row">
      <div class="stat-item"><div class="num">3,500+</div><div class="lbl">Senior dogs adopted</div></div>
      <div class="stat-item"><div class="num">1,500+</div><div class="lbl">Volunteers</div></div>
      <div class="stat-item"><div class="num">16 yrs</div><div class="lbl">Of lifetime commitment</div></div>
      <div class="stat-item"><div class="num">3</div><div class="lbl">Tri-county area served</div></div>
    </div>
  </div>
</section>

<!-- WHAT WE DO -->
<section class="section">
  <div class="container">
    <div class="eyebrow">What We Do</div>
    <h2 class="section-title">A lifetime <em>commitment.</em></h2>
    <div class="wwd-rows">

      <article class="wwd-row reveal">
        <div class="wwd-num">01</div>
        <div class="wwd-body">
          <h3 class="wwd-title">A <em>soft landing</em> for every dog.</h3>
          <p>We find loving foster and forever homes for dogs whose guardians can no longer care for them, and for senior dogs in shelters.</p>
          <a href="/adopt/" class="btn btn-primary">Meet our dogs</a>
        </div>
        <div class="wwd-media"><img src="<?php echo $img; ?>/dog12.jpeg" alt="A senior POMDR dog resting in a foster home" loading="lazy"></div>
      </article>

      <article class="wwd-row wwd-row--flip reveal">
        <div class="wwd-num">02</div>
        <div class="wwd-media"><img src="<?php echo $img; ?>/pages/helpingpaw.jpg" alt="A Helping Paw volunteer with a senior guardian and their dog" loading="lazy"></div>
        <div class="wwd-body">
          <h3 class="wwd-title">Keeping seniors and their dogs <em>together.</em></h3>
          <p>We help senior citizens pay for veterinary care when they cannot afford it, provide temporary foster care for people who are hospitalized, and walk dogs for people who can no longer walk them.</p>
          <a href="/helping-paw/" class="btn btn-primary">Explore Helping Paw</a>
        </div>
      </article>

      <article class="wwd-row wwd-row--text reveal">
        <div class="wwd-num">03</div>
        <div class="wwd-body">
          <h3 class="wwd-title">Peace of mind, <em>pre-arranged.</em></h3>
          <p>We make pre-arrangements to take in dogs should their guardians become unable to care for them, so no one has to worry about what happens next.</p>
          <a href="/perpetual-care-program/" class="btn btn-outline">Perpetual Care Program</a>
        </div>
        <img class="wwd-paw" src="<?php echo $img; ?>/paw-cyan.svg" alt="" role="presentation">
      </article>

      <article class="wwd-row wwd-row--text reveal">
        <div class="wwd-num">04</div>
        <div class="wwd-body">
          <h3 class="wwd-title">A home for <em>life.</em></h3>
          <p>Every dog who comes into our care is either adopted into a wonderful, permanent home or lives out their life in one of our foster homes. Sometimes a senior dog should not have to endure one more move, and they stay with us.</p>
          <a href="/sponsor-a-dog/" class="btn btn-outline">Sponsor a dog in our care</a>
        </div>
        <img class="wwd-paw" src="<?php echo $img; ?>/paw-cyan.svg" alt="" role="presentation">
      </article>

    </div>
  </div>
</section>

<!-- OUR VALUES -->
<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <div class="eyebrow">Our Values</div>
    <h2 class="section-title">How we <em>show up.</em></h2>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;margin-top:40px;">
      <article class="card" style="padding:28px;">
        <h3 style="font-family:var(--font-serif);font-size:20px;margin:0 0 12px;">Compassionate Food Policy</h3>
        <p style="color:var(--ink-2);font-size:16px;line-height:1.65;margin:0;">We believe all animals should be treated humanely and with compassion throughout their lives. To honor that, POMDR serves only vegetarian food at all POMDR events, from small staff and volunteer meetings to large gatherings like our annual Lucky Dog Gala. We recommend the same when others host, though we do not control food options at outside-hosted events.</p>
      </article>
      <article class="card" style="padding:28px;">
        <h3 style="font-family:var(--font-serif);font-size:20px;margin:0 0 12px;">Diversity, Equity and Inclusion</h3>
        <p style="color:var(--ink-2);font-size:16px;line-height:1.65;margin:0;">Diversity, equity, and inclusion matter to POMDR across our staff, volunteers, adopters, and Helping Paw clients. We do not tolerate discrimination or harassment of any kind based on race, color, sex, religion, sexual orientation, national origin, disability, genetic information, or pregnancy. Hiring decisions are based solely on qualifications, merit, and business needs. Adoption decisions are based on the best match for the dog and adopter, considering the dog's activity level, special needs, size, health, and temperament.</p>
      </article>
    </div>
    <div class="recognition-row">
      <img src="<?php echo $img; ?>/pages/greymuzzle-2025.png" alt="Grey Muzzle Organization 2025 grant recipient" loading="lazy">
      <img src="<?php echo $img; ?>/pages/saving-senior-dogs.png" alt="Saving senior dogs recognition" loading="lazy">
    </div>
  </div>
</section>

<!-- REPORTS AND FILINGS -->
<section class="section-sm">
  <div class="container" style="max-width:840px">
    <div class="eyebrow">Accountability</div>
    <h2 class="section-title">Reports and <em>filings.</em></h2>
    <ul style="list-style:none;margin:16px 0 0;padding:0;display:grid;grid-template-columns:1fr 1fr;gap:10px 32px">
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRImpactReport2025" target="_blank" rel="noopener">2025 Impact Report</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRImpactReport2024.pdf" target="_blank" rel="noopener">2024 Impact Report</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRAnnualReport2023.pdf" target="_blank" rel="noopener">2023 Annual Report</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDR990form2024.pdf" target="_blank" rel="noopener">2024 990 Form</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDR990form2023.pdf" target="_blank" rel="noopener">2023 990 Form</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDR990form2022.pdf" target="_blank" rel="noopener">2022 990 Form</a></li>
    </ul>
    <p style="margin-top:18px;color:var(--ink-3);font-size:15px">These files are currently hosted on the existing site; they move into this site&rsquo;s media library at launch.</p>
  </div>
</section>

<!-- FOUNDERS QUOTE -->
<div class="founders-band">
  <div class="container">
    <blockquote>"Every senior dog deserves a second chance. Every senior person deserves peace of mind."</blockquote>
    <cite>Monica Rua and Carie Broecker, Co-Founders</cite>
  </div>
</div>

<!-- HISTORY TIMELINE -->
<section class="section">
  <div class="container">
    <div class="eyebrow">Our History</div>
    <h2 class="section-title">How it <em>started.</em></h2>
    <div class="timeline">
      <div class="tl-item">
        <div class="tl-year">2009</div>
        <div class="tl-content">
          <h4>Founded in October</h4>
          <p>Monica Rua and Carie Broecker co-found Peace of Mind Dog Rescue in Pacific Grove, CA, with a focus on senior dogs and the senior people who love them.</p>
        </div>
      </div>
      <div class="tl-item">
        <div class="tl-year">2012</div>
        <div class="tl-content">
          <h4>Helping Paw Program Launched</h4>
          <p>We expand our mission to support senior guardians facing hardship, offering walking brigades, financial assistance, and temporary foster care to keep pets and people together.</p>
        </div>
      </div>
      <div class="tl-item">
        <div class="tl-year">2017</div>
        <div class="tl-content">
          <h4>Patricia J. Bauer Center Opens</h4>
          <p>POMDR opens its forever home at 615 Forest Avenue, Pacific Grove. The Patricia J. Bauer Center, a dedicated space for dogs, volunteers, and community.</p>
        </div>
      </div>
      <div class="tl-item">
        <div class="tl-year">2021</div>
        <div class="tl-content">
          <h4>Harry and Jaynne Boand Veterinary Clinic</h4>
          <p>The POMDR Boand Clinic opens at 1251 10th St, Monterey, giving our dogs access to in-house veterinary care and specialist services.</p>
        </div>
      </div>
      <div class="tl-item">
        <div class="tl-year">2025</div>
        <div class="tl-content">
          <h4>1,500+ Volunteers & Growing</h4>
          <p>Our volunteer base reaches over 1,500 dedicated community members, fostering, transporting, walking, writing bios, and championing senior dogs every day.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- BOARD OF DIRECTORS -->
<section class="team-section">
  <div class="container">
    <div class="eyebrow">Leadership</div>
    <h2 class="section-title">Board of <em>Directors.</em></h2>
    <?php echo do_shortcode("[team_board]"); ?>

    <div class="eyebrow" style="margin-top:60px;">Our Team</div>
    <h2 class="section-title">Staff & <em>Advisory Council.</em></h2>
    <?php echo do_shortcode("[team_office]"); ?>

    <div class="eyebrow" style="margin-top:60px;color:var(--purple);">Advisory Council</div>
    <?php echo do_shortcode("[advisory_council]"); ?>
  </div>
</section>

<!-- LOCATIONS -->
<section class="section">
  <div class="container">
    <div class="eyebrow">Our Locations</div>
    <h2 class="section-title">Three places, <em>one mission.</em></h2>
    <div class="locations-grid">
      <div class="location-card">
        <div class="loc-icon"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <h3>Patricia J. Bauer Center</h3>
        <p>615 Forest Avenue<br>Pacific Grove, CA 93950</p>
        <p>Main office, adoptions &amp; volunteer hub.</p>
        <div class="hours">Mon – Sat, 11am – 4pm<br>Adoptable dogs by appointment only</div>
      </div>
      <div class="location-card">
        <div class="loc-icon"><svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
        <h3>Harry &amp; Jaynne Boand Clinic</h3>
        <p>1251 10th St<br>Monterey, CA 93940</p>
        <p>In-house veterinary care for all POMDR dogs.</p>
        <div class="hours">Call (831) 718-9122 for clinic hours</div>
      </div>
      <div class="location-card">
        <div class="loc-icon"><svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
        <h3>POMDR Benefit Shop</h3>
        <p>223 Grand Avenue, Suite 1<br>Pacific Grove, CA 93950</p>
        <p>Shop where hidden gems are found. Every purchase supports senior dogs.</p>
        <div class="hours">See shop hours &amp; donation needs</div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-strip">
  <div class="container">
    <h2>Ready to make a <em>difference?</em></h2>
    <div class="ctas">
      <a href="/adopt/" class="btn btn-primary">Meet our dogs
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
      </a>
      <a href="/volunteer/" class="btn btn-outline">Volunteer with us</a>
      <a href="/donate/" class="btn btn-outline">Ways to give</a>
    </div>
  </div>
</section>

<!-- FOOTER -->

</main>
<?php get_footer();
