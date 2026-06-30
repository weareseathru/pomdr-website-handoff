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
        <p style="font-size:17px;line-height:1.7">223 Grand Avenue, Pacific Grove, CA<br/>Open Tuesday through Saturday. Call ahead to confirm hours: (831) 718-9122.</p>
        <h2 class="section-title" style="margin:48px 0 16px">Donate goods</h2>
        <p style="font-size:17px;line-height:1.7">We accept gently used books, kitchenware, small furniture, collectibles, and clothing in good condition. Please call before bringing larger items.</p>
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
      <a href="mailto:info@pomdr.org" class="btn btn-outline">Call the Shop</a>
    </div>
  </div>
</section>
</main>
<?php get_footer();
