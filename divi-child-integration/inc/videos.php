<?php
/**
 * Videos backend: a staff-editable "Videos" post type plus its fields.
 *
 * Before this, the Videos page held a hardcoded PHP array, so adding a film
 * meant a code change. Now a non-technical editor adds a video in wp-admin:
 * Videos > Add New, paste the YouTube ID (or an external link), fill in the
 * caption and credit, and it appears on the Videos page automatically.
 *
 * The type is registered here (not in a plugin) so it travels with the theme
 * and is version-controlled. public=false keeps it out of the front end as its
 * own URL (videos only ever show on the Videos page), which also means no
 * rewrite rules and no permalink flush at launch.
 */

defined( 'ABSPATH' ) || exit;

/* ---- The Videos post type ------------------------------------------------ */
add_action( 'init', function () {
	register_post_type( 'videos', array(
		'labels' => array(
			'name'               => 'Videos',
			'singular_name'      => 'Video',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Video',
			'edit_item'          => 'Edit Video',
			'new_item'           => 'New Video',
			'view_item'          => 'View Video',
			'search_items'       => 'Search Videos',
			'not_found'          => 'No videos yet',
			'not_found_in_trash' => 'No videos in the trash',
			'all_items'          => 'All Videos',
			'menu_name'          => 'Videos',
		),
		'public'        => false,       // no front-end single pages
		'show_ui'       => true,        // but fully editable in wp-admin
		'show_in_menu'  => true,
		'show_in_rest'  => true,        // block editor + future REST use
		'menu_position' => 26,
		'menu_icon'     => 'dashicons-video-alt3',
		'supports'      => array( 'title', 'page-attributes' ), // title + drag-order
		'capability_type' => 'post',
	) );
} );

/* ---- The fields a staff editor fills in ---------------------------------- */
add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	acf_add_local_field_group( array(
		'key'      => 'group_pomdr_video',
		'title'    => 'Video',
		'fields'   => array(
			array(
				'key'          => 'field_pomdr_video_youtube_id',
				'label'        => 'YouTube video ID',
				'name'         => 'youtube_id',
				'type'         => 'text',
				'instructions' => 'The ID is the part of a YouTube link after "v=". For https://www.youtube.com/watch?v=B7RQI4beRZU the ID is B7RQI4beRZU. Leave blank if this video lives on another site (fill in the link below instead).',
			),
			array(
				'key'          => 'field_pomdr_video_external_url',
				'label'        => 'Or a link to another site',
				'name'         => 'external_url',
				'type'         => 'url',
				'instructions' => 'Use this only for videos hosted somewhere other than YouTube (for example a news feature). It opens in a new tab. Leave the YouTube ID blank when you use this.',
			),
			array(
				'key'          => 'field_pomdr_video_year',
				'label'        => 'Year',
				'name'         => 'video_year',
				'type'         => 'text',
				'instructions' => 'The year the video is from, e.g. 2026. Videos show newest first.',
			),
			array(
				'key'          => 'field_pomdr_video_caption',
				'label'        => 'Caption',
				'name'         => 'video_caption',
				'type'         => 'textarea',
				'rows'         => 4,
				'instructions' => 'A short description shown under the video.',
			),
			array(
				'key'          => 'field_pomdr_video_credits',
				'label'        => 'Credit',
				'name'         => 'video_credits',
				'type'         => 'text',
				'instructions' => 'Who made the video, e.g. "Created by Monica Rua, 2026".',
			),
		),
		'location' => array(
			array(
				array( 'param' => 'post_type', 'operator' => '==', 'value' => 'videos' ),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'active'                => true,
		'description'           => 'Fields for each video on the Videos page.',
	) );
} );

/**
 * Return the videos for the front end, newest first.
 * Ordered by the Year field (desc), then by manual page-order as a tiebreak.
 *
 * @return array List of arrays: title, youtube_id, external_url, year, caption, credits.
 */
function pomdr_get_videos() {
	$q = new WP_Query( array(
		'post_type'      => 'videos',
		'post_status'    => 'publish',
		'posts_per_page' => 100,
		'meta_key'       => 'video_year',
		'orderby'        => array( 'meta_value_num' => 'DESC', 'menu_order' => 'ASC', 'date' => 'DESC' ),
	) );
	$out = array();
	foreach ( $q->posts as $p ) {
		$out[] = array(
			'title'    => get_the_title( $p ),
			'youtube_id' => trim( (string) get_field( 'youtube_id', $p->ID ) ),
			'url'      => trim( (string) get_field( 'external_url', $p->ID ) ),
			'year'     => trim( (string) get_field( 'video_year', $p->ID ) ),
			'caption'  => trim( (string) get_field( 'video_caption', $p->ID ) ),
			'credits'  => trim( (string) get_field( 'video_credits', $p->ID ) ),
		);
	}
	return $out;
}
