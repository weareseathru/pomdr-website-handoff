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

    // Concatenate values
    if ($looks_like_value && $age_value && $weight_value && $sex_value_display) {
   echo "<span style='font-weight:600; font-weight:bold; font-size:1.6em;'>" . esc_html($looks_like_value) . "</span>" .
	  "<span style='font-weight:400; font-size:1em;'> (looks like)</span><br/>" .
      "<span style='font-weight:600; font-size:1.2em; '>" .
      esc_html($sex_value_display) . ", " . esc_html($age_value) . " years old (est), " .
      esc_html($weight_value) . " lbs</span>";

} else {
    echo esc_html("Inquire Directly");
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
function register_custom_blog_template($templates) {
    $templates['template-blog-list.php'] = 'Custom Blog List';
    return $templates;
}
add_filter('theme_page_templates', 'register_custom_blog_template');

/**
 * Handle template loading
 */
function load_custom_blog_template($template) {
    if(is_page_template('template-blog-list.php')) {
        $template = get_stylesheet_directory() . '/template-blog-list.php';
    }
    return $template;
}
add_filter('template_include', 'load_custom_blog_template');
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

    echo '<div class="custom-acf-posts-grid">';

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            $status  = get_field('status') ?: [];
            $image   = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium_large') : '';
            $looks   = get_field('looks_like');
            $sex_display = pom_acf_sex_display(get_the_ID());
            $age     = get_field('age');
            $weight  = get_field('weight');

            echo '<div class="custom-post-item">';

            if ($image) {
                echo '<div class="custom-post-image">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '" loading="lazy">';
                echo '</a></div>';
            }

            echo '<div class="custom-post-content">';
            echo '<div class="custom-post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></div>';

            if ($looks) echo esc_html($looks) . '<br>';
            if ($sex_display) echo esc_html($sex_display) . ', ';
            if ($age) echo esc_html($age) . ' yrs, ';
            if ($weight) echo esc_html($weight) . ' lbs';

            if (in_array('Foster Needed', $status)) {
                echo '<br>Foster Needed!';
            }

            if (in_array('Adoption Pending', $status)) {
                echo '<br>Adoption Pending';
            }

            echo '</div></div>';

        endwhile;
        wp_reset_postdata();
    endif;

    echo '</div>';

    return ob_get_clean();
}

add_shortcode('adopt_a_pet', 'adopt_a_pet_shortcode');


function adopt_a_pet_fullwidth_shortcode() {
    ob_start();

    $args = [
        'post_type'      => 'pets',
        'posts_per_page' => 3,
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
            'meta_value' => 'DESC',
            'date'       => 'DESC',
        ],
        'meta_key' => 'feature',
    ];

    $query = new WP_Query($args);

    echo '<div class="custom-acf-posts-fullwidth">';

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            $status  = get_field('status') ?: [];
            $image   = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';
            $looks   = get_field('looks_like');
            $sex     = get_field('sex');
            $age     = get_field('age');
            $weight  = get_field('weight');

            echo '<article class="custom-post-full">';

            if ($image) {
                echo '<div class="custom-post-full-image">';
                echo '<a href="' . get_permalink() . '">';
                echo '<img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '">';
                echo '</a></div>';
            }

            echo '<div class="custom-post-full-content">';
            echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';

            echo '<div class="pet-meta">';
            if ($looks)  echo esc_html($looks) . '<br>';
            if ($sex)    echo esc_html($sex) . ' · ';
            if ($age)    echo esc_html($age) . ' yrs · ';
            if ($weight) echo esc_html($weight) . ' lbs';
            echo '</div>';

            if (in_array('Foster Needed', $status)) {
                echo '<div class="pet-badge foster">Foster Needed</div>';
            }

            if (in_array('Adoption Pending', $status)) {
                echo '<div class="pet-badge pending">Adoption Pending</div>';
            }

            echo '</div></article>';

        endwhile;
        wp_reset_postdata();
    endif;

    echo '</div>';

    return ob_get_clean();
}

add_shortcode('adopt_a_pet_fullwidth', 'adopt_a_pet_fullwidth_shortcode');

// *************************Benifit Shop Staff *******************************

