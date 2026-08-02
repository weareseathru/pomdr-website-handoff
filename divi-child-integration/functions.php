<?php

// *********** Apply wpautop to Team bio field ***********
add_filter('acf/format_value/name=bio', function($value, $post_id, $field) {
    if ($value) {
        return wpautop($value);
    }
    return $value;
}, 10, 3);

// *********** Pulls Pet Name Used Many Places ***********
function pet_name_shortcode($atts) {
    // Default to the current post ID
    $atts = shortcode_atts( array(
        'post_id' => get_the_ID()
    ), $atts );

    $title = get_the_title($atts['post_id']);

    return esc_html($title);
}
add_shortcode('pet_name', 'pet_name_shortcode');

// *********** Pet Page Addl Info ***********
function pet_age_sex_weight($atts) {
    ob_start();

    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'looks_like'    => 'looks_like',
			'age'    => 'age',    
            'weight' => 'weight',
            'sex'    => 'sex',
            'post_id' => get_the_ID(),
        ),
        $atts,
        'age_sex_weight_shortcode'
    );

    // Get ACF field values
    $looks_like_value    = get_field($atts['looks_like'], $atts['post_id']);
	$age_value    = get_field($atts['age'], $atts['post_id']);
    $weight_value = get_field($atts['weight'], $atts['post_id']);
    // Display value for the sex field (handles mapping of value->label)
    $sex_value_display = pom_acf_sex_display($atts['post_id']);

    // Show whichever vitals exist (a missing field no longer hides the rest),
    // in the redesign voice: "~age" with a tilde, never "(est)".
    $bits = array();
    if ($age_value !== '' && $age_value !== null && $age_value !== false) {
        $bits[] = is_numeric($age_value) ? '~' . $age_value . ' yrs' : $age_value;
    }
    if ($sex_value_display) { $bits[] = $sex_value_display; }
    if ($weight_value) { $bits[] = $weight_value . ' lb'; }
    if ($looks_like_value || $bits) {
        echo '<div class="pdp-vitals">';
        if ($looks_like_value) {
            echo '<div class="pdp-breed">' . esc_html($looks_like_value) . '</div>';
        }
        if ($bits) {
            echo '<div class="pdp-vitals-line">' . esc_html(implode(' · ', $bits)) . '</div>';
        }
        echo '</div>';
    } else {
        echo esc_html('Inquire directly');
    }

    return ob_get_clean();
}
add_shortcode('pet_age_sex_weight_shortcode', 'pet_age_sex_weight');

// Utility: display human-friendly sex label for ACF sex field
function pom_acf_sex_display($post_id) {
    $field = function_exists('get_field_object') ? get_field_object('sex', $post_id) : null;
    if (!$field) return '';
    $value = isset($field['value']) ? $field['value'] : '';
    $choices = isset($field['choices']) ? $field['choices'] : [];
    if (is_array($value)) {
        $labels = [];
        foreach ($value as $v) {
            $labels[] = isset($choices[$v]) ? $choices[$v] : $v;
        }
        return implode(', ', $labels);
    } else {
        return isset($choices[$value]) ? $choices[$value] : $value;
    }
}

/*function auto_generate_slug_with_year($slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug) {
    if ( $post_type !== 'pets' ) {
        return $slug;
    }

    $post = get_post($post_ID);

    // Only run if we have a post object
    if ( ! $post ) {
        return $slug;
    }

    // Don't generate slug for auto-drafts or empty titles
    if (
        in_array( $post->post_status, array('auto-draft', 'draft'), true ) ||
        empty( $post->post_title ) ||
        strtolower( $post->post_title ) === 'auto draft' // skip default titles
    ) {
        return $slug;
    }

    // Avoid double-appending the year if it's already there
    if ( preg_match( '/\d{2}$/', $slug ) ) {
        return $slug;
    }

    $title_slug = sanitize_title( $post->post_title );
    $year_2_digit = date('y');
    $new_slug = $title_slug . $year_2_digit;
    return $new_slug;
}*/
//add_filter('wp_unique_post_slug', 'auto_generate_slug_with_year', 10, 6);

add_action('init', function () {
    add_rewrite_rule(
        '^pets/([0-9]+)/?$',
        'index.php?post_type=pets&p=$matches[1]',
        'top'
    );
});
add_filter('post_type_link', function ($permalink, $post) {
    if ($post->post_type !== 'pets') {
        return $permalink;
    }

    return home_url('/pets/' . $post->ID . '/');
}, 10, 2);


function dt_enqueue_styles() {
    $parenthandle = 'divi-style'; 
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(), // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') 
    );
}
/* The "Custom Blog List" template registration was removed 2026-07-07: it
   pointed at a file that only existed in temp/ scratch (deleted), so choosing
   it in the page editor produced a blank page. */
add_action( 'wp_enqueue_scripts', 'dt_enqueue_styles' );


function adopt_a_pet_shortcode() {
    ob_start();

    $args = [
        'post_type'      => 'pets',
        'posts_per_page' => -1,
        'meta_query'     => [
            'relation' => 'AND',
            [
                'key'     => 'status',
                'value'   => 'Adoptable',
                'compare' => 'LIKE',
            ],
            [
                'key'     => 'status',
                'value'   => 'Adopted',
                'compare' => 'NOT LIKE',
            ],
        ],
        'orderby' => [
            'meta_value' => 'DESC', // Featured first
            'date'       => 'DESC', // Newest first
        ],
        'meta_key' => 'feature',
    ];

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) :
        echo '<div class="dogs-grid">';
        while ($query->have_posts()) : $query->the_post();
            echo pom_render_dog_card(get_the_ID());
        endwhile;
        echo '</div>';
        wp_reset_postdata();
    else :
        echo '<p>No dogs are listed for adoption right now. Please check back soon, or call us at (831) 718-9122.</p>';
    endif;

    return ob_get_clean();
}

add_shortcode('adopt_a_pet', 'adopt_a_pet_shortcode');


function adopt_a_pet_fullwidth_shortcode() { return pomdr_dogs_by_status('Adoptable'); }
add_shortcode('adopt_a_pet_fullwidth', 'adopt_a_pet_fullwidth_shortcode');

// *************************Benifit Shop Staff *******************************

function benefit_shop_staff_shortcode() { return pomdr_team_grid('Benefit Shop Staff'); }
add_shortcode('benefit_shop_staff', 'benefit_shop_staff_shortcode');
 
// ***************************Clinic Staff****************************

function clinic_staff_shortcode() { return pomdr_team_grid('Clinic Staff'); }
add_shortcode('clinic_staff', 'clinic_staff_shortcode');
 
// ***************************Advisory Council****************************

function advisory_council_shortcode() { return pomdr_team_grid('Advisory Council'); }
add_shortcode('advisory_council', 'advisory_council_shortcode');
 

// *********** FOSTER A PET LIST VIEW ***********
function foster_a_pet_shortcode() {
    ob_start(); // Start output buffering
    
    // Custom query to get your posts
    $args = array(
        'post_type' => 'pets', // Change to your custom post type if needed
        'order' => 'DESC',
        'orderby' => 'date',
		'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'status',
            'compare' => 'NOT EXISTS'
        ),
        array(
            'key' => 'status',
            'value' => '"Foster Needed"',
            'compare' => 'LIKE'
        )
    	)
    );
    
    $custom_query = new WP_Query($args);
    
    // Check if there are posts
    if ($custom_query->have_posts()) :
        echo '<div class="custom-acf-posts-grid">';
        
        while ($custom_query->have_posts()) : $custom_query->the_post();
			// Get your ACF fields - replace field_name with your actual field names
			$looks_like = get_field('looks_like');
			$sex = get_field('sex');
			$age = get_field('age');
			$weight = get_field('weight');
			$youtube_video = get_field('youtube_video');

			// Get the status checkbox field (returns an array of selected values)
			$status = get_field('status');

			// Check if "Foster Needed" is in the status array
			$needs_foster = false;
			if ($status && is_array($status)) {
				$needs_foster = in_array('Foster Needed', $status);
			}
			$pending_adoption = false;
			if ($status && is_array($status)) {
				$pending_adoption = in_array('Adoption Pending', $status);
			}

			// Get featured image if it exists
			$image = '';
			if (has_post_thumbnail()) {
				$image = get_the_post_thumbnail_url(get_the_ID(), 'full');
			}
            
            // Start building the post container
            echo '<div class="custom-post-item">';
            
            // Add featured image if it exists
            if ($image) {
                echo '<div class="custom-post-image">';
                echo '<a href="' . esc_url(get_permalink()) . '"><img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '"></a>';
                echo '</div>';
            }
            // Post content
            echo '<div class="custom-post-content">';
            echo '<div class="custom-post-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></div>';

            // Display your ACF fields. Escape every value; route sex through the
            // shared display helper so a checkbox array never prints as "Array".
            if ($looks_like) {
                echo esc_html($looks_like) . '<br/>';
            }
            $sex_display = function_exists('pom_acf_sex_display') ? pom_acf_sex_display(get_the_ID()) : '';
            if ($sex_display !== '') {
                echo esc_html($sex_display) . ', ';
            }
			if ($age) {
                echo esc_html($age) . ' yrs, ';
            }
			if ($weight) {
                echo esc_html($weight) . ' lbs';
            }
			if ($needs_foster) {
        		echo '</br>Foster Needed!';
    		}
			if ($pending_adoption) {
        		echo '</br>Adoption Pending';
    		}
            
            // Post excerpt
            
            
            echo '</div>'; // End .custom-post-content
            echo '</div>'; // End .custom-post-item
        endwhile;        
        echo '</div>'; // End .custom-acf-posts-grid
        
        // Reset post data
        wp_reset_postdata();
        
    else :
        echo '<p>No posts found.</p>';
    endif;
    
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('foster_a_pet', 'foster_a_pet_shortcode');



