<?php
/**
 * Single Pet (Dog) Detail Page Template
 *
 * Overrides Divi's default single post template for the `pets` CPT.
 * Replicates the design from pomdr-website/project/dog/pebble.html.
 *
 * Visual spec: pomdr-website/project/dog/pebble.html
 * CSS: assets/css/pomdr-design.css (loaded via inc/enqueue.php)
 */

get_header();

while ( have_posts() ) :
    the_post();

    // ACF field helpers.
    $breed         = get_field( 'breed' );
    $sex           = get_field( 'sex' );
    $age_years     = get_field( 'age_years' );
    $age_approx    = get_field( 'age_approximate' );
    $weight_lb     = get_field( 'weight_lb' );
    $status        = get_field( 'status' ) ?: 'available';
    $foster_start  = get_field( 'foster_date_start' );
    $foster_end    = get_field( 'foster_date_end' );
    $campaign_tag  = get_field( 'campaign_tag' );
    $story_short   = get_field( 'story_short' );
    $story_long    = get_field( 'story_long' );
    $gallery       = get_field( 'gallery' );

    // Override layer: if an override value is set, use it.
    $name          = get_field( 'override_name' ) ?: get_the_title();
    $breed         = get_field( 'override_breed' ) ?: $breed;
    $age_years     = get_field( 'override_age_years' ) ?: $age_years;

    // Age display string.
    $age_prefix    = $age_approx ? '~' : '';
    $age_display   = $age_years ? $age_prefix . $age_years . ' yrs' : '';

    // Vitals line: ~12 yrs · Female · 11 lb · Long-haired Dachshund
    $vitals = implode( ' &middot; ', array_filter( array(
        $age_display,
        $sex,
        $weight_lb ? $weight_lb . ' lb' : '',
        $breed,
    ) ) );

    // Adoption questionnaire URL with dog name prefill.
    $adopt_url = home_url( '/adoption-questionnaire/?dogname=' . rawurlencode( $name ) );
    $foster_url = home_url( '/foster-needs/' );

    // Status labels map.
    $status_labels = array(
        'available'           => 'Available for adoption',
        'foster-needed'       => 'Foster needed',
        'foster-needed-dated' => 'Foster needed',
        'adoption-pending'    => 'Adoption pending',
        'recently-adopted'    => 'Recently adopted',
        'hospice'             => 'Hospice care',
        'courtesy-listing'    => 'Courtesy listing',
    );
    $status_label = $status_labels[ $status ] ?? 'Available for adoption';
?>

<main id="main" class="pomdr-pet-single">
    <header class="page-header">
        <div class="container">

            <a href="<?php echo esc_url( home_url( '/adopt/' ) ); ?>" class="back-link" aria-label="Back to all dogs">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                All Dogs
            </a>

            <?php if ( $campaign_tag ) : ?>
                <div class="campaign-ribbon campaign-ribbon--<?php echo esc_attr( $campaign_tag ); ?>">
                    <?php echo $campaign_tag === 'forever-starts-here' ? 'Forever Starts Here' : 'Helping Paw Featured'; ?>
                </div>
            <?php endif; ?>

            <div class="pet-hero-grid">
                <div class="pet-hero-text">
                    <span class="eyebrow">
                        <?php
                        $category = get_field( 'category' ) ?: 'adoptable';
                        $eyebrow_labels = array(
                            'adoptable' => 'Adoptable Dog',
                            'courtesy'  => 'Courtesy Listing',
                            'hospice'   => 'Hospice Care',
                        );
                        echo esc_html( $eyebrow_labels[ $category ] ?? 'Adoptable Dog' );
                        ?>
                    </span>

                    <h1 class="page-title"><?php echo esc_html( $name ); ?></h1>

                    <?php if ( $vitals ) : ?>
                        <p class="vitals-line"><?php echo $vitals; ?></p>
                    <?php endif; ?>

                    <div class="dog-status" data-status="<?php echo esc_attr( $status ); ?>">
                        <span class="dog-status-dot" aria-hidden="true"></span>
                        <span class="dog-status-label"><?php echo esc_html( $status_label ); ?></span>
                        <?php if ( $status === 'foster-needed-dated' && $foster_start && $foster_end ) : ?>
                            <span class="foster-dates">
                                <?php echo esc_html( date( 'M j', strtotime( $foster_start ) ) . ' to ' . date( 'M j', strtotime( $foster_end ) ) ); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ( $story_long ) : ?>
                        <div class="page-lead prose">
                            <?php echo wp_kses_post( $story_long ); ?>
                        </div>
                    <?php elseif ( $story_short ) : ?>
                        <p class="page-lead"><?php echo esc_html( $story_short ); ?></p>
                    <?php endif; ?>

                    <?php if ( in_array( $status, array( 'available', 'foster-needed', 'foster-needed-dated' ) ) ) : ?>
                        <div class="adoption-block">
                            <p class="adoption-assurance">We review every application and reply within 48 hours. No pressure, just a conversation.</p>
                            <div class="page-cta">
                                <a href="<?php echo esc_url( $adopt_url ); ?>" class="btn btn-primary">
                                    Apply to Adopt <?php echo esc_html( $name ); ?>
                                </a>
                                <a href="<?php echo esc_url( $foster_url ); ?>" class="btn btn-outline">
                                    Foster <?php echo esc_html( $name ); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="pet-hero-media">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="media media-portrait" style="aspect-ratio:3/2">
                            <?php the_post_thumbnail( 'large', array(
                                'alt'   => esc_attr( $name ),
                                'class' => 'pet-featured-photo',
                            ) ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <?php if ( $gallery ) : ?>
        <section class="section" style="padding-top:0">
            <div class="container">
                <p class="gallery-caption">More of <?php echo esc_html( $name ); ?></p>
                <div class="dog-gallery" aria-label="<?php echo esc_attr( $name ); ?> photo gallery">
                    <?php foreach ( $gallery as $i => $image ) : ?>
                        <button
                            type="button"
                            class="gallery-thumb"
                            aria-label="Open photo <?php echo $i + 1; ?> of <?php echo count( $gallery ); ?>"
                            data-full="<?php echo esc_url( $image['url'] ); ?>"
                            data-caption="<?php echo esc_attr( $image['alt'] ?: $name ); ?>"
                        >
                            <img
                                src="<?php echo esc_url( $image['sizes']['medium'] ); ?>"
                                alt="<?php echo esc_attr( $image['alt'] ?: $name ); ?>"
                                loading="lazy"
                            />
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="cta-strip" style="background:var(--cream-2)">
        <div class="container">
            <h2 class="serif">Meet more <em>senior dogs</em>.</h2>
            <div class="ctas">
                <a href="<?php echo esc_url( home_url( '/adopt/' ) ); ?>" class="btn btn-primary">All Dogs</a>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline">Ask a Question</a>
            </div>
        </div>
    </section>
</main>

<?php
endwhile;

get_footer();
