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
