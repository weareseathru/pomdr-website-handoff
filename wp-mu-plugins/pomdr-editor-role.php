<?php
/**
 * Plugin Name: POMDR Editor Hardening
 * Description: A safe editing surface for non-technical POMDR volunteers. Registers the "POMDR Content Editor" role (full CRUD on dogs, events, and team plus media, nothing else), locks the dog status vocabulary, adds kind editor validation, and de-clutters wp-admin for the role. Administrators are unaffected.
 * Version: 1.0.0
 * Author: Andrew Z.
 *
 * Must-use plugin: auto-loaded, no activation needed. Lives in mu-plugins (not
 * the theme) so the role and its rails survive theme switches. Mirrored in the
 * repo at wp-mu-plugins/pomdr-editor-role.php.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * The role slug and its capability-set version. Bumping the version is how the
 * role is re-synced: the registration only writes when the stored version does
 * not match, so nothing runs add_role() on every request. (Cf. risk A4, where
 * an unguarded flush_rewrite_rules() on init wrote to the DB every page load.
 * Anything that mutates roles/rewrites must be guarded like this.)
 */
define('POMDR_EDITOR_ROLE', 'pomdr_editor');
define('POMDR_EDITOR_ROLE_VERSION', 1);

/**
 * The exact capabilities for the POMDR Content Editor.
 *
 * The pets, events, and team CPTs are registered with the default `post`
 * capability_type, so the standard post caps below cover full CRUD on all
 * three (plus media via upload_files). Pages, plugins, themes, users,
 * settings, the customizer, and the Divi builder are all deliberately absent,
 * so WordPress blocks those screens for the role. The irrelevant menus that
 * would still show (Posts, Comments) are hidden separately.
 *
 * @return array<string,bool>
 */
function pomdr_editor_role_caps() {
    return array(
        'read'                   => true,
        'upload_files'           => true,  // add and manage media
        // Full CRUD on the CPTs (they use the 'post' capability_type):
        'edit_posts'             => true,
        'edit_others_posts'      => true,
        'edit_published_posts'   => true,
        'publish_posts'          => true,
        'delete_posts'           => true,
        'delete_others_posts'    => true,
        'delete_published_posts' => true,
        'read_private_posts'     => true,
    );
}

/**
 * Register or re-sync the role, guarded by a version option so the write only
 * happens once per capability-set change (never on every request).
 */
function pomdr_editor_sync_role() {
    $stored = (int) get_option('pomdr_editor_role_version', 0);
    if ($stored === POMDR_EDITOR_ROLE_VERSION && get_role(POMDR_EDITOR_ROLE)) {
        return;
    }

    // Re-create cleanly so removed caps do not linger from an older version.
    remove_role(POMDR_EDITOR_ROLE);
    add_role(POMDR_EDITOR_ROLE, 'POMDR Content Editor', pomdr_editor_role_caps());

    update_option('pomdr_editor_role_version', POMDR_EDITOR_ROLE_VERSION, false);
}
add_action('init', 'pomdr_editor_sync_role');

/**
 * Convenience: is the current user a POMDR Content Editor (and not also an
 * administrator)? Used to scope the admin de-cluttering so administrators are
 * never affected.
 *
 * @return bool
 */
function pomdr_is_content_editor() {
    $user = wp_get_current_user();
    if (!$user || !$user->exists()) {
        return false;
    }
    return in_array(POMDR_EDITOR_ROLE, (array) $user->roles, true)
        && !in_array('administrator', (array) $user->roles, true);
}

/* ============================================================
 * (2) Status vocabulary lockdown
 * ============================================================
 * Per settled risk A1, the dog status vocabulary is canonical: a multi-value
 * ACF *checkbox* (not a single select) with exactly these Title-Case values,
 * with custom values and "other" turned off. The field UI already prevents
 * free text. This filter is the belt-and-suspenders guard for any programmatic
 * or import path (REST, the MCP abilities, a CSV import) that could otherwise
 * write an off-vocabulary value. It rejects anything outside the canonical set
 * instead of silently storing it.
 */

/**
 * The canonical dog status values (the exact ACF checkbox choices).
 *
 * @return string[]
 */
function pomdr_canonical_statuses() {
    return array(
        'Adoptable',
        'Foster Needed',
        'Sponsor Needed',
        'Adoption Pending',
        'Adopted',
        'Hospice',
        'Courtesy Listing',
    );
}