function benefit_shop_staff_shortcode() {
 
    ob_start(); // Start output buffering
 
    $args = array(

        'post_type'      => 'team',

        'order'          => 'ASC',

        'orderby'        => 'meta_value',

        'meta_key'       => 'sort',

        // 'posts_per_page' => 2, // Show only 2 staff

        'meta_query'     => array(

            array(

                'key'     => 'group',

                'value'   => 'Benefit Shop Staff', // Change if needed

                'compare' => 'LIKE'

            )

        )

    );
 
    $custom_query = new WP_Query($args);
 
    if ($custom_query->have_posts()) :
 
        echo '<div class="custom-acf-posts-grid benefit-shop-staff">';
 
        while ($custom_query->have_posts()) : $custom_query->the_post();
 
            $title = get_field('title');

            $image = '';
 
            if (has_post_thumbnail()) {

                $image = get_the_post_thumbnail_url(get_the_ID(), 'full');

            }
 
            echo '<div class="custom-post-item">';
 
            // Image

            if ($image) {

                echo '<div class="custom-post-image">';

                echo '<a href="' . get_permalink() . '">
<img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '">
</a>';

                echo '</div>';

            }
 
            // Content

            echo '<div class="custom-post-content">';
 
            echo '<h2 class="custom-post-title">
<a href="' . get_permalink() . '">' . get_the_title() . '</a>
</h2>';
 
            if ($title) {

                echo '<div class="staff-title">' . esc_html($title) . '</div>';

            }
 
            echo '</div>'; // content

            echo '</div>'; // item
 
        endwhile;
 
        echo '</div>'; // grid
 
        wp_reset_postdata();
 
    else :
 
        echo '<p>No Benefit Shop Staff found.</p>';
 
    endif;
 
    return ob_get_clean();

}
 
add_shortcode('benefit_shop_staff', 'benefit_shop_staff_shortcode');
 
// ***************************Clinic Staff****************************

function clinic_staff_shortcode() {

    ob_start(); // Start output buffering

    // Custom query to get your posts

    $args = array(

        'post_type' => 'team', // Change to your custom post type if needed

        'order' => 'ASC',

        'orderby' => 'meta_value', // Sort by the custom field value

        'meta_key' => 'sort', // The ACF field name used for sorting

        'meta_query' => array(

            array(

                'key' => 'group', // The ACF field name

                'value' => 'Clinic Staff', // The value you want to match

                'compare' => 'LIKE' // Use LIKE for checkbox fields

            )

        )

    );

    $custom_query = new WP_Query($args);

    // Check if there are posts

    if ($custom_query->have_posts()) :

        echo '<div class="custom-acf-posts-grid">';

        while ($custom_query->have_posts()) : $custom_query->the_post();

            // Get your ACF fields - replace field_name with your actual field names

            $title = get_field('title');

            $image = '';

            if (has_post_thumbnail()) {

                $image = get_the_post_thumbnail_url(get_the_ID(), 'full');

            }

            // Start building the post container

            echo '<div class="custom-post-item">';

            // Add featured image if it exists

            if ($image) {

                echo '<div class="custom-post-image">';

                echo '<a href="' . get_permalink() . '"><img src="' . $image . '" alt="' . get_the_title() . '"></a>';

                echo '</div>';

            }

            // Post content

            echo '<div class="custom-post-content">';

            echo '<h2 class="custom-post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';

            // Display your ACF fields

            if ($title) {

                echo $title;

            }

            echo '</div>'; // End .custom-post-content

            echo '</div>'; // End .custom-post-item

        endwhile;

        echo '</div>'; // End .custom-acf-posts-grid

        // Reset post data

        wp_reset_postdata();

    else :

        echo '<p>Oops, error.</p>';

    endif;

    return ob_get_clean(); // Return the buffered content

}

add_shortcode('clinic_staff', 'clinic_staff_shortcode');
 
// ***************************Advisory Council****************************

