<?php
/**
 * Plugin Name: POMDR MCP Abilities
 * Description: Registers WordPress abilities for POMDR content (dogs/pets and events) and exposes them as tools on the mcp-adapter default MCP server, so the WordPress MCP can read and manage content. No delete abilities by design; writes require edit_posts.
 * Version: 1.0.0
 * Author: Andrew Z.
 *
 * Must-use plugin: auto-loaded, no activation needed. Lives in the WP install
 * (not the theme repo) because mu-plugins load before the theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ============================================================
 * Helpers
 * ============================================================ */

/**
 * Normalize the ACF status (checkbox, stored Title Case) to a string array.
 */
function pomdr_mcp_status_array($post_id) {
    $status = get_field('status', $post_id);
    if (is_array($status)) {
        return array_values(array_filter(array_map('strval', $status)));
    }
    return array_values(array_filter(array_map('trim', explode(',', (string) $status))));
}

/**
 * Shape a single pet into a compact array for MCP output.
 */
function pomdr_mcp_dog_summary($post_id) {
    $sex = function_exists('pom_acf_sex_display') ? pom_acf_sex_display($post_id) : (string) get_field('sex', $post_id);
    return array(
        'id'        => (int) $post_id,
        'name'      => get_the_title($post_id),
        'age'       => (string) get_field('age', $post_id),
        'sex'       => $sex,
        'weight'    => (string) get_field('weight', $post_id),
        'breed'     => (string) get_field('looks_like', $post_id),
        'status'    => pomdr_mcp_status_array($post_id),
        'permalink' => get_permalink($post_id),
    );
}

/** The exact ACF status checkbox choices (Title Case canonical). */
function pomdr_mcp_status_choices() {
    return array('Adoptable', 'Foster Needed', 'Sponsor Needed', 'Adoption Pending', 'Adopted', 'Hospice', 'Courtesy Listing');
}

/** Fields a write ability is allowed to touch (no taxonomy/system fields). */
function pomdr_mcp_writable_fields() {
    return array('age', 'sex', 'weight', 'looks_like', 'pet_description', 'sponsored_by', 'foster_start_date', 'foster_end_date', 'feature');
}

/* ============================================================
 * Category
 * ============================================================ */

add_action('wp_abilities_api_categories_init', function () {
    wp_register_ability_category('pomdr', array(
        'label'       => 'POMDR',
        'description' => 'Peace of Mind Dog Rescue content abilities.',
    ));
});

/* ============================================================
 * Abilities
 * ============================================================ */

