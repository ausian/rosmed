<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'rostest_doctors_register_post_types' );

function rostest_doctors_register_post_types(): void {
	$labels = array(
		'name'               => __( 'Doctors', 'rostest-doctors' ),
		'singular_name'      => __( 'Doctor', 'rostest-doctors' ),
		'menu_name'          => __( 'Doctors', 'rostest-doctors' ),
		'name_admin_bar'     => __( 'Doctor', 'rostest-doctors' ),
		'add_new'            => __( 'Add New', 'rostest-doctors' ),
		'add_new_item'       => __( 'Add New Doctor', 'rostest-doctors' ),
		'new_item'           => __( 'New Doctor', 'rostest-doctors' ),
		'edit_item'          => __( 'Edit Doctor', 'rostest-doctors' ),
		'view_item'          => __( 'View Doctor', 'rostest-doctors' ),
		'all_items'          => __( 'All Doctors', 'rostest-doctors' ),
		'search_items'       => __( 'Search Doctors', 'rostest-doctors' ),
		'not_found'          => __( 'No doctors found.', 'rostest-doctors' ),
		'not_found_in_trash' => __( 'No doctors found in Trash.', 'rostest-doctors' ),
	);

	$args = array(
		'labels'       => $labels,
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-id-alt',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'rewrite'      => array( 'slug' => 'doctors' ),
	);

	register_post_type( 'doctors', $args );
}

