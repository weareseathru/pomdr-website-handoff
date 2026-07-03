<?php
/**
 * Template for the Adoption Events page (WP slug: adoption-events).
 *
 * A filtered view of the existing Events CPT, not a hand-edited page: it lists
 * only events flagged with the ACF `is_adoption_event` checkbox, upcoming only
 * (past events excluded), soonest first. It reuses the same .events-grid /
 * .event-card markup and the pomdr_event_date() helper as the [events]
 * shortcode, and does not touch that shortcode or the main events listing.
 *
 * Chrome + footer from the theme; shared look from pomdr.css.
 */
get_header();

// event_start is an ACF date_time_picker stored as Y-m-d H:i:s. Exclude events
// whose start is before now (local time), soonest first.
$pom_now = current_time( 'Y-m-d H:i:s' );

$pom_events = new WP_Query( array(
    'post_type'      => 'events',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => 'event_start',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => array(
        'relation' => 'AND',
        array( 'key' => 'is_adoption_event', 'value' => '1', 'compare' => '=' ),
        array( 'key' => 'event_start', 'value' => $pom_now, 'compare' => '>=', 'type' => 'DATETIME' ),
    ),
) );
?>
<main id="main-content">
<style>
/* Page hero, matching the Adopt listing template's header treatment. */
.hero{padding:40px 0 56px;background:radial-gradient(ellipse at 20% 0%,rgba(0,139,176,.07),transparent 55%),radial-gradient(ellipse at 80% 10%,rgba(99,47,136,.05),transparent 55%),var(--cream);border-bottom:1px solid var(--line);}
.hero .lead{font-size:19px;color:var(--ink-2);max-width:60ch;margin:18px 0 0;}
.events-empty{font-size:19px;color:var(--ink-2);max-width:62ch;line-height:1.55;padding:20px 0 40px;}
</style>

<section class="hero">
  <div class="container hero-inner">
    <span class="eyebrow">Out and About</span>
    <h1 class="page-headline">Adoption Events</h1>
    <p class="page-narrative">See our dogs <em>in person.</em></p>
    <p class="lead">Our adoptable dogs come to events around the Monterey Peninsula. Here is where you can say hello next.</p>
  </div>
</section>

<section class="grid-section">
  <div class="container">
    <?php if ( $pom_events->have_posts() ) : ?>
      <div class="events-grid">
        <?php
        while ( $pom_events->have_posts() ) :
            $pom_events->the_post();
            $id      = get_the_ID();
            $type    = trim( (string) get_field( 'event_type', $id ) );
            $start   = pomdr_event_date( get_field( 'event_start', $id ) );
            $end     = pomdr_event_date( get_field( 'event_end', $id ) );
            $details = trim( (string) get_field( 'event_details', $id ) );
            $thumb   = get_post_thumbnail_id( $id );
            $when    = $start . ( ( $end && $end !== $start ) ? ' &ndash; ' . $end : '' );
            ?>
            <article class="event-card">
              <?php if ( $thumb ) echo '<div class="event-photo">' . wp_get_attachment_image( $thumb, 'medium_large', false, array( 'alt' => get_the_title(), 'loading' => 'lazy' ) ) . '</div>'; ?>
              <div class="event-body">
                <?php if ( $type !== '' ) echo '<div class="eyebrow">' . esc_html( $type ) . '</div>'; ?>
                <h3 class="event-title"><?php echo esc_html( get_the_title() ); ?></h3>
                <?php if ( $when !== '' ) echo '<div class="event-meta">' . wp_kses_post( $when ) . '</div>'; ?>
                <?php if ( $details !== '' ) echo '<p class="event-desc">' . esc_html( wp_trim_words( $details, 36 ) ) . '</p>'; ?>
              </div>
            </article>
            <?php
        endwhile;
        ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p class="events-empty">No adoption events are on the calendar right now. See <a href="<?php echo esc_url( home_url( '/events/' ) ); ?>">all upcoming events</a> or <a href="<?php echo esc_url( home_url( '/mailing-list/' ) ); ?>">join our mailing list</a> so you hear about the next one.</p>
    <?php endif; ?>
  </div>
</section>

</main>
<?php get_footer();