add_action('wp_abilities_api_init', function () {

    // ---- list-dogs (readonly) ----
    wp_register_ability('pomdr/list-dogs', array(
        'label'               => 'List Dogs',
        'description'         => 'List dogs (pets) with optional status filter and text search. Returns id, name, age, sex, weight, breed, status, permalink.',
        'category'            => 'pomdr',
        'input_schema'        => array(
            'type'       => 'object',
            'properties' => array(
                'status' => array('type' => 'string', 'description' => 'Filter by a single status, e.g. "Adoptable", "Foster Needed", "Adopted".'),
                'search' => array('type' => 'string', 'description' => 'Free-text search on the dog name.'),
                'limit'  => array('type' => 'integer', 'description' => 'Max results (default 20, max 100).'),
            ),
        ),
        'output_schema'       => array('type' => 'object'),
        'permission_callback' => function () { return current_user_can('read'); },
        'execute_callback'    => function ($input = array()) {
            $limit = isset($input['limit']) ? max(1, min(100, (int) $input['limit'])) : 20;
            $args  = array(
                'post_type'      => 'pets',
                'posts_per_page' => $limit,
                'orderby'        => 'title',
                'order'          => 'ASC',
            );
            if (!empty($input['search'])) {
                $args['s'] = sanitize_text_field($input['search']);
            }
            if (!empty($input['status'])) {
                $args['meta_query'] = array(array(
                    'key'     => 'status',
                    'value'   => sanitize_text_field($input['status']),
                    'compare' => 'LIKE',
                ));
            }
            $q    = new WP_Query($args);
            $dogs = array();
            foreach ($q->posts as $p) {
                $dogs[] = pomdr_mcp_dog_summary($p->ID);
            }
            wp_reset_postdata();
            return array('count' => count($dogs), 'dogs' => $dogs);
        },
        'meta'                => array(
            'annotations' => array('readonly' => true, 'destructive' => false, 'idempotent' => true),
            'mcp'         => array('public' => true),
        ),
    ));

    // ---- get-dog (readonly) ----
    wp_register_ability('pomdr/get-dog', array(
        'label'               => 'Get Dog',
        'description'         => 'Get the full detail of one dog by id or exact name, including bio, sponsor, foster dates, and gallery count.',
        'category'            => 'pomdr',
        'input_schema'        => array(
            'type'       => 'object',
            'properties' => array(
                'id'   => array('type' => 'integer', 'description' => 'The pet post ID.'),
                'name' => array('type' => 'string', 'description' => 'Exact dog name (used if id is not given).'),
            ),
        ),
        'output_schema'       => array('type' => 'object'),
        'permission_callback' => function () { return current_user_can('read'); },
        'execute_callback'    => function ($input = array()) {
            $post_id = 0;
            if (!empty($input['id'])) {
                $post_id = (int) $input['id'];
            } elseif (!empty($input['name'])) {
                $page = get_page_by_title(sanitize_text_field($input['name']), OBJECT, 'pets');
                $post_id = $page ? $page->ID : 0;
            }
            if (!$post_id || get_post_type($post_id) !== 'pets') {
                return new WP_Error('not_found', 'No dog found for that id or name.');
            }
            $data = pomdr_mcp_dog_summary($post_id);
            $data['bio']               = (string) get_field('pet_description', $post_id);
            $data['sponsored_by']      = (string) get_field('sponsored_by', $post_id);
            $data['foster_start_date'] = (string) get_field('foster_start_date', $post_id);
            $data['foster_end_date']   = (string) get_field('foster_end_date', $post_id);
            $data['date_adopted']      = (string) get_field('date_adopted', $post_id);
            $data['featured']          = (bool) get_field('feature', $post_id);
            $gallery = get_field('photo_gallery', $post_id);
            $data['gallery_count']     = is_array($gallery) ? count($gallery) : 0;
            return $data;
        },
        'meta'                => array(
            'annotations' => array('readonly' => true, 'destructive' => false, 'idempotent' => true),
            'mcp'         => array('public' => true),
        ),
    ));

    // ---- create-dog (write) ----
    wp_register_ability('pomdr/create-dog', array(
        'label'               => 'Create Dog',
        'description'         => 'Create a new dog (pet) listing. Requires a name. Optional: age, sex, weight, breed, status (array of Title Case values), bio. Returns the new id and permalink.',
        'category'            => 'pomdr',
        'input_schema'        => array(
            'type'       => 'object',
            'properties' => array(
                'name'   => array('type' => 'string', 'description' => 'Dog name (post title). Required.'),
                'age'    => array('type' => 'string', 'description' => 'Approximate age in years.'),
                'sex'    => array('type' => 'string', 'description' => 'Sex value as stored in ACF.'),
                'weight' => array('type' => 'string', 'description' => 'Weight in lb.'),
                'breed'  => array('type' => 'string', 'description' => 'What the dog looks like (looks_like).'),
                'status' => array('type' => 'array', 'items' => array('type' => 'string'), 'description' => 'Status values (Title Case): ' . implode(', ', pomdr_mcp_status_choices()) . '.'),
                'bio'    => array('type' => 'string', 'description' => 'Pet description / bio.'),
                'photo_url' => array('type' => 'string', 'description' => 'Optional URL of a photo to sideload and set as the dog\'s featured image.'),
            ),
            'required'   => array('name'),
        ),
        'output_schema'       => array('type' => 'object'),
        'permission_callback' => function () { return current_user_can('edit_posts'); },
        'execute_callback'    => function ($input = array()) {
            if (empty($input['name'])) {
                return new WP_Error('missing_name', 'A name is required to create a dog.');
            }
            $post_id = wp_insert_post(array(
                'post_type'   => 'pets',
                'post_status' => 'publish',
                'post_title'  => sanitize_text_field($input['name']),
            ), true);
            if (is_wp_error($post_id)) {
                return $post_id;
            }
            if (isset($input['age']))    update_field('age', sanitize_text_field($input['age']), $post_id);
            if (isset($input['sex']))    update_field('sex', sanitize_text_field($input['sex']), $post_id);
            if (isset($input['weight'])) update_field('weight', sanitize_text_field($input['weight']), $post_id);
            if (isset($input['breed']))  update_field('looks_like', sanitize_text_field($input['breed']), $post_id);
            if (isset($input['bio']))    update_field('pet_description', wp_kses_post($input['bio']), $post_id);
            if (!empty($input['status']) && is_array($input['status'])) {
                $valid = array_values(array_intersect(pomdr_mcp_status_choices(), array_map('sanitize_text_field', $input['status'])));
                update_field('status', $valid, $post_id);
            }
            $photo_set = false;
            if (!empty($input['photo_url'])) {
                require_once ABSPATH . 'wp-admin/includes/media.php';
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/image.php';
                $att_id = media_sideload_image(esc_url_raw($input['photo_url']), $post_id, get_the_title($post_id), 'id');
                if (!is_wp_error($att_id)) {
                    set_post_thumbnail($post_id, $att_id);
                    $photo_set = true;
                }
            }
            return array('id' => (int) $post_id, 'permalink' => get_permalink($post_id), 'created' => true, 'photo_set' => $photo_set);
        },
        'meta'                => array(
            'annotations' => array('readonly' => false, 'destructive' => false, 'idempotent' => false),
        ),
    ));

    // ---- update-dog (write, allowlisted fields) ----
    wp_register_ability('pomdr/update-dog', array(
        'label'               => 'Update Dog',
        'description'         => 'Update an existing dog by id. Allowed fields: status (array, Title Case), age, sex, weight, looks_like, pet_description, sponsored_by, foster_start_date, foster_end_date, feature. Does not delete anything.',
        'category'            => 'pomdr',
        'input_schema'        => array(
            'type'       => 'object',
            'properties' => array(
                'id'                => array('type' => 'integer', 'description' => 'The pet post ID. Required.'),
                'status'            => array('type' => 'array', 'items' => array('type' => 'string'), 'description' => 'Replace status with these Title Case values: ' . implode(', ', pomdr_mcp_status_choices()) . '.'),
                'age'               => array('type' => 'string'),
                'sex'               => array('type' => 'string'),
                'weight'            => array('type' => 'string'),
                'looks_like'        => array('type' => 'string'),
                'pet_description'   => array('type' => 'string'),
                'sponsored_by'      => array('type' => 'string'),
                'foster_start_date' => array('type' => 'string'),
                'foster_end_date'   => array('type' => 'string'),
                'feature'           => array('type' => 'boolean'),
            ),
            'required'   => array('id'),
        ),
        'output_schema'       => array('type' => 'object'),
        'permission_callback' => function () { return current_user_can('edit_posts'); },
        'execute_callback'    => function ($input = array()) {
            $post_id = isset($input['id']) ? (int) $input['id'] : 0;
            if (!$post_id || get_post_type($post_id) !== 'pets') {
                return new WP_Error('not_found', 'No dog found for that id.');
            }
            $updated = array();
            if (isset($input['status']) && is_array($input['status'])) {
                $valid = array_values(array_intersect(pomdr_mcp_status_choices(), array_map('sanitize_text_field', $input['status'])));
                update_field('status', $valid, $post_id);
                $updated['status'] = $valid;
            }
            foreach (pomdr_mcp_writable_fields() as $field) {
                if ($field === 'feature') {
                    if (isset($input['feature'])) {
                        update_field('feature', (bool) $input['feature'], $post_id);
                        $updated['feature'] = (bool) $input['feature'];
                    }
                    continue;
                }
                if (isset($input[$field])) {
                    $val = ($field === 'pet_description') ? wp_kses_post($input[$field]) : sanitize_text_field($input[$field]);
                    update_field($field, $val, $post_id);
                    $updated[$field] = $val;
                }
            }
            return array('id' => $post_id, 'updated' => $updated);
        },
        'meta'                => array(
            'annotations' => array('readonly' => false, 'destructive' => false, 'idempotent' => true),
        ),
    ));

    // ---- list-events (readonly) ----
    wp_register_ability('pomdr/list-events', array(
        'label'               => 'List Events',
        'description'         => 'List events with start/end, type, and permalink.',
        'category'            => 'pomdr',
        'input_schema'        => array(
            'type'       => 'object',
            'properties' => array(
                'limit' => array('type' => 'integer', 'description' => 'Max results (default 20, max 100).'),
            ),
        ),
        'output_schema'       => array('type' => 'object'),
        'permission_callback' => function () { return current_user_can('read'); },
        'execute_callback'    => function ($input = array()) {
            $limit  = isset($input['limit']) ? max(1, min(100, (int) $input['limit'])) : 20;
            $q      = new WP_Query(array('post_type' => 'events', 'posts_per_page' => $limit, 'orderby' => 'date', 'order' => 'DESC'));
            $events = array();
            foreach ($q->posts as $p) {
                $events[] = array(
                    'id'        => (int) $p->ID,
                    'title'     => get_the_title($p->ID),
                    'start'     => (string) get_field('event_start', $p->ID),
                    'end'       => (string) get_field('event_end', $p->ID),
                    'type'      => (string) get_field('event_type', $p->ID),
                    'permalink' => get_permalink($p->ID),
                );
            }
            wp_reset_postdata();
            return array('count' => count($events), 'events' => $events);
        },
        'meta'                => array(
            'annotations' => array('readonly' => true, 'destructive' => false, 'idempotent' => true),
            'mcp'         => array('public' => true),
        ),
    ));
});

/* ============================================================
 * Expose these abilities as tools on the default MCP server
 * ============================================================ */

add_filter('mcp_adapter_default_server_config', function ($config) {
    $pomdr_tools = array(
        'pomdr/list-dogs',
        'pomdr/get-dog',
        'pomdr/create-dog',
        'pomdr/update-dog',
        'pomdr/list-events',
    );
    $config['tools'] = array_merge(isset($config['tools']) ? $config['tools'] : array(), $pomdr_tools);
    return $config;
});
