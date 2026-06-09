<?php
/*
Template Name: Pet Profile
Description: Display pet details.
*/
get_header();

// Start the Divi wrapper
echo '<!-- Pet Profile Page Template -->';
echo '<div id="main-content">';
echo '<div class="container">';
echo '<div id="content-area" class="clearfix">';
echo '<div class="et_pb_column">';

// Setup the WP Query
$args = array(
    'post_type' => 'pets',
    'posts_per_page' => 1,
    'orderby' => 'date',
    'order' => 'DESC'
);

$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        // Start blog item
        echo '<article class="et_pb_post">';
        
        // Check if there's a featured image
        if (has_post_thumbnail()) {
            echo '<div class="et_pb_image_container">';
            echo '<a href="' . get_permalink() . '">';
            the_post_thumbnail('large');
            echo '</a>';
            echo '</div>';
        }

        // Post title
        echo '<h2 class="entry-title">PetProfilePageTemplate';
        echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
        echo '</h2>';

        // Get ACF fields - modify these field names to match your ACF setup
        
        $pet_description = get_field('pet_description');
		$sex = get_field('sex');

        // Display ACF fields
        

        if ($pet_description) {
            echo '<div class="custom-category">' . esc_html($pet_description) . '</div>';
        } 
		
		if ($sex) {
            echo '<h3 class="custom-subtitle">' . esc_html($sex) . '</h3>';
        }

        // Read more button in Divi style
        echo '<a href="' . get_permalink() . '" class="more-link et_pb_button">Read More</a>';
        
        echo '</article>';

    endwhile;

    // Pagination
    echo '<div class="pagination">';
    echo paginate_links(array(
        'total' => $query->max_num_pages,
        'prev_text' => __('Previous'),
        'next_text' => __('Next')
    ));
    echo '</div>';

else :
    echo '<p>No posts found.</p>';
endif;

wp_reset_postdata();

// Close Divi wrappers
echo '</div>'; // .et_pb_column
echo '</div>'; // #content-area
echo '</div>'; // .container
echo '</div>'; // #main-content

get_footer();
?>
