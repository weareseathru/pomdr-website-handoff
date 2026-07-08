<?php
/** Template for the Veterinary Clinic page (WP slug: clinic). Ports prototype clinic.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<style>
.page-header .page-cta{display:flex;gap:14px;flex-wrap:wrap;margin-top:8px;}
/* INFO BAND */
.info-band{background:var(--blue-50);padding:64px 0;border-top:1px solid var(--line);border-bottom:1px solid var(--line);}
.info-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;}
.info-card{background:#fff;border-radius:var(--radius-lg);padding:28px;border:1px solid var(--line);}
.info-card .info-icon{width:48px;height:48px;border-radius:15px;background:var(--blue-50);display:grid;place-items:center;color:var(--blue);margin-bottom:16px;}
.info-card .info-icon svg{width:22px;height:22px;stroke:currentColor;fill:none;stroke-width:1.5;}
.info-card h3{font-family:var(--font-serif);font-size:22px;font-weight:500;margin:0 0 8px;}
.info-card p{font-size:16px;color:var(--ink-2);margin:0;line-height:1.6;}
.info-card a{color:var(--blue-700);font-weight:600;}
@media(max-width:820px){.info-grid{grid-template-columns:1fr;}}
/* SERVICES */
.svc-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:24px;}
.svc-card{background:#fff;border-radius:var(--radius-lg);padding:26px;border:1px solid var(--line);transition:all .35s var(--ease);}
.svc-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
.svc-card h3{font-family:var(--font-serif);font-size:20px;font-weight:500;margin:0 0 8px;}
.svc-card p{font-size:16px;color:var(--ink-2);margin:0;line-height:1.6;}
@media(max-width:700px){.svc-grid{grid-template-columns:1fr;}}
/* MISSION */
.mission-band{background:var(--purple-50);padding:88px 0;}
.mission-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;}
.mission-inner{max-width:760px;}
.mission-media{display:grid;gap:16px;}
.mission-media img{width:100%;height:100%;object-fit:cover;border-radius:var(--radius-lg);display:block;}
.mission-media .plaque{max-height:200px;object-fit:cover;}
@media(max-width:860px){.mission-grid{grid-template-columns:1fr;gap:28px;}}
.mission-band h2{font-family:var(--font-serif);font-size:clamp(28px,3.5vw,46px);font-weight:300;letter-spacing:-.02em;margin:10px 0 18px;color:var(--purple);}
.mission-band h2 em{font-style:italic;color:var(--purple);}
.mission-band p{font-size:18px;color:var(--ink-2);margin:0 0 18px;line-height:1.7;}
.cta-strip{padding:72px 0;background:var(--blue-50);}
</style>
<main id="main-content">
<header class="page-header">
  <div class="container">
    <div class="ph-split">
      <div class="ph-text">
        <h1 class="page-headline">Veterinary Clinic</h1>
        <p class="page-narrative">Quality care, kept <em>affordable</em>.</p>
        <p class="page-lead">The Harry and Jaynne Boand Veterinary Clinic in Monterey provides accessible, compassionate veterinary care for our rescue dogs and for the senior people and dogs we serve across California's Central Coast.</p>
        <div class="page-cta">
          <a href="tel:8317189122" class="btn btn-primary">Call the clinic</a>
          <a href="#services" class="btn btn-outline">What we offer</a>
        </div>
      </div>
      <div class="ph-media"><picture><img src="<?php echo $img; ?>/pages/clinic.jpg" alt="The Harry and Jaynne Boand Veterinary Clinic in Monterey" loading="lazy"/></picture></div>
    </div>
  </div>
</header>

<section class="info-band">
  <div class="container">
    <div class="info-grid">
      <div class="info-card">
        <div class="info-icon"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
        <h3>Visit us</h3>
        <p>1251 10th St<br/>Monterey, CA 93940</p>
      </div>
      <div class="info-card">
        <div class="info-icon"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
        <h3>Call us</h3>
        <p>To make an appointment or ask a question, call <a href="tel:8317189122">(831) 718-9122</a>.</p>
      </div>
      <div class="info-card">
        <div class="info-icon"><svg viewBox="0 0 24 24"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
        <h3>Email us</h3>
        <p>Reach the team at <a href="mailto:info@pomdr.org">info@pomdr.org</a>.</p>
      </div>
    </div>
  </div>
