<?php
//test
/**
 * Template for displaying single Pets posts
 */

get_header();
?>
<!-- Single Pet Post -->
<div id="main-content">
  <div class="et-l et-l--body">
    <div class="et_builder_inner_content et_pb_gutters3">

      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <?php
          // Get ACF fields
          $pet_description = get_field('pet_description');
          $sex = get_field('sex');
          $age = get_field('age');
          $status = get_field('status');
		  $foster_start_date = get_field('foster_start_date');
		  $foster_end_date = get_field('foster_end_date');
        ?>

        <!-- Header Section: Title + Buttons -->
        <div class="et_pb_section et_section_regular">
          <div class="et_pb_row et_pb_row_3-5_1-5_1-5">

            <!-- Column 1: Pet Name -->
            <div class="et_pb_column et_pb_column_3_5">
              <div class="et_pb_module et_pb_post_title et_pb_bg_layout_light et_pb_text_align_left">
                <div class="et_pb_title_container">
                  <h1 class="entry-title"><?php the_title(); ?>single-pet.php</h1>
                </div>
              </div>
            </div>

            <!-- Column 2: Adopt Button -->
            <div class="et_pb_column et_pb_column_1_5">
              <div class="et_pb_button_module_wrapper">
                <a class="et_pb_button et_pb_bg_layout_light"
                   href="/adoption-questionnaire/?dogname=<?php echo urlencode( get_the_title() ); ?>">
                  Adopt
                </a>
              </div>
            </div>

            <!-- Column 3: Sponsor Button (customize link if needed) -->
            <div class="et_pb_column et_pb_column_1_5 et-last-child">
              <div class="et_pb_button_module_wrapper">
                <a class="et_pb_button et_pb_bg_layout_light" href="#">
                  Sponsor
                </a>
              </div>
            </div>

          </div>
        </div>

        <!-- Pet Profile Content -->
        <div class="et_pb_section et_section_regular">
          <div class="et_pb_row" style="margin-top:0; padding-top:0;">

            <!-- Left Column: Image & FancyBox (Optional) -->
            <div class="et_pb_column et_pb_column_3_5"  style="margin-top:0; padding-top:0;">

              <!-- Featured thumbnail -->
<?php if ( has_post_thumbnail() ) : ?>
  <div class="et_pb_image">
    <span class="et_pb_image_wrap">
      <?php
        $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        echo '<a data-fancybox="gallery" href="' . esc_url( $thumb_url ) . '">';
        the_post_thumbnail( 'large' );
        echo '</a>';
      ?>
    </span>
  </div>
<?php endif; ?>

<!-- Display additional gallery images from ACF -->
<?php
$gallery = get_field('photo_gallery');
if ( $gallery ) : ?>
  <?php echo do_shortcode('[acf_gallery]');?>
<?php endif; ?>

            </div>

            <!-- Right Column: Pet Details -->
            <div class="et_pb_column et_pb_column_2_5 et-last-child">

              <!-- Top: Quick Info -->
              <div class="et_pb_module et_pb_text">
                <div class="et_pb_text_inner">
					<?php echo do_shortcode('[pet_age_sex_weight_shortcode]');?>
                </div>
              </div>

              <!-- Full Bio / Description -->
              <?php if ( $pet_description ) : ?>
                <div class="et_pb_module et_pb_text">
                  <div class="et_pb_text_inner">
					  <?php if ( $foster_start_date || $foster_end_date ) :?>
					  <b>Foster Needed: <?php echo esc_html( $foster_start_date )." - ".esc_html( $foster_end_date ) ; ?></b>
					  <?php endif; ?>
					  <?php if (is_array($needs_foster) && in_array('Foster Needed', $needs_foster)) : ?>
    <b>Foster Needed</b>
<?php endif; ?>
                    <?php echo wpautop( wp_kses_post( $pet_description ) ); ?>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Previous Adoptable Pet Button -->
              <?php
              $current_post_date = get_the_date( 'Y-m-d H:i:s' );
              $args = array(
                'post_type' => 'pets',
                'posts_per_page' => 1,
                'order' => 'DESC',
                'orderby' => 'date',
                'date_query' => array(
                  array(
                    'before' => $current_post_date,
                    'inclusive' => false,
                  )
                ),
                'meta_query' => array(
                  array(
                    'key' => 'status',
                    'value' => 'Adoptable',
                    'compare' => '='
                  )
                )
              );
              $prev_pet_query = new WP_Query( $args );
              if ( $prev_pet_query->have_posts() ) :
                $prev_pet_query->the_post(); ?>
                <div class="et_pb_module et_pb_text et_pb_text_align_left">
                  <div class="et_pb_text_inner">
                    <a class="et_pb_button" href="<?php the_permalink(); ?>">
                      ← Previous Adoptable Pet: <?php the_title(); ?>
                    </a>
                  </div>
                </div>
              <?php
              wp_reset_postdata();
              endif;
              ?>

              <!-- Adoption Prompt Text -->
              <div class="et_pb_module et_pb_text">
                <div class="et_pb_text_inner">
                  <p><strong>Interested in <?php the_title(); ?>?</strong><br>
                  Press the <strong>Adopt</strong> button to fill out our online form, and we’ll get back to you!</p>
                </div>
              </div>

            </div>
          </div>
        </div>

      <?php endwhile; endif; ?>

    </div> <!-- /.et_builder_inner_content -->
  </div> <!-- /.et-l -->
</div> <!-- /#main-content -->

<!-- FancyBox dependencies -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css">
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    Fancybox.bind('[data-fancybox="gallery"]', {});
  });
</script>

<?php get_footer(); ?>