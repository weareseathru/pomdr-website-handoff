<?php
/**
 * POMDR dog roster importer. Idempotent and batched; safe to re-run.
 *
 * Reads the scraped rosters from the theme's data/dogsync/*.json and syncs the
 * pets CPT: updates matched dogs (by case-insensitive name), creates missing
 * ones (sideloading their photo), and flips mirror dogs to Adopted when they
 * have left the live rosters and appear in the live adopted-names wall.
 *
 * Run inside WordPress:
 *   wp eval-file scripts/sync-dogs.php         (all batches)
 * or via the QA fixture pattern with ?offset=N&limit=N for manual batching.
 * Define POMDR_SYNC_OFFSET / POMDR_SYNC_LIMIT before including to batch.
 */

if (!defined('ABSPATH')) { exit; }

function pomdr_sync_dogs($offset = 0, $limit = 0) {
    $dir = get_stylesheet_directory() . '/data/dogsync/';
    $load = function ($f) use ($dir) {
        $j = json_decode((string) file_get_contents($dir . $f), true);
        return is_array($j) ? $j : array();
    };
    $adoptable = $load('adoptable.json');
    $courtesy  = $load('courtesy.json');
    $hospice   = $load('hospice.json');
    $fosters   = $load('foster_needed.json');
    $adopted_names = array_map('strtolower', array_map('trim', $load('adopted_names.json')));

    // Merge rosters by name. Later entries enrich earlier ones.
    $by_name = array();
    $merge = function ($rec) use (&$by_name) {
        $key = strtolower(trim($rec['name']));
        if (!isset($by_name[$key])) { $by_name[$key] = $rec; return; }
        $cur = &$by_name[$key];
        $cur['statuses'] = array_values(array_unique(array_merge($cur['statuses'] ?? array(), $rec['statuses'] ?? array())));
        foreach (array('foster_dates','contact','posted','updated','sponsors','photo_url','writeup','looks_like','sex','age','weight','live_id') as $f) {
            if (empty($cur[$f]) && !empty($rec[$f])) { $cur[$f] = $rec[$f]; }
        }
        // Prefer the longer writeup.
        if (!empty($rec['writeup']) && strlen($rec['writeup']) > strlen((string) ($cur['writeup'] ?? ''))) { $cur['writeup'] = $rec['writeup']; }
        if (!empty($rec['aged_to_perfection'])) { $cur['aged_to_perfection'] = true; }
    };
    foreach (array($adoptable, $courtesy, $hospice, $fosters) as $set) { foreach ($set as $r) { $merge($r); } }

    // Index existing pets by name.
    $existing = array();
    foreach (get_posts(array('post_type' => 'pets', 'post_status' => 'any', 'posts_per_page' => -1)) as $p) {
        $existing[strtolower(trim($p->post_title))] = $p->ID;
    }

    $records = array_values($by_name);
    if ($limit > 0) { $records = array_slice($records, $offset, $limit); }

    $canon = array('Adoptable','Foster Needed','Sponsor Needed','Adoption Pending','Adopted','Hospice','Courtesy Listing');
    $out = array('created' => array(), 'updated' => array(), 'photo_fail' => array(), 'flipped_adopted' => array());

    foreach ($records as $rec) {
        $key = strtolower(trim($rec['name']));
        $statuses = array_values(array_intersect($rec['statuses'] ?? array(), $canon));
        if (!$statuses) { $statuses = array('Adoptable'); }

        if (isset($existing[$key])) {
            $id = $existing[$key];
        } else {
            $id = wp_insert_post(array('post_type' => 'pets', 'post_status' => 'publish', 'post_title' => trim($rec['name'])));
            if (is_wp_error($id) || !$id) { continue; }
            $out['created'][] = $rec['name'];
        }
        if (!in_array($rec['name'], $out['created'], true)) { $out['updated'][] = $rec['name']; }

        update_field('status', $statuses, $id);
        if (!empty($rec['looks_like'])) { update_field('looks_like', $rec['looks_like'], $id); }
        if (isset($rec['age']) && $rec['age'] !== null && $rec['age'] !== '') { update_field('age', $rec['age'], $id); }
        if (!empty($rec['weight'])) { update_field('weight', $rec['weight'], $id); }
        if (!empty($rec['sex'])) { update_field('sex', array($rec['sex']), $id); }
        if (!empty($rec['writeup'])) { update_field('pet_description', $rec['writeup'], $id); }
        if (!empty($rec['sponsors']) && is_array($rec['sponsors'])) { update_field('sponsored_by', implode(', ', $rec['sponsors']), $id); }
        update_post_meta($id, 'aged_to_perfection', empty($rec['aged_to_perfection']) ? 0 : 1);
        update_post_meta($id, 'live_id', $rec['live_id'] ?? '');
        update_post_meta($id, 'courtesy_contact', $rec['contact'] ?? '');
        update_post_meta($id, 'courtesy_posted', $rec['posted'] ?? '');
        update_post_meta($id, 'courtesy_updated', $rec['updated'] ?? '');

        // Foster date range like "9/26-10/23": store as ACF Ymd, current year.
        if (!empty($rec['foster_dates']) && preg_match('#(\d{1,2})/(\d{1,2})\s*-\s*(\d{1,2})/(\d{1,2})#', $rec['foster_dates'], $m)) {
            $y = (int) current_time('Y');
            update_field('foster_start_date', sprintf('%04d%02d%02d', $y, $m[1], $m[2]), $id);
            update_field('foster_end_date',   sprintf('%04d%02d%02d', $y, $m[3], $m[4]), $id);
        }

        // Photo: sideload only when the dog has no featured image yet.
        if (!get_post_thumbnail_id($id) && !empty($rec['photo_url'])) {
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $att = media_sideload_image($rec['photo_url'], $id, $rec['name'], 'id');
            if (is_wp_error($att)) { $out['photo_fail'][] = $rec['name']; }
            else { set_post_thumbnail($id, $att); }
        }
    }

    // Full-run only: flip stale Adoptable mirror dogs that live says are adopted.
    if ($limit <= 0 || ($offset + $limit) >= count($by_name)) {
        foreach ($existing as $key => $id) {
            if (isset($by_name[$key])) { continue; }
            $status = get_field('status', $id);
            $status = is_array($status) ? $status : array();
            if (in_array('Adoptable', $status, true) && in_array($key, $adopted_names, true)) {
                update_field('status', array('Adopted'), $id);
                $out['flipped_adopted'][] = get_the_title($id);
            }
        }
    }

    $out['counts'] = array('created' => count($out['created']), 'updated' => count($out['updated']),
        'photo_fail' => count($out['photo_fail']), 'flipped' => count($out['flipped_adopted']), 'total_records' => count($by_name));
    return $out;
}

if (defined('WP_CLI') && WP_CLI) {
    $r = pomdr_sync_dogs();
    WP_CLI::log(json_encode($r['counts']));
}
