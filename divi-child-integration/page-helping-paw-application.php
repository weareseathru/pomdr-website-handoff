<?php
/**
 * Template for the Helping Paw Application page (WP slug: helping-paw-application).
 * Embeds the live LGL Helping Paw financial-assistance form, in English or
 * Spanish (?lang=es). Form IDs verified 2026-07-07 by reading the iframes on
 * the live pages (POMDRHelpingPawFinanceApplication.php and the Spanish
 * variant). The program guidelines live on /helping-paw/.
 */
get_header();

$lang    = ( isset( $_GET['lang'] ) && 'es' === sanitize_key( $_GET['lang'] ) ) ? 'es' : 'en';
$form_en = 'juTPWG_R_fqyeTJQ7kHyGQ'; // Helping Paw financial assistance (English)
$form_es = '2_mtgQzfi1X4cayx8BYrVw'; // Solicitud de ayuda financiera (Spanish)
$form_id = ( 'es' === $lang ) ? $form_es : $form_en;
$iframe_src = 'https://secure.lglforms.com/form_engine/s/' . $form_id;
$self       = home_url( '/helping-paw-application/' );
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Helping Paw</h1>
    <p class="page-narrative"><?php echo ( 'es' === $lang ) ? 'Solicite <em>ayuda financiera</em>.' : 'Apply for <em>financial help</em>.'; ?></p>
    <p class="page-lead"><?php echo ( 'es' === $lang )
        ? 'Cu&eacute;ntenos sobre usted y su perro. Nuestro equipo revisa cada solicitud personalmente.'
        : 'Tell us about you and your dog. Our team reads every application personally, and we will be in touch.'; ?></p>
    <div style="margin-top:18px">
      <?php if ( 'es' === $lang ) : ?>
        <a class="btn btn-outline" href="<?php echo esc_url( $self ); ?>">Switch to English</a>
      <?php else : ?>
        <a class="btn btn-outline" href="<?php echo esc_url( add_query_arg( 'lang', 'es', $self ) ); ?>">Solicitar en espa&ntilde;ol</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:820px">
    <iframe id="helping-paw-iframe"
            src="<?php echo esc_url( $iframe_src ); ?>"
            title="<?php echo ( 'es' === $lang ) ? 'Solicitud de ayuda financiera Helping Paw' : 'Helping Paw financial assistance application'; ?>"
            width="100%" height="1600"
            style="border:0;max-width:760px;margin:0 auto;display:block;background:#fff;border-radius:14px;"></iframe>
    <noscript><p><a href="<?php echo esc_url( $iframe_src ); ?>"><?php echo ( 'es' === $lang ) ? 'Abrir la solicitud' : 'Open the application form'; ?></a></p></noscript>

    <p style="margin-top:32px;color:var(--ink-3);font-size: 21px"><?php echo ( 'es' === $lang )
        ? '¿Preguntas? Llame al (831) 718-9122 o escriba a <a href="mailto:info@pomdr.org" style="color:var(--blue-text)">info@pomdr.org</a>.'
        : 'Questions first? Call (831) 718-9122 or email <a href="mailto:info@pomdr.org" style="color:var(--blue-text)">info@pomdr.org</a>. Program guidelines are on the <a href="' . esc_url( home_url( '/helping-paw/' ) ) . '" style="color:var(--blue-text)">Helping Paw page</a>.'; ?></p>
  </div>
</section>

</main>
<script src="https://secure.lglforms.com/form_engine/s/tfs_iframe.js"></script>
<?php get_footer();