</section>

<section class="section" id="services">
  <div class="container">
    <span class="eyebrow blue">What we do</span>
    <h2 class="section-title blue">Care for the dogs in our rescue, and <em>beyond</em>.</h2>
    <p class="section-lead">The clinic opened in November 2019 and is the medical home for every dog in our care. It is where new arrivals get a clean start and where our long-term and hospice dogs are kept comfortable. The same clinic helps us deliver the Helping Paw Program for guardians who need a hand with veterinary costs.</p>
    <div class="svc-grid">
      <div class="svc-card">
        <h3>Intake and wellness exams</h3>
        <p>Every dog who comes into our rescue starts with a full health check, so we understand their needs and can match them with the right home.</p>
      </div>
      <div class="svc-card">
        <h3>Vaccines and preventive care</h3>
        <p>Routine vaccinations, parasite prevention, and the ongoing care that keeps our dogs healthy while they wait for adoption.</p>
      </div>
      <div class="svc-card">
        <h3>Dental and surgical care</h3>
        <p>Senior dogs often arrive with neglected teeth and other conditions. The clinic gives them the treatment they need to feel like themselves again.</p>
      </div>
      <div class="svc-card">
        <h3>Hospice and comfort care</h3>
        <p>For our hospice and sanctuary dogs, the clinic focuses on comfort, pain management, and dignity through every stage of life.</p>
      </div>
      <div class="svc-card">
        <h3>Diagnostics on site</h3>
        <p>Every new dog receives a full exam, senior blood panel, heartworm test, urinalysis, vaccines, and a microchip. We have a full x-ray machine, a dental x-ray machine, and ultrasound, so we can look closer when something needs attention.</p>
      </div>
      <div class="svc-card">
        <h3>Dental, surgery, and lumps</h3>
        <p>Most dogs need a dental cleaning and many need extractions. We also aspirate, biopsy, or remove any lumps that need it, treating conditions other shelters often cannot take on.</p>
      </div>
      <div class="svc-card">
        <h3>Specialist referrals</h3>
        <p>When a dog needs more, we refer to veterinary specialists including ophthalmology, oncology, dermatology, dentistry, and internal medicine.</p>
      </div>
    </div>
  </div>
</section>

<section class="mission-band">
  <div class="container">
    <div class="mission-grid">
      <div class="mission-inner">
        <span class="eyebrow purple">Why it matters</span>
        <h2>Good medicine is what makes a <em>second chance possible</em>.</h2>
        <p>Many of the dogs who come to us are seniors. Some have been overlooked for years, and some arrive with medical needs that other shelters could not take on. A clinic of our own means we never have to turn a dog away because care felt out of reach.</p>
        <p>Founded in 2009, Peace of Mind Dog Rescue serves senior dogs and senior people across the Central Coast. The clinic is a cornerstone of that promise, keeping costs manageable so more dogs can heal and more families can stay together.</p>
      </div>
      <div class="mission-media">
        <img class="plaque" src="<?php echo $img; ?>/pages/clinic-plaque.jpg" alt="The clinic dedication plaque" loading="lazy"/>
      </div>
    </div>
  </div>
</section>

<section class="cta-strip">
  <div class="container">
    <h2 class="serif">Have a question about your dog's care? <em>We are here.</em></h2>
    <div class="ctas">
      <a href="tel:8317189122" class="btn btn-primary">Call (831) 718-9122</a>
      <a href="/helping-paw/" class="btn btn-outline">Explore Helping Paw</a>
    </div>
  </div>
</section>

<section class="section" id="team">
  <div class="container">
    <span class="eyebrow purple">Our team</span>
    <h2 class="section-title">The people you will <em>meet</em>.</h2>
    <p class="section-lead">The veterinarians and technicians who keep our dogs healthy, from intake exams to dental work.</p>
    <?php echo do_shortcode('[clinic_staff]'); ?>
  </div>
</section>

</main>
<?php get_footer();