// ************* ADOPTED PETS LIST VIEW ***********************
function adopted_pets_shortcode() {
    // Do NOT pass meta_key => date_adopted into the query: that inner-joins on
    // the date field and silently hides every dog whose date was never filled
    // in, which left /adopted/ rendering empty. Query by status only, sort by
    // the date in PHP (missing dates last), and cap the page so it does not
    // grow unbounded as adoptions accumulate.
    $q = new WP_Query(array(
        'post_type'      => 'pets',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => array(array('key' => 'status', 'value' => 'Adopted', 'compare' => 'LIKE')),
    ));
    $ids = $q->posts;
    usort($ids, function ($a, $b) {
        // Raw meta is stored Ymd, which string-sorts chronologically.
        return strcmp((string) get_post_meta($b, 'date_adopted', true), (string) get_post_meta($a, 'date_adopted', true));
    });
    $ids = array_slice($ids, 0, 48);
    if (empty($ids)) {
        return '<p>No dogs to show right now. Please call (831) 718-9122.</p>';
    }
    $out = '<div class="dogs-grid">';
    foreach ($ids as $id) { $out .= pom_render_dog_card($id); }
    $out .= '</div>';
    $out .= pomdr_adopted_wall_html();
    return $out;
}
add_shortcode('adopted_pets', 'adopted_pets_shortcode');

/**
 * The adopted-names wall: every dog POMDR has ever adopted out (synced from
 * the live site, data/dogsync/adopted_names.json in the theme). Rendered as a
 * compact flowing wall under the recent-adoption cards.
 */
function pomdr_adopted_wall_html() {
    $file = get_stylesheet_directory() . '/data/dogsync/adopted_names.json';
    if (!is_readable($file)) { return ''; }
    $names = json_decode((string) file_get_contents($file), true);
    if (!is_array($names) || !$names) { return ''; }
    $count = count($names);
    $out  = '<div class="adopted-wall">';
    $out .= '<h2 class="section-title" style="text-align:center;margin-top:72px">' . esc_html(number_format($count)) . ' dogs, <em>all adopted.</em></h2>';
    $out .= '<p style="text-align:center;color:var(--ink-2);font-size: 22px;margin:0 0 28px">What do all these dogs have in common? They are all adopted!</p>';
    $out .= '<p class="adopted-wall-names">' . esc_html(implode(' · ', array_map('trim', $names))) . '</p>';
    return $out . '</div>';
}


// *********** HOSPICE LIST VIEW ***********
/* Render a status-filtered grid of dogs in the new card design. */
function pomdr_dogs_by_status($status, $args = array()) {
    $defaults = array(
        'post_type'      => 'pets',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => array(array('key' => 'status', 'value' => $status, 'compare' => 'LIKE')),
    );
    $q = new WP_Query(array_merge($defaults, $args));
    ob_start();
    if ($q->have_posts()) {
        echo '<div class="dogs-grid">';
        while ($q->have_posts()) { $q->the_post(); echo pom_render_dog_card(get_the_ID()); }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p>No dogs to show right now. Please call (831) 718-9122.</p>';
    }
    return ob_get_clean();
}

/* Foster Needs page: every dog with Foster Needed status, newest first. */
function foster_needed_dogs_shortcode() { return pomdr_dogs_by_status('Foster Needed', array('orderby' => 'date', 'order' => 'DESC')); }
add_shortcode('foster_needed_dogs', 'foster_needed_dogs_shortcode');

function hospice_care_shortcode() { return pomdr_dogs_by_status('Hospice'); }
add_shortcode('hospice_care', 'hospice_care_shortcode');


// *********** Courtesy Listings LIST VIEW ***********
function courtesy_listings_shortcode() { return pomdr_dogs_by_status('Courtesy Listing'); }
add_shortcode('courtesy_listings', 'courtesy_listings_shortcode');


// ********************* RECENTLY ADOPTED PET SIDEBAR *********************
function adopted_pet_sidebar_shortcode() {
    ob_start(); // Start output buffering
    
    // Custom query to get your posts
    $args = array(
    'post_type' => 'pets',
    'posts_per_page' => 5,
    'meta_key' => 'date_adopted', // Use meta_key for ACF field ordering
    'orderby' => 'meta_value', // Order by the meta field value
    'order' => 'DESC',
    'meta_query' => array( // Wrap the meta query in meta_query array
        array(
            'key' => 'status',
            'value' => 'Adoptable', // Remove quotes unless they're actually part of the value
            'compare' => 'LIKE'
        )
    )
);
    
    $custom_query = new WP_Query($args);
    
    // Check if there are posts
    if ($custom_query->have_posts()) :
        //echo '<div class="custom-acf-posts-grid">';
        
        while ($custom_query->have_posts()) : $custom_query->the_post();
            // Get featured image if it exists
            $image = '';
            if (has_post_thumbnail()) {
                $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            }
            
            // Add featured image if it exists
            if ($image) {
                echo '<div class="custom-post-image">';
                echo '<a href="' . get_permalink() . '"><img src="' . $image . '" alt="' . get_the_title() . '"></a>';
                echo '</div>';
            }
            // Post content
            echo '<div class="custom-post-content">';
            echo '<h2 class="custom-post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
            echo '</div>'; // End .custom-post-content 
            //echo '</div>'; // End .custom-post-item
        endwhile;
        
        //echo '</div>'; // End .custom-acf-posts-grid
        
        // Reset post data
        wp_reset_postdata();
        
    else :
        echo '<p>Oops, error.</p>';
    endif;
    
    return ob_get_clean(); // Return the buffered content
}
//add_shortcode('adopted_pet_sidebar', 'adopted_pet_sidebar_shortcode');
function adopted_pets_recent_shortcode($atts) {
    ob_start();

    $atts = shortcode_atts(
        array(
            'status' => 'Adopted',
        ),
        $atts,
        'adopted_pets_recent'
    );

    $filter_status = trim($atts['status']);
   // echo '<pre>STATUS ATTR = ' . esc_html($filter_status) . '</pre>';

    $args = array(
        'post_type'      => 'pets',
        'posts_per_page' => 6,
        'order'          => 'DESC',
        'orderby'        => 'date',
    );

    if ($filter_status === 'Adopted') {
        $args['meta_key'] = 'date_adopted';
        $args['orderby']  = 'meta_value';
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        echo '<div class="custom-acf-posts-grid">';

        while ($query->have_posts()) : $query->the_post();

            $status = get_field('status');
            if (!is_array($status)) {
                continue;
            }

            if ($filter_status === 'Adoptable') {

                if (!in_array('Adoptable', $status)) {
                    continue;
                }

                if (in_array('Adopted', $status)) {
                    continue;
                }
            }

            if ($filter_status === 'Adopted') {

                if (!in_array('Adopted', $status)) {
                    continue;
                }

                $date_adopted = trim((string) get_field('date_adopted'));
                if ($date_adopted === '') {
                    continue;
                }

                $adopted_date = DateTime::createFromFormat(
                    'm/d/Y',
                    $date_adopted,
                    wp_timezone()
                );

                if (!$adopted_date) {
                    continue;
                }

                $today = new DateTime('today', wp_timezone());
                $days_diff = (int) $today->diff($adopted_date)->days;

                if ($adopted_date > $today || $days_diff >= 14) {
                    continue;
                }
            }

            $image = has_post_thumbnail()
                ? get_the_post_thumbnail_url(get_the_ID(), 'full')
                : '';

            echo '<div class="custom-post-item">';

            if ($image) {
                echo '<div class="custom-post-image">';
                echo '<img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '">';
                echo '</div>';
            }

            echo '<div class="custom-post-content">';
            echo '<h2 class="custom-post-title">' . esc_html(get_the_title()) . '</h2>';

            if ($filter_status === 'Adopted') {
                // echo '<div class="recently-adopted">Recently Adopted</div>';
            }

            echo '</div>';
            echo '</div>';

        endwhile;

        echo '</div>';
        wp_reset_postdata();
    endif;

    return ob_get_clean();
}

add_shortcode('adopted_pets_recent', 'adopted_pets_recent_shortcode');










// *************************** RANDOM PET SIDEBAR ****************************
function random_pet_shortcode() {
    $q = new WP_Query(array('post_type' => 'pets', 'posts_per_page' => 1, 'orderby' => 'rand', 'meta_query' => array(array('key' => 'status', 'value' => 'Adoptable', 'compare' => 'LIKE'))));
    ob_start();
    if ($q->have_posts()) { echo '<div class="dogs-grid">'; while ($q->have_posts()) { $q->the_post(); echo pom_render_dog_card(get_the_ID()); } echo '</div>'; wp_reset_postdata(); }
    return ob_get_clean();
}
add_shortcode('random_pet', 'random_pet_shortcode');



// *************************** HOME PAGE PETS ****************************
/**
 * Map a pet's status (ACF checkbox, Title Case array, possibly multi-value) to
 * a single badge. Class names match the deployed CSS in pomdr-design.css.
 * Priority is most-urgent-first so a multi-status dog shows the right badge.
 * Returns [class, label] or ['',''] when no recognized status.
 */
/**
 * Dog highlights: the 3 to 5 quick facts an adopter needs (leadership spec,
 * 2026-07-09). A code-registered ACF field so it is version-controlled; staff
 * type one bullet per line on the dog's edit screen. Display is hard-capped
 * at 5 on the dog page.
 */
add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) { return; }
    acf_add_local_field_group(array(
        'key'    => 'group_pomdr_highlights',
        'title'  => 'Dog Highlights (shown at the top of the dog\'s page)',
        'fields' => array(array(
            'key'          => 'field_pomdr_highlights',
            'label'        => 'Highlights',
            'name'         => 'highlights',
            'type'         => 'textarea',
            'instructions' => 'The 3 to 5 things an adopter should know, one per line (for example: Loves slow morning walks). The page shows at most 5.',
            'rows'         => 5,
            'new_lines'    => '',
        )),
        'location'   => array(array(array('param' => 'post_type', 'operator' => '==', 'value' => 'pets'))),
        'menu_order' => 1,
        'position'   => 'normal',
    ));
});

