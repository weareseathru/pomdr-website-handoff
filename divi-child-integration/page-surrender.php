<?php
/** Template for the Surrender page (WP slug: surrender). Ports prototype surrender.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<style>
.page-hero{padding:160px 0 80px;background:var(--ink);color:#fff;position:relative;overflow:hidden;}
.page-hero .eyebrow{color:#fff;}
.page-hero .eyebrow::before{background:rgba(255,255,255,.65);}
.page-hero::before{content:"";position:absolute;inset:0;background:radial-gradient(ellipse at 0% 0%,rgba(0,139,176,.3),transparent 55%),radial-gradient(ellipse at 100% 100%,rgba(99,47,136,.2),transparent 55%);}
.hero-inner{position:relative;z-index:1;display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:end;}
.hero-card{background:rgba(255,255,255,.08);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:28px;}
.hero-card h3{font-family:var(--font-serif);font-size:20px;font-weight:400;margin:0 0 12px;color:#fff;}
.hero-card p{font-size:15px;color:rgba(255,255,255,.8);margin:0 0 18px;}
@media(max-width:900px){.hero-inner{grid-template-columns:1fr;gap:36px;}}
/* OPTIONS */
.options-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:16px;}
.option-card{background:#fff;border-radius:var(--radius-lg);padding:28px;border:1px solid var(--line);transition:all .35s var(--ease);display:flex;flex-direction:column;}
.option-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
.option-card.featured{background:var(--blue-700);color:#fff;border-color:var(--blue);}
.option-card.featured p{color:rgba(255,255,255,.85);}
.opt-icon{width:48px;height:48px;border-radius:15px;background:var(--blue-50);display:grid;place-items:center;color:var(--blue);margin-bottom:18px;}
.option-card.featured .opt-icon{background:rgba(255,255,255,.15);color:#fff;}
.opt-icon svg{width:22px;height:22px;stroke:currentColor;fill:none;stroke-width:1.5;}
.option-card h3{font-family:var(--font-serif);font-size:22px;font-weight:500;margin:0 0 8px;}
.option-card p{font-size:16px;color:var(--ink-2);margin:0 0 14px;flex:1;}
.opt-link{display:inline-flex;align-items:center;gap:6px;font-size:15px;font-weight:600;color:var(--blue);margin-top:auto;}
.option-card.featured .opt-link{color:rgba(255,255,255,.88);}
.opt-link::after{content:"→";transition:transform .25s;}
.option-card:hover .opt-link::after{transform:translateX(4px);}
@media(max-width:800px){.options-grid{grid-template-columns:1fr;}}
/* PROCESS */
.process-section{background:var(--cream-2);padding:100px 0;}
.process-intro{display:grid;grid-template-columns:1.25fr 1fr;gap:48px;align-items:center;margin-bottom:36px;}
.process-intro__media{width:100%;height:100%;min-height:260px;max-height:360px;object-fit:cover;border-radius:var(--radius-lg);display:block;}
.steps-2col{display:grid;grid-template-columns:1fr 1fr;gap:0 56px;}
.steps-2col .step{padding:22px 0;}
@media(max-width:860px){.process-intro{grid-template-columns:1fr;gap:26px;}.steps-2col{grid-template-columns:1fr;gap:0;}}
/* PERPETUAL */
.perpetual-section{background:var(--purple);color:#fff;padding:100px 0;}
.perpetual-inner{display:grid;grid-template-columns:1.1fr 1fr;gap:72px;align-items:start;}
.perpetual-section h2{font-family:var(--font-serif);font-size:clamp(30px,4vw,52px);font-weight:300;letter-spacing:-.025em;margin:10px 0 18px;line-height:1.05;}
.perpetual-section h2 em{font-style:italic;color:var(--purple-100);}
.perpetual-section p{color:rgba(255,255,255,.85);font-size:16px;margin:0 0 18px;}
.perp-steps{display:flex;flex-direction:column;gap:14px;margin-top:10px;}
.perp-step{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:14px;padding:18px 20px;display:flex;gap:14px;align-items:flex-start;}
.perp-num{font-family:var(--font-serif);font-size:26px;font-weight:300;color:var(--purple-100);line-height:1;flex-shrink:0;width:32px;}
.perp-step h4{font-family:var(--font-serif);font-size:17px;font-weight:500;margin:0 0 5px;}
.perp-step p{font-size:15px;opacity:.8;margin:0;}
.perp-cta{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);border-radius:var(--radius);padding:24px;margin-top:20px;}
.perp-cta h3{font-family:var(--font-serif);font-size:18px;font-weight:400;margin:0 0 10px;}
.perp-cta p{font-size:16px;opacity:.8;margin:0 0 16px;}
@media(max-width:800px){.perpetual-inner{grid-template-columns:1fr;gap:36px;}}
/* QUOTE */
.quote-band{padding:72px 0;text-align:center;background:var(--blue-50);}
.quote-band blockquote{font-family:var(--font-serif);font-size:clamp(20px,3vw,36px);font-weight:300;font-style:italic;line-height:1.2;letter-spacing:-.015em;max-width:760px;margin:0 auto 16px;color:var(--blue-900);}
.quote-band cite{font-size:14px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-3);}
/* FAQ */
.faq-section{padding:100px 0;}
.faq-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:36px;}
.faq-item{background:#fff;border-radius:var(--radius);padding:24px;border:1px solid var(--line);}
.faq-item h4{font-family:var(--font-serif);font-size:18px;font-weight:500;margin:0 0 10px;}
.faq-item p{font-size:16px;color:var(--ink-2);margin:0;}
@media(max-width:700px){.faq-grid{grid-template-columns:1fr;}}
/* CTA */
.cta-strip{padding:72px 0;background:var(--ink);color:#fff;}
.cta-strip h2 em{color:var(--blue-200);}
</style>
<main id="main-content">


<script>
  // Scroll effect
  window.addEventListener('scroll', () => {
    document.getElementById('site-nav').classList.toggle('scrolled', window.scrollY > 40);
  });
</script><!-- HERO -->
<section class="page-hero">
  <div class="container">
    <div class="hero-inner">
      <div>
        <h1 class="page-headline">Placing Your Dog</h1>
        <p class="page-narrative">We'll be here, <em>whatever comes next.</em></p>
        <p class="hero-lead">Whether you need to surrender your dog now, or want peace of mind for the future, POMDR is a resource and partner, not just a rescue.</p>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
          <a href="https://www.peaceofminddogrescue.org/POMDRsurrenderapplication.php" target="_blank" style="background:var(--blue-700);color:#fff;padding:16px 26px;border-radius:999px;font-weight:600;font-size:15px;display:inline-flex;align-items:center;gap:10px;transition:all .25s;box-shadow:0 10px 24px -6px rgba(0,139,176,.5);">
            Start the Process
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
          </a>
          <a href="#perpetual" style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.25);padding:16px 26px;border-radius:999px;font-weight:600;font-size:15px;display:inline-flex;align-items:center;gap:10px;transition:all .25s;">Learn About Perpetual Care</a>
        </div>
      </div>
      <div class="hero-card">
        <h3>Have a question first?</h3>
        <p>We understand this is a difficult decision. Please reach out. We are here to listen and help you find the best path forward for you and your dog.</p>
        <div style="display:flex;flex-direction:column;gap:12px;font-size:15px;">
          <a href="tel:8317189122" style="display:flex;align-items:center;gap:12px;color:var(--blue-200);font-weight:500;">
            <span style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:grid;place-items:center;flex-shrink:0;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.62 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </span>
            (831) 718-9122
          </a>
          <a href="mailto:info@pomdr.org" style="display:flex;align-items:center;gap:12px;color:var(--blue-200);font-weight:500;">
            <span style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:grid;place-items:center;flex-shrink:0;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </span>
            info@pomdr.org
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SURRENDER OPTIONS -->
<section class="section">
  <div class="container">
    <div style="font-size:17px;letter-spacing:.2em;text-transform:uppercase;font-weight:600;color:var(--blue);display:inline-flex;align-items:center;gap:10px;margin-bottom:16px;"><span style="width:24px;height:1px;background:var(--blue-700);display:inline-block;"></span>Placing Your Dog</div>
    <h2 class="section-title">We have <em>options.</em></h2>
    <p class="section-lead">We work with guardians in a variety of situations. Whether you are facing an immediate need or planning ahead, we'll find the right path together.</p>
    <div class="options-grid">
      <div class="option-card featured">
        <div class="opt-icon"><svg viewBox="0 0 24 24"><path d="M12 21s-8-5.5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 11-8 11z"/></svg></div>
        <h3>Surrender to POMDR</h3>
        <p style="color:rgba(255,255,255,.85);">Place your dog directly into our care. We accept dogs from Monterey, Santa Cruz and San Benito counties. We work with a waitlist, so please plan ahead when possible.</p>
        <div style="font-size:15px;padding-top:16px;border-top:1px solid rgba(255,255,255,.2);color:rgba(255,255,255,.65);margin-bottom:16px;">We are rarely able to accept dogs outside our tri-county area.</div>
        <a href="https://www.peaceofminddogrescue.org/POMDRsurrenderapplication.php" target="_blank" class="opt-link">Start Surrender Application</a>
      </div>
      <div class="option-card">
        <div class="opt-icon" style="background:var(--purple-50);color:var(--purple);"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <h3>Courtesy Listing</h3>
        <p>If we are unable to take your dog directly, we can list them on our website as a courtesy listing, helping you find a home on your own while we provide resources and support.</p>
        <div style="font-size:15px;color:var(--ink-3);padding-top:16px;border-top:1px dashed var(--line);margin-bottom:16px;">Guardian remains responsible for dog during listing period.</div>
        <a href="https://www.peaceofminddogrescue.org/POMDRsurrendercourtesy.php" target="_blank" class="opt-link">Request a Courtesy Listing</a>
      </div>
      <div class="option-card">
        <div class="opt-icon" style="background:#F1EBF5;color:#632F88;"><svg viewBox="0 0 24 24"><path d="M22 12s-2.5 7-10 7S2 12 2 12s2.5-7 10-7 10 7 10 7z"/><circle cx="12" cy="12" r="3"/></svg></div>
        <h3>Helping Paw First</h3>
        <p>Before surrendering, check if the Helping Paw Program can help you keep your dog. We offer walking assistance, financial aid and temporary foster care for guardians in crisis.</p>
        <div style="font-size:15px;color:var(--ink-3);padding-top:16px;border-top:1px dashed var(--line);margin-bottom:16px;">Available to seniors and people with disabilities in our tri-county area.</div>
        <a href="/helping-paw/" class="opt-link">Learn about Helping Paw</a>
      </div>
    </div>
  </div>
</section>

<!-- SURRENDER PROCESS -->
<section class="process-section">
  <div class="container">
    <div class="process-intro">
      <div>
        <div style="font-size:17px;letter-spacing:.2em;text-transform:uppercase;font-weight:600;color:var(--blue);display:inline-flex;align-items:center;gap:10px;margin-bottom:16px;"><span style="width:24px;height:1px;background:var(--blue-700);display:inline-block;"></span>The Process</div>
        <h2 class="section-title">What to <em>expect.</em></h2>
        <p class="section-lead">Surrendering a dog is never easy. We've made the process as gentle and clear as possible.</p>
      </div>
      <picture>
        <img class="process-intro__media" src="<?php echo $img; ?>/pages/surrender.jpg" alt="A senior dog being cared for by POMDR" loading="lazy">
      </picture>
    </div>
    <div class="steps steps-2col">
      <div class="step">
        <div class="step-num">01</div>
        <div>
          <h3>Submit the Application</h3>
          <p>Fill out our online surrender application. Include as much information as possible about your dog's health, temperament, and history. This helps us find the ideal match.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">02</div>
        <div>
          <h3>We Review &amp; Reach Out</h3>
          <p>Our team reviews every application. We may reach out with follow-up questions or to schedule a phone call. We work with a waitlist, so the more notice you can give us, the better.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">03</div>
        <div>
          <h3>Medical Evaluation</h3>
          <p>Once accepted, your dog receives a thorough medical evaluation at our Harry &amp; Jaynne Boand Veterinary Clinic. We want to understand their full health picture before placing them in a foster home.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">04</div>
        <div>
          <h3>Foster Placement</h3>
          <p>Your dog is placed with one of our trained foster families while we work to find their forever home. All approved medical expenses are covered by POMDR.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">05</div>
        <div>
          <h3>A Loving Home and a Lifetime Commitment</h3>
          <p>We carefully match your dog with an adopter and maintain a lifetime commitment to every dog in our care. If an adoption doesn't work out, your dog comes back to us. Always.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WHAT WE PROVIDE -->
<section class="section">
  <div class="container">
    <div style="font-size:17px;letter-spacing:.2em;text-transform:uppercase;font-weight:600;color:var(--blue);display:inline-flex;align-items:center;gap:10px;margin-bottom:16px;"><span style="width:24px;height:1px;background:var(--blue-700);display:inline-block;"></span>If Your Dog Is Accepted</div>
    <h2 class="section-title">What POMDR <em>provides.</em></h2>
    <p class="section-lead">If your dog is accepted into our program, here is everything we take care of, for as long as it takes.</p>
    <div class="faq-grid">
      <div class="faq-item">
        <h4>A foster home</h4>
        <p>Placement in a loving foster home until your dog is adopted, never a kennel.</p>
      </div>
      <div class="faq-item">
        <h4>High quality dog food</h4>
        <p>Nutritious food appropriate for your dog's age and health for the duration of their stay.</p>
      </div>
      <div class="faq-item">
        <h4>Full medical coverage</h4>
        <p>We cover any medical needs that come up while your dog is in our care.</p>
      </div>
      <div class="faq-item">
        <h4>Grooming and flea control</h4>
        <p>Grooming and flea control while your dog is with us.</p>
      </div>
      <div class="faq-item">
        <h4>Socialization and companionship</h4>
        <p>Your dog spends their time in a real home with people who love them.</p>
      </div>
      <div class="faq-item">
        <h4>Careful adopter screening</h4>
        <p>Screening of potential adopters, including a home visit and reference check.</p>
      </div>
      <div class="faq-item">
        <h4>Annual follow-ups</h4>
        <p>Annual follow-ups and home visits to make sure the placement has been successful.</p>
      </div>
      <div class="faq-item">
        <h4>A lifetime safety net</h4>
        <p>A safety net for the lifetime of your dog, should they ever need to find a new home at any point in their life.</p>
      </div>
    </div>
  </div>
</section>

<!-- QUOTE -->
<div class="quote-band">
  <div class="container">
    <blockquote>"We have a lifetime commitment to every senior dog in our care."</blockquote>
    <cite>POMDR Mission</cite>
  </div>
</div>

<!-- PERPETUAL CARE -->
<section class="perpetual-section" id="perpetual">
  <div class="container">
    <div class="perpetual-inner">
      <div>
        <div class="eyebrow purple">Perpetual Care</div>
        <h2>What happens to your dog if <em>you can no longer care for them?</em></h2>
        <p>Our Perpetual Care program gives you peace of mind that your dog will be loved and cared for, no matter what life brings. This is a promise, not a program.</p>
        <p>By creating a Pet Trust and making an annual gift to POMDR, you establish a formal arrangement ensuring we will place your dog in a loving home if you pass away or can no longer provide care.</p>
        <a href="https://www.peaceofminddogrescue.org/POMDRPerpetualCareApplication.php" target="_blank" class="btn-white">
          Apply for Perpetual Care
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
      </div>
      <div>
        <div class="perp-steps">
          <div class="perp-step">
            <div class="perp-num">01</div>
            <div>
              <h4>Apply Online</h4>
              <p>Complete the Perpetual Care application with information about your dog and your situation.</p>
            </div>
          </div>
          <div class="perp-step">
            <div class="perp-num">02</div>
            <div>
              <h4>Create a Pet Trust</h4>
              <p>Work with an attorney to create a Pet Trust naming POMDR as the caretaker of your dog.</p>
            </div>
          </div>
          <div class="perp-step">
            <div class="perp-num">03</div>
            <div>
              <h4>Make an Annual Gift</h4>
              <p>An annual donation to POMDR formally establishes the relationship and supports our ability to honor the commitment.</p>
            </div>
          </div>
          <div class="perp-step">
            <div class="perp-num">04</div>
            <div>
              <h4>Peace of Mind</h4>
              <p>Rest easy knowing your dog will always have a safe, loving place to go, no matter what.</p>
            </div>
          </div>
        </div>
        <div class="perp-cta">
          <h3>Questions about Perpetual Care?</h3>
          <p>Call us at (831) 718-9122 or email info@pomdr.org. We're happy to walk you through the process.</p>
          <a href="mailto:info@pomdr.org" class="btn-white">Email Us</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="faq-section">
  <div class="container">
    <div style="font-size:17px;letter-spacing:.2em;text-transform:uppercase;font-weight:600;color:var(--blue);display:inline-flex;align-items:center;gap:10px;margin-bottom:16px;"><span style="width:24px;height:1px;background:var(--blue-700);display:inline-block;"></span>Common Questions</div>
    <h2 class="section-title">Frequently <em>asked.</em></h2>
    <div class="faq-grid">
      <div class="faq-item">
        <h4>Do you accept dogs from outside your tri-county area?</h4>
        <p>We usually work with a waitlist of dogs from our Monterey, Santa Cruz and San Benito county area and are rarely able to bring in dogs from outside our area. Please contact us to discuss your situation.</p>
      </div>
      <div class="faq-item">
        <h4>What do you mean by "lifetime commitment"?</h4>
        <p>Every dog that enters our program has a commitment from POMDR. If an adoption doesn't work out at any point, the dog comes back to us. We never re-surrender a dog.</p>
      </div>
      <div class="faq-item">
        <h4>What happens to my dog medically?</h4>
        <p>Every dog receives a full medical evaluation at our Boand Clinic. We cover all approved veterinary expenses while your dog is in our care, from routine to specialist care.</p>
      </div>
      <div class="faq-item">
        <h4>How long does the surrender process take?</h4>
        <p>It varies based on our current capacity. We work with a waitlist, so the more notice you can give us, the better. Please contact us early. We will always do our best.</p>
      </div>
      <div class="faq-item">
        <h4>Can I stay in touch with my dog after surrendering?</h4>
        <p>We understand how difficult this is. Once a dog is in our care, we focus on their transition and finding the right home. We encourage you to reach out to us with any questions about their wellbeing.</p>
      </div>
      <div class="faq-item">
        <h4>What is a Courtesy Listing?</h4>
        <p>A courtesy listing is where we post your dog on our website to help you find a home. You remain responsible for their care during this time. We provide guidance and resources throughout the process.</p>
      </div>
      <div class="faq-item">
        <h4>Will you meet my dog before taking them?</h4>
        <p>Yes. Before POMDR can intake your dog, we need to meet them. Dogs who come to POMDR must be good with other dogs (no aggression) and good with people (no bite history or propensity to bite).</p>
      </div>
      <div class="faq-item">
        <h4>Can you take a dog with medical needs?</h4>
        <p>We are equipped to handle a variety of medical cases and senior-related issues. Some extreme cases can be difficult for us to take on, for example Epilepsy, Diabetes, Paralysis, Megaesophagus, or Advanced Dementia. Medical intakes are evaluated case by case.</p>
      </div>
      <div class="faq-item">
        <h4>What if my dog isn't a POMDR candidate?</h4>
        <p>You can still fill out an intake questionnaire. Even if we cannot take your dog directly, we can refer you to additional resources to help you find the right placement.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-strip">
  <div class="container">
    <h2>Ready to take the <em>next step?</em></h2>
    <div class="ctas">
      <a href="https://www.peaceofminddogrescue.org/POMDRsurrenderapplication.php" target="_blank" class="btn-primary">
        Surrender Application
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
      </a>
      <a href="https://www.peaceofminddogrescue.org/POMDRPerpetualCareApplication.php" target="_blank" class="btn-ghost">Perpetual Care Application</a>
      <a href="/helping-paw/" class="btn-ghost">Helping Paw Program</a>
    </div>
  </div>
</section>


</main>
<?php get_footer();