function advisory_council_shortcode() {

    ob_start(); // Start output buffering

    // Custom query to get your posts

    $args = array(

        'post_type' => 'team', // Change to your custom post type if needed

        'order' => 'ASC',

        'orderby' => 'meta_value', // Sort by the custom field value

        'meta_key' => 'sort', // The ACF field name used for sorting

        'meta_query' => array(

            array(

                'key' => 'group', // The ACF field name

                'value' => 'Advisory Council', // The value you want to match

                'compare' => 'LIKE' // Use LIKE for checkbox fields

            )

        )

    );

    $custom_query = new WP_Query($args);

    // Check if there are posts

    if ($custom_query->have_posts()) :

        echo '<div class="custom-acf-posts-grid">';

        while ($custom_query->have_posts()) : $custom_query->the_post();

            // Get your ACF fields - replace field_name with your actual field names

            $title = get_field('title');

            $image = '';

            if (has_post_thumbnail()) {

                $image = get_the_post_thumbnail_url(get_the_ID(), 'full');

            }

            // Start building the post container

            echo '<div class="custom-post-item">';

            // Add featured image if it exists

            if ($image) {

                echo '<div class="custom-post-image">';

                echo '<a href="' . get_permalink() . '"><img src="' . $image . '" alt="' . get_the_title() . '"></a>';

                echo '</div>';

            }

            // Post content

            echo '<div class="custom-post-content">';

            echo '<h2 class="custom-post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';

            // Display your ACF fields

            if ($title) {

                echo $title;

            }

            echo '</div>'; // End .custom-post-content

            echo '</div>'; // End .custom-post-item

        endwhile;

        echo '</div>'; // End .custom-acf-posts-grid

        // Reset post data

        wp_reset_postdata();

    else :

        echo '<p>Oops, error.</p>';

    endif;

    return ob_get_clean(); // Return the buffered content

}

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
                echo '<a href="' . get_permalink() . '"><img src="' . $image . '" alt="' . get_the_title() . '"></a>';
                echo '</div>';
            }
            // Post content
            echo '<div class="custom-post-content">';
            echo '<div class="custom-post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></div>';
            
            // Display your ACF fields
            if ($looks_like) {
                echo $looks_like . '<br/>';
            }
            if ($sex) {
                echo $sex . ', ';
            }
			if ($age) {
                echo $age . ' yrs, ';
            }
			if ($weight) {
                echo $weight . ' lbs';
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
    ob_start(); // Start output buffering
    
    // Custom query to get your posts
    $args = array(
        'post_type' => 'pets', // Change to your custom post type if needed
        'order' => 'DESC',
        'orderby' => 'date_adopted',
		'meta_query'     => array(
            array(
                'key'     => 'status',
                'value'   => '"Adopted"',
                'compare' => 'LIKE'
            )
        )
    );
    
    $custom_query = new WP_Query($args);
    
    // Check if there are posts
    if ($custom_query->have_posts()) :
        echo '<div class="custom-acf-posts-grid">';
        
        while ($custom_query->have_posts()) : $custom_query->the_post();
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
                echo '<img src="' . $image . '" alt="' . get_the_title() . '">';
                echo '</div>';
            }
            // Post content
            echo '<div class="custom-post-content">';
            echo '<h2 class="custom-post-title">' . get_the_title() . '</h2>';
                
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
add_shortcode('adopted_pets', 'adopted_pets_shortcode');


// *********** HOSPICE LIST VIEW ***********
function hospice_care_shortcode() {
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
            'value' => '"Hospice"',
            'compare' => 'LIKE'
        )
    	)
    );
    
    $custom_query = new WP_Query($args);
    
    // Check if there are posts
    if ($custom_query->have_posts()) :
        echo '<div class="hospice-care-list">';
        
        while ($custom_query->have_posts()) : $custom_query->the_post();
			
			$pet_description = get_field('pet_description');

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
            


echo '<div class="hospice-post-item" style="display: flex; align-items: flex-start; gap: 20px; margin-bottom: 20px;">';

// Featured image if it exists
if ($image) {
    echo '<div class="custom-post-image" style="flex-shrink: 0; width: 400px; max-width: 400px;">';
    echo '<img src="' . $image . '" alt="' . get_the_title() . '" style="width: 100%; height: auto; border-radius: 6px; display: block;">';
    echo '</div>';
}

// Post content
echo '<div class="custom-post-content" style="flex: 1; display: flex; flex-direction: column; min-width: 0;">';
echo '<div class="custom-post-title" style="font-weight: bold; margin-bottom: 10px;">' . get_the_title() . '</div>';

// Only display description if it exists
if ($pet_description) {
    echo '<div class="custom-post-description" style="margin-bottom: 10px;">' . $pet_description . '</div>';
}

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
add_shortcode('hospice_care', 'hospice_care_shortcode');