/**
 * The capped bullet list for the dog page. Returns '' when staff have not
 * filled the field yet.
 */
function pom_pet_highlights_html($post_id) {
    $raw = function_exists('get_field') ? get_field('highlights', $post_id) : '';
    if (!$raw) { return ''; }
    $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $raw)));
    if (empty($lines)) { return ''; }
    $lines = array_slice(array_values($lines), 0, 5); // hard cap per the spec
    $out = '<ul class="pdp-bullets">';
    foreach ($lines as $line) { $out .= '<li>' . esc_html($line) . '</li>'; }
    return $out . '</ul>';
}

function pom_pet_badge($post_id) {
    $status = get_field('status', $post_id);
    $status = is_array($status)
        ? $status
        : array_filter(array_map('trim', explode(',', (string) $status)));

    $foster_start = get_field('foster_start_date', $post_id);
    $foster_end   = get_field('foster_end_date', $post_id);
    $has_dates    = (!empty($foster_start) || !empty($foster_end));

    if (in_array('Adoption Pending', $status, true)) return ['adoption_pending', 'Adoption Pending'];
    if (in_array('Foster Needed', $status, true))    return [$has_dates ? 'foster_needed_dated' : 'foster_needed', 'Foster Needed'];
    if (in_array('Hospice', $status, true))          return ['hospice', 'Hospice'];
    if (in_array('Sponsor Needed', $status, true))   return ['sponsor_needed', 'Sponsor Needed'];
    if (in_array('Courtesy Listing', $status, true)) return ['courtesy_listing', 'Courtesy Listing'];
    if (in_array('Adopted', $status, true))          return ['recently_adopted', 'Adopted'];
    // Plain Adoptable gets NO badge (leadership spec 2026-07-09): available is
    // the default state, so the bubble carried no information. Badges are for
    // special situations only.
    return ['', ''];
}

/**
 * Shared dog-card renderer for the redesign. Outputs the .dog-card markup used
 * on the homepage sample and the Adopt listing, bound to real `pets` ACF data
 * so staff edit dogs in wp-admin and the new design renders automatically.
 *
 * Voice rules (CLAUDE.md s2): age shows as "~N yrs" with a tilde, never "est".
 * No invented data: the only derived tag is "Senior" (age >= 10).
 */
function pom_render_dog_card($post_id, $card_args = array()) {
    $name      = get_the_title($post_id);
    $permalink = get_permalink($post_id);
    // 'eager' => true loads the photo immediately (used for the homepage row,
    // so visitors never see the empty cream placeholder while lazy images decode).
    $img_loading = !empty($card_args['eager']) ? 'eager' : 'lazy';

    list($badge_class, $badge_label) = pom_pet_badge($post_id);

    // Meta line: ~age yrs · Sex · weight lb · breed (looks_like)
    $age        = get_field('age', $post_id);
    $sex        = function_exists('pom_acf_sex_display') ? pom_acf_sex_display($post_id) : get_field('sex', $post_id);
    $weight     = get_field('weight', $post_id);
    $looks_like = get_field('looks_like', $post_id);

    $parts = [];
    if (is_numeric($age))    $parts[] = '~' . intval($age) . ' yrs';
    if (!empty($sex))        $parts[] = $sex;
    if ($weight !== '' && $weight !== null) $parts[] = $weight . ' lb';
    if (!empty($looks_like)) $parts[] = $looks_like;
    $meta_line = implode(' · ', $parts);

    $tags = [];
    if (is_numeric($age) && intval($age) >= 10) $tags[] = 'Senior';
    if (get_post_meta($post_id, 'aged_to_perfection', true)) $tags[] = 'Aged to Perfection';

    $thumb_id = get_post_thumbnail_id($post_id);

    // Filter/sort data attributes for the Adopt page JS. The grid stays
    // CPT-driven; these only describe each rendered card so vanilla JS can
    // search, filter, and sort the existing DOM nodes.
    $status_raw = get_field('status', $post_id);
    $status_raw = is_array($status_raw)
        ? $status_raw
        : array_filter(array_map('trim', explode(',', (string) $status_raw)));
    // Lowercase each status, kebab-case multi-word values, space-join them.
    $status_slugs = array_map(function ($s) {
        return str_replace(' ', '-', strtolower(trim($s)));
    }, $status_raw);
    $data_status = implode(' ', array_filter($status_slugs));
    $data_age    = is_numeric($age) ? intval($age) : '';
    $data_weight = is_numeric($weight) ? floatval($weight) : '';

    ob_start();
    ?>
    <a href="<?php echo esc_url($permalink); ?>" class="dog-card-link" style="display:block;color:inherit;text-decoration:none"
       data-name="<?php echo esc_attr($name); ?>"
       data-breed="<?php echo esc_attr($looks_like); ?>"
       data-status="<?php echo esc_attr($data_status); ?>"
       data-age="<?php echo esc_attr($data_age); ?>"
       data-weight="<?php echo esc_attr($data_weight); ?>">
      <div class="dog-card"><div class="photo-wrap">
        <div class="dog-photo"><?php
            if ($thumb_id) {
                echo wp_get_attachment_image($thumb_id, 'medium_large', false, [
                    'alt'     => $name,
                    'loading' => $img_loading,
                    // center 30% keeps a dog's face (usually upper-middle) framed
                    // instead of pinning to the very top and cropping the subject.
                    'style'   => 'width:100%;height:100%;object-fit:cover;object-position:center 30%;display:block',
                ]);
            }
        ?></div>
        <?php if ($badge_label) : ?>
          <div class="badge <?php echo esc_attr($badge_class); ?>"><?php echo esc_html($badge_label); ?></div>
        <?php endif; ?>
        <button class="heart" type="button" data-name="<?php echo esc_attr($name); ?>" aria-label="Save <?php echo esc_attr($name); ?> to favorites"><svg viewBox="0 0 24 24"><path d="M12 21s-8-5.5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 11-8 11z"/></svg></button>
      </div><div class="info">
        <div class="name"><?php echo esc_html($name); ?></div>
        <?php if ($meta_line) : ?><div class="meta-line"><?php echo esc_html($meta_line); ?></div><?php endif; ?>
        <?php if (!empty($tags)) : ?>
          <div class="tags"><?php foreach ($tags as $t) echo '<span class="tag">' . esc_html($t) . '</span>'; ?></div>
        <?php endif; ?>
        <div class="cta-row"><span>View profile</span><span class="arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span></div>
      </div></div>
    </a>
    <?php
    return ob_get_clean();
}

/**
 * Homepage dog sample: featured adoptable dogs in the new card design.
 * Wrapped in .pomdr-home so the scoped redesign CSS applies wherever the
 * shortcode is placed.
 */
function pet_home_shortcode() {
    // This row must NEVER silently vanish. The old query inner-joined on the
    // optional 'feature' meta (meta_key => 'feature'), so any dog without that
    // flag was invisible and an unmaintained flag emptied the whole section
    // (the same trap that blanked /adopted/ and dropped team members). Now:
    // query adoptable dogs with no meta join, prefer featured ones in PHP,
    // fall back to newest adoptable, and if there are truly no adoptable dogs,
    // still render a friendly link instead of returning nothing.
    $q = new WP_Query([
        'post_type'      => 'pets',
        'posts_per_page' => 30,
        'fields'         => 'ids',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [
            'relation' => 'AND',
            ['key' => 'status', 'value' => 'Adoptable', 'compare' => 'LIKE'],
            ['key' => 'status', 'value' => 'Adopted',   'compare' => 'NOT LIKE'],
        ],
    ]);
    $ids = $q->posts;

    // Featured dogs first (feature = Yes), newest first within each group.
    usort($ids, function ($a, $b) {
        $fa = ('Yes' === get_post_meta($a, 'feature', true)) ? 0 : 1;
        $fb = ('Yes' === get_post_meta($b, 'feature', true)) ? 0 : 1;
        return $fa <=> $fb; // usort is stable in PHP 8; date order holds within groups
    });
    $ids = array_slice($ids, 0, 6);

    if (empty($ids)) {
        return '<div class="pomdr-home"><div class="dogs-more"><a href="' . esc_url(home_url('/adopt/')) . '" class="btn btn-primary">See all adoptable dogs</a></div></div>';
    }

    $out = '<div class="pomdr-home"><div class="dogs-grid">';
    foreach ($ids as $id) {
        // Eager images: this row sits near the fold and lazy placeholders read
        // as "the cards are not appearing".
        $out .= pom_render_dog_card($id, array('eager' => true));
    }
    $out .= '</div>';
    $out .= '<div class="dogs-more"><a href="' . esc_url(home_url('/adopt/')) . '" class="btn btn-primary">See all adoptable dogs <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a></div>';
    $out .= '</div>';
    return $out;
}

add_shortcode('pet_home', 'pet_home_shortcode');


/**
 * Adopt page listing: every dog not fully adopted, in the new card design,
 * alphabetical so none are dropped. Cards are styled by the site-wide
 * component in pomdr-design.css (works off the homepage). Status badges
 * communicate adoptable / foster-needed / pending / etc.
 */
function adopt_a_pet_plp_shortcode() {
    $query = new WP_Query(array(
        'post_type'      => 'pets',
        'posts_per_page' => -1,
        'meta_query'     => array(array(
            'key'     => 'status',
            'value'   => 'Adopted',
            'compare' => 'NOT LIKE',
        )),
        'orderby'        => 'title',
        'order'          => 'ASC',
    ));

    ob_start();

    if ($query->have_posts()) :
        echo '<div class="dogs-grid">';
        while ($query->have_posts()) : $query->the_post();
            echo pom_render_dog_card(get_the_ID());
        endwhile;
        echo '</div>';
        wp_reset_postdata();
    else :
        echo '<p>No dogs are listed for adoption right now. Please check back soon, or call us at (831) 718-9122.</p>';
    endif;

    return ob_get_clean();
}

add_shortcode('adopt_a_pet_plp', 'adopt_a_pet_plp_shortcode');


