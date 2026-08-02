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



/**
 * The redesigned dog-page header: back link, status badge, big serif name,
 * vitals line, and the Adopt / Sponsor actions as design-system buttons.
 * Used by every header render path (logic builder and fallback).
 */
function pom_pdp_header_html($post_id)
{
    $name = get_the_title($post_id);
    list($badge_class, $badge_label) = function_exists('pom_pet_badge') ? pom_pet_badge($post_id) : array('', '');

    $status      = get_field('status', $post_id);
    $status_arr  = is_array($status) ? $status : array_filter(array_map('trim', explode(',', (string) $status)));
    $is_adopted  = in_array('Adopted', $status_arr, true);
    $is_courtesy = in_array('Courtesy Listing', $status_arr, true);
    $is_hospice  = in_array('Hospice', $status_arr, true);

    $age    = get_field('age', $post_id);
    $sex    = function_exists('pom_acf_sex_display') ? pom_acf_sex_display($post_id) : '';
    $weight = get_field('weight', $post_id);
    $breed  = get_field('looks_like', $post_id);
    $bits   = array();
    if ($age !== '' && $age !== null && $age !== false) { $bits[] = is_numeric($age) ? '~' . $age . ' yrs' : $age; }
    if ($sex) { $bits[] = $sex; }
    if ($weight) { $bits[] = $weight . ' lb'; }
    if ($breed) { $bits[] = $breed; }

    $out  = '<div class="pom-section pom-section--header"><header class="pdp-header">';
    $out .= '<a class="pdp-back" href="' . esc_url(home_url('/adopt/')) . '">&larr; All adoptable dogs</a>';
    // Name and vitals on the left, the actions on the right of the same line.
    $out .= '<div class="pdp-header-row"><div class="pdp-header-left">';
    if ($badge_label) {
        $out .= '<span class="pdp-badge pdp-badge--' . esc_attr($badge_class) . '">' . esc_html($badge_label) . '</span>';
    }
    $out .= '<h1 class="pdp-name">' . esc_html($name) . '</h1>';
    if ($bits) {
        $out .= '<p class="pdp-meta">' . esc_html(implode(' · ', $bits)) . '</p>';
    }
    $out .= '</div>';
    $out .= '<div class="pdp-ctas">';
    // Live-site contract: adopted, hospice, and courtesy dogs are not offered
    // an Adopt button (courtesy adoptions go through the listed contact, and
    // hospice dogs live out their days in POMDR care).
    if (!$is_adopted && !$is_courtesy && !$is_hospice) {
        $out .= '<a class="btn btn-primary" href="' . esc_url(add_query_arg('dogname', $name, home_url('/adoption-questionnaire/'))) . '">Adopt ' . esc_html($name) . '</a>';
    }
    if (!$is_courtesy) {
        $out .= '<a class="btn btn-purple" href="' . esc_url(add_query_arg('dogname', $name, home_url('/sponsor-a-dog/'))) . '">Sponsor</a>';
    }
    $out .= '</div></div>';

    // Courtesy listings: the listed person is the contact, not POMDR.
    if ($is_courtesy) {
        $contact = trim((string) get_post_meta($post_id, 'courtesy_contact', true));
        $posted  = trim((string) get_post_meta($post_id, 'courtesy_posted', true));
        $updated = trim((string) get_post_meta($post_id, 'courtesy_updated', true));
        $out .= '<div class="pdp-courtesy">';
        $out .= '<p class="pdp-courtesy-note"><strong>Courtesy listing.</strong> This dog is listed on behalf of their current guardian or another rescue; adoption is arranged directly with the contact below, not through POMDR.</p>';
        if ($contact !== '') { $out .= '<p class="pdp-courtesy-contact">' . esc_html($contact) . '</p>'; }
        if ($posted !== '' || $updated !== '') {
            $bits2 = array();
            if ($posted !== '')  { $bits2[] = 'Posted ' . $posted; }
            if ($updated !== '') { $bits2[] = 'Updated ' . $updated; }
            $out .= '<p class="pdp-courtesy-dates">' . esc_html(implode(' · ', $bits2)) . '</p>';
        }
        $out .= '</div>';
    }
    $out .= '</header></div>';
    return $out;
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
                    <a data-fancybox="gallery" href="<?php echo esc_url($thumb_url); ?>" aria-label="<?php echo esc_attr('View a larger photo of ' . get_the_title($post_id)); ?>">
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
            // The 3-5 highlight bullets lead the bio (leadership spec).
            if (function_exists('pom_pet_highlights_html')) {
                echo pom_pet_highlights_html($post_id);
            }
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
            <div class="pom-content pdp-prompt">
                <p><strong><?php echo esc_html(get_the_title($post_id)); ?></strong>
                    <?php echo esc_html__('could be your new old best friend. Press Adopt to fill out our online form, and a real person will get back to you.', 'pom'); ?></p>
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
<main id="main-content">
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

                                $status      = get_field('status', $post_id);
                                $status_arr  = is_array($status) ? $status : array_filter(array_map('trim', explode(',', (string) $status)));
                                $is_adopted  = in_array('Adopted', $status_arr, true) || in_array('Courtesy Listing', $status_arr, true) || in_array('Hospice', $status_arr, true);

                                $adopt_link = esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/adoption-questionnaire/')));
                                $adopt_html = $is_adopted ? '' : '<a class="et_pb_button" href="' . $adopt_link . '">' . esc_html__('Adopt', 'pom') . '</a>';
                                $sponsor_html = in_array('Courtesy Listing', $status_arr, true) ? '' : '<a class="btn btn-purple" href="' . esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/sponsor-a-dog/'))) . '">' . esc_html__('Sponsor', 'pom') . '</a>';

                                echo pom_pdp_header_html($post_id);

                                continue;
                            }

                            if ($layout === 'header_section_buttons') {
                                $status      = get_field('status', $post_id);
                                $status_arr  = is_array($status) ? $status : array_filter(array_map('trim', explode(',', (string) $status)));
                                $is_adopted  = in_array('Adopted', $status_arr, true) || in_array('Courtesy Listing', $status_arr, true) || in_array('Hospice', $status_arr, true);

                                $adopt_link   = esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/adoption-questionnaire/')));
                                $adopt_html   = $is_adopted ? '' : '<a class="btn btn-primary" href="' . $adopt_link . '">' . esc_html__('Adopt', 'pom') . ' ' . esc_html(get_the_title($post_id)) . '</a>';
                                $sponsor_html = in_array('Courtesy Listing', $status_arr, true) ? '' : '<a class="btn btn-purple" href="' . esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/sponsor-a-dog/'))) . '">' . esc_html__('Sponsor', 'pom') . '</a>';

                                $right_buffer[] = '<div class="pom-buttons"><div class="pom-buttons-row">' . $adopt_html . $sponsor_html . '</div><div class="pom-buttons-row pom-buttons-row--browse"><a class="btn btn-outline" href="' . esc_url(home_url('/adopt/')) . '">Browse all dogs</a></div></div>';
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

                        // Single-column bio flow (2026-08-01): the photo takes the
                        // full column, the info card sits below at the same width
                        // (vitals a step larger than body copy), then a click-through
                        // gallery with the video as its last slide.
                        $status      = get_field('status', $post_id);
                        $status_arr  = is_array($status) ? $status : array_filter(array_map('trim', explode(',', (string) $status)));
                        $no_adopt    = in_array('Adopted', $status_arr, true) || in_array('Courtesy Listing', $status_arr, true) || in_array('Hospice', $status_arr, true);
                        $is_courtesy = in_array('Courtesy Listing', $status_arr, true);
                        $is_hospice  = in_array('Hospice', $status_arr, true);

                        echo '<div class="pdp-flow">';
                        echo pom_pdp_header_html($post_id);

                        echo '<div class="pdp-cols"><div class="pdp-media">';
                        if (has_post_thumbnail($post_id)) {
                            $full_url = get_the_post_thumbnail_url($post_id, 'full');
                            echo '<figure class="pdp-photo"><a data-fancybox="gallery" href="' . esc_url($full_url) . '" aria-label="' . esc_attr('View a larger photo of ' . get_the_title($post_id)) . '">';
                            echo get_the_post_thumbnail($post_id, 'large');
                            echo '</a></figure>';
                        }

                        // Gallery + the video as the last slide (Fancybox plays
                        // YouTube links natively; the tile shows a play badge).
                        $gallery   = get_field('photo_gallery', $post_id);
                        $video_url = trim((string) get_post_meta($post_id, 'youtube_video', true));
                        $video_id  = '';
                        if ($video_url && preg_match('~(?:youtu\.be/|v=|embed/|shorts/)([A-Za-z0-9_-]{6,20})~', $video_url, $vm)) {
                            $video_id = $vm[1];
                        }
                        if ((is_array($gallery) && $gallery) || $video_id !== '') {
                            echo '<div class="pdp-gallery">';
                            if (is_array($gallery)) {
                                foreach ($gallery as $image) {
                                    if (!is_array($image) || empty($image['ID'])) { continue; }
                                    echo '<a href="' . esc_url($image['url']) . '" data-fancybox="gallery" data-caption="' . esc_attr($image['caption']) . '">';
                                    echo wp_get_attachment_image($image['ID'], 'medium', false, ['class' => 'pdp-thumb']);
                                    echo '</a>';
                                }
                            }
                            if ($video_id !== '') {
                                echo '<a class="pdp-video-tile" href="' . esc_url($video_url) . '" data-fancybox="gallery" data-caption="' . esc_attr(get_the_title($post_id)) . '" aria-label="' . esc_attr('Watch a video of ' . get_the_title($post_id)) . '">';
                                echo '<img class="pdp-thumb" src="' . esc_url('https://i.ytimg.com/vi/' . $video_id . '/hqdefault.jpg') . '" alt="" loading="lazy">';
                                echo '<span class="pdp-play" aria-hidden="true"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>';
                                echo '</a>';
                            }
                            echo '</div>';
                        } elseif ($video_url !== '') {
                            $video = get_field('youtube_video', $post_id);
                            if ($video) { echo '<div class="pom-video">' . $video . '</div>'; }
                        }

                        echo '</div><div class="pdp-info">';
                        echo '<section class="pdp-card">';
                        echo '<div class="pdp-vitals">' . do_shortcode('[pet_age_sex_weight_shortcode]') . '</div>';
                        if (function_exists('pom_pet_highlights_html')) { echo pom_pet_highlights_html($post_id); }
                        $foster_start = get_field('foster_start_date', $post_id);
                        $foster_end   = get_field('foster_end_date', $post_id);
                        if (in_array('Foster Needed', $status_arr, true)) {
                            $range = trim(($foster_start ?: '') . (($foster_start && $foster_end) ? ' - ' : '') . ($foster_end ?: ''));
                            echo '<p class="pdp-note"><strong>' . esc_html__('Foster Needed', 'pom') . ($range !== '' ? ':' : '') . '</strong> ' . esc_html($range) . '</p>';
                        }
                        $pet_description = get_field('pet_description', $post_id);
                        if ($pet_description) { echo '<div class="pdp-bio">' . wpautop(wp_kses_post($pet_description)) . '</div>'; }
                        $sponsor = get_field('sponsored_by', $post_id);
                        // Some synced writeups already end with a Sponsored By line; skip the field note then.
                        if ($sponsor && stripos((string) $pet_description, 'sponsored by') === false) {
                            echo '<p class="pdp-note"><strong>' . esc_html__('Sponsored By:', 'pom') . '</strong> ' . esc_html($sponsor) . '</p>';
                        }
                        if ($is_hospice) { echo '<p class="pdp-note"><strong>' . esc_html__('Hospice Care.', 'pom') . '</strong> ' . esc_html__('Please contact the rescue for special care details.', 'pom') . '</p>'; }
                        if (!$no_adopt) {
                            echo '<p class="pdp-note">' . esc_html(get_the_title($post_id)) . ' ' . esc_html__('could be your new old best friend. Press Adopt to fill out our online form, and a real person will get back to you.', 'pom') . '</p>';
                        }
                        // Actions live inside the info card (design method: primary action
                        // above the fold and again after the story).
                        echo '<div class="pom-buttons"><div class="pom-buttons-row pom-buttons-row--inline">';
                        if (!$no_adopt) { echo '<a class="btn btn-primary" href="' . esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/adoption-questionnaire/'))) . '">' . esc_html__('Adopt', 'pom') . '</a>'; }
                        if (!$is_courtesy) { echo '<a class="btn btn-purple" href="' . esc_url(add_query_arg('dogname', get_the_title($post_id), home_url('/sponsor-a-dog/'))) . '">' . esc_html__('Sponsor', 'pom') . '</a>'; }
                        echo '<a class="btn btn-outline" href="' . esc_url(home_url('/adopt/')) . '">' . esc_html__('Browse all dogs', 'pom') . '</a></div></div>';
                        echo '</section>';
                        echo '</div></div>';



                        echo '</div>';

                    endif;

                endwhile;
            endif; ?>

        </div> <!-- /.et_builder_inner_content -->
    </div> <!-- /.et-l -->
</main> <!-- /#main-content -->

<?php get_footer(); ?>
