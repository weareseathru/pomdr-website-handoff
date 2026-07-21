<?php
/** Template for the Perpetual Care Program page (WP slug: perpetual-care-program). Ports prototype perpetual-care-program.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<style>
/* Lay the content across the width: a text+photo intro and 2-up lists,
   so the page reads horizontally instead of one tall vertical column. */
.perp-intro{display:grid;grid-template-columns:1.25fr 1fr;gap:48px;align-items:center;}
.perp-intro__media{width:100%;height:100%;min-height:280px;max-height:380px;object-fit:cover;border-radius:var(--radius-lg);display:block;}
.perp-howlist{font-size:17px;line-height:1.8;padding-left:24px;margin:0;columns:2;column-gap:56px;}
.perp-howlist li{margin:0 0 14px;break-inside:avoid;}
@media(max-width:860px){.perp-intro{grid-template-columns:1fr;gap:26px;}.perp-howlist{columns:1;}}
</style>
<main id="main-content">

<main id="main">

<header class="page-header">
  <div class="container">
    <div class="ph-split">
      <div class="ph-text">
        <h1 class="page-headline">Perpetual Care</h1>
        <p class="page-narrative">A plan for <em>after you</em>.</p>
        <p class="page-lead">If something happens to you, POMDR can take your dog. The Lifetime Care Program is for senior people who want to know their senior dog has a soft landing.</p>
      </div>
      <div class="ph-media">
        <picture>
          <source type="image/webp" srcset="<?php echo $img; ?>/pages/perpetualcare.webp">
          <img src="<?php echo $img; ?>/pages/perpetualcare.jpg" alt="A senior dog enjoying a calm day in POMDR's care" loading="lazy">
        </picture>
      </div>
    </div>
  </div>
</header>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start">
      <div>
        <h2 class="section-title" style="margin-bottom:16px">How it works</h2>
        <ol class="perp-howlist">
          <li>You enroll your dog while you can. We need to meet your dog first to determine whether POMDR can accept them into the program.</li>
          <li>To enroll, we suggest setting up a legal pet trust with your attorney to cover your dog's lifetime care expenses in the event of your illness, injury, or death.</li>
          <li>If you can no longer care for your dog (accident, illness, or death), your dog is placed in a loving POMDR foster home, either for the rest of their life or until a perfect match with a new permanent guardian is found.</li>
          <li>We honor any specific requests on file: medical, behavioral, and lifestyle preferences for the dog's next home.</li>
        </ol>

        <p style="font-size:17px;line-height:1.7;margin-top:24px">If you are unable to set up a pet trust or make an annual gift but would still like to enroll your dog, please contact us to discuss the next steps. Your attorney or our resources page can help you set up a pet trust and plan for your pet's future should you predecease them.</p>

        <h2 class="section-title" style="margin:48px 0 16px">What it costs</h2>
        <p style="font-size:17px;line-height:1.7">We suggest a legal pet trust to ensure the resources are available for your dog's lifetime care, plus an annual gift in any amount to support our mission. At the time of your dog's death, any remainder of the trust goes into POMDR's general fund to help more dogs, carrying on the legacy of your love for dogs.</p>
      </div>
      <div>
        <h2 class="section-title" style="margin-bottom:16px">Who it is for</h2>
        <p style="font-size:17px;line-height:1.7">The program is for guardians without a friend or family member who can care for their dog. Eligibility for enrollment is determined by our staff and depends on the dog's age, size, temperament, and any special medical needs. Many of our Perpetual Care members also receive Helping Paw support during their lifetime.</p>
        <h2 class="section-title" style="margin:48px 0 16px">Our promise to you</h2>
        <p style="font-size:17px;line-height:1.7">A lifetime commitment. Once your dog is in our care, we guarantee a loving, warm, and safe home, either in a POMDR foster home or with a new adoptive family, for the rest of their life. You can have peace of mind that your dog will never end up alone and frightened in an animal shelter.</p>
        <h2 class="section-title" style="margin:48px 0 16px">Get started</h2>
        <p style="font-size:17px;line-height:1.7">Call us first. We sit down with you, meet your dog, and walk through paperwork together.</p>
        <a href="mailto:info@pomdr.org" class="btn btn-primary" style="margin-top:16px">Schedule a Conversation</a>
      </div>
    </div>
  </div>
</section>

<section class="cta-strip" style="background:var(--cream-2)">
  <div class="container">
    <h2 class="serif">Also see <em>Helping Paw</em>.</h2>
    <div class="ctas">
      <a href="/helping-paw/" class="btn btn-primary">Helping Paw Program</a>
      <a href="/surrender/" class="btn btn-outline">Placing Your Dog</a>
    </div>
  </div>
</section>

<section class="section-sm">
  <div class="container" style="max-width:760px;text-align:center">
    <p style="font-size:18px;color:var(--ink-2);margin:0 0 16px">For more information, visit our page of frequently asked questions.</p>
    <a href="/perpetual-care-faq/" class="btn btn-primary">Perpetual Care FAQ</a>
  </div>
</section>

</main>

</main>
<?php get_footer();
