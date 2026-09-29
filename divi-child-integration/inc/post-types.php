<?php
/**
 * Safety-net registration for the pets, events, and team post types.
 *
 * On the current sites these three types are defined as ACF "post type"
 * records inside the database. The failed production deploy happened exactly
 * because those definitions never travel with the theme folder. This file
 * makes the theme self-sufficient: if the ACF definitions are present they
 * win untouched (we register late and skip anything that already exists);
 * on a site where they are missing, the theme registers identical types so
 * dogs, events, and team members keep working.
 *
 * Args mirror the effective runtime registration dumped from the live local
 * site on 2026-08-18. If the ACF definitions are ever edited on purpose,
 * update this file to match (docs/NATIVE-DIVI-PLAN.md, Phase 1).
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {

	if ( ! post_type_exists( 'pets' ) ) {
		register_post_type( 'pets', array(
			'labels' => array(
				'name'               => 'Pets',
				'singular_name'      => 'Pet',
				'menu_name'          => 'Pets',
				'all_items'          => 'All Pets',
				'add_new'            => 'Add New Pet',
				'add_new_item'       => 'Add New Pet',
				'edit_item'          => 'Edit Pet',
				'new_item'           => 'New Pet',
				'view_item'          => 'View Pet',
				'view_items'         => 'View Pets',
				'search_items'       => 'Search Pets',
				'not_found'          => 'No pets found',
				'not_found_in_trash' => 'No pets found in Trash',
			),
			'public'              => true,
			'hierarchical'        => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'menu_position'       => 3,
			'menu_icon'           => 'dashicons-pets',
			'supports'            => array( 'title', 'thumbnail', 'custom-fields' ),
			'has_archive'         => false,
			'rewrite'             => array(
				'slug'       => 'pets',
				'with_front' => true,
				'feeds'      => false,
				'pages'      => true,
			),
			'query_var'           => 'pets',
			'can_export'          => true,
			'delete_with_user'    => false,
		) );
	}

	if ( ! post_type_exists( 'events' ) ) {
		register_post_type( 'events', array(
			'labels' => array(
				'name'               => 'Events',
				'singular_name'      => 'Event',
				'menu_name'          => 'Events',
				'all_items'          => 'All Events',
				'add_new'            => 'Add New Event',
				'add_new_item'       => 'Add New Event',
				'edit_item'          => 'Edit Event',
				'new_item'           => 'New Event',
				'view_item'          => 'View Event',
				'view_items'         => 'View Events',
				'search_items'       => 'Search Events',
				'not_found'          => 'No events found',
				'not_found_in_trash' => 'No events found in Trash',
			),
			'public'              => true,
			'hierarchical'        => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'menu_position'       => 7,
			'menu_icon'           => 'dashicons-calendar-alt',
			'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'has_archive'         => false,
			'rewrite'             => array(
				'slug'       => 'events',
				'with_front' => true,
				'feeds'      => false,
				'pages'      => true,
			),
			'query_var'           => 'events',
			'can_export'          => true,
			'delete_with_user'    => false,
		) );
	}

	if ( ! post_type_exists( 'team' ) ) {
		register_post_type( 'team', array(
			'labels' => array(
				'name'               => 'Team',
				'singular_name'      => 'Team',
				'menu_name'          => 'Team',
				'all_items'          => 'Team Members',
				'add_new'            => 'Add New Team Member',
				'add_new_item'       => 'Add New Team Member',
				'edit_item'          => 'Edit Team Member',
				'new_item'           => 'New Team Member',
				'view_item'          => 'View Team Member',
				'view_items'         => 'View Team Members',
				'search_items'       => 'Search Team Members',
				'not_found'          => 'No team members found',
				'not_found_in_trash' => 'No team members found in Trash',
			),
			'public'              => true,
			'hierarchical'        => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'menu_position'       => 4,
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'thumbnail', 'custom-fields' ),
			'has_archive'         => false,
			'rewrite'             => array(
				'slug'       => 'team',
				'with_front' => true,
				'feeds'      => false,
				'pages'      => true,
			),
			'query_var'           => 'team',
			'can_export'          => true,
			'delete_with_user'    => false,
		) );
	}

}, 20 ); // Late enough that ACF's own definitions register first and win.

/* ---- ACF JSON sync -------------------------------------------------------
 * Field groups save to and load from the theme's acf-json folder, so field
 * definitions are version-controlled and travel with the theme on deploy.
 * ACF loads from this folder automatically once it exists; the save filter
 * makes edits in wp-admin write back to it on this machine.
 */
add_filter( 'acf/settings/save_json', function () {
	return get_stylesheet_directory() . '/acf-json';
} );