// ********************* EVENTS *********************

/**
 * Query upcoming events (today or later), soonest first.
 *
 * @param array $types Optional list of event_type values to include, e.g.
 *                     array('Adoption Event') or array('Special Event','Perpetual Event').
 *                     Empty means every type. This is what powers the two
 *                     co-equal sections on the What's Happening page.
 * @return array List of event display arrays.
 */
function pomdr_collect_events($types = array()) {
    $meta = array(
        array(
            'key'     => 'event_start',
            'value'   => current_time('Y-m-d') . ' 00:00:00',
            'compare' => '>=',
            'type'    => 'DATETIME',
        ),
    );
    if (!empty($types)) {
        $meta['relation'] = 'AND';
        $meta[] = array(
            'key'     => 'event_type',
            'value'   => (array) $types,
            'compare' => 'IN',
        );
    }
    $q = new WP_Query(array(
        'post_type'      => 'events',
        'posts_per_page' => 50,
        'meta_key'       => 'event_start',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => $meta,
    ));

    $events = array();
    if ($q->have_posts()) {
        while ($q->have_posts()) { $q->the_post();
            $id      = get_the_ID();
            $raw     = (string) get_post_meta($id, 'event_start', true); // Y-m-d H:i:s
            $ts      = $raw ? strtotime($raw) : false;
            $end_raw = trim((string) get_field('event_end', $id));
            $thumb   = get_post_thumbnail_id($id);
            if (!$thumb) {
                $legacy = get_field('event_image', $id);
                if (is_array($legacy) && !empty($legacy['ID'])) { $thumb = (int) $legacy['ID']; }
                elseif (is_numeric($legacy))                    { $thumb = (int) $legacy; }
            }
            $events[] = array(
                'id'      => $id,
                'title'   => get_the_title($id),
                'type'    => trim((string) get_field('event_type', $id)),
                'ts'      => $ts,
                'time'    => $ts ? date('g:i a', $ts) . ($end_raw !== '' ? ' to ' . $end_raw : '') : '',
                'details' => trim((string) get_field('event_details', $id)),
                'thumb'   => $thumb,
            );
        }
        wp_reset_postdata();
    }
    return $events;
}

/**
 * Render a set of events (an at-a-glance strip plus the card grid) to HTML.
 * Shared by the [events] shortcode and the What's Happening page template.
 *
 * @param array  $events      From pomdr_collect_events().
 * @param string $hlevel      Heading level for card titles (h2/h3/h4).
 * @param bool   $show_glance Whether to print the condensed at-a-glance list.
 * @param string $empty_msg   Message when there are no events in this set.
 */
function pomdr_render_events($events, $hlevel = 'h3', $show_glance = true, $empty_msg = '') {
    $hlevel = in_array($hlevel, array('h2', 'h3', 'h4'), true) ? $hlevel : 'h3';
    ob_start();
    if ($events) {
        // Quick view: every event at a glance (a condensed list, not a hover
        // preview, so it works for keyboard and touch too).
        if ($show_glance) {
            echo '<div class="events-glance" aria-label="Upcoming events at a glance">';
            foreach ($events as $ev) {
                echo '<a class="glance-row" href="#event-' . (int) $ev['id'] . '">';
                echo '<span class="glance-date">' . esc_html($ev['ts'] ? date('M j', $ev['ts']) : '') . '</span>';
                echo '<span class="glance-title">' . esc_html($ev['title']) . '</span>';
                if ($ev['time']) { echo '<span class="glance-time">' . esc_html($ev['time']) . '</span>'; }
                echo '</a>';
            }
            echo '</div>';
        }

        // The cards: siblings of the dog cards (same radius, hover, hairline),
        // with the date square from the homepage events band.
        echo '<div class="events-grid">';
        foreach ($events as $ev) {
            echo '<article class="event-card" id="event-' . (int) $ev['id'] . '">';
            if ($ev['thumb']) echo '<div class="event-photo">' . wp_get_attachment_image($ev['thumb'], 'medium_large', false, array('alt' => $ev['title'], 'loading' => 'lazy')) . '</div>';
            echo '<div class="event-body">';
            echo '<div class="event-when">';
            echo '<div class="event-date-sq"><span class="month">' . esc_html($ev['ts'] ? date('M', $ev['ts']) : '') . '</span><span class="day">' . esc_html($ev['ts'] ? date('d', $ev['ts']) : '') . '</span></div>';
            echo '<div class="event-when-text">';
            if ($ev['type'] !== '') { echo '<div class="eyebrow event-type">' . esc_html($ev['type']) . '</div>'; }
            if ($ev['time'])        { echo '<div class="event-meta">' . esc_html($ev['time']) . '</div>'; }
            echo '</div></div>';
            echo '<' . $hlevel . ' class="event-title">' . esc_html($ev['title']) . '</' . $hlevel . '>';
            if ($ev['details'] !== '') echo '<p class="event-desc">' . esc_html(wp_trim_words($ev['details'], 36)) . '</p>';
            echo '</div></article>';
        }
        echo '</div>';
    } else {
        echo '<p>' . esc_html($empty_msg !== '' ? $empty_msg : 'No upcoming events right now. Call (831) 718-9122 or check our Facebook for dates.') . '</p>';
    }
    return ob_get_clean();
}

/**
 * [events] shortcode: all upcoming types, glance + grid. Kept for any Divi page.
 * Optional attribute: [events types="Adoption Event"] to limit the set.
 */
function events_shortcode($atts = array()) {
    $atts  = shortcode_atts(array('hlevel' => 'h3', 'types' => ''), $atts, 'events');
    $types = array_filter(array_map('trim', explode(',', (string) $atts['types'])));
    return pomdr_render_events(pomdr_collect_events($types), $atts['hlevel'], true);
}
add_shortcode('events', 'events_shortcode');


// **************************** BOARD TEAM MEMBERS **************************
/* ===== New-design renderers for daily-content CPTs (team, events) ===== */
function pomdr_initials($name) {
    $parts = preg_split('/\s+/', trim((string) $name));
    $ini = '';
    foreach ($parts as $p) { if ($p !== '' && ctype_alpha($p[0])) $ini .= strtoupper($p[0]); if (strlen($ini) >= 2) break; }
    return $ini !== '' ? $ini : '?';
}

function pom_render_person_card($id) {
    $name  = get_the_title($id);
    $role  = trim((string) get_field('title', $id));
    $thumb = get_post_thumbnail_id($id);
    ob_start(); ?>
    <div class="person-card">
      <div class="person-photo<?php echo $thumb ? '' : ' person-initials'; ?>">
        <?php echo $thumb ? wp_get_attachment_image($thumb, 'medium', false, array('alt' => $name, 'loading' => 'lazy')) : esc_html(pomdr_initials($name)); ?>
      </div>
      <h4 class="person-name"><?php echo esc_html($name); ?></h4>
      <?php if ($role !== '') : ?><div class="role"><?php echo esc_html($role); ?></div><?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

function pomdr_team_grid($group) {
    // Query by group only. Passing meta_key => sort into the query inner-joins
    // on the sort field and silently DROPS any team member whose sort was never
    // filled in; it also string-sorts (1, 10, 2). Sort numerically in PHP
    // instead, with unsorted members last (alphabetical among themselves).
    $q = new WP_Query(array(
        'post_type'      => 'team',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => array(array('key' => 'group', 'value' => $group, 'compare' => 'LIKE')),
    ));
    $ids = $q->posts;
    usort($ids, function ($a, $b) {
        $sa = get_post_meta($a, 'sort', true);
        $sb = get_post_meta($b, 'sort', true);
        $na = is_numeric($sa) ? (float) $sa : PHP_FLOAT_MAX;
        $nb = is_numeric($sb) ? (float) $sb : PHP_FLOAT_MAX;
        if ($na === $nb) { return strcasecmp(get_the_title($a), get_the_title($b)); }
        return $na <=> $nb;
    });
    if (empty($ids)) { return ''; }
    $out = '<div class="team-grid">';
    foreach ($ids as $id) { $out .= pom_render_person_card($id); }
    return $out . '</div>';
}

function pomdr_event_date($v) {
    if (!$v) return '';
    $v = (string) $v;
    if (preg_match('/^\d{8}$/', $v)) { $d = DateTime::createFromFormat('Ymd', $v); return $d ? $d->format('M j, Y') : $v; }
    $t = strtotime($v);
    return $t ? date('M j, Y', $t) : $v;
}

function team_board_shortcode() { return pomdr_team_grid('Board of Directors'); }
add_shortcode('team_board', 'team_board_shortcode');


// *************************** OFFICE TEAM MEMBERS ****************************
function team_office_shortcode() { return pomdr_team_grid('Office Staff'); }
add_shortcode('team_office', 'team_office_shortcode');




function display_acf_gallery_shortcode($atts) {
    ob_start(); // Start output buffering

    // Fetch gallery field from current post
    $gallery = get_field('photo_gallery'); // Replace 'photo_gallery' with your actual field name
    
    if ($gallery) {
        echo '<div class="gallery">';
        foreach ($gallery as $image) {
            if (is_array($image) && isset($image['ID'])) {
                // Wrap image in an anchor tag for FancyBox
                echo '<a href="' . esc_url($image['url']) . '" data-fancybox="gallery" data-caption="' . esc_attr($image['caption']) . '">';
                // Display the image using wp_get_attachment_image()
                echo wp_get_attachment_image($image['ID'], 'medium', false, [
                    'class' => 'gallery__image',
                ]);
                echo '</a>';
            }
        }
        echo '</div>';
    }

    return ob_get_clean(); // Return buffered content
}
add_shortcode('acf_gallery', 'display_acf_gallery_shortcode');

// Flush rewrite rules only when the theme is activated, not on every request.
// The /pets/{id}/ rewrite rule above is registered on every 'init'; the rule
// cache itself only needs rebuilding once. Calling flush_rewrite_rules() on
// every 'init' is a DB write on every single page load (a real cost at this
// traffic level). After deploying this change to an already-active theme,
// flush the cache once by hand: Settings > Permalinks > Save, or the WP-CLI
// command `wp rewrite flush`.
add_action('after_switch_theme', function() {
    flush_rewrite_rules();
});




if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'pom_acf_get_post_id_from_attr' ) ) {
    function pom_acf_get_post_id_from_attr( $attrs ) {
        if ( isset( $attrs['post_id'] ) && intval( $attrs['post_id'] ) > 0 ) {
            return intval( $attrs['post_id'] );
        }
        global $post;
        return isset( $post->ID ) ? intval( $post->ID ) : 0;
    }
}

