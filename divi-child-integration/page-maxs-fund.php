<?php
/**
 * Template for the Max's Helping Paws Fund page (WP slug: maxs-fund).
 * The origin story from the live maxsfund.html (synced 2026-07-21,
 * data/content/maxsfund.json).
 */
get_header();

$file = get_stylesheet_directory() . '/data/content/maxsfund.json';
$d    = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : array();
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline"><?php echo esc_html( $d['headline'] ?? "Max's Helping Paws Fund" ); ?></h1>
    <p class="page-narrative">Veterinary help when it <em>matters most.</em></p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:800px">
    <?php foreach ( ( $d['body_paragraphs'] ?? array() ) as $para ) : ?>
    <p style="font-size:18px;line-height:1.75;color:var(--ink-2);margin:0 0 18px"><?php echo esc_html( $para ); ?></p>
    <?php endforeach; ?>
    <div style="margin-top:30px">
      <a href="<?php echo esc_url( home_url( '/donation/?fund=' . rawurlencode( "Max's Helping Paws Fund" ) ) ); ?>" class="btn btn-primary"><?php echo esc_html( $d['cta_text'] ?? "Donate to Max's Fund" ); ?></a>
      <a href="/helping-paw/" class="btn btn-outline">About Helping Paw</a>
    </div>
  </div>
</section>

</main>
<?php get_footer();
