<?php
/**
 * Template for the Perpetual Care FAQ page (WP slug: perpetual-care-faq).
 * The seven real questions and answers from the live lifetimecareFAQ.html
 * (synced 2026-07-21, data/content/perpetual_faq.json).
 */
get_header();

$file = get_stylesheet_directory() . '/data/content/perpetual_faq.json';
$faqs = is_readable( $file ) ? json_decode( (string) file_get_contents( $file ), true ) : array();
if ( ! is_array( $faqs ) ) { $faqs = array(); }
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <div class="ph-split">
      <div class="ph-text">
    <h1 class="page-headline">Perpetual Care FAQ</h1>
    <p class="page-narrative">Peace of mind, <em>answered.</em></p>
    <p class="page-lead">Common questions about the Perpetual Care Program. For the program overview, see the <a href="/perpetual-care-program/" style="color:var(--blue-text);text-decoration:underline">Perpetual Care page</a>.</p>
      </div>
      <div class="ph-media">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/pages/perpetualcare.jpg" alt="A senior dog cared for at home">
      </div>
    </div>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:840px">
    <?php foreach ( $faqs as $f ) : ?>
    <details class="faq-acc">
      <summary><?php echo esc_html( $f['question'] ?? '' ); ?></summary>
      <p><?php echo esc_html( $f['answer'] ?? '' ); ?></p>
    </details>
    <?php endforeach; ?>
    <div style="margin-top:36px">
      <a href="/perpetual-care-program/" class="btn btn-primary">About Perpetual Care</a>
      <a href="mailto:info@pomdr.org" class="btn btn-outline">Ask Us Directly</a>
    </div>
  </div>
</section>

</main>
<style>
.faq-acc { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); margin-bottom: 14px; padding: 0 26px; }
.faq-acc summary { cursor: pointer; font-family: var(--font-serif); font-size: 21px; font-weight: 500; padding: 20px 0; min-height: 44px; }
.faq-acc summary:hover { color: var(--blue-text); }
.faq-acc p { margin: 0 0 22px; font-size: 17px; line-height: 1.7; color: var(--ink-2); }
</style>
<?php get_footer();