if ( ! function_exists( 'pom_safe_text' ) ) {
    function pom_safe_text( $text ) {
        return esc_html( (string) $text );
    }
}
if ( ! function_exists( 'pom_safe_html' ) ) {
    function pom_safe_html( $html ) {
        return wp_kses_post( $html );
    }
}


if ( ! function_exists( 'pom_acf_normalize_val' ) ) {
    function pom_acf_normalize_val( $val ) {
        if ( is_array( $val ) ) return $val;
        if ( is_bool( $val ) ) return $val ? '1' : '0';
        if ( $val === null ) return '';
        return (string) $val;
    }
}


if ( ! function_exists( 'pom_acf_eval_condition' ) ) {
    function pom_acf_eval_condition( $field_name, $operator = '=', $compare_value = '', $post_id = 0 ) {
        if ( ! function_exists( 'get_field' ) ) return false;

        $post_id = intval( $post_id );
        $raw = get_field( $field_name, $post_id );


        $left = $raw;

        if ( $operator === 'empty' ) {
            return ( $left === null || $left === false || $left === '' || ( is_array( $left ) && empty( $left ) ) );
        }
        if ( $operator === 'not_empty' ) {
            return ! ( $left === null || $left === false || $left === '' || ( is_array( $left ) && empty( $left ) ) );
        }

        if ( is_array( $left ) ) {
            
            if ( $operator === 'contains' ) {
                foreach ( $left as $item ) {
                    if ( (string)$item === (string)$compare_value ) return true;
                }
                return false;
            }
            
            if ( $operator === '=' ) {
                foreach ( $left as $item ) {
                    if ( (string)$item === (string)$compare_value ) return true;
                }
                return false;
            }
            if ( $operator === '!=' ) {
                foreach ( $left as $item ) {
                    if ( (string)$item === (string)$compare_value ) return false;
                }
                return true;
            }
            
            return false;
        }

        
        $left_norm = pom_acf_normalize_val( $left );
        $right_norm = pom_acf_normalize_val( $compare_value );

        
        if ( in_array( $operator, array( '>', '<' ), true ) ) {
            if ( is_numeric( $left_norm ) && is_numeric( $right_norm ) ) {
                if ( $operator === '>' ) return floatval( $left_norm ) > floatval( $right_norm );
                if ( $operator === '<' ) return floatval( $left_norm ) < floatval( $right_norm );
            }
            return false;
        }

        switch ( $operator ) {
            case 'contains':
                return ( stripos( (string)$left_norm, (string)$right_norm ) !== false );
            case '=':
                return ( (string)$left_norm === (string)$right_norm );
            case '!=':
                return ( (string)$left_norm !== (string)$right_norm );
            default:
                return false;
        }
    }
}

add_shortcode( 'acf', function( $atts ) {
    $a = shortcode_atts( array(
        'field'   => '',
        'post_id' => 0,
        'format'  => 'text', 
    ), $atts, 'acf' );

    if ( empty( $a['field'] ) ) return '';

    if ( ! function_exists( 'get_field' ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );

    $val = get_field( $a['field'], $post_id );

    if ( $val === null || $val === false || $val === '' ) return '';

    if ( $a['format'] === 'html' ) {
       
        if ( is_array( $val ) ) {
            return pom_safe_html( wp_json_encode( $val ) );
        }
        return pom_safe_html( $val );
    }

    
    if ( is_array( $val ) ) {
        return pom_safe_text( implode( ', ', $val ) );
    }

    return pom_safe_text( $val );
});

add_shortcode( 'acf_image', function( $atts ) {
    $a = shortcode_atts( array(
        'field'   => '',
        'post_id' => 0,
        'size'    => 'full',
        'link'    => 'false',
        'class'   => '',
        'alt'     => '',
    ), $atts, 'acf_image' );

    if ( empty( $a['field'] ) ) return '';
    if ( ! function_exists( 'get_field' ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );
    $img = get_field( $a['field'], $post_id );

    if ( empty( $img ) ) return '';

    
    $id = 0;
    if ( is_array( $img ) && isset( $img['ID'] ) ) {
        $id = intval( $img['ID'] );
    } elseif ( is_numeric( $img ) ) {
        $id = intval( $img );
    } elseif ( is_string( $img ) && preg_match( '/^https?:\\/\\//', $img ) ) {
        $src = esc_url( $img );
        $alt = esc_attr( $a['alt'] );
        $cls = $a['class'] ? ' class="' . esc_attr( $a['class'] ) . '"' : '';
        $img_tag = '<img src="' . $src . '" alt="' . $alt . '"' . $cls . ' />';
        if ( $a['link'] === 'true' ) {
            return '<a href="' . $src . '">' . $img_tag . '</a>';
        }
        return $img_tag;
    } else {
        return '';
    }

    if ( function_exists( 'wp_get_attachment_image' ) && $id ) {
        $img_tag = wp_get_attachment_image( $id, $a['size'], false, array( 'class' => $a['class'], 'alt' => $a['alt'] ) );
    } else {
        $src = wp_get_attachment_url( $id );
        if ( ! $src ) return '';
        $img_tag = '<img src="' . esc_url( $src ) . '" alt="' . esc_attr( $a['alt'] ) . '" class="' . esc_attr( $a['class'] ) . '" />';
    }

    if ( $a['link'] === 'true' && $id ) {
        $link = wp_get_attachment_url( $id );
        return '<a href="' . esc_url( $link ) . '">' . $img_tag . '</a>';
    }

    return $img_tag;
});

/* Note: [acf_if] was registered twice (here and in the branching version
   further down). WordPress silently uses the LAST registration, so this first,
   simpler handler never ran. Removed 2026-07-07; the branching handler below
   now also accepts operator= as an alias for op= so both syntaxes work. */


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'pom_acf_get_subfield_value_for_placeholder' ) ) {
    
    function pom_acf_get_subfield_value_for_placeholder( $placeholder ) {
        $val = get_sub_field( $placeholder );
        if ( is_array( $val ) ) {
            
            if ( isset( $val['url'] ) ) return esc_url( $val['url'] );
            if ( isset( $val['ID'] ) ) {
                $url = wp_get_attachment_url( intval( $val['ID'] ) );
                return $url ? esc_url( $url ) : '';
            }
           
            return esc_html( wp_json_encode( $val ) );
        } else {
            return pom_safe_html( $val );
        }
    }
}

