<?php
/** Template for the Benefit Shop page (WP slug: benefit-shop). Ports prototype benefit-shop.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<style>
/* Lay the content across the width: a text+photo intro, and the long donation
   lists read in 2 columns so the page reads horizontally, not one tall column. */
.shop-intro{display:grid;grid-template-columns:1.1fr 1fr;gap:48px;align-items:center;}
.shop-intro .media{aspect-ratio:4/5;background:var(--cream-2);border-radius:var(--radius-lg);overflow:hidden;}
.shop-intro .media img{width:100%;height:100%;object-fit:cover;display:block;}
.lists-2col{display:grid;grid-template-columns:1fr 1fr;gap:32px 48px;margin-top:8px;}
.lists-2col ul{font-size:16px;line-height:1.7;padding-left:1.2em;columns:2;column-gap:32px;}
.lists-2col li{break-inside:avoid;}
@media(max-width:860px){.shop-intro{grid-template-columns:1fr;gap:26px;}.lists-2col{grid-template-columns:1fr;gap:24px;}.lists-2col ul{columns:1;}}
</style>
<main id="main-content">
<header class="page-header">
  <div class="container">
    <div class="ph-split">
      <div class="ph-text">
        <h1 class="page-headline">Benefit Shop</h1>
        <p class="page-narrative">A whole store that <em>helps senior dogs</em>.</p>
        <p class="page-lead">Books, housewares, treasures, all donated by neighbors, all sold to fund vet care for senior dogs. Run by our shop staff and dedicated volunteers.</p>
        <div class="ctas" style="display:flex;gap:14px;flex-wrap:wrap;margin-top:22px">
          <a href="https://maps.google.com/?q=223+Grand+Ave,+Pacific+Grove,+CA" target="_blank" rel="noopener" class="btn btn-primary">Get Directions</a>
          <a href="tel:8313128991" class="btn btn-outline">Call the Shop</a>
        </div>
      </div>
      <div class="ph-media"><picture><img src="<?php echo $img; ?>/pages/benefitshop.jpg" alt="Inside the POMDR Benefit Shop in Pacific Grove" loading="lazy"/></picture></div>
    </div>
  </div>
</header>

<section class="section">
  <div class="container">
    <div class="shop-intro">
      <div>
        <h2 class="section-title" style="margin-bottom:16px">Visit the shop</h2>
        <p style="font-size:17px;line-height:1.7">223 Grand Avenue, Suite 1, Pacific Grove, CA 93950<br/>Shop phone: (831) 312-8991</p>
        <p style="font-size:17px;line-height:1.7"><strong>Hours:</strong><br/>Tuesday through Saturday, 11:00 am to 5:00 pm<br/>Closed Sunday and Monday</p>
        <p style="font-size:17px;line-height:1.7">Come check out our artwork, clothing, jewelry, home goods, furniture, and other merchandise. The shop specializes in higher-end, lightly used items at reasonable prices.</p>
      </div>
      <div class="media">
        <img src="<?php echo $img; ?>/dog12.jpeg" alt="A senior dog cared for thanks to Benefit Shop sales" loading="lazy"/>
      </div>
    </div>

    <h2 class="section-title" style="margin:48px 0 16px">Donate goods</h2>
    <p style="font-size:17px;line-height:1.7">To make a donation, email <a href="mailto:shop@pomdr.org">shop@pomdr.org</a>. Please let us know what you have to donate and your preferred day and time frame. If an item is large, email a photo first so our shop manager can confirm we have space.</p>
    <div class="lists-2col">
      <div>
        <h3 style="font-size:19px;margin:20px 0 8px">Items we take</h3>
        <ul>
          <li>Accessories such as jewelry, purses, and scarves</li>
          <li>Home decor such as artwork and frames</li>
          <li>Lamps and lighting</li>
          <li>Diningware such as dish sets and glassware</li>
          <li>Patio decor</li>
          <li>Seasonal and holiday decor (in season)</li>
          <li>Vintage and collectibles for the home</li>
          <li>Table linens</li>
          <li>Dressers, bookcases, coffee and end tables, chairs, and kitchen or dining room tables</li>
          <li>Books</li>
          <li>Gardening items</li>
          <li>Sports equipment such as sports balls, hand weights, and small equipment</li>
          <li>Toys and games</li>
          <li>Clothing, men's and women's lightly worn items</li>
        </ul>
      </div>
      <div>
        <h3 style="font-size:19px;margin:20px 0 8px">Items we cannot use</h3>
        <ul>
          <li>Mattresses</li>
          <li>Baby equipment such as car seats and high chairs (safety regulations and recalls)</li>
          <li>Upholstered furniture of any type</li>
          <li>Hangers</li>
          <li>Medical and mobility equipment such as walkers and portable toilet seats</li>
          <li>TVs</li>
          <li>Toxic materials and opened paint cans</li>
          <li>Anything in disrepair, dirty, moldy, or mildewed</li>
        </ul>
      </div>
    </div>

    <h2 class="section-title" style="margin:48px 0 16px">Volunteer</h2>
    <p style="font-size:17px;line-height:1.7">The shop runs on volunteers. Sorting, staging, retail shifts, pickups. A few hours a month makes a real difference.</p>
    <a href="/volunteer/" class="btn btn-primary" style="margin-top:24px">Volunteer at the Shop</a>
  </div>
</section>

<section class="cta-strip" style="background:var(--cream-2)">
  <div class="container">
    <h2 class="serif">Every dollar funds <em>senior dog care</em>.</h2>
    <div class="ctas">
      <a href="/donate/" class="btn btn-primary">Donate Funds</a>
      <a href="tel:8313128991" class="btn btn-outline">Call the Shop</a>
    </div>
  </div>
</section>

<section class="section" id="team">
  <div class="container">
    <span class="eyebrow purple">Our team</span>
    <h2 class="section-title">The people you will <em>meet</em>.</h2>
    <p class="section-lead">The team behind the counter, turning donated treasures into vet care for senior dogs.</p>
    <?php echo do_shortcode('[benefit_shop_staff]'); ?>
  </div>
</section>

</main>
<?php get_footer();
