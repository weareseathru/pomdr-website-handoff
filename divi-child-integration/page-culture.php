<?php
/** Template for the Culture page (WP slug: culture). Ports prototype culture.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<style>
.values-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:22px;margin-top:8px;}
.value-card{background:#fff;border-radius:var(--radius-lg);padding:30px;border:1px solid var(--line);transition:all .35s var(--ease);}
.value-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
.value-card .vk{width:46px;height:46px;border-radius:14px;background:var(--purple-50);display:grid;place-items:center;color:var(--purple);margin-bottom:16px;font-family:var(--font-serif);font-size:22px;font-weight:500;}
.value-card h3{font-family:var(--font-serif);font-size:23px;font-weight:500;margin:0 0 8px;}
.value-card p{font-size:17px;color:var(--ink-2);margin:0;}
@media(max-width:760px){.values-grid{grid-template-columns:1fr;}}
.people-band{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;}
.people-band h2{font-family:var(--font-serif);font-size:clamp(28px,3.4vw,44px);font-weight:300;letter-spacing:-.02em;margin:10px 0 18px;}
.people-band h2 em{font-style:italic;color:var(--purple);}
.people-band p{font-size:17px;color:var(--ink-2);margin:0 0 18px;}
@media(max-width:800px){.people-band{grid-template-columns:1fr;gap:32px;}}
</style>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Our Culture <span class="page-headline-sub">How we work</span></h1>
    <p class="page-narrative">Kindness, run like a <em>professional</em>.</p>
    <p class="page-lead">Behind every rescued dog is a team that shows up with warmth, honesty, and care. This is what it feels like to work and volunteer at Peace of Mind Dog Rescue.</p>
    <div class="page-cta">
      <a href="/jobs/" class="btn btn-purple">Join our team</a>
      <a href="/volunteer/" class="btn btn-outline">Volunteer with us</a>
    </div>
  </div>
</header>

<section class="section">
  <div class="container">
    <span class="eyebrow purple">What we believe</span>
    <h2 class="section-title">Values we <em>live</em>, not just post.</h2>
    <p class="section-lead">Since 2009, POMDR has grown from a small group of volunteers into a trusted Central Coast institution. The way we treat dogs, adopters, donors, and each other has stayed the same the whole way through.</p>
    <div class="values-grid">
      <div class="value-card">
        <div class="vk">1</div>
        <h3>Compassion first</h3>
        <p>We meet every dog and every person with patience and respect. The senior owner in a hard moment and the dog who has waited the longest both deserve our full kindness.</p>
      </div>
      <div class="value-card">
        <div class="vk">2</div>
        <h3>Honesty always</h3>
        <p>We tell the truth about a dog's health, history, and needs. Trust is the foundation of every adoption, and we protect it carefully.</p>
      </div>
      <div class="value-card">
        <div class="vk">3</div>
        <h3>Dignity to the end</h3>
        <p>Through Perpetual Care, no dog is abandoned for being old or ill. Every dog in our care is seen through to the end with comfort and love.</p>
      </div>
      <div class="value-card">
        <div class="vk">4</div>
        <h3>Community over ego</h3>
        <p>Staff, volunteers, fosters, and donors are one team. We share the work, share the credit, and keep our focus on the dogs.</p>
      </div>
    </div>
  </div>
</section>

<section class="section" style="background:var(--cream);">
  <div class="container">
    <div class="people-band">
      <div>
        <span class="eyebrow purple">The people</span>
        <h2>A team that <em>cares for each other.</em></h2>
        <p>Our work asks a lot, emotionally and practically. So we build a workplace that gives back, flexible, supportive, and grounded in a shared purpose everyone can feel.</p>
        <p>Staff and volunteers come from every walk of life. What they have in common is steadiness, a sense of humor on the hard days, and a deep belief that older dogs and older people deserve more than they often get.</p>
        <p>Whether you join as an employee or give a few hours a week, you will find colleagues who notice your effort and have your back.</p>
        <a href="/about/" class="btn btn-purple">About our organization</a>
      </div>
      <div>
        <div style="background:#fff;border:1px solid var(--line);border-radius:var(--radius-lg);padding:30px;">
          <span class="eyebrow purple">Ways to belong</span>
          <ul style="list-style:none;margin:14px 0 0;padding:0;display:flex;flex-direction:column;gap:14px;">
            <li style="font-size:17px;color:var(--ink-2);"><strong style="color:var(--ink);">Staff roles.</strong> Mission-driven work with a team that values balance and respect.</li>
            <li style="font-size:17px;color:var(--ink-2);"><strong style="color:var(--ink);">Volunteers.</strong> Walk dogs, foster, drive, photograph, or lend a skill. Every hour matters.</li>
            <li style="font-size:17px;color:var(--ink-2);"><strong style="color:var(--ink);">Fosters.</strong> Open your home and give a senior dog comfort while we find their person.</li>
            <li style="font-size:17px;color:var(--ink-2);"><strong style="color:var(--ink);">Donors and sponsors.</strong> Fund the care that makes all of this possible.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <span class="eyebrow purple">Why it works</span>
    <h2 class="section-title">Warmth and <em>rigor</em>, in equal measure.</h2>
    <p class="section-lead">We are tender with the dogs and disciplined with the work. Medical records are thorough, applications are read personally, and follow-up is real. A loving culture and a well-run rescue are not opposites. They depend on each other.</p>
    <p class="lead">If that sounds like the kind of place you want to spend your time, there is a seat for you here.</p>
  </div>
</section>

<section class="cta-strip" style="background:var(--purple-50);">
  <div class="container">
    <h2 class="serif">Bring your <em>good heart</em> to work.</h2>
    <div class="ctas">
      <a href="/jobs/" class="btn btn-purple">Join our team</a>
      <a href="/volunteer/" class="btn btn-outline">Volunteer with us</a>
    </div>
    <p style="margin-top:20px;font-size:17px;color:var(--ink-2);">Reach us at <a href="tel:8317189122" style="color:var(--purple);font-weight:600;">(831) 718-9122</a> or <a href="mailto:info@pomdr.org" style="color:var(--purple);font-weight:600;">info@pomdr.org</a>.</p>
  </div>
</section>

</main>
<?php get_footer();
