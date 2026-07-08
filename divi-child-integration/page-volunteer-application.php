<?php
/**
 * Template for the Volunteer Application page (WP slug: volunteer-application).
 * Embeds the live Little Green Light (LGL) volunteer application form. The form
 * ID was verified 2026-07-07 by reading the iframe on the live page
 * (peaceofminddogrescue.org/POMDRVolunteerApplication.php).
 */
get_header();

$lgl_form_id = 'HsUdXNpwCEoU8Kc9e_H6Hw'; // POMDR volunteer application form
$iframe_src  = 'https://secure.lglforms.com/form_engine/s/' . $lgl_form_id;
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Volunteer</h1>
    <p class="page-narrative">Apply to <em>volunteer</em>.</p>
    <p class="page-lead">Tell us a little about you and how you would like to help. A volunteer coordinator follows up within a week.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:820px">
    <iframe id="volunteer-iframe"
            src="<?php echo esc_url( $iframe_src ); ?>"
            title="POMDR volunteer application"
            width="100%" height="1600"
            style="border:0;max-width:760px;margin:0 auto;display:block;background:#fff;border-radius:14px;"></iframe>
    <noscript><p>To apply, visit <a href="<?php echo esc_url( $iframe_src ); ?>">our volunteer application form</a>.</p></noscript>

    <p style="margin-top:32px;color:var(--ink-3);font-size:16px">Trouble with the form? Email <a href="mailto:info@pomdr.org" style="color:var(--blue-text)">info@pomdr.org</a> or call (831) 718-9122.</p>
  </div>
</section>

</main>
<script src="https://secure.lglforms.com/form_engine/s/tfs_iframe.js"></script>
<?php get_footer();