// *********** Courtesy Listings LIST VIEW ***********
function courtesy_listings_shortcode() {
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
            'value' => '"Courtesy Listing"',
            'compare' => 'LIKE'
        )
    	)
    );
    
    $custom_query = new WP_Query($args);
    
    // Check if there are posts
    if ($custom_query->have_posts()) :
        echo '<div class="hospice-care-list">';
        
        while ($custom_query->have_posts()) : $custom_query->the_post();
			

			
			$pet_description = get_field('pet_description');

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
            


echo '<div class="hospice-post-item" style="display: flex; align-items: flex-start; gap: 20px; margin-bottom: 20px;">';

// Featured image if it exists
if ($image) {
    echo '<div class="custom-post-image" style="flex-shrink: 0; width: 400px; max-width: 400px;">';
    echo '<img src="' . $image . '" alt="' . get_the_title() . '" style="width: 100%; height: auto; border-radius: 6px; display: block;">';
    echo '</div>';
}

// Post content
echo '<div class="custom-post-content" style="flex: 1; display: flex; flex-direction: column; min-width: 0;">';
echo '<div class="custom-post-title" style="font-weight: bold; margin-bottom: 10px;">' . get_the_title() . '</div>';

// Only display description if it exists
if ($pet_description) {
    echo '<div class="custom-post-description" style="margin-bottom: 10px;">' . $pet_description . '</div>';
}

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
    ob_start(); // Start output buffering
    
    // Custom query to get your posts
    $args = array(
        'post_type' => 'pets', // Change to your custom post type if needed
        'posts_per_page' => 3, // Number of posts to display
		'orderby' => 'rand', // Randomize the order of results
		'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'adopted',
            'compare' => 'NOT EXISTS'
        ),
        array(
            'key' => 'status',
            'value' => '"Adoptable"',
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
add_shortcode('random_pet', 'random_pet_shortcode');



// *************************** HOME PAGE PETS ****************************
function pet_home_shortcode() {
    ob_start();

    $args = [
        'post_type'      => 'pets',
        'posts_per_page' => 7,
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
            'date'       => 'DESC', // Newest next
        ],
        'meta_key' => 'feature',
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        echo '<div class="custom-acf-posts-grid-4">';

        while ($query->have_posts()) : $query->the_post();

            $image = has_post_thumbnail()
                ? get_the_post_thumbnail_url(get_the_ID(), 'full')
                : '';

            echo '<div class="custom-post-item">';

            if ($image) {
                echo '<div class="custom-post-image">';
                echo '<a href="' . esc_url(get_permalink()) . '">';
                echo '<img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '">';
                echo '</a></div>';
            }

            echo '<div class="custom-post-content">';
            echo '<h2 class="custom-post-title">' . esc_html(get_the_title()) . '</h2>';
            echo '</div>';

            echo '</div>';

        endwhile;

        // See More tile
        echo '<a href="/adopt/">';
        echo '<div class="custom-post-item">';
        echo '<div class="custom-post-image">';
        echo '<img decoding="async" src="/wp-content/uploads/2025/07/blurred.jpg" alt="Browse All">';
        echo '</div>';
        echo '<div class="custom-post-content">';
        echo '<h2 class="custom-post-title">See More &gt;</h2>';
        echo '</div>';
        echo '</div>';
        echo '</a>';

        echo '</div>';

        wp_reset_postdata();
    else :
        echo '<p>Oops, error.</p>';
    endif;

    return ob_get_clean();
}

add_shortcode('pet_home', 'pet_home_shortcode');


function adopt_a_pet_plp_shortcode() {
    ob_start();

    $args = array(
        'post_type'      => 'pets',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        echo '<div class="custom-acf-posts-grid">';

        while ($query->have_posts()) : $query->the_post();

            
            $recently_adopted = false;
            $date_adopted = get_field('date_adopted');

            if ($date_adopted) {
                // Support both Ymd and formatted date values
                if (is_numeric($date_adopted)) {
                    $dt = DateTime::createFromFormat('Ymd', $date_adopted);
                    $adopted_timestamp = $dt ? $dt->getTimestamp() : false;
                } else {
                    $adopted_timestamp = strtotime($date_adopted);
                }

                if ($adopted_timestamp) {
                    $days_diff = floor(
                        (current_time('timestamp') - $adopted_timestamp) / DAY_IN_SECONDS
                    );

                    if ($days_diff >= 0 && $days_diff < 14) {
                        $recently_adopted = true;
                    }
                }
            }

            
            $looks_like = get_field('looks_like');
            $sex        = get_field('sex');
            $age        = get_field('age');
            $weight     = get_field('weight');

            $image = has_post_thumbnail()
                ? get_the_post_thumbnail_url(get_the_ID(), 'full')
                : '';

            echo '<div class="custom-post-item">';

            if ($image) {
                echo '<div class="custom-post-image">';
                echo '<a href="' . esc_url(get_permalink()) . '">';
                echo '<img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '">';
                echo '</a>';
                echo '</div>';
            }

            echo '<div class="custom-post-content">';
            echo '<div class="custom-post-title">';
            echo '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a>';
            echo '</div>';

            
            if ($recently_adopted) {
                //echo '<div class="recently-adopted">Recently Adopted</div>';
            }

            if ($looks_like) echo esc_html($looks_like) . '<br>';
            $sex_display_plp = pom_acf_sex_display(get_the_ID());
            if ($sex_display_plp) echo esc_html($sex_display_plp) . ', ';
            if ($age) echo esc_html($age) . ' yrs, ';
            if ($weight) echo esc_html($weight) . ' lbs';

            echo '</div></div>';

        endwhile;

        echo '</div>';
        wp_reset_postdata();
    endif;

    return ob_get_clean();
}

add_shortcode('adopt_a_pet_plp', 'adopt_a_pet_plp_shortcode');


// ********************* EVENTS *********************
function events_shortcode() {
    ob_start(); // Start output buffering
    
    // Custom query to get your posts
    $args = array(
        'post_type' => 'events',
        'meta_key' => 'event_start', // Use meta_key for ACF field ordering
        'orderby' => 'meta_value', // Order by the meta field value
        'order' => 'ASC',
        'meta_query' => array( // Wrap the meta query in meta_query array
            array(
                'key' => 'event_type',
                'value' => 'Special Event', // Remove quotes unless they're actually part of the value
                'compare' => 'LIKE'
            )
        )
    );
    
    $custom_query = new WP_Query($args);
    
    // Check if there are posts
    if ($custom_query->have_posts()) :
        echo '<div class="custom-acf-posts-grid">'; // Added opening container
        
        while ($custom_query->have_posts()) : $custom_query->the_post();
            echo '<div class="custom-post-item">'; // Added opening post item container
            
            // Get featured image if it exists
            $image = '';
            if (has_post_thumbnail()) {
                $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            }
            
            // Add featured image if it exists
            if ($image) {
                echo '<div class="custom-post-image">';
                echo '<a href="' . esc_url(get_permalink()) . '"><img src="' . esc_url($image) . '" alt="' . esc_attr(get_the_title()) . '"></a>';
                echo '</div>';
            }
            
            // Post content
            echo '<div class="custom-post-content">';
            echo '<h2 class="custom-post-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h2>';
            echo '</div>'; // End .custom-post-content
            
            echo '</div>'; // End .custom-post-item
        endwhile;
        
        echo '</div>'; // End .custom-acf-posts-grid
        
        // Reset post data
        wp_reset_postdata();
        
    else :
        echo '<p>No events found.</p>';
    endif;
    
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('events', 'events_shortcode');


// **************************** BOARD TEAM MEMBERS **************************
function team_board_shortcode() {
    ob_start(); // Start output buffering
    // Custom query to get your posts
    $args = array(
        'post_type' => 'team', // Change to your custom post type if needed
        'order' => 'ASC',
        'orderby' => 'meta_value', // Sort by the custom field value
        'meta_key' => 'sort', // The ACF field name used for sorting
        'meta_query' => array(
            array(
                'key' => 'group', // The ACF field name
                'value' => 'Board of Directors', // The value you want to match
                'compare' => 'LIKE' // Use LIKE for checkbox fields
            )
        )
    );
	
    $custom_query = new WP_Query($args);
    
    // Check if there are posts
    if ($custom_query->have_posts()) :
        echo '<div class="custom-acf-posts-grid">';
        
        while ($custom_query->have_posts()) : $custom_query->the_post();
            // Get your ACF fields - replace field_name with your actual field names
            $title = get_field('title');
            $image = '';
            if (has_post_thumbnail()) {
                $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            }
            
            // Start building the post container
            echo '<div class="custom-post-item">';
            
            // Add featured image if it exists
            if ($image) {
                echo '<div class="custom-post-image">';
                echo '<a href="' . get_permalink() . '"><img src="' . $image . '" alt="' . get_the_title() . '"></a>';
                echo '</div>';
            }
            // Post content
            echo '<div class="custom-post-content">';
            echo '<h2 class="custom-post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
            // Display your ACF fields
            if ($title) {
                echo $title;
            }
            echo '</div>'; // End .custom-post-content
            echo '</div>'; // End .custom-post-item
        endwhile;

        echo '</div>'; // End .custom-acf-posts-grid

        // Reset post data
        wp_reset_postdata();

    else :
        echo '<p>Oops, error.</p>';
    endif;

    return ob_get_clean(); // Return the buffered content
}
add_shortcode('team_board', 'team_board_shortcode');


// *************************** OFFICE TEAM MEMBERS ****************************
function team_office_shortcode() {
    ob_start(); // Start output buffering
    // Custom query to get your posts
    $args = array(
        'post_type' => 'team', // Change to your custom post type if needed
        'order' => 'ASC',
        'orderby' => 'meta_value', // Sort by the custom field value
        'meta_key' => 'sort', // The ACF field name used for sorting
        'meta_query' => array(
            array(
                'key' => 'group', // The ACF field name
                'value' => 'Office Staff', // The value you want to match
                'compare' => 'LIKE' // Use LIKE for checkbox fields
            )
        )
    );
	
    $custom_query = new WP_Query($args);
    
    // Check if there are posts
    if ($custom_query->have_posts()) :
        echo '<div class="custom-acf-posts-grid">';
        
        while ($custom_query->have_posts()) : $custom_query->the_post();
            // Get your ACF fields - replace field_name with your actual field names
            $title = get_field('title');
            $image = '';
            if (has_post_thumbnail()) {
                $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            }
            
            // Start building the post container
            echo '<div class="custom-post-item">';
            
            // Add featured image if it exists
            if ($image) {
                echo '<div class="custom-post-image">';
                echo '<a href="' . get_permalink() . '"><img src="' . $image . '" alt="' . get_the_title() . '"></a>';
                echo '</div>';
            }
            // Post content
            echo '<div class="custom-post-content">';
            echo '<h2 class="custom-post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
            // Display your ACF fields
            if ($title) {
                echo $title;
            }
            echo '</div>'; // End .custom-post-content
            echo '</div>'; // End .custom-post-item
        endwhile;
        
        echo '</div>'; // End .custom-acf-posts-grid
        
        // Reset post data
        wp_reset_postdata();
        
    else :
        echo '<p>Oops, error.</p>';
    endif;
    
    return ob_get_clean(); // Return the buffered content
}
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

add_action('init', function() {
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

add_shortcode( 'acf_if', function( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'field'    => '',
        'operator' => '=',
        'value'    => '',
        'post_id'  => 0,
    ), $atts, 'acf_if' );

    if ( empty( $a['field'] ) ) return '';

    if ( ! function_exists( 'get_field' ) ) return '';

    $post_id = pom_acf_get_post_id_from_attr( $a );

    $result = pom_acf_eval_condition( $a['field'], $a['operator'], $a['value'], $post_id );

    // split content on [acf_else] if present
    $true_part = $content;
    $false_part = '';
    if ( $content !== null && stripos( $content, '[acf_else]' ) !== false ) {
        $parts = preg_split( '/\\[acf_else\\]/i', $content, 2 );
        $true_part = isset( $parts[0] ) ? $parts[0] : '';
        $false_part = isset( $parts[1] ) ? $parts[1] : '';
    }

    if ( $result ) {
        return do_shortcode( $true_part );
    } else {
        return do_shortcode( $false_part );
    }
});


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

        $name = $term->name;
        $slug = $term->slug;
        $link = get_term_link($term);

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
        'field'   => '',
        'post_id' => 0,
        'op'      => '=',
        'value'   => '',
    ), $atts, 'acf_if' );

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
        wp_enqueue_style( 'fancybox-css', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css', array(), null );
        wp_enqueue_script( 'fancybox-js', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js', array(), null, true );
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
 * ACF field registration (inc/acf-fields.php) and the legacy redirect handler
 * (inc/redirects.php) are intentionally NOT loaded here. ACF fields are owned
 * by the ACF plugin, and the redirect module is opt-in.
 */
require_once get_stylesheet_directory() . '/inc/enqueue.php';
