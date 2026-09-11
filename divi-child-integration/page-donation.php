<?php
/**
 * Template for the Donation form page (WP slug: donation).
 * Embeds the main LGL donation form (62FAoG7Obtf81TYETJMN3Q, confirmed in
 * STACK.md) with the same query passthrough the live POMDRDonation.php uses,
 * verified 2026-07-21 against the live page's iframe construction:
 *   ?initialdonation=N        -> field_26 (amount)
 *   ?fund=Fund Name           -> field_64 (fund restriction)
 *   ?donationtype=tribute     -> field_37=Tribute
 * Unlike live, values are properly URL-encoded. Links from /donate/ and the
 * legacy redirects carry these params, so fund-restricted and tribute giving
 * complete on this site.
 */
get_header();

$iframe_src = 'https://secure.lglforms.com/form_engine/s/62FAoG7Obtf81TYETJMN3Q';
$args = array();
if ( isset( $_GET['initialdonation'] ) && is_numeric( $_GET['initialdonation'] ) ) {
    $args['field_26'] = (int) $_GET['initialdonation'];
}
if ( isset( $_GET['fund'] ) ) {
    $fund = sanitize_text_field( wp_unslash( $_GET['fund'] ) );
    if ( '' !== $fund ) { $args['field_64'] = $fund; }
}
if ( isset( $_GET['donationtype'] ) && 'tribute' === sanitize_key( $_GET['donationtype'] ) ) {
    $args['field_37'] = 'Tribute';
}
if ( $args ) { $iframe_src = add_query_arg( array_map( 'rawurlencode', $args ), $iframe_src ); }
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Make a Donation</h1>
    <p class="page-narrative">Every dollar goes to <em>the dogs.</em></p>
    <?php if ( isset( $args['field_64'] ) ) : ?>
      <p class="page-lead" style="font-weight:600;color:var(--purple)"><?php echo esc_html( 'Giving to: ' . $args['field_64'] ); ?></p>
    <?php endif; ?>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:860px">
    <iframe id="donation-iframe"
            src="<?php echo esc_url( $iframe_src ); ?>"
            title="POMDR donation form"
            width="100%" height="2200"
            style="border:0;max-width:800px;margin:0 auto;display:block;background:#fff;border-radius:14px;"></iframe>
    <noscript><p>To donate, visit <a href="<?php echo esc_url( $iframe_src ); ?>">our donation form</a>.</p></noscript>
    <p style="margin-top:28px;color:var(--ink-3);font-size: 21px">Peace of Mind Dog Rescue is a 501(c)(3) nonprofit. EIN 27-1154816. Questions? Email <a href="mailto:info@pomdr.org" style="color:var(--blue-text)">info@pomdr.org</a> or call (831) 718-9122.</p>
  </div>
</section>

</main>
<script src="https://secure.lglforms.com/form_engine/s/tfs_iframe.js"></script>
<?php get_footer();
