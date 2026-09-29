<?php
/**
 * Template for the Adoption Application page (WP slug: adoption-questionnaire).
 * Embeds the live Little Green Light (LGL) adoption form. A dog name passed as
 * ?field_21= (from a dog profile "Apply to adopt" link) prefills LGL field_21.
 * LGL is the confirmed forms provider (see STACK.md / docs/RISK-REGISTER.md).
 */
get_header();

$lgl_form_id = 'utzjcNEZaqAcJk3QURlQmw'; // POMDR adoption inquiry form
// Dog profile links send ?dogname=; accept it (and the LGL-native ?field_21=)
// so the prefill actually reaches the form either way. Closes risk B1/R7.
$prefill     = isset( $_GET['field_21'] ) ? sanitize_text_field( wp_unslash( $_GET['field_21'] ) ) : '';
if ( '' === $prefill && isset( $_GET['dogname'] ) ) {
    $prefill = sanitize_text_field( wp_unslash( $_GET['dogname'] ) );
}
$iframe_src  = 'https://secure.lglforms.com/form_engine/s/' . $lgl_form_id;
if ( '' !== $prefill ) {
    $iframe_src = add_query_arg( 'field_21', rawurlencode( $prefill ), $iframe_src );
}
?>
<main id="main-content">
  <header class="page-header">
    <div class="container">
      <h1 class="page-headline">Adoption</h1>
      <p class="page-narrative">Start your <em>application</em>.</p>
      <p class="page-lead">Tell us about your home and the senior dog you have in mind. We read every application personally and will be in touch. We cover the care; you bring the couch and the love.</p>
    </div>
  </header>
  <section class="section">
    <div class="container">
      <?php if ( '' !== $prefill ) : ?>
        <p style="font-weight:600;color:var(--purple);margin:0 0 18px;font-size: 23px;"><?php echo esc_html( 'Applying to adopt: ' . $prefill ); ?></p>
      <?php endif; ?>
      <iframe id="adoption-iframe"
              src="<?php echo esc_url( $iframe_src ); ?>"
              title="POMDR adoption application"
              width="100%" height="1400"
              style="border:0;max-width:760px;margin:0 auto;display:block;background:#fff;border-radius:14px;"></iframe>
      <noscript><p>To apply, visit <a href="<?php echo esc_url( $iframe_src ); ?>">our adoption form</a>.</p></noscript>
    </div>
  </section>
  <script src="https://secure.lglforms.com/form_engine/s/tfs_iframe.js"></script>
</main>
<?php get_footer();
