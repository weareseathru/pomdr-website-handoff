<?php
/**
 * Template for the Sponsor a Dog page (WP slug: sponsor-a-dog).
 * Embeds the live LGL sponsor-a-dog form. The form ID was verified 2026-07-07
 * by reading the iframe on the live page (POMDRSponsorDog.php). Dog profile
 * Sponsor buttons pass ?dogname= so the page can greet with the dog's name
 * (the LGL form has no prefill field, so the name is shown above the form).
 */
get_header();

$lgl_form_id = 'k1_dO7VqC3nd7fsh5tFJCA'; // POMDR sponsor-a-dog form
$iframe_src  = 'https://secure.lglforms.com/form_engine/s/' . $lgl_form_id;
$dogname     = isset( $_GET['dogname'] ) ? sanitize_text_field( wp_unslash( $_GET['dogname'] ) ) : '';
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Sponsor a dog</h1>
    <p class="page-narrative">Fund their <em>care</em> while they wait.</p>
    <p class="page-lead">Sponsorship covers food, medicine, and vet visits for a dog still looking for a home, or living out their days in hospice care. Every dollar goes to their care.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:820px">
    <?php if ( '' !== $dogname ) : ?>
      <p style="font-weight:600;color:var(--purple);margin:0 0 18px;font-size:18px;"><?php echo esc_html( 'Sponsoring: ' . $dogname . ' (write the name in the form so we credit the right pup)' ); ?></p>
    <?php endif; ?>
    <iframe id="sponsor-iframe"
            src="<?php echo esc_url( $iframe_src ); ?>"
            title="POMDR sponsor a dog"
            width="100%" height="1400"
            style="border:0;max-width:760px;margin:0 auto;display:block;background:#fff;border-radius:14px;"></iframe>
    <noscript><p>To sponsor, visit <a href="<?php echo esc_url( $iframe_src ); ?>">our sponsor form</a>.</p></noscript>

    <p style="margin-top:32px;color:var(--ink-3);font-size:16px">Questions about sponsorship? Email <a href="mailto:info@pomdr.org" style="color:var(--blue-text)">info@pomdr.org</a> or call (831) 718-9122.</p>
  </div>
</section>

</main>
<script src="https://secure.lglforms.com/form_engine/s/tfs_iframe.js"></script>
<?php get_footer();
