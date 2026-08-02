<?php
/**
 * Template for the Intake Questionnaire page (WP slug: intake-questionnaire).
 * Embeds the live LGL intake (guardian surrender) form. The form ID was
 * verified 2026-07-07 by reading the iframe on the live page
 * (peaceofminddogrescue.org/POMDRIntakeQuestionnaire.php).
 */
get_header();

$lgl_form_id = 'qaEwtEwFynxBALn0rRh7AA'; // POMDR intake questionnaire
$iframe_src  = 'https://secure.lglforms.com/form_engine/s/' . $lgl_form_id;
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Placing your dog</h1>
    <p class="page-narrative">Start the <em>conversation</em>.</p>
    <p class="page-lead">Tell us about your dog and your situation. This is a conversation starter, not a commitment. Our intake team reads every submission personally and will reach out with next steps.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:820px">
    <iframe id="intake-iframe"
            src="<?php echo esc_url( $iframe_src ); ?>"
            title="POMDR intake questionnaire"
            width="100%" height="1600"
            style="border:0;max-width:760px;margin:0 auto;display:block;background:#fff;border-radius:14px;"></iframe>
    <noscript><p>To start, visit <a href="<?php echo esc_url( $iframe_src ); ?>">our intake questionnaire</a>.</p></noscript>

    <p style="margin-top:32px;color:var(--ink-3);font-size: 21px">Rather talk it through first? Call (831) 718-9122 or email <a href="mailto:info@pomdr.org" style="color:var(--blue-text)">info@pomdr.org</a>. Si necesita ayuda en espa&ntilde;ol, ll&aacute;menos y con gusto le asistiremos.</p>
  </div>
</section>

</main>
<script src="https://secure.lglforms.com/form_engine/s/tfs_iframe.js"></script>
<?php get_footer();
