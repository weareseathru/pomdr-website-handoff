<?php
/** Template for the Benefit Shop page (WP slug: benefit-shop). Ports prototype benefit-shop.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">
<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Benefit Shop</h1>
    <p class="page-narrative">A whole store that <em>helps senior dogs</em>.</p>
    <p class="page-lead">Books, housewares, treasures, all donated by neighbors, all sold to fund vet care for senior dogs. Run by a tiny team of dedicated volunteers.</p>
  </div>
</header>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1.1fr 1fr;gap:48px;align-items:center">
      <div>
        <h2 class="section-title" style="margin-bottom:16px">Visit the shop</h2>
        <p style="font-size:17px;line-height:1.7">223 Grand Avenue, Suite 1, Pacific Grove, CA 93950<br/>Shop phone: (831) 312-8991</p>
        <p style="font-size:17px;line-height:1.7"><strong>Hours:</strong><br/>Tuesday through Saturday, 11:00 am to 5:00 pm<br/>Sunday, 11:00 am to 3:00 pm<br/>Closed Mondays</p>
        <p style="font-size:17px;line-height:1.7">Come check out our artwork, clothing, jewelry, home goods, furniture, and other merchandise. The shop specializes in higher-end, lightly used items at reasonable prices.</p>
        <h2 class="section-title" style="margin:48px 0 16px">Donate goods</h2>
        <p style="font-size:17px;line-height:1.7">To make a donation, email <a href="mailto:info@pomdr.org">info@pomdr.org</a>. Please let us know what you have to donate and your preferred day and time frame. If an item is large, email a photo first so our shop manager can confirm we have space.</p>
        <h3 style="font-size:19px;margin:28px 0 8px">Items we take</h3>
        <ul style="font-size:16px;line-height:1.7;padding-left:1.2em">
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
        <h3 style="font-size:19px;margin:28px 0 8px">Items we cannot use</h3>
        <ul style="font-size:16px;line-height:1.7;padding-left:1.2em">
          <li>Mattresses</li>
          <li>Baby equipment such as car seats and high chairs (safety regulations and recalls)</li>
          <li>Upholstered furniture of any type</li>
          <li>Hangers</li>
          <li>Medical and mobility equipment such as walkers and portable toilet seats</li>
          <li>TVs</li>
          <li>Toxic materials and opened paint cans</li>
          <li>Anything in disrepair, dirty, moldy, or mildewed</li>
        </ul>
        <h2 class="section-title" style="margin:48px 0 16px">Volunteer</h2>
        <p style="font-size:17px;line-height:1.7">The shop runs on volunteers. Sorting, staging, retail shifts, pickups. A few hours a month makes a real difference.</p>
        <a href="/volunteer/" class="btn btn-primary" style="margin-top:24px">Volunteer at the Shop</a>
      </div>
      <div class="media" style="aspect-ratio:4/5;background:var(--cream-2)">
        <picture><source type="image/webp" srcset="<?php echo $img; ?>/hero-mission.webp"><img src="<?php echo $img; ?>/hero-mission.jpeg" alt="A scene from the POMDR Benefit Shop"/></picture>
      </div>
    </div>
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
</main>
<?php get_footer();
