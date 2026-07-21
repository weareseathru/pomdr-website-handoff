<?php
/** Template for the Why page (WP slug: why). Ports prototype why.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<style>
.reasons-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:8px;}
.reason-card{background:#fff;border-radius:var(--radius-lg);padding:28px;border:1px solid var(--line);transition:all .35s var(--ease);}
.reason-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);}
.reason-card .num{font-family:var(--font-serif);font-size:34px;font-weight:300;color:var(--blue);line-height:1;margin-bottom:14px;}
.reason-card h3{font-family:var(--font-serif);font-size:22px;font-weight:500;margin:0 0 8px;}
.reason-card p{font-size:17px;color:var(--ink-2);margin:0;}
@media(max-width:900px){.reasons-grid{grid-template-columns:1fr;}}
.twocol{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:start;}
.twocol p{font-size:17px;color:var(--ink-2);margin:0 0 18px;}
.twocol h2{font-family:var(--font-serif);font-size:clamp(28px,3.4vw,44px);font-weight:300;letter-spacing:-.02em;margin:10px 0 18px;}
.twocol h2 em{font-style:italic;color:var(--purple);}
@media(max-width:800px){.twocol{grid-template-columns:1fr;gap:32px;}}
.myth-list{display:flex;flex-direction:column;gap:14px;margin-top:8px;}
.myth-list li{list-style:none;background:#fff;border:1px solid var(--line);border-radius:14px;padding:20px 22px;}
.myth-list strong{display:block;font-size:18px;color:var(--ink);margin-bottom:6px;font-weight:600;}
.myth-list span{display:block;font-size:17px;color:var(--ink-2);}
</style>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Why Senior Dogs</h1>
    <p class="page-narrative">The best dogs are sometimes the <em>oldest</em>.</p>
    <p class="page-lead">Peace of Mind Dog Rescue exists for the dogs other shelters overlook, the gray-muzzled, the slow-walking, the deeply loyal. Here is why a senior dog may be the right dog, and why supporting them matters.</p>
    <div class="page-cta">
      <a href="/adopt/" class="btn btn-primary">Adopt a senior dog</a>
      <a href="/foster-needs/" class="btn btn-outline">Foster a senior dog</a>
    </div>
  </div>
</header>

<section class="section">
  <div class="container">
    <span class="eyebrow purple">Our focus</span>
    <h2 class="section-title">Senior dogs and <em>senior people</em>, together.</h2>
    <p class="section-lead">Founded in 2009 on California's Central Coast, POMDR helps senior dogs find loving homes and helps senior people keep the dogs they love. Older dogs are at the highest risk in shelters and the least likely to be chosen. We changed that math for our community, one dog at a time.</p>
    <div class="reasons-grid">
      <div class="reason-card">
        <div class="num">01</div>
        <h3>They are already who they are</h3>
        <p>A senior dog's size, temperament, and energy are settled. What you see is who you get, no guessing, no surprises as they grow.</p>
      </div>
      <div class="reason-card">
        <div class="num">02</div>
        <h3>Calm company, gentle pace</h3>
        <p>Many older dogs are house-trained, leash-mannered, and happy with a short walk and a long nap. They fit a calmer life beautifully.</p>
      </div>
      <div class="reason-card">
        <div class="num">03</div>
        <h3>Gratitude you can feel</h3>
        <p>Dogs who have waited the longest tend to bond the deepest. A second chance late in life is not lost on them.</p>
      </div>
    </div>
  </div>
</section>

<section class="section" style="background:var(--cream);">
  <div class="container">
    <div class="twocol">
      <div>
        <span class="eyebrow purple">A two-way promise</span>
        <h2>Caring for the <em>people</em> too.</h2>
        <p>Senior dogs are often surrendered for heartbreaking reasons. An owner enters care, a health crisis hits, or the bills become impossible. The dog did nothing wrong, and neither did the person.</p>
        <p>Our Helping Paw program supports senior people and people with disabilities so they can keep their dogs at home. Walking help, financial assistance for veterinary care, and temporary foster during a hospital stay all keep families together.</p>
        <p>When staying together is not possible, we welcome the dog into our care and find the right next home. No one is judged. Everyone is helped.</p>
        <a href="/helping-paw/" class="btn btn-purple">Explore Helping Paw</a>
      </div>
      <div>
        <span class="eyebrow purple">Common worries, answered</span>
        <ul class="myth-list">
          <li>
            <strong>"A senior dog will not be around long."</strong>
            <span>Many dogs labeled senior have years of good life left. We share each dog's health honestly so you can choose with open eyes.</span>
          </li>
          <li>
            <strong>"Older dogs cannot bond with a new person."</strong>
            <span>They bond quickly and fully. A settled dog often attaches faster than a distracted puppy.</span>
          </li>
          <li>
            <strong>"The vet bills will be overwhelming."</strong>
            <span>We are transparent about each dog's needs, and we never leave an adopter to navigate care alone.</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <span class="eyebrow">Lifelong commitment</span>
    <h2 class="section-title">No dog is ever <em>left behind</em>.</h2>
    <p class="section-lead">Some dogs in our care have medical or behavioral needs that mean adoption is not the right path. Through our Perpetual Care promise, these dogs live out their days safe, comfortable, and loved in POMDR care. We see every dog through to the end, with dignity.</p>
    <p class="lead">Whether you adopt, foster, sponsor, or give, you become part of that promise. A senior dog's later years can be their happiest, and you can be the reason.</p>
  </div>
</section>

<section class="cta-strip" style="background:var(--blue-50);">
  <div class="container">
    <h2 class="serif">Give an older dog a <em>soft place to land.</em></h2>
    <div class="ctas">
      <a href="/adopt/" class="btn btn-primary">Adopt a senior dog</a>
      <a href="/foster-needs/" class="btn btn-outline">Foster a senior dog</a>
      <a href="/donate/" class="btn btn-outline">Support our work</a>
    </div>
    <p style="margin-top:20px;font-size:17px;color:var(--ink-2);">Questions? Call <a href="tel:8317189122" style="color:var(--blue-700);font-weight:600;">(831) 718-9122</a> or email <a href="mailto:info@pomdr.org" style="color:var(--blue-700);font-weight:600;">info@pomdr.org</a>.</p>
  </div>
</section>


<section class="section" style="background:var(--cream-2)">
  <div class="container">
    <div class="section-header">
      <span class="eyebrow purple">From Real Adopters</span>
      <h2 class="section-title">They said it <em>best.</em></h2>
    </div>
    <div class="why-quotes">
      <figure class="why-quote">
        <blockquote>Getting to watch an animal go from neglected and…just plain sad to enjoying his new life is just incredibly rewarding in a way that’s very different from raising a puppy and molding him in your own image.</blockquote>
        <figcaption>Wes, IndefinitelyWild.Gizmodo.com</figcaption>
      </figure>
      <figure class="why-quote">
        <blockquote>They appreciate the smallest gestures--cuddles, a nap on the bed, a brisk walk--because they had to ‘go without’ at some point. It makes them, and more importantly us humans, appreciate every little thing, a bit more!</blockquote>
        <figcaption>Dina Eastwood, POMDR Adopter & Volunteer</figcaption>
      </figure>
      <figure class="why-quote">
        <blockquote>Sasha was the first senior dog that I adopted. Though I had fostered many dogs before her, Sasha chose me. While I thought I might get 4-5 years with her, she is still healthy and happy over 5 years later. Having a senior dog makes me cherish the time that I have with my pets even more, knowing that our time together is limited.</blockquote>
        <figcaption>Christine D., POMDR Adopter & Volunteer</figcaption>
      </figure>
      <figure class="why-quote">
        <blockquote>Senior dogs have more ‘life experiences under their collar,’ and because of that they are more appreciative of having a loving forever home. I have adopted many, and their wisdom and love has enhanced my life in many ways.</blockquote>
        <figcaption>Linda L., POMDR Adopter & Volunteer</figcaption>
      </figure>
      <figure class="why-quote">
        <blockquote>Senior dogs are usually easier to bring in to your home. They are potty trained and [it’s] so nice to have another old lady to keep me company.</blockquote>
        <figcaption>Sue C., POMDR Foster Volunteer</figcaption>
      </figure>
      <figure class="why-quote">
        <blockquote>I adopted Disney aka Joe and all I can say is he is the best thing that has happened to me in a long time. Everything you said about older dogs is true: they are pretty much trained when you get them and he is been such a wonderful addition to my life and even though I&#039;ve had him a little over two months he has become my best little buddy and we are so bonded you would not believe it. So I want everybody out there to know this and to give these senior dogs a home. I will be forever grateful!</blockquote>
        <figcaption>Sharon S., POMDR Adopter</figcaption>
      </figure>
    </div>
  </div>
</section>
<style>
.why-quotes { columns: 2 380px; column-gap: 24px; }
.why-quote { break-inside: avoid; margin: 0 0 24px; background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 26px 28px; }
.why-quote blockquote { margin: 0 0 12px; font-size: 17px; line-height: 1.65; color: var(--ink-2); }
.why-quote figcaption { font-weight: 700; font-size: 16px; }
</style>

</main>
<?php get_footer();