add_shortcode( 'acf_repeater', function( $atts ) {
    $a = shortcode_atts( array(
        'field'    => '',
        'subfield' => '',
        'post_id'  => 0,
        'before'   => '',
        'after'    => '',
        'sep'      => '',
        'limit'    => 0,
        'offset'   => 0,
    ), $atts, 'acf_repeater' );

    if ( empty( $a['field'] ) ) return '';
    if ( ! function_exists( 'have_rows' ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );

    if ( ! have_rows( $a['field'], $post_id ) ) return '';

    $limit = intval( $a['limit'] );
    $offset = max( 0, intval( $a['offset'] ) );
    $i = 0;
    $collected = array();

    while ( have_rows( $a['field'], $post_id ) ) {
        the_row();
        if ( $i < $offset ) { $i++; continue; }
        if ( $limit > 0 && count( $collected ) >= $limit ) break;

        if ( empty( $a['subfield'] ) ) {
            
            $row = get_row(true); 
            $val = is_array( $row ) ? wp_json_encode( $row ) : '';
            $collected[] = $a['before'] . pom_safe_html( $val ) . $a['after'];
        } else {
            $val = get_sub_field( $a['subfield'] );
            if ( is_array( $val ) ) {
                $val = implode( ', ', $val );
            }
            $collected[] = $a['before'] . pom_safe_html( $val ) . $a['after'];
        }
        $i++;
    }
    
    if ( function_exists( 'reset_row_index' ) ) {
        reset_row_index();
    }

    if ( empty( $collected ) ) return '';
    return implode( $a['sep'], $collected );
});

add_shortcode( 'acf_repeater_template', function( $atts ) {
    $a = shortcode_atts( array(
        'field'    => '',
        'post_id'  => 0,
        'template' => '',
        'wrapper'  => '',
        'class'    => '',
        'limit'    => 0,
        'offset'   => 0,
    ), $atts, 'acf_repeater_template' );

    if ( empty( $a['field'] ) || $a['template'] === '' ) return '';
    if ( ! function_exists( 'have_rows' ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );

    if ( ! have_rows( $a['field'], $post_id ) ) return '';

    $out = '';
    $limit = intval( $a['limit'] );
    $offset = max( 0, intval( $a['offset'] ) );
    $i = 0;
    while ( have_rows( $a['field'], $post_id ) ) {
        the_row();
        if ( $i < $offset ) { $i++; continue; }
        if ( $limit > 0 && ( $i - $offset ) >= $limit ) break;

        $row_html = $a['template'];

        
        if ( preg_match_all( '/\\{([a-zA-Z0-9_\\-]+)\\}/', $row_html, $matches ) ) {
            foreach ( $matches[1] as $placeholder ) {
                $replacement = pom_acf_get_subfield_value_for_placeholder( $placeholder );
                $row_html = str_replace( '{' . $placeholder . '}', $replacement, $row_html );
            }
        }

        
        $row_html = do_shortcode( $row_html );
        $out .= $row_html;
        $i++;
    }
    if ( function_exists( 'reset_row_index' ) ) reset_row_index();

    if ( $a['wrapper'] ) {
        $class_attr = $a['class'] ? ' class="' . esc_attr( $a['class'] ) . '"' : '';
        return '<' . esc_attr( $a['wrapper'] ) . $class_attr . '>' . $out . '</' . esc_attr( $a['wrapper'] ) . '>';
    }
    return $out;
});


add_shortcode( 'acf_repeater_count', function( $atts ) {
    $a = shortcode_atts( array(
        'field'   => '',
        'post_id' => 0,
    ), $atts, 'acf_repeater_count' );

    if ( empty( $a['field'] ) ) return '0';
    if ( ! function_exists( 'get_field' ) ) return '0';

    $post_id = pom_acf_get_post_id_from_attr( $a );
    $rows = get_field( $a['field'], $post_id );

    if ( is_array( $rows ) ) return strval( count( $rows ) );
    return '0';
});


add_shortcode( 'acf_if_repeater', function( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'field'   => '',
        'post_id' => 0,
    ), $atts, 'acf_if_repeater' );

    if ( empty( $a['field'] ) ) return '';
    if ( ! function_exists( 'have_rows' ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );

    if ( have_rows( $a['field'], $post_id ) ) {
        return do_shortcode( $content );
    }
    return '';
});


add_shortcode( 'acf_relationship', function( $atts ) {
    $a = shortcode_atts( array(
        'field'    => '',
        'post_id'  => 0,
        'template' => "<a href='{permalink}'>{title}</a>",
        'sep'      => '',
        'before'   => '',
        'after'    => '',
    ), $atts, 'acf_relationship' );

    if ( empty( $a['field'] ) ) return '';
    if ( ! function_exists( 'get_field' ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );
    $rels = get_field( $a['field'], $post_id );

    if ( empty( $rels ) ) return '';

    $items = array();
    foreach ( $rels as $r ) {
        if ( is_object( $r ) && isset( $r->ID ) ) {
            $pid = intval( $r->ID );
        } elseif ( is_numeric( $r ) ) {
            $pid = intval( $r );
        } else {
            continue;
        }

        $title = get_the_title( $pid );
        $permalink = get_permalink( $pid );

        $tpl = $a['template'];
        $tpl = str_replace( '{permalink}', esc_url( $permalink ), $tpl );
        $tpl = str_replace( '{title}', esc_html( $title ), $tpl );
        $tpl = str_replace( '{id}', intval( $pid ), $tpl );

        $items[] = $a['before'] . $tpl . $a['after'];
    }

    return implode( $a['sep'], $items );
});


add_shortcode( 'acf_if_group', function( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'conditions' => '',
        'op'         => 'AND',
        'post_id'    => 0,
    ), $atts, 'acf_if_group' );

    if ( empty( $a['conditions'] ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );

    
    $parts = array_map( 'trim', explode( ';', $a['conditions'] ) );
    $results = array();

    foreach ( $parts as $part ) {
        if ( $part === '' ) continue;
      
        $bits = explode( '|', $part );
        
        $field = isset( $bits[0] ) ? trim( $bits[0] ) : '';
        $operator = isset( $bits[1] ) ? trim( $bits[1] ) : '=';
        $value = isset( $bits[2] ) ? trim( $bits[2] ) : '';

        if ( $field === '' ) continue;

        $res = pom_acf_eval_condition( $field, $operator, $value, $post_id );
        $results[] = (bool) $res;
    }

    if ( empty( $results ) ) return '';

    $op = strtoupper( trim( $a['op'] ) );
    if ( $op === 'OR' ) {
        $ok = in_array( true, $results, true );
    } else {
        
        $ok = ! in_array( false, $results, true );
    }

    if ( $ok ) {
        return do_shortcode( $content );
    }
    return '';
});


if ( ! defined('ABSPATH') ) exit;


add_shortcode('acf_link', function($atts){
    $a = shortcode_atts([
        'field' => '',
        'post_id' => 0,
        'class' => '',
        'target' => '',
        'text' => '', // override link text
    ], $atts, 'acf_link');

    if (!$a['field']) return '';
    if (!function_exists('get_field')) return '';

    $post_id = pom_acf_get_post_id_from_attr($a);
    $link = get_field($a['field'], $post_id);

    if (!$link || !is_array($link)) return '';

    $url = esc_url($link['url']);
    $title = $a['text'] ? pom_safe_text($a['text']) : pom_safe_text($link['title']);
    $target = $a['target'] ? ' target="' . esc_attr($a['target']) . '"' :
               (!empty($link['target']) ? ' target="' . esc_attr($link['target']) . '"' : '');

    $class = $a['class'] ? ' class="' . esc_attr($a['class']) . '"' : '';

    return "<a href='{$url}'{$class}{$target}>{$title}</a>";
});

add_shortcode('acf_post_object', function($atts){
    $a = shortcode_atts([
        'field' => '',
        'post_id' => 0,
        'template' => "<a href='{permalink}'>{title}</a>",
    ], $atts, 'acf_post_object');

    if (!$a['field']) return '';

    $post_id = pom_acf_get_post_id_from_attr($a);
    $obj = get_field($a['field'], $post_id);

    if (!$obj) return '';

    $pid = is_object($obj) ? $obj->ID : intval($obj);
    $permalink = get_permalink($pid);
    $title = get_the_title($pid);

    $tpl = str_replace('{permalink}', esc_url($permalink), $a['template']);
    $tpl = str_replace('{title}', esc_html($title), $tpl);
    $tpl = str_replace('{id}', $pid, $tpl);

    return $tpl;
});


add_shortcode('acf_page_link', function($atts){
    $a = shortcode_atts([
        'field' => '',
        'post_id' => 0,
        'class' => '',
        'text' => '',
    ], $atts, 'acf_page_link');

    $post_id = pom_acf_get_post_id_from_attr($a);
    $value = get_field($a['field'], $post_id);

    if (!$value) return '';

    $url = esc_url($value);
    $text = $a['text'] ?: $url;
    $class = $a['class'] ? ' class="' . esc_attr($a['class']) . '"' : '';

    return "<a href='{$url}'{$class}>{$text}</a>";
});


add_shortcode('acf_taxonomy', function($atts){
    $a = shortcode_atts([
        'field' => '',
        'post_id' => 0,
        'template' => "{name}",
        'sep' => ", ",
    ], $atts, 'acf_taxonomy');

    $post_id = pom_acf_get_post_id_from_attr($a);
    $terms = get_field($a['field'], $post_id);

    if (!$terms) return '';

    $items = [];

    foreach ((array)$terms as $term) {
        if (!is_object($term)) $term = get_term($term);
        // A deleted or invalid term returns null/WP_Error; skip it rather than
        // fataling the page (PHP 8 property access on null).
        if (!$term instanceof WP_Term) { continue; }

        $name = $term->name;
        $slug = $term->slug;
        $link = get_term_link($term);
        if (is_wp_error($link)) { $link = ''; }

        $tpl = str_replace('{name}', esc_html($name), $a['template']);
        $tpl = str_replace('{slug}', esc_html($slug), $tpl);
        $tpl = str_replace('{link}', esc_url($link), $tpl);

        $items[] = $tpl;
    }

    return implode($a['sep'], $items);
});


add_shortcode('acf_user', function($atts){
    $a = shortcode_atts([
        'field' => '',
        'post_id' => 0,
        'template' => "{display_name}",
    ], $atts, 'acf_user');

    $post_id = pom_acf_get_post_id_from_attr($a);
    $user = get_field($a['field'], $post_id);

    if (!$user) return '';

    if (is_array($user)) $user = $user['ID'];
    $u = get_user_by('id', $user);

    if (!$u) return '';

    $out = $a['template'];
    $out = str_replace('{display_name}', esc_html($u->display_name), $out);
    $out = str_replace('{email}', esc_html($u->user_email), $out);
    $out = str_replace('{id}', $u->ID, $out);

    return $out;
});


add_shortcode('acf_date', function($atts){
    $a = shortcode_atts([
        'field' => '',
        'post_id' => 0,
        'format' => 'F j, Y',
    ], $atts, 'acf_date');

    $post_id = pom_acf_get_post_id_from_attr($a);
    $date = get_field($a['field'], $post_id);

    if (!$date) return '';

    $ts = strtotime($date);
    if (!$ts) return pom_safe_text($date);

    return date_i18n($a['format'], $ts);
});


add_shortcode('acf_time', function($atts){
    $a = shortcode_atts([
        'field' => '',
        'post_id' => 0,
        'format' => 'g:i a',
    ], $atts, 'acf_time');

    $post_id = pom_acf_get_post_id_from_attr($a);
    $time = get_field($a['field'], $post_id);

    if (!$time) return '';

    return date_i18n($a['format'], strtotime($time));
});


add_shortcode('acf_group', function($atts){
    $a = shortcode_atts([
        'field' => '',
        'sub' => '',
        'post_id' => 0,
    ], $atts, 'acf_group');

    if (!$a['field'] || !$a['sub']) return '';

    $post_id = pom_acf_get_post_id_from_attr($a);
    $group = get_field($a['field'], $post_id);

    if (!$group || !is_array($group)) return '';

    $val = $group[$a['sub']] ?? '';
    return pom_safe_html($val);
});


add_shortcode('acf_flex', function($atts){
    $a = shortcode_atts([
        'field' => '',
        'post_id' => 0,
    ], $atts, 'acf_flex');

    if (!$a['field']) return '';
    if (!function_exists('have_rows')) return '';

    $post_id = pom_acf_get_post_id_from_attr($a);

    if (!have_rows($a['field'], $post_id)) return '';

    $out = '';

    while (have_rows($a['field'], $post_id)) {
        the_row();
        $layout = get_row_layout();


        if ($layout === 'text_block') {
            $text = get_sub_field('text');
            $out .= "<div class='flex-text'>" . pom_safe_html($text) . "</div>";
        }

        if ($layout === 'image_block') {
            $img = get_sub_field('image');
            if ($img) {
                $out .= wp_get_attachment_image($img['ID'], 'large');
            }
        }
    }

    if (function_exists('reset_row_index')) reset_row_index();
    return $out;
});



if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! function_exists( 'pom_acf_eval_cond' ) ) {
    function pom_acf_eval_cond( $field_val, $op, $value ) {
       
        $op = strtolower( trim( $op ) );
       
        if ( is_array( $field_val ) ) {
            
            switch ( $op ) {
                case 'empty':
                    return empty( $field_val );
                case 'not_empty':
                    return ! empty( $field_val );
                case 'contains':
                    return in_array( $value, $field_val, true );
                case 'not_contains':
                    return ! in_array( $value, $field_val, true );
                case '=':
                case '==':
                    return in_array( $value, $field_val, true );
                case '!=':
                    return ! in_array( $value, $field_val, true );
                default:
                    return false;
            }
        }

        switch ( $op ) {
            case 'empty':
                return ( $field_val === null || $field_val === false || $field_val === '' );
            case 'not_empty':
                return !( $field_val === null || $field_val === false || $field_val === '' );
            case 'contains':
                return ( stripos( (string) $field_val, (string) $value ) !== false );
            case 'not_contains':
                return ( stripos( (string) $field_val, (string) $value ) === false );
            case '=':
            case '==':
                if ( is_numeric( $field_val ) && is_numeric( $value ) ) return floatval( $field_val ) == floatval( $value );
                return ( (string) $field_val === (string) $value );
            case '!=':
                if ( is_numeric( $field_val ) && is_numeric( $value ) ) return floatval( $field_val ) != floatval( $value );
                return ( (string) $field_val !== (string) $value );
            case '>':
                return is_numeric( $field_val ) && is_numeric( $value ) ? floatval( $field_val ) > floatval( $value ) : false;
            case '<':
                return is_numeric( $field_val ) && is_numeric( $value ) ? floatval( $field_val ) < floatval( $value ) : false;
            case '>=':
                return is_numeric( $field_val ) && is_numeric( $value ) ? floatval( $field_val ) >= floatval( $value ) : false;
            case '<=':
                return is_numeric( $field_val ) && is_numeric( $value ) ? floatval( $field_val ) <= floatval( $value ) : false;
            case 'in':
               
                $list = array_map('trim', explode(',', $value));
                return in_array( (string) $field_val, $list, true );
            default:
                return false;
        }
    }
}

