<?php
/** Template for the Volunteer page (WP slug: volunteer). Ports prototype volunteer.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<style>
.page-hero{padding:160px 0 80px;background:linear-gradient(135deg,rgba(99,47,136,.06) 0%,transparent 60%),var(--cream);border-bottom:1px solid var(--line);}
.hero-stat-row{display:flex;gap:36px;margin-top:44px;padding-top:36px;border-top:1px solid var(--line);flex-wrap:wrap;}
.hero-stat .num{font-family:var(--font-serif);font-size:40px;font-weight:300;color:var(--purple);line-height:1;}
.hero-stat .lbl{font-size:15px;color:var(--ink-3);margin-top:3px;}
/* Lead copy + the three stats share one row (mirrors the adopt header). */
.hero-row{display:grid;grid-template-columns:1.25fr 1fr;gap:44px;align-items:center;margin:22px 0 26px;}
.hero-row .lead{margin:0;}
.hero-row .hero-stat-row{margin:0;padding:0 0 0 44px;border-top:none;border-left:1px solid var(--line);gap:28px;}
@media(max-width:820px){.hero-row{grid-template-columns:1fr;gap:20px;}.hero-row .hero-stat-row{padding:22px 0 0;border-left:none;border-top:1px solid var(--line);}}
/* ROLES */
.roles-section{padding:100px 0;}
.roles-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;}
/* Standard photo card (matches the homepage pillars): photo fills the card, a
   dark gradient keeps the icon and text legible, content overlays in white. */