/**
 * Reject any status value that is not in the canonical set.
 *
 * @param bool|string $valid True if valid, or an error message string.
 * @param mixed       $value The field value (array for a checkbox).
 * @return bool|string
 */
function pomdr_validate_status_value($valid, $value, $field, $input) {
    if ($valid !== true) {
        return $valid;
    }
    $allowed = pomdr_canonical_statuses();
    foreach ((array) $value as $v) {
        if ($v !== '' && !in_array($v, $allowed, true)) {
            return 'That status is not one we use. Please pick from the listed choices.';
        }
    }
    return $valid;
}
add_filter('acf/validate_value/name=status', 'pomdr_validate_status_value', 10, 4);

/* ============================================================
 * (3) Editor validation (kind, plain-language messages)
 * ============================================================
 * Enforced for everyone (admins included) because these keep the public cards
 * from breaking, not just the role. Messages are gentle and say why.
 *
 * Dogs (pets):
 *   - a featured photo (the card image) is required
 *   - the name (post title) must not be empty
 *   - weight is numeric, 1 to 250 lb
 *   - age is numeric, 0 to 25 yrs
 * Events:
 *   - the start date is required (blocks save if missing)
 *   - a start date in the past shows a friendly warning, but does NOT block
 */

/**
 * Weight: numeric, 1 to 250 lb.
 */
function pomdr_validate_weight($valid, $value, $field, $input) {
    if ($valid !== true || $value === '' || $value === null) {
        return $valid;
    }
    if (!is_numeric($value)) {
        return 'Please enter the weight as a number (pounds).';
    }
    $w = (float) $value;
    if ($w < 1 || $w > 250) {
        return 'Please enter a weight between 1 and 250 lb.';
    }
    return $valid;
}
add_filter('acf/validate_value/name=weight', 'pomdr_validate_weight', 10, 4);

/**
 * Age: numeric, 0 to 25 yrs.
 */
function pomdr_validate_age($valid, $value, $field, $input) {
    if ($valid !== true || $value === '' || $value === null) {
        return $valid;
    }
    if (!is_numeric($value)) {
        return 'Please enter the age as a number (years).';
    }
    $a = (float) $value;
    if ($a < 0 || $a > 25) {
        return 'Please enter an age between 0 and 25 yrs.';
    }
    return $valid;
}
add_filter('acf/validate_value/name=age', 'pomdr_validate_age', 10, 4);

/**
 * Event start date is required.
 */
function pomdr_validate_event_start($valid, $value, $field, $input) {
    if ($valid !== true) {
        return $valid;
    }
    if ($value === '' || $value === null) {
        return 'Please add the event start date so it shows up on the calendar.';
    }
    return $valid;
}
add_filter('acf/validate_value/name=event_start', 'pomdr_validate_event_start', 10, 4);

/**
 * Dog-only, form-level checks that are not single ACF fields: the featured
 * photo (the card image) and the name (post title). Runs during ACF's save
 * validation, so a failure blocks the save with a kind message.
 */
function pomdr_validate_pet_save() {
    $post_type = isset($_POST['post_type']) ? sanitize_key($_POST['post_type']) : '';
    if ($post_type !== 'pets') {
        return;
    }

    $name = isset($_POST['post_title']) ? trim(sanitize_text_field(wp_unslash($_POST['post_title']))) : '';
    if ($name === '') {
        acf_add_validation_error('', 'Please give the dog a name in the Title field at the top.');
    }
    $friendly = $name !== '' ? $name : 'this dog';

    // Featured image: the card uses the post thumbnail. It arrives as the
    // hidden _thumbnail_id field (classic editor); fall back to any already
    // saved thumbnail for the block editor.
    $thumb = isset($_POST['_thumbnail_id']) ? (int) $_POST['_thumbnail_id'] : 0;
    if ($thumb <= 0) {
        $post_id = isset($_POST['post_ID']) ? (int) $_POST['post_ID'] : 0;
        $thumb = $post_id ? (int) get_post_thumbnail_id($post_id) : 0;
    }
    if ($thumb <= 0) {
        acf_add_validation_error(
            '',
            sprintf('Please add a featured photo so %s\'s card looks great.', $friendly)
        );
    }
}
add_action('acf/validate_save_post', 'pomdr_validate_pet_save');

/**
 * Event start date in the past: a friendly, non-blocking warning. Set during
 * save, shown once on the next admin screen via a transient.
 */
