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