add_shortcode( 'acf_if', function( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'field'    => '',
        'post_id'  => 0,
        'op'       => '=',
        'operator' => '',
        'value'    => '',
    ), $atts, 'acf_if' );
    // Accept operator= (the older syntax) as an alias for op=.
    if ( '' !== $a['operator'] ) { $a['op'] = $a['operator']; }

    if ( empty( $a['field'] ) ) return '';

    if ( ! function_exists( 'get_field' ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );
    $field_val = get_field( $a['field'], $post_id );

    
    if ( $content === null ) return '';

    $pattern = '/\\[acf_elseif\\b([^\\]]*)\\]|\\[acf_else\\b[^\\]]*\\]/i';


    preg_match_all( $pattern, $content, $matches, PREG_OFFSET_CAPTURE );

    $branches = array();
    if ( empty( $matches[0] ) ) {
        
        $branches[] = array(
            'attrs' => $a,
            'content' => $content,
        );
    } else {
       
        $lastPos = 0;
        $firstAttrs = $a;
        foreach ( $matches[0] as $i => $m ) {
            $matchText = $m[0];
            $pos = $m[1];
            $slice = substr( $content, $lastPos, $pos - $lastPos );
            $branches[] = array( 'attrs' => $firstAttrs, 'content' => $slice );
            
            $attrText = $matches[1][$i][0]; 
            if ( stripos( $matchText, '[acf_else' ) === 0 ) {
               
                $firstAttrs = array( 'field' => '__ELSE__' );
            } else {
                
                $tmp = shortcode_parse_atts( $attrText );
                $firstAttrs = array_merge( array( 'field'=> '', 'post_id'=>0, 'op'=>'=', 'value'=>'' ), $tmp );
            }
            $lastPos = $pos + strlen( $matchText );
        }

        $branches[] = array( 'attrs' => $firstAttrs, 'content' => substr( $content, $lastPos ) );
    }

    
    foreach ( $branches as $branch ) {
        $b = $branch['attrs'];
        if ( isset( $b['field'] ) && $b['field'] === '__ELSE__' ) {

            return do_shortcode( $branch['content'] );
        }

        if ( empty( $b['field'] ) ) continue;

        $b_post = isset( $b['post_id'] ) ? pom_acf_get_post_id_from_attr( $b ) : $post_id;
        $left = get_field( $b['field'], $b_post );
        $op = isset( $b['op'] ) ? $b['op'] : '=';
        $val = isset( $b['value'] ) ? $b['value'] : '';

        if ( pom_acf_eval_cond( $left, $op, $val ) ) {
            return do_shortcode( $branch['content'] );
        }
    }

    return '';
});


