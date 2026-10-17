<?php
/**
 * Template for the Dog Tag Donor page (WP slug: dog-tag-donor-donation).
 * Embeds the live LGL form 6PuyQVxzC0csoooyizCrhQ, verified 2026-07-21 from the
 * iframe on the live page (POMDRDogTagDonor.php).
 */
get_header();

$iframe_src = 'https://secure.lglforms.com/form_engine/s/6PuyQVxzC0csoooyizCrhQ';
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Dog Tag Donor</h1>
    <p class="page-narrative">Wear the <em>pack colors.</em></p>
    <p class="page-lead">Become a Dog Tag Donor with a gift of $500, and carry a POMDR dog tag that shows you are part of the pack.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:860px">
    <iframe src="<?php echo esc_url( $iframe_src ); ?>"
            title="POMDR dog tag donor form"
            width="100%" height="1600"
            style="border:0;max-width:800px;margin:0 auto;display:block;background:#fff;border-radius:14px;"></iframe>
    <noscript><p><a href="<?php echo esc_url( $iframe_src ); ?>">Open the form</a>.</p></noscript>
    <p style="margin-top:28px;color:var(--ink-3);font-size: 21px">Questions? Email <a href="mailto:info@pomdr.org" style="color:var(--blue-text)">info@pomdr.org</a> or call (831) 718-9122.</p>
  </div>
</section>

</main>
<script src="https://secure.lglforms.com/form_engine/s/tfs_iframe.js"></script>
<?php get_footer();