function pomdr_flag_past_event($post_id) {
    if (get_post_type($post_id) !== 'events') {
        return;
    }
    $start = get_field('event_start', $post_id);
    if (!$start) {
        return;
    }
    $ts = strtotime((string) $start);
    if ($ts && $ts < current_time('timestamp')) {
        set_transient('pomdr_event_past_' . get_current_user_id(), (int) $post_id, 60);
    }
}
add_action('acf/save_post', 'pomdr_flag_past_event', 20);

/**
 * Show the past-event warning once (non-blocking).
 */
function pomdr_past_event_notice() {
    $key = 'pomdr_event_past_' . get_current_user_id();
    $post_id = (int) get_transient($key);
    if (!$post_id) {
        return;
    }
    delete_transient($key);
    printf(
        '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
        esc_html__('Heads up: this event\'s start date is in the past, so it will not appear in the upcoming list. That is fine if you meant to log a past event.', 'pomdr')
    );
}
add_action('admin_notices', 'pomdr_past_event_notice');

/* ============================================================
 * (4) Admin de-cluttering for the role (administrators untouched)
 * ============================================================ */

/**
 * The URL the welcome box links to for the full staff guide. Filterable so it
 * can point at wherever the guide is published for staff (a Google Doc, a
 * hosted page). Defaults to the version-controlled copy in the repo.
 *
 * @return string
 */
function pomdr_staff_guide_url() {
    return (string) apply_filters(
        'pomdr_staff_guide_url',
        'https://github.com/weareseathru/pomdr-website-handoff/blob/main/docs/STAFF-CONTENT-GUIDE.md'
    );
}

/**
 * Hide admin menus that are irrelevant to a content editor, and relabel the
 * Pets menu to "Dogs" for the role. Scoped so administrators see the full,
 * unchanged admin.
 */
function pomdr_editor_tidy_menus() {
    if (!pomdr_is_content_editor()) {
        return;
    }

    // Hide menus the role does not need. (Plugins, Themes, Users, Settings,
    // Appearance, and ACF are already hidden by missing capabilities; these are
    // the ones that would otherwise show.)
    remove_menu_page('edit.php');                    // Posts
    remove_menu_page('edit-comments.php');           // Comments
    remove_menu_page('tools.php');                   // Tools
    remove_menu_page('edit.php?post_type=project');  // Projects CPT
    remove_menu_page('et_divi_options');             // Divi (theme options / builder)
    remove_menu_page('et_onboarding');               // Divi dashboard

    // Relabel Pets to Dogs for the role only.
    global $menu, $submenu;
    if (is_array($menu)) {
        foreach ($menu as $i => $item) {
            if (isset($item[2]) && $item[2] === 'edit.php?post_type=pets') {
                $menu[$i][0] = 'Dogs';
            }
        }
    }
    if (isset($submenu['edit.php?post_type=pets']) && is_array($submenu['edit.php?post_type=pets'])) {
        foreach ($submenu['edit.php?post_type=pets'] as $j => $sub) {
            if (isset($sub[0]) && $sub[0] === 'All Pets') {
                $submenu['edit.php?post_type=pets'][$j][0] = 'All Dogs';
            } elseif (isset($sub[0]) && $sub[0] === 'Add New Pet') {
                $submenu['edit.php?post_type=pets'][$j][0] = 'Add New Dog';
            }
        }
    }
}
add_action('admin_menu', 'pomdr_editor_tidy_menus', 999);

/**
 * A warm welcome box on the editor's dashboard, linking to the staff guide.
 */
function pomdr_editor_welcome_notice() {
    if (!pomdr_is_content_editor()) {
        return;
    }
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->id !== 'dashboard') {
        return;
    }
    $user = wp_get_current_user();
    $first = $user && $user->display_name ? explode(' ', $user->display_name)[0] : 'there';
    printf(
        '<div class="notice notice-info" style="border-left-color:#0099A8;padding:14px 16px;">
            <h2 style="margin:0 0 6px;">Welcome, %1$s.</h2>
            <p style="margin:0 0 8px;max-width:60em;">You can add and edit <strong>Dogs</strong>, <strong>Events</strong>, and <strong>Team</strong> members, and upload photos. Everything else (the layout, colors, and settings) is handled for you, so there is nothing here you can break.</p>
            <p style="margin:0;"><a class="button button-primary" href="%2$s" target="_blank" rel="noopener">Read the Staff Content Guide</a></p>
        </div>',
        esc_html($first),
        esc_url(pomdr_staff_guide_url())
    );
}
add_action('admin_notices', 'pomdr_editor_welcome_notice');