.role-card{position:relative;overflow:hidden;border-radius:var(--radius-lg);min-height:340px;padding:28px;color:#fff;display:flex;flex-direction:column;transition:transform .4s var(--ease),box-shadow .4s var(--ease);}
.role-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
.role-card::before{content:"";position:absolute;inset:0;z-index:0;background:var(--ink-2) center/cover no-repeat;transition:transform .7s var(--ease);}
.role-card:hover::before{transform:scale(1.05);}
.role-card::after{content:"";position:absolute;inset:0;z-index:1;background:linear-gradient(180deg,rgba(22,32,43,.55) 0%,rgba(22,32,43,.65) 42%,rgba(22,32,43,.92) 100%);}
.role-card>*{position:relative;z-index:2;}
.role-card:nth-of-type(1)::before{background-image:url(<?php echo $img; ?>/hero-mission.jpeg);}
.role-card:nth-of-type(2)::before{background-image:url(<?php echo $img; ?>/dog4.jpeg);}
.role-card:nth-of-type(3)::before{background-image:url(<?php echo $img; ?>/hero-adopt.jpeg);}
.role-card:nth-of-type(4)::before{background-image:url(<?php echo $img; ?>/dog9.jpeg);}
.role-card:nth-of-type(5)::before{background-image:url(<?php echo $img; ?>/pages/benefitshop.jpg);}
.role-card:nth-of-type(6)::before{background-image:url(<?php echo $img; ?>/dog12.jpeg);}
.role-card:nth-of-type(7)::before{background-image:url(<?php echo $img; ?>/dog2.jpeg);}
.role-card:nth-of-type(8)::before{background-image:url(<?php echo $img; ?>/pages/helpingpaw.jpg);}
.role-card:nth-of-type(9)::before{background-image:url(<?php echo $img; ?>/dog6.jpeg);}
.role-card:nth-of-type(10)::before{background-image:url(<?php echo $img; ?>/dog16.jpeg);}
.role-icon{width:50px;height:50px;border-radius:15px;display:grid;place-items:center;margin-bottom:18px;background:rgba(255,255,255,.2)!important;color:#fff!important;backdrop-filter:blur(3px);}
.role-icon svg{width:24px;height:24px;stroke:currentColor;fill:none;stroke-width:1.6;}
.role-card h3{font-family:var(--font-serif);font-size:23px;font-weight:500;margin:0 0 10px;letter-spacing:-.01em;color:#fff;text-shadow:0 2px 12px rgba(0,0,0,.55);}
.role-card p{font-size:16px;color:rgba(255,255,255,.94);margin:0 0 14px;flex:1;text-shadow:0 1px 8px rgba(0,0,0,.6);}
.role-card .learn{display:inline-flex;align-items:center;gap:6px;font-size:16px;font-weight:700;color:#fff;margin-top:auto;}
.role-card .learn::after{content:"→";transition:transform .25s var(--ease);}
.role-card:hover .learn::after{transform:translateX(5px);}
@media(max-width:900px){.roles-grid{grid-template-columns:1fr 1fr;}}
@media(max-width:580px){.roles-grid{grid-template-columns:1fr;}}
/* APPLY BAND */
.apply-band{background:var(--blue-900);color:#fff;padding:100px 0;text-align:center;}
.apply-band h2{font-family:var(--font-serif);font-size:clamp(32px,4.5vw,60px);font-weight:300;letter-spacing:-.025em;margin:10px 0 18px;}
.apply-band h2 em{font-style:italic;color:var(--blue-200);}
.apply-band p{font-size:18px;color:rgba(255,255,255,.8);max-width:520px;margin:0 auto 32px;}
</style>
<main id="main-content">


<script>
  // Scroll effect
  window.addEventListener('scroll', () => {
    document.getElementById('site-nav').classList.toggle('scrolled', window.scrollY > 40);
  });
</script><section class="page-hero">
  <div class="container">
    <div class="ph-split">
      <div class="ph-text">
        <h1 class="page-headline">Volunteer</h1>
        <p class="page-narrative">Give your time. <em>Change a life.</em></p>
        <div class="hero-row">
          <p class="lead">Peace of Mind Dog Rescue relies on the time and talents of over 1,800 volunteers to help senior dogs and senior people across California's Central Coast.</p>
          <div class="hero-stat-row">
            <div class="hero-stat"><div class="num">1,800+</div><div class="lbl">Active volunteers</div></div>
            <div class="hero-stat"><div class="num">10+</div><div class="lbl">Ways to volunteer</div></div>
            <div class="hero-stat"><div class="num">16 yrs</div><div class="lbl">Of community support</div></div>
          </div>
        </div>
        <div style="display:flex;gap:14px;flex-wrap:wrap;">
          <a href="/volunteer-application/" class="btn btn-purple">
            Fill Out Application
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
          </a>
          <a href="#roles" class="btn btn-outline">See all opportunities</a>
        </div>
      </div>
      <div class="ph-media">
        <picture>
          <source type="image/webp" srcset="<?php echo $img; ?>/hero-volunteer.webp">
          <img src="<?php echo $img; ?>/hero-volunteer.jpg" alt="A POMDR volunteer spending time with a senior dog">
        </picture>
      </div>
    </div>
  </div>
</section>

<section class="roles-section" id="roles">
  <div class="container">
    <div class="section-header">
      <div class="eyebrow">Opportunities</div>
      <h2 class="section-title">Find your <em>perfect role.</em></h2>
      <p class="section-lead">Whether you can commit to a regular schedule or just help once a year, we have a place for you.</p>
    </div>
    <div class="roles-grid">

      <div class="role-card">
        <div class="role-icon" style="background:var(--blue-50);color:var(--blue);">
          <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <h3>Foster Home</h3>
        <p>Foster volunteers are the heart and soul of what we do. POMDR covers all approved medical expenses, supplies crates, beds and toys as available, and provides a foster manual, training, an emergency contact number, and a dedicated mentor.</p>
        <a href="https://www.peaceofminddogrescue.org/fostering.html" target="_blank" class="learn">Learn more about fostering</a>
      </div>

      <div class="role-card">
        <div class="role-icon" style="background:var(--blue-50);color:var(--blue);">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <h3>Transportation</h3>
        <p>We often need help picking up dogs from shelters, taking them to their foster home, or driving them to vet appointments. Ideal for people with flexible daytime weekday availability.</p>
        <a href="/volunteer-application/" class="learn">Apply now</a>
      </div>

      <div class="role-card">
        <div class="role-icon" style="background:#F1EBF5;color:#632F88;">
          <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <h3>Adoption Events</h3>
        <p>Help put on adoption events once or twice a month at dog-friendly locations. Volunteers are trained as adoption counselors and help answer questions about POMDR. A perfect role if you love people and dogs equally.</p>
        <a href="/adopt/" class="learn">See upcoming events</a>
      </div>

      <div class="role-card">
        <div class="role-icon" style="background:var(--purple-50);color:var(--purple);">
          <svg viewBox="0 0 24 24"><circle cx="5" cy="9" r="2"/><circle cx="12" cy="5" r="2"/><circle cx="19" cy="9" r="2"/><path d="M7 17c0-3 2-5 5-5s5 2 5 5-2 4-5 4-5-1-5-4z"/></svg>
        </div>
        <h3>Behavior &amp; Enrichment Team</h3>
        <p>Volunteer in two-hour shifts with a pack of dogs at our Pacific Grove office. You'll supervise group interactions and provide socialization, exercise and enrichment activities.</p>
        <a href="/volunteer-application/" class="learn">Apply now</a>
      </div>

      <div class="role-card">
        <div class="role-icon" style="background:#E3F2F6;color:#006c8a;">
          <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
        </div>
        <h3>Benefit Shop</h3>
        <p>Our Benefit Shop at 223 Grand Ave, Pacific Grove needs help greeting visitors, running the register, sorting and pricing donations, and creating displays. Truck owners can help with donation pickups too!</p>
        <a href="https://www.peaceofminddogrescue.org/benefitshop.html" target="_blank" class="learn">Visit the Benefit Shop</a>
      </div>

      <div class="role-card">
        <div class="role-icon" style="background:#EFEAF4;color:#632F88;">
          <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <h3>Fundraising</h3>
        <p>Help at fundraising events or coordinate a fundraiser of your own. Fundraising is critical to our mission. If planning events is your strength, we would love to hear from you.</p>
        <a href="/donate/" class="learn">Learn about giving</a>
      </div>

      <div class="role-card">
        <div class="role-icon" style="background:var(--blue-50);color:var(--blue);">
          <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <h3>Tabling</h3>
        <p>Set up an info table in front of retail venues to tell people about POMDR. A great way to gain volunteers and supporters, perfect if you love getting people excited about our mission.</p>
        <a href="/volunteer-application/" class="learn">Apply now</a>
      </div>

      <div class="role-card">
        <div class="role-icon" style="background:var(--purple-50);color:var(--purple);">
          <svg viewBox="0 0 24 24"><path d="M12 21s-8-5.5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 11-8 11z"/></svg>
        </div>
        <h3>Helping Paw</h3>
        <p>Walk dogs for seniors or ill guardians who can no longer walk their own dogs. We also help with transportation to vets and groomers, and other supportive tasks that keep pets and people together.</p>
        <a href="/helping-paw/" class="learn">Learn about Helping Paw</a>
      </div>

      <div class="role-card">
        <div class="role-icon" style="background:#F1EBF5;color:#632F88;">
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <h3>Other Specialties</h3>
        <p>We often have openings for volunteers with specific skills: writing, grant writing, graphic design, PR &amp; marketing, photography, and fundraising. Bring your expertise and make a direct impact.</p>
        <a href="/volunteer-application/" class="learn">Tell us your skills</a>
      </div>

      <div class="role-card">
        <div class="role-icon" style="background:var(--blue-50);color:var(--blue);">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/><circle cx="5" cy="11" r="2"/><circle cx="19" cy="11" r="2"/></svg>
        </div>
        <h3>Volunteer Brigade</h3>
        <p>Want to help but cannot commit to a set task or schedule? Join the brigade and get occasional email requests: special events, networking to find an adopter for a specific dog, or a transportation run. Even once a year, your help makes a difference.</p>
        <a href="/volunteer-application/" class="learn">Join the brigade</a>
      </div>

    </div>
  </div>
</section>

<!-- Youth Volunteers section removed per 2026-06 review. -->

<!-- APPLICATION CTA -->
<section class="apply-band">
  <div class="container">
    <div class="eyebrow" style="color:var(--blue-200);justify-content:center;">Join 1,800+ volunteers</div>
    <h2>Thanks for your interest in <em>volunteering!</em></h2>
    <p>Fill out an application and our volunteer coordinator will be in touch with opportunities that match your availability and interests.</p>
    <a href="/volunteer-application/" class="btn btn-light">
      Fill Out Volunteer Application
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
    </a>
    <div style="margin-top:32px;font-size:16px;color:rgba(255,255,255,.55);">Questions? <a href="mailto:info@pomdr.org" style="color:var(--blue-200);">info@pomdr.org</a> · (831) 718-9122</div>
  </div>
</section>


</main>
<?php get_footer();
