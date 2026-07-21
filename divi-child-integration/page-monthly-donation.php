<?php
/**
 * Template for the Monthly Giving page (WP slug: monthly-donation).
 * Embeds the live LGL form 4T09LUBDyJtOCrfw3GMJqA, verified 2026-07-21 from the
 * iframe on the live page (POMDRMonthlyDonation.php).
 */
get_header();

$iframe_src = 'https://secure.lglforms.com/form_engine/s/4T09LUBDyJtOCrfw3GMJqA';
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Monthly Giving</h1>
    <p class="page-narrative">Steady help, <em>every month.</em></p>
    <p class="page-lead">A monthly gift is the steadiest way to fund vet care for senior dogs. Set it once; change or cancel anytime.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:860px">
    <iframe src="<?php echo esc_url( $iframe_src ); ?>"
            title="POMDR monthly donation form"
            width="100%" height="1600"
            style="border:0;max-width:800px;margin:0 auto;display:block;background:#fff;border-radius:14px;"></iframe>
    <noscript><p><a href="<?php echo esc_url( $iframe_src ); ?>">Open the form</a>.</p></noscript>
    <p style="margin-top:28px;color:var(--ink-3);font-size:16px">Questions? Email <a href="mailto:info@pomdr.org" style="color:var(--blue-text)">info@pomdr.org</a> or call (831) 718-9122.</p>
  </div>
</section>

</main>
<script src="https://secure.lglforms.com/form_engine/s/tfs_iframe.js"></script>
<?php get_footer();
