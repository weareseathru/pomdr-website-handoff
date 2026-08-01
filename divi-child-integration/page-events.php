<?php
/**
 * Template for the What's Happening page (WP slug: events; the /events/ URL is
 * kept so existing links and the nav do not break).
 *
 * Two co-equal sections, both fed live from the Events post type (wp-admin >
 * Events), split by the event_type field that staff already choose:
 *   - "What's Happening"  = Special Event + Perpetual Event (fundraisers, galas,
 *                            community happenings)
 *   - "Adoption Events"   = Adoption Event (meet the dogs in person)
 * Add or edit an event in wp-admin and it lands in the right section by its
 * type. Both sections show upcoming events only, soonest first.
 */
get_header();

$whats_happening = pomdr_collect_events( array( 'Special Event', 'Perpetual Event' ) );
$adoption_events = pomdr_collect_events( array( 'Adoption Event' ) );
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">What's Happening</h1>
    <p class="page-narrative">Fundraisers, celebrations, and <em>days out with the dogs.</em></p>
    <p class="page-lead">Come say hello. Below are our upcoming fundraisers and special events, and the adoption events where you can meet our dogs in person.</p>
  </div>
</header>

<section class="section" id="whats-happening" aria-labelledby="whats-happening-title">
  <div class="container">
    <h2 class="section-title" id="whats-happening-title">Fundraisers &amp; Special Events</h2>
    <p class="section-intro">Galas, community gatherings, and ways to have fun while helping senior dogs.</p>
    <?php echo pomdr_render_events( $whats_happening, 'h3', false, 'No fundraisers or special events on the calendar right now. Call (831) 718-9122 or check our Facebook for what is coming up.' ); ?>
  </div>
</section>

<section class="section section--tint" id="adoption-events" aria-labelledby="adoption-events-title">
  <div class="container">
    <h2 class="section-title" id="adoption-events-title">Adoption Events</h2>
    <p class="section-intro">Meet our adoptable senior dogs in person and find your new old best friend.</p>
    <?php echo pomdr_render_events( $adoption_events, 'h3', false, 'No adoption events scheduled right now. You can still meet our dogs any time. Call (831) 718-9122 to arrange a visit.' ); ?>
  </div>
</section>

</main>
<style>
/* The two sections carry equal weight: same title size, same spacing; the
   second gets a soft tint so the split reads clearly. */
.section--tint { background: var(--purple-50); }
.section-intro { font-size: 18px; line-height: 1.6; color: var(--ink-2); margin: 0 0 28px; max-width: 60ch; }
</style>
<?php get_footer();