add_shortcode( 'acf_exists', function( $atts, $content = null ) {
    $a = shortcode_atts( array( 'field'=>'', 'post_id'=>0, 'not'=>0 ), $atts, 'acf_exists' );
    if ( empty( $a['field'] ) ) return '';
    if ( ! function_exists( 'get_field' ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );
    $val = get_field( $a['field'], $post_id );
    $exists = !( $val === null || $val === false || $val === '' || ( is_array( $val ) && empty( $val ) ) );

    if ( $a['not'] && $a['not'] !== '0' ) $exists = ! $exists;
    if ( $exists ) return do_shortcode( $content );
    return '';
});

foreach ( array( 'sum', 'avg', 'min', 'max', 'count' ) as $fn ) {
    add_shortcode( 'acf_' . $fn, function( $atts ) use ( $fn ) {
        $a = shortcode_atts( array( 'field'=>'', 'sub'=>'', 'post_id'=>0, 'decimals'=>2 ), $atts, 'acf_' . $fn );
        if ( empty( $a['field'] ) ) return '';

        if ( ! function_exists( 'have_rows' ) ) return '';

        $post_id = pom_acf_get_post_id_from_attr( $a );
        if ( ! have_rows( $a['field'], $post_id ) ) return '';

        $values = array();
        while ( have_rows( $a['field'], $post_id ) ) {
            the_row();
            if ( empty( $a['sub'] ) ) {
                
                if ( $fn === 'count' ) {
                    $values[] = 1;
                } else {
                    continue;
                }
            } else {
                $v = get_sub_field( $a['sub'] );
                if ( is_numeric( $v ) ) {
                    $values[] = floatval( $v );
                } elseif ( is_string( $v ) ) {
                    
                    $v2 = preg_replace('/[^0-9.-]/','', $v );
                    if ( $v2 !== '' && is_numeric( $v2 ) ) $values[] = floatval( $v2 );
                }
            }
        }
       
        if ( function_exists( 'reset_row_index' ) ) reset_row_index();

        if ( empty( $values ) ) return '';

        switch ( $fn ) {
            case 'sum':
                return number_format( array_sum( $values ), intval( $a['decimals'] ) );
            case 'avg':
                $avg = array_sum( $values ) / count( $values );
                return number_format( $avg, intval( $a['decimals'] ) );
            case 'min':
                return number_format( min( $values ), intval( $a['decimals'] ) );
            case 'max':
                return number_format( max( $values ), intval( $a['decimals'] ) );
            case 'count':
                return count( $values );
        }
        return '';
    });
}

add_shortcode( 'acf_render', function( $atts ) {
    $a = shortcode_atts( array(
        'field' => '',
        'post_id' => 0,
        'format' => 'text',
        'image_size' => 'large',
        'gallery_lightbox' => 'false',
    ), $atts, 'acf_render' );

    if ( empty( $a['field'] ) ) return '';
    if ( ! function_exists( 'get_field' ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );
    $val = get_field( $a['field'], $post_id );

    if ( $val === null || $val === false || $val === '' ) return '';

 
    if ( is_array( $val ) && isset( $val['ID'] ) ) {
        return wp_get_attachment_image( intval( $val['ID'] ), $a['image_size'] );
    }
    if ( is_numeric( $val ) && wp_attachment_is_image( intval( $val ) ) ) {
        return wp_get_attachment_image( intval( $val ), $a['image_size'] );
    }

    if ( is_array( $val ) && isset( $val[0] ) && ( isset( $val[0]['ID'] ) || is_numeric( $val[0] ) ) ) {
        $out = '';
        foreach ( $val as $img ) {
            $id = is_array( $img ) && isset( $img['ID'] ) ? intval( $img['ID'] ) : intval( $img );
            $thumb = wp_get_attachment_image( $id, $a['image_size'] );
            $url = wp_get_attachment_url( $id );
            if ( $a['gallery_lightbox'] === 'true' || $a['gallery_lightbox'] === '1' ) {
                $gid = 'acf-gallery-' . sanitize_title( $a['field'] ) . '-' . intval( $post_id );
                $out .= '<a data-fancybox="' . esc_attr( $gid ) . '" href="' . esc_url( $url ) . '">' . $thumb . '</a>';
            } else {
                $out .= $thumb;
            }
        }
        return $out;
    }

 
    if ( is_string( $val ) && preg_match( '#^https?://#', $val ) ) {
        
        if ( function_exists( 'wp_oembed_get' ) ) {
            $o = wp_oembed_get( $val );
            if ( $o ) return $o;
        }

        return '<a href="' . esc_url( $val ) . '">' . esc_html( $val ) . '</a>';
    }

  
    if ( is_array( $val ) ) {
        return pom_safe_html( implode( ', ', array_map( 'strval', $val ) ) );
    }


    if ( $a['format'] === 'html' ) return pom_safe_html( $val );
    return pom_safe_text( $val );
});

add_shortcode( 'acf_gallery_lightbox', function( $atts ) {
    $a = shortcode_atts( array(
        'field' => '',
        'post_id' => 0,
        'size' => 'thumbnail',
        'group' => '',
        'caption' => 'false',
    ), $atts, 'acf_gallery_lightbox' );

    if ( empty( $a['field'] ) ) return '';
    if ( ! function_exists( 'get_field' ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );
    $gallery = get_field( $a['field'], $post_id );
    if ( empty( $gallery ) || ! is_array( $gallery ) ) return '';

    $group = $a['group'] ? sanitize_title( $a['group'] ) : 'acf-gallery-' . sanitize_title( $a['field'] ) . '-' . intval( $post_id );
    $out = '<div class="acf-gallery-lightbox">';

    foreach ( $gallery as $img ) {
        $id = is_array( $img ) && isset( $img['ID'] ) ? intval( $img['ID'] ) : intval( $img );
        $thumb = wp_get_attachment_image( $id, $a['size'] );
        $src = wp_get_attachment_url( $id );
        $caption = ( $a['caption'] === 'true' ) ? wp_get_attachment_caption( $id ) : '';
        $data_caption = $caption ? ' data-caption="' . esc_attr( $caption ) . '"' : '';
        $out .= '<a data-fancybox="' . esc_attr( $group ) . '" href="' . esc_url( $src ) . '"' . $data_caption . '>' . $thumb . '</a>';
    }

    $out .= '</div>';
    return $out;
});

function pom_enqueue_frontend_assets() {
    if ( is_singular( 'pets' ) ) {
        // Vendored and pinned (5.0.36) so the dog-gallery lightbox does not
        // depend on a CDN being up or on an unpinned "latest" release.
        wp_enqueue_style( 'fancybox-css', get_stylesheet_directory_uri() . '/assets/vendor/fancybox.css', array(), '5.0.36' );
        wp_enqueue_script( 'fancybox-js', get_stylesheet_directory_uri() . '/assets/vendor/fancybox.umd.js', array(), '5.0.36', true );
        $inline = "document.addEventListener('DOMContentLoaded', function(){ if(typeof Fancybox !== 'undefined'){ Fancybox.bind('[data-fancybox=\"gallery\"]', {}); }} );";
        wp_add_inline_script( 'fancybox-js', $inline );
    }
}
add_action( 'wp_enqueue_scripts', 'pom_enqueue_frontend_assets' );


// *********** FOSTER NEEDS - Stacked Layout ***********
add_shortcode('foster_needs', function($atts) {
    $atts = shortcode_atts([
        'status' => 'Foster Needed',
        'limit' => -1,
    ], $atts);

    $args = [
        'post_type' => 'pets',
        'posts_per_page' => $atts['limit'],
        'meta_query' => [
            [
                'key' => 'status',
                'value' => '"' . $atts['status'] . '"',
                'compare' => 'LIKE',
            ]
        ]
    ];

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p>No foster pets found.</p>';
    }

    $output = '<div class="foster-needs-list">';

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();

        $output .= '<div class="foster-card">';

        // Pet name
        $pet_name = get_the_title();
        $output .= '<h3 class="foster-card__name">' . esc_html($pet_name) . '</h3>';

        // Feature photo (first from gallery)
        $gallery = get_field('photo_gallery', $post_id);
        if (!empty($gallery) && is_array($gallery)) {
            $photo = $gallery[0];
            $output .= '<div class="foster-card__image">';
            $output .= '<img src="' . esc_url($photo['sizes']['large'] ?? $photo['url']) . '" alt="' . esc_attr($pet_name) . '">';
            $output .= '</div>';
        }

        // Breed
        $looks_like = get_field('looks_like', $post_id);
        if ($looks_like) {
            $output .= '<p class="foster-card__breed">' . esc_html($looks_like) . '</p>';
        }

        // Sex, age, weight
        $details = [];
        $sex = get_field('sex', $post_id);
        $age = get_field('age', $post_id);
        $weight = get_field('weight', $post_id);

        if ($sex) $details[] = esc_html($sex);
        if ($age) $details[] = esc_html($age) . ' yrs';
        if ($weight) $details[] = esc_html($weight) . ' lbs';

        if (!empty($details)) {
            $output .= '<p class="foster-card__details">' . implode(' · ', $details) . '</p>';
        }

        // Description
        $description = get_field('pet_description', $post_id);
        if ($description) {
            $output .= '<div class="foster-card__description">' . wp_kses_post($description) . '</div>';
        }

        // Foster details
        $foster_start = get_field('foster_start_date', $post_id);
        $foster_end = get_field('foster_end_date', $post_id);
        if ($foster_start || $foster_end) {
            $output .= '<div class="foster-card__foster-info">';
            if ($foster_start) $output .= '<span>Start: ' . esc_html($foster_start) . '</span>';
            if ($foster_start && $foster_end) $output .= ' · ';
            if ($foster_end) $output .= '<span>End: ' . esc_html($foster_end) . '</span>';
            $output .= '</div>';
        }

        $output .= '</div>'; // .foster-card
    }

    wp_reset_postdata();
    $output .= '</div>';

    return $output;
});

/*
 * POMDR 2026 redesign layer.
 * Loads the design-system CSS and JS (assets/) on top of the production
 * Divi child theme. This is additive: it only registers a wp_enqueue_scripts
 * action and does not change any of the production logic above.
 * ACF fields are owned by the ACF Pro plugin, not registered in code. The
 * authoritative field-group schema is version-controlled as a reference export
 * at acf-export-2026-06-09.json (theme root). The legacy redirect handler
 * (inc/redirects.php) is intentionally NOT loaded here; it is opt-in.
 */
/* ============================================================
 * Promo Banner: a staff-toggleable announcement for adoption promotions,
 * fundraisers, etc. The Options page is registered here; its FIELDS are created
 * once in the ACF UI (per this theme's "fields owned by ACF" convention) on the
 * Promo Banner options page, with these names:
 *   promo_enabled   true_false   (show/hide)
 *   promo_label     text         e.g. "Adoption Promotion" or "Fundraiser"
 *   promo_message   textarea     the announcement
 *   promo_btn_text  text         button label (optional)
 *   promo_btn_url   url          button link (optional)
 *   promo_style     select       choices: purple, teal (default purple)
 *   promo_start     date_picker  Ymd return (optional auto show)
 *   promo_end       date_picker  Ymd return (optional auto hide)
 * Placement: put [promo_banner] in a full-width Code module directly below the
 * hero. It renders nothing when disabled or out of the date range.
 * ============================================================ */
add_action('acf/init', function () {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => 'Promo Banner',
            'menu_title' => 'Promo Banner',
            'menu_slug'  => 'pomdr-promo-banner',
            'capability' => 'edit_posts',
            'icon_url'   => 'dashicons-megaphone',
            'position'   => 4,
            'redirect'   => false,
        ));
    }
});

function pomdr_promo_banner_shortcode() {
    if (!function_exists('get_field') || !get_field('promo_enabled', 'option')) {
        return '';
    }
    $start = get_field('promo_start', 'option');
    $end   = get_field('promo_end', 'option');
    $today = current_time('Ymd');
    if ($start && $today < $start) return '';
    if ($end && $today > $end)     return '';

    $label = trim((string) get_field('promo_label', 'option'));
    $msg   = trim((string) get_field('promo_message', 'option'));
    $bt    = trim((string) get_field('promo_btn_text', 'option'));
    $bu    = trim((string) get_field('promo_btn_url', 'option'));
    $style = (get_field('promo_style', 'option') === 'teal') ? 'teal' : 'purple';
    if ($msg === '' && $label === '') return '';

    $paw = '<svg class="promo-paw" viewBox="0 0 32 32" aria-hidden="true"><circle cx="16" cy="16" r="16" fill="#fff"/><g fill="#632F88"><ellipse cx="10.5" cy="13" rx="2" ry="2.5"/><ellipse cx="14.7" cy="10.6" rx="2" ry="2.5"/><ellipse cx="18.3" cy="10.6" rx="2" ry="2.5"/><ellipse cx="22" cy="13.4" rx="2" ry="2.5"/><path d="M16.2 15.4c-3 0-5.4 2.2-5.4 4.7 0 1.8 1.5 2.8 3.2 2.8.9 0 1.5-.5 2.2-.5s1.3.5 2.2.5c1.7 0 3.2-1 3.2-2.8 0-2.5-2.4-4.7-5.4-4.7z"/></g></svg>';

    ob_start(); ?>
    <aside class="promo-banner promo-banner--<?php echo esc_attr($style); ?>" role="region" aria-label="Announcement">
      <div class="promo-inner">
        <div class="promo-text">
          <?php if ($label !== '') : ?><span class="promo-label"><?php echo $paw . esc_html($label); ?></span><?php endif; ?>
          <?php if ($msg !== '') : ?><p class="promo-message"><?php echo esc_html($msg); ?></p><?php endif; ?>
        </div>
        <?php if ($bt !== '' && $bu !== '') : ?>
        <div class="promo-actions"><a class="promo-btn" href="<?php echo esc_url($bu); ?>"><?php echo esc_html($bt); ?></a></div>
        <?php endif; ?>
      </div>
    </aside>
    <?php
    return ob_get_clean();
}
add_shortcode('promo_banner', 'pomdr_promo_banner_shortcode');

require_once get_stylesheet_directory() . '/inc/chrome.php';
require_once get_stylesheet_directory() . '/inc/enqueue.php';
require_once get_stylesheet_directory() . '/inc/videos.php';

/**
 * Accessibility: restore pinch-zoom (WCAG 2.2, SC 1.4.4 Resize Text).
 * Divi hardcodes `maximum-scale=1.0, user-scalable=0` in its viewport meta,
 * which stops people (older adopters especially) from zooming the page on a
 * phone. Remove Divi's tag and emit a zoom-allowing one instead. The removal
 * runs on `init` (after the parent theme has registered its hook), and our own
 * meta is added at a late priority so it wins even if the removal ever no-ops.
 */
add_action( 'init', function () {
	remove_action( 'wp_head', 'et_add_viewport_meta' );
} );
add_action( 'wp_head', function () {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
}, 99 );
