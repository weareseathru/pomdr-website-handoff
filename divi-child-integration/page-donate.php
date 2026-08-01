<?php
/** Template for the Donate page (WP slug: donate). Ports prototype donate.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<style>
.page-hero{padding:160px 0 0;background:var(--blue-900);color:#fff;position:relative;overflow:hidden;}
.page-hero .eyebrow{color:#fff;}
.page-hero .eyebrow::before{background:rgba(255,255,255,.65);}
.page-hero::before{content:"";position:absolute;inset:0;background:radial-gradient(ellipse at 20% 0%,rgba(0,139,176,.35),transparent 60%),radial-gradient(ellipse at 80% 80%,rgba(99,47,136,.2),transparent 60%);}
.page-hero>.container{position:relative;z-index:1;}
.page-hero .page-headline{color:#fff;margin-top:8px;}
.page-hero .page-headline-sub{color:var(--blue-200);}
.page-hero .page-narrative{color:rgba(255,255,255,.92);}
.page-hero .page-narrative em{color:#fff;} /* tints never on colored bands (benchmark 2026-08-01) */
.donate-card{background:rgba(255,255,255,.08);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.15);border-radius:var(--radius-lg);padding:36px;margin-top:44px;display:grid;grid-template-columns:1.2fr 1fr;gap:44px;align-items:start;}
.donate-card h1{font-family:var(--font-serif);font-size:clamp(18px,2.4vw,32px);line-height:1.1;letter-spacing:-.02em;font-weight:400;margin:0 0 14px;}
.donate-card h1 em{font-style:italic;color:#fff;}
.donate-card p{font-size:16px;color:rgba(255,255,255,.85);margin:0 0 10px;}
.donate-card .tax{font-size:16px;color:rgba(255,255,255,.78);margin-top:14px;}
.amounts{display:flex;gap:9px;flex-wrap:wrap;margin-bottom:18px;}
.amount-btn{min-height:44px;padding:13px 18px;border-radius:999px;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.4);color:#fff;font-size:16px;font-weight:600;font-family:var(--font-sans);cursor:pointer;transition:all .2s;}
.amount-btn:hover,.amount-btn.active{background:var(--blue-700);border-color:var(--blue);}
.custom-row{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.08);border:1.5px solid rgba(255,255,255,.4);border-radius:999px;padding:6px 6px 6px 20px;}
.custom-row input{flex:1;min-height:44px;border:none;background:transparent;font:inherit;font-size:16px;color:#fff;outline:none;}
.custom-row input::placeholder{color:rgba(255,255,255,.78);}
.donate-btn{min-height:44px;background:var(--blue-700);color:#fff;padding:13px 24px;border-radius:999px;font-weight:600;font-size:16px;box-shadow:0 6px 18px -4px rgba(0,139,176,.55);transition:all .25s;border:none;font-family:var(--font-sans);cursor:pointer;white-space:nowrap;}
.donate-btn:hover{background:var(--blue-700);}
.donate-meta{display:flex;gap:20px;margin-top:18px;font-size:16px;color:rgba(255,255,255,.85);flex-wrap:wrap;}
@media(max-width:800px){.donate-card{grid-template-columns:1fr;gap:28px;}}
/* MONTHLY */
.monthly-band{background:var(--purple);color:#fff;padding:72px 0;text-align:center;}
.monthly-band h2{font-family:var(--font-serif);font-size:clamp(28px,3.5vw,48px);font-weight:300;letter-spacing:-.02em;margin:10px 0 16px;}
.monthly-band h2 em{font-style:italic;color:#fff;}
.monthly-band p{font-size:17px;color:rgba(255,255,255,.85);max-width:500px;margin:0 auto 28px;}
/* WAYS GRID */
.ways-section{padding:80px 0 100px;}
.ways-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:16px;}
/* Light directory cards (benchmark 2026-08-01): the giving directory reads as
   a calm, white index. Dark photo scrims removed; the hero ask and the monthly
   band above carry the emotion, this grid carries the information. */
.way-card{position:relative;border-radius:var(--radius-lg);padding:28px;color:var(--ink);display:flex;flex-direction:column;background:#fff;border:1px solid var(--line);transition:transform .4s var(--ease),box-shadow .4s var(--ease);}
.way-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
.way-icon{width:46px;height:46px;border-radius:14px;background:var(--blue-50);display:grid;place-items:center;color:var(--blue-700);margin-bottom:16px;}
.way-icon svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:1.7;}
.way-card h3{font-family:var(--font-serif);font-size:23px;font-weight:500;margin:0 0 8px;letter-spacing:-.01em;color:var(--ink);}
.way-card p{font-size:16px;color:var(--ink-2);margin:0 0 14px;flex:1;}
.way-link{display:inline-flex;align-items:center;gap:6px;font-size:17px;font-weight:700;color:var(--blue-700);margin-top:auto;transition:gap .25s;}
.way-card:hover .way-link{gap:10px;}
@media(max-width:900px){.ways-grid{grid-template-columns:1fr 1fr;}}
@media(max-width:580px){.ways-grid{grid-template-columns:1fr;}}
</style>
<main id="main-content">


<script>
  // Scroll effect
  window.addEventListener('scroll', () => {
    document.getElementById('site-nav').classList.toggle('scrolled', window.scrollY > 40);
  });
</script><!-- HERO + DONATE WIDGET -->
<section class="page-hero">
  <div class="container">
    <h1 class="page-headline">Ways to Give</h1>
    <p class="page-narrative">Your gift <em>saves lives.</em></p>
    <div class="donate-card">
      <div>
        <p>POMDR relies heavily on donations to further our mission of helping senior dogs and senior people stay together. Our biggest expense is medical care for the senior dogs we rescue.</p>
        <p>Peace of Mind Dog Rescue is a non-profit 501(c)(3). Your donations are tax-deductible to the extent allowable by law.</p>
        <div class="tax" style="font-size: 15px;color:rgba(255,255,255,.6);">501(c)(3) nonprofit · EIN 27-1154816</div>
      </div>
      <div>
        <div style="font-size: 16px;letter-spacing:.14em;text-transform:uppercase;font-weight:600;color:rgba(255,255,255,.6);margin-bottom:14px;">Choose an amount</div>
        <div class="amounts" id="amounts">
          <button class="amount-btn" data-amount="60">$60</button>
          <button class="amount-btn active" data-amount="125">$125</button>
          <button class="amount-btn" data-amount="275">$275</button>
          <button class="amount-btn" data-amount="600">$600</button>
          <button class="amount-btn" data-amount="1250">$1,250</button>
        </div>
        <div class="custom-row">
          <input type="number" id="custom-amount" placeholder="Other amount" min="1"/>
          <button class="donate-btn" id="donate-btn">Donate Now →</button>
        </div>
        <div class="donate-meta">
          <span>🔒 Secure</span>
          <span>♻️ Monthly option available</span>
          <span>✓ Tax-deductible</span>
        </div>
      </div>
    </div>
  </div>
  <div style="height:60px;"></div>
</section>

<!-- MONTHLY CTA -->
<section class="monthly-band">
  <div class="container">
    <div class="eyebrow" style="color:var(--purple-100);justify-content:center;">Sustaining Donors</div>
    <h2>Become a monthly donor. <em>Steady love for senior dogs.</em></h2>
    <p>Your credit card will be charged your pledged amount at the first of every month. Cancel anytime.</p>
    <a href="/monthly-donation/" class="btn btn-white">
      Set Up Monthly Gift
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
    </a>
  </div>
</section>

<!-- ALL WAYS TO GIVE -->
<section class="ways-section">
  <div class="container">
    <div class="eyebrow" style="color:var(--blue);">All Ways to Give</div>
    <h2 class="section-title">More ways to make an <em>impact.</em></h2>
    <div class="ways-grid">

      <div class="way-card featured">
        <div class="way-icon"><svg viewBox="0 0 24 24"><path d="M12 21s-8-5.5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 11-8 11z"/></svg></div>
        <h3>Max's Helping Paws Fund</h3>
        <p>Financial assistance for low-income guardians facing unexpected, urgent veterinary care, including surgery and specialist visits.</p>
        <a href="/donation/?fund=Max's%20Helping%20Paws%20Fund" class="way-link">Donate to Max's Fund →</a>
      </div>

      <div class="way-card">
        <div class="way-icon"><svg viewBox="0 0 24 24"><path d="M12 2l3 6 6 1-4.5 4 1 6-5.5-3-5.5 3 1-6L3 9l6-1 3-6z"/></svg></div>
        <h3>Monica Rua Silver Hearts Fund</h3>
        <p>For dogs with dire medical needs. Special surgeries, specialist visits, physical rehabilitation, supplements. Named in honor of co-founder Monica Rua.</p>
        <a href="/donation/?fund=Silver%20Hearts%20Fund" class="way-link">Give to Silver Hearts →</a>
      </div>

      <div class="way-card">
        <div class="way-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        <h3>Tribute Donation</h3>
        <p>Make a donation in honor of or in memory of a special person or animal. We'll send a beautiful tribute card announcing your gift.</p>
        <a href="/donation/?donationtype=tribute" class="way-link">Give a Tribute Gift →</a>
      </div>

      <div class="way-card">
        <div class="way-icon" style="background:var(--purple-50);color:var(--purple);"><svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></div>
        <h3>Sponsor a Dog</h3>
        <p>Choose a pup on our adoptable dog page and click "Sponsor," or sponsor directly. Your gift supports that dog's care while they wait for their forever home.</p>
        <a href="/sponsor-a-dog/" class="way-link">Sponsor a Dog →</a>
      </div>

      <div class="way-card">
        <div class="way-icon"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></div>
        <h3>Benefit Shop</h3>
        <p>Visit the POMDR Benefit Shop at 223 Grand Ave, Suite 1 in Pacific Grove. Shop for hidden gems and support senior dogs with every purchase.</p>
        <a href="/benefit-shop/" class="way-link">Visit the Shop →</a>
      </div>

      <div class="way-card">
        <div class="way-icon"><svg viewBox="0 0 24 24"><path d="M12 21s-8-5.5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 11-8 11z"/></svg></div>
        <h3>Helping Paw Fund</h3>
        <p>Donate to our Food Fund or Helping Paw Veterinary Assistance Fund to keep pet guardians and their pets together during difficult times.</p>
        <a href="/donation/?fund=Helping%20Paw%20Fund" class="way-link">Donate to Helping Paw →</a>
      </div>

      <div class="way-card">
        <div class="way-icon"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        <h3>Sponsor a Newspaper Ad</h3>
        <p>Placing ads in local newspapers is very effective in finding homes for our dogs. Ad sponsorships are $120 per ad.</p>
        <a href="/sponsor-an-ad/" class="way-link">Sponsor an Ad →</a>
      </div>

      <div class="way-card">
        <div class="way-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg></div>
        <h3>Legacy Donor</h3>
        <p>Legacy Donors are keystone supporters of our mission. Be an invaluable part of our work with an annual gift of $5,000 or more.</p>
        <a href="/legacy/" class="way-link">Become a Legacy Donor →</a>
      </div>

      <div class="way-card">
        <div class="way-icon" style="background:var(--purple-50);color:var(--purple);"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <h3>Perpetual Care</h3>
        <p>Create a Pet Trust for your dog and make an annual gift to POMDR. We will commit to providing a loving, safe home for your dog if something happens to you.</p>
        <a href="/surrender/" class="way-link">Learn About Perpetual Care →</a>
      </div>

      <div class="way-card">
        <div class="way-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
        <h3>Donate Stock</h3>
        <p>Donate appreciated stock to POMDR and receive tax benefits. We are set up to receive your stock donations. Email us for instructions.</p>
        <a href="mailto:info@pomdr.org" class="way-link">Contact Us →</a>
      </div>

      <div class="way-card">
        <div class="way-icon"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
        <h3>Planned Giving</h3>
        <p>Make a lasting gift to help animals in need. Planned giving can provide tax benefits while ensuring POMDR can continue its work long into the future.</p>
        <a href="/planned-giving/" class="way-link">Learn About Planned Giving →</a>
      </div>

      <div class="way-card">
        <div class="way-icon"><svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
        <h3>Wish List</h3>
        <p>Donate supplies directly to help our senior dogs while in foster care. See exactly what our dogs need, from food to crates to cozy beds.</p>
        <a href="/wish-list/" class="way-link">View Wish List →</a>
      </div>

      <div class="way-card way-card--flat">
        <div class="way-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v10M9 10h6"/></svg></div>
        <h3>Dog Tag Donor</h3>
        <p>Become a Dog Tag Donor with a gift of $500, and carry a POMDR dog tag that shows you are part of the pack.</p>
        <a href="/dog-tag-donor/" class="way-link">Become a Dog Tag Donor →</a>
      </div>

      <div class="way-card way-card--flat">
        <div class="way-icon"><svg viewBox="0 0 24 24"><path d="M3 21V8l9-5 9 5v13"/><path d="M9 21v-8h6v8"/></svg></div>
        <h3>Sponsor a Doggie Suite</h3>
        <p>Sponsor a doggie suite at the Bauer Center for $5,000 a year and give recovering dogs a comfortable place to land.</p>
        <a href="/doggie-suite-sponsorship/" class="way-link">Sponsor a Suite →</a>
      </div>

      <div class="way-card way-card--flat">
        <div class="way-icon"><svg viewBox="0 0 24 24"><rect x="3" y="8" width="18" height="12" rx="2"/><path d="M8 8V6a4 4 0 0 1 8 0v2"/></svg></div>
        <h3>Plaques and River Stones</h3>
        <p>Sponsor a mural plaque, welcome room plaque, or garden river stone at the Bauer Center, a lasting tribute in a place full of wagging tails.</p>
        <a href="/sponsorship/" class="way-link">See Sponsorships →</a>
      </div>

      <div class="way-card way-card--flat">
        <div class="way-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg></div>
        <h3>Corporate Sponsor</h3>
        <p>Partner your business with POMDR and put your name behind senior dogs and senior people in our community.</p>
        <a href="mailto:info@pomdr.org?subject=Corporate%20Sponsorship" class="way-link">Email Us →</a>
      </div>

      <div class="way-card way-card--flat">
        <div class="way-icon"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
        <h3>Annual Gift</h3>
        <p>Set up an annual gift and we will contact you each year to renew it. One decision, a whole year of impact.</p>
        <a href="/annual-donation/" class="way-link">Set Up an Annual Gift →</a>
      </div>

    </div>
  </div>
</section>



<script>
// Amount selector
const amountBtns = document.querySelectorAll('.amount-btn');
const customInput = document.getElementById('custom-amount');
const donateBtn = document.getElementById('donate-btn');
let selectedAmount = 125;

amountBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    amountBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    selectedAmount = parseInt(btn.dataset.amount);
    customInput.value = '';
  });
});
customInput.addEventListener('input', () => {
  amountBtns.forEach(b => b.classList.remove('active'));
  selectedAmount = parseInt(customInput.value) || 0;
});
donateBtn.addEventListener('click', () => {
  const amt = customInput.value ? parseInt(customInput.value) : selectedAmount;
  if (amt > 0) {
    window.location.href = `/donation/?initialdonation=${amt}`;
  }
});
</script>
</main>
<?php get_footer();
