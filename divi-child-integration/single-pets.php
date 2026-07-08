<?php
/**
 * single-pets.php
 * PDP with ACF flexible sections + admin Logic Builder
 * Advanced layout engine: supports section_position (left,right,full) and row_mode (merge_with_previous,start_new_row)
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();


function pom_evaluate_rule($rule, $derived, $post_id)
{
    $field = isset($rule['field_name']) ? trim($rule['field_name']) : '';
    $op = isset($rule['operator']) ? $rule['operator'] : '=';
    $value = isset($rule['value']) ? $rule['value'] : '';

    if ($field === '') {
        return false;
    }

    if (array_key_exists($field, $derived)) {
        $left = $derived[$field];
    } else {
        $left = get_field($field, $post_id);
    }

    if ($left === null || $left === false || $left === '') {
        if ($op === 'empty')
            return true;
        if ($op === 'not_empty')
            return false;
        if (in_array($op, array('>', '<'), true)) {
            return false;
        }
    }

    switch ($op) {
        case 'empty':
            return empty($left);
        case 'not_empty':
            return !empty($left);
        case 'contains':
            if (is_array($left)) {
                return in_array($value, $left, true);
            }
            return (stripos((string) $left, (string) $value) !== false);
        case '=':
            if (is_numeric($left) && is_numeric($value)) {
                return floatval($left) == floatval($value);
            }
            if (is_array($left)) {
                return in_array($value, $left, true);
            }
            return ((string) $left === (string) $value);
        case '!=':
            if (is_numeric($left) && is_numeric($value)) {
                return floatval($left) != floatval($value);
            }
            if (is_array($left)) {
                return !in_array($value, $left, true);
            }
            return ((string) $left !== (string) $value);
        case '>':
            if (!is_numeric($left) || !is_numeric($value))
                return false;
            return floatval($left) > floatval($value);
        case '<':
            if (!is_numeric($left) || !is_numeric($value))
                return false;
            return floatval($left) < floatval($value);
        default:
            return false;
    }
}

function pom_evaluate_group($group, $derived, $post_id)
{
    $op = isset($group['group_operator']) ? strtoupper($group['group_operator']) : 'AND';
    $rules = isset($group['rules']) && is_array($group['rules']) ? $group['rules'] : array();

    if (empty($rules)) {
        return false;
    }

    if ($op === 'OR') {
        foreach ($rules as $r) {
            if (pom_evaluate_rule($r, $derived, $post_id)) {
                return true;
            }
        }
        return false;
    } else {
        foreach ($rules as $r) {
            if (!pom_evaluate_rule($r, $derived, $post_id)) {
                return false;
            }
        }
        return true;
    }
}

function pom_get_derived_fields($post_id)
{
    $derived = array();

    $gallery = get_field('photo_gallery', $post_id);
    $derived['photo_count'] = is_array($gallery) ? count($gallery) : 0;
    $derived['has_gallery'] = $derived['photo_count'] > 0;

    $video = get_field('youtube_video', $post_id);
    $derived['has_video'] = !empty($video);

    $foster_start = get_field('foster_start_date', $post_id);
    $foster_end = get_field('foster_end_date', $post_id);
    $derived['has_foster_dates'] = (!empty($foster_start) || !empty($foster_end));

    $status = get_field('status', $post_id);
    $derived['is_adoptable'] = false;
    $derived['is_foster_needed'] = false;
    $derived['is_hospice'] = false;
    $derived['is_pending'] = false;

    if (is_array($status)) {
        $derived['is_adoptable'] = in_array('Adoptable', $status, true);
        $derived['is_foster_needed'] = in_array('Foster Needed', $status, true);
        $derived['is_hospice'] = in_array('Hospice', $status, true);
        $derived['is_pending'] = in_array('Adoption Pending', $status, true);
    } else {
        $s = (string) $status;
        $derived['is_adoptable'] = (stripos($s, 'Adoptable') !== false);
        $derived['is_foster_needed'] = (stripos($s, 'Foster Needed') !== false);
        $derived['is_hospice'] = (stripos($s, 'Hospice') !== false);
        $derived['is_pending'] = (stripos($s, 'Adoption Pending') !== false);
    }

    $age = get_field('age', $post_id);
    $derived['age'] = is_numeric($age) ? intval($age) : null;
    $derived['is_senior'] = (is_numeric($age) && intval($age) >= 10);

    return $derived;
}

function pom_should_show_section($section_name, $post_id)
{
    $groups = get_field('pdp_logic', $post_id);
    if (empty($groups) || !is_array($groups)) {
        return true;
    }

    $derived = pom_get_derived_fields($post_id);

    usort($groups, function ($a, $b) {
        $pa = isset($a['priority']) ? intval($a['priority']) : 0;
        $pb = isset($b['priority']) ? intval($b['priority']) : 0;
        return $pb - $pa;
    });

    foreach ($groups as $group) {
        $targets = isset($group['target_sections']) ? (array) $group['target_sections'] : array();
        if (!empty($targets) && !in_array($section_name, $targets, true)) {
            continue;
        }

        $group_matches = pom_evaluate_group($group, $derived, $post_id);

        if ($group_matches) {
            $action = isset($group['action']) ? $group['action'] : 'show';
            return ($action === 'show');
        }
    }

    return true;
}


function render_module_fragment($layout, $post_id)
{
    ob_start();

    switch ($layout) {
        case 'gallery_section':
            if (has_post_thumbnail($post_id)) {
                $thumb_html = get_the_post_thumbnail($post_id, 'large');
                $thumb_url  = get_the_post_thumbnail_url($post_id, 'large');
                ?>
                <div class="pom-image">
                    <a data-fancybox="gallery" href="<?php echo esc_url($thumb_url); ?>">
                        <?php echo $thumb_html; ?>
                    </a>
                </div>
                <?php
            }
            $gallery = get_field('photo_gallery', $post_id);
            if ($gallery && is_array($gallery)) {
                echo do_shortcode('[acf_gallery]');
            }
            break;

        case 'quick_info_section':
            ?>
            <div class="pom-content">
                <?php echo do_shortcode('[pet_age_sex_weight_shortcode]'); ?>
            </div>
            <?php
            break;

        case 'bio_section':
            $pet_description = get_field('pet_description', $post_id);
            $foster_start    = get_field('foster_start_date', $post_id);
            $foster_end      = get_field('foster_end_date', $post_id);
            $status          = get_field('status', $post_id);
            $needs_foster    = (is_array($status) ? in_array('Foster Needed', $status, true) : (stripos((string) $status, 'Foster Needed') !== false));

            if ($pet_description) {
                ?>
                <div class="pom-content">
                    <?php if ($needs_foster): ?>
                        <b><?php echo esc_html__('Foster Needed', 'pom'); ?>
                            <?php if ($foster_start || $foster_end): ?>
                                    <?php echo esc_html__(':', 'pom'); ?>
                                <?php echo esc_html($foster_start ?: ''); ?>                         <?php echo ($foster_start && $foster_end) ? ' - ' : ''; ?>
                                <?php echo esc_html($foster_end ?: ''); ?>
                            <?php endif; ?>
                        </b><br /><br />
                    <?php endif; ?>
                    <?php echo wpautop(wp_kses_post($pet_description)); ?>
                </div>
                <?php
            }
            break;

        case 'foster_banner_section':
            $foster_start = get_field('foster_start_date', $post_id);
            $foster_end   = get_field('foster_end_date', $post_id);
            if ($foster_start || $foster_end) {
                ?>
                <div class="pom-content">
                    <strong><?php echo esc_html__('Foster Needed:', 'pom'); ?></strong>
                    <?php echo esc_html($foster_start ?: ''); ?><?php echo ($foster_start && $foster_end) ? ' - ' : ''; ?><?php echo esc_html($foster_end ?: ''); ?>
                </div>
                <?php
            }
            break;

        case 'video_section':
            $video = get_field('youtube_video', $post_id);
            if ($video) {
                ?>
                <div class="pom-video">
                    <?php echo $video; ?>
                </div>
                <?php
            }
            break;

        case 'sponsor_section':
            $sponsor = get_field('sponsored_by', $post_id);
            if ($sponsor) {
                ?>
                <div class="pom-content">
                    <strong><?php echo esc_html__('Sponsored By:', 'pom'); ?> <?php echo esc_html($sponsor); ?></strong>
                </div>
                <?php
            }
            break;

        case 'hospice_section':
            $status     = get_field('status', $post_id);
            $is_hospice = is_array($status) ? in_array('Hospice', $status, true) : (stripos((string) $status, 'Hospice') !== false);
            if ($is_hospice) {
                ?>
                <div class="pom-content">
                    <strong><?php echo esc_html__('Hospice Care', 'pom'); ?>.</strong>
                    <?php echo esc_html__('Please contact the rescue for special care details.', 'pom'); ?>
                </div>
                <?php
            }
            break;

        case 'previous_pet_section':
            // Browse All Pets button moved into pom-buttons via header_section_buttons
            break;

        case 'adoption_prompt_section':
            ?>
            <div class="pom-content">
                <p><strong><?php echo esc_html__('Interested in', 'pom'); ?>
                        <?php echo esc_html(get_the_title($post_id)); ?>?</strong><br>
                    <?php echo esc_html__('Press the', 'pom'); ?> <strong><?php echo esc_html__('Adopt', 'pom'); ?></strong>
                    <?php echo esc_html__('button to fill out our online form, and we&#8217;ll get back to you!', 'pom'); ?></p>
            </div>
            <?php
            break;

        case 'custom_html_section':
            $custom_html = function_exists('get_sub_field') ? get_sub_field('custom_html') : get_field('custom_html', $post_id);
            if ($custom_html) {
                ?>
                <div class="pom-content">
                    <?php echo do_shortcode(wp_kses_post($custom_html)); ?>
                </div>
                <?php
            }
            break;

        default:
            break;
    }

    return ob_get_clean();
}


function pom_flush_row_buffers(&$left_buf, &$right_buf)
{
    if (empty($left_buf) && empty($right_buf)) {
        return;
    }

    echo '<div class="pom-section">';
    echo '<div class="pom-row pom-row--two-col">';

    echo '<div class="pom-col-main">';
    foreach ($left_buf as $html) {
        echo $html;
    }
    echo '</div>';

    echo '<div class="pom-col-side">';
    foreach ($right_buf as $html) {
        echo $html;
    }
    echo '</div>';

    echo '</div>';
    echo '</div>';

    $left_buf  = array();
    $right_buf = array();
}


?>
<div id="main-content">
    <div class="et-l et-l--body">
        <div class="et_builder_inner_content et_pb_gutters3">

            <?php if (have_posts()):
                while (have_posts()):
                    the_post();

                    $post_id = get_the_ID();

                    $left_buffer  = array();
                    $right_buffer = array();

                    if (function_exists('have_rows') && have_rows('pdp_sections', $post_id)):

                        while (have_rows('pdp_sections', $post_id)):
                            the_row();

                            $layout = get_row_layout();

                            $enabled = get_sub_field('enable_section');
                            if ($enabled === null) {
                                $enabled = get_sub_field('enable');
                            }

                            if ($enabled === '0' || $enabled === 0 || $enabled === false || $enabled === '') {
                                continue;
                            }

                            if (function_exists('pom_should_show_section') && !pom_should_show_section($layout, $post_id)) {
                                continue;
                            }

                            $position = get_sub_field('section_position');
                            $row_mode = get_sub_field('row_mode');

                            if (!in_array($position, array('left', 'right', 'full'), true)) {
                                $position = 'full';
                            }
                            if (!in_array($row_mode, array('merge_with_previous', 'start_new_row'), true)) {
                                $row_mode = 'merge_with_previous';
                            }

                            if ($layout === 'header_section') {
                                pom_flush_row_buffers($left_buffer, $right_buffer);

                                $status     = get_field('status', $post_id);
                                $is_adopted = is_array($status) ? in_array('Adopted', $status, true) : (stripos((string) $status, 'Adopted') !== false);

                                $adopt_link = esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/adoption-questionnaire/')));
                                $adopt_html = $is_adopted ? '' : '<a class="et_pb_button" href="' . $adopt_link . '">' . esc_html__('Adopt', 'pom') . '</a>';
                                $sponsor_html = '<a class="et_pb_button" href="' . esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/sponsor-a-dog/'))) . '">' . esc_html__('Sponsor', 'pom') . '</a>';

                                echo '<div class="pom-section pom-section--header">';
                                echo '<div class="pom-row pom-row--header">';

                                echo '<div class="pom-col-title">';
                                echo '<h1 class="entry-title">' . esc_html(get_the_title($post_id)) . '</h1>';
                                echo '</div>';

                                echo '<div class="pom-col-btn">';
                                echo $adopt_html;
                                echo '</div>';

                                echo '<div class="pom-col-btn">';
                                echo $sponsor_html;
                                echo '</div>';

                                echo '</div>';
                                echo '</div>';

                                continue;
                            }

                            if ($layout === 'header_section_buttons') {
                                $status     = get_field('status', $post_id);
                                $is_adopted = is_array($status) ? in_array('Adopted', $status, true) : (stripos((string) $status, 'Adopted') !== false);

                                $adopt_link   = esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/adoption-questionnaire/')));
                                $adopt_html   = $is_adopted ? '' : '<a class="et_pb_button" href="' . $adopt_link . '">' . esc_html__('Adopt', 'pom') . '</a>';
                                $sponsor_html = '<a class="et_pb_button" href="' . esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/sponsor-a-dog/'))) . '">' . esc_html__('Sponsor', 'pom') . '</a>';

                                $right_buffer[] = '<div class="pom-buttons"><div class="pom-buttons-row">' . $adopt_html . $sponsor_html . '</div><div class="pom-buttons-row pom-buttons-row--browse"><a class="et_pb_button" href="/adopt/">Browse All Pets</a></div></div>';
                                continue;
                            }

                            $fragment = render_module_fragment($layout, $post_id);

                            if ($position === 'full') {
                                pom_flush_row_buffers($left_buffer, $right_buffer);

                                echo '<div class="pom-section">';
                                echo '<div class="pom-row pom-row--full">';
                                echo '<div class="pom-col-main">';
                                echo $fragment;
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                continue;
                            }

                            if ($row_mode === 'start_new_row') {
                                pom_flush_row_buffers($left_buffer, $right_buffer);
                            }

                            if ($position === 'left') {
                                $left_buffer[] = $fragment;
                            } else {
                                $right_buffer[] = $fragment;
                            }

                        endwhile;

                        pom_flush_row_buffers($left_buffer, $right_buffer);

                    else:

                        $default_sections = [
                            ['layout' => 'header_section',          'position' => 'full',  'row_mode' => 'start_new_row'],
                            ['layout' => 'gallery_section',         'position' => 'left',  'row_mode' => 'merge_with_previous'],
                            ['layout' => 'quick_info_section',      'position' => 'right', 'row_mode' => 'merge_with_previous'],
                            ['layout' => 'bio_section',             'position' => 'right', 'row_mode' => 'merge_with_previous'],
                            ['layout' => 'foster_banner_section',   'position' => 'right', 'row_mode' => 'merge_with_previous'],
                            ['layout' => 'video_section',           'position' => 'left',  'row_mode' => 'merge_with_previous'],
                            ['layout' => 'sponsor_section',         'position' => 'right', 'row_mode' => 'merge_with_previous'],
                            ['layout' => 'hospice_section',         'position' => 'right', 'row_mode' => 'merge_with_previous'],
                            ['layout' => 'previous_pet_section',    'position' => 'right', 'row_mode' => 'merge_with_previous'],
                            ['layout' => 'adoption_prompt_section', 'position' => 'right', 'row_mode' => 'merge_with_previous'],
                            ['layout' => 'header_section_buttons',  'position' => 'full',  'row_mode' => 'start_new_row'],
                        ];

                        $left_buffer  = [];
                        $right_buffer = [];

                        foreach ($default_sections as $sec) {
                            $layout   = $sec['layout'];
                            $position = $sec['position'];
                            $row_mode = $sec['row_mode'];

                            if (function_exists('pom_should_show_section') && !pom_should_show_section($layout, $post_id)) {
                                continue;
                            }

                            if ($layout === 'header_section') {
                                pom_flush_row_buffers($left_buffer, $right_buffer);

                                $status     = get_field('status', $post_id);
                                $is_adopted = is_array($status) ? in_array('Adopted', $status, true) : (stripos((string) $status, 'Adopted') !== false);

                                $adopt_link = esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/adoption-questionnaire/')));
                                $adopt_html = $is_adopted ? '' : '<a class="et_pb_button" href="' . $adopt_link . '">Adopt</a>';

                                echo '<div class="pom-section pom-section--header">';
                                echo '<div class="pom-row pom-row--header">';

                                echo '<div class="pom-col-title">';
                                echo '<h1 class="entry-title">' . esc_html(get_the_title($post_id)) . '</h1>';
                                echo '</div>';

                                echo '<div class="pom-col-btn">';
                                echo $adopt_html;
                                echo '</div>';

                                echo '<div class="pom-col-btn">';
                                echo '<a class="et_pb_button" href="' . esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/sponsor-a-dog/'))) . '">Sponsor</a>';
                                echo '</div>';

                                echo '</div>';
                                echo '</div>';
                                continue;
                            }

                            if ($layout === 'header_section_buttons') {
                                $status     = get_field('status', $post_id);
                                $is_adopted = is_array($status) ? in_array('Adopted', $status, true) : (stripos((string) $status, 'Adopted') !== false);

                                $adopt_link   = esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/adoption-questionnaire/')));
                                $adopt_html   = $is_adopted ? '' : '<a class="et_pb_button" href="' . $adopt_link . '">Adopt</a>';
                                $sponsor_html = '<a class="et_pb_button" href="' . esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/sponsor-a-dog/'))) . '">Sponsor</a>';

                                $right_buffer[] = '<div class="pom-buttons"><div class="pom-buttons-row">' . $adopt_html . $sponsor_html . '</div><div class="pom-buttons-row pom-buttons-row--browse"><a class="et_pb_button" href="/adopt/">Browse All Pets</a></div></div>';
                                continue;
                            }

                            $fragment = render_module_fragment($layout, $post_id);

                            if ($position === 'full') {
                                pom_flush_row_buffers($left_buffer, $right_buffer);
                                echo '<div class="pom-section">';
                                echo '<div class="pom-row pom-row--full">';
                                echo '<div class="pom-col-main">' . $fragment . '</div>';
                                echo '</div>';
                                echo '</div>';
                                continue;
                            }

                            if ($row_mode === 'start_new_row') {
                                pom_flush_row_buffers($left_buffer, $right_buffer);
                            }

                            if ($position === 'left') {
                                $left_buffer[] = $fragment;
                            } else {
                                $right_buffer[] = $fragment;
                            }
                        }

                        pom_flush_row_buffers($left_buffer, $right_buffer);

                    endif;

                endwhile;
            endif; ?>

        </div> <!-- /.et_builder_inner_content -->
    </div> <!-- /.et-l -->
</div> <!-- /#main-content -->

<?php get_footer(); ?>
