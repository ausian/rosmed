<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'rostest_doctors_register_taxonomies' );

function rostest_doctors_register_taxonomies(): void {
	$specialization_labels = array(
		'name'              => __( 'Specializations', 'rostest-doctors' ),
		'singular_name'     => __( 'Specialization', 'rostest-doctors' ),
		'search_items'      => __( 'Search Specializations', 'rostest-doctors' ),
		'all_items'         => __( 'All Specializations', 'rostest-doctors' ),
		'parent_item'       => __( 'Parent Specialization', 'rostest-doctors' ),
		'parent_item_colon' => __( 'Parent Specialization:', 'rostest-doctors' ),
		'edit_item'         => __( 'Edit Specialization', 'rostest-doctors' ),
		'update_item'       => __( 'Update Specialization', 'rostest-doctors' ),
		'add_new_item'      => __( 'Add New Specialization', 'rostest-doctors' ),
		'new_item_name'     => __( 'New Specialization Name', 'rostest-doctors' ),
		'menu_name'         => __( 'Specializations', 'rostest-doctors' ),
	);

	register_taxonomy(
		'specialization',
		array( 'doctors' ),
		array(
			'hierarchical'      => true,
			'labels'            => $specialization_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'specialization' ),
		)
	);

	$city_labels = array(
		'name'                       => __( 'Cities', 'rostest-doctors' ),
		'singular_name'              => __( 'City', 'rostest-doctors' ),
		'search_items'               => __( 'Search Cities', 'rostest-doctors' ),
		'popular_items'              => __( 'Popular Cities', 'rostest-doctors' ),
		'all_items'                  => __( 'All Cities', 'rostest-doctors' ),
		'edit_item'                  => __( 'Edit City', 'rostest-doctors' ),
		'update_item'                => __( 'Update City', 'rostest-doctors' ),
		'add_new_item'               => __( 'Add New City', 'rostest-doctors' ),
		'new_item_name'              => __( 'New City Name', 'rostest-doctors' ),
		'separate_items_with_commas' => __( 'Separate cities with commas', 'rostest-doctors' ),
		'add_or_remove_items'        => __( 'Add or remove cities', 'rostest-doctors' ),
		'choose_from_most_used'      => __( 'Choose from the most used cities', 'rostest-doctors' ),
		'not_found'                  => __( 'No cities found.', 'rostest-doctors' ),
		'menu_name'                  => __( 'Cities', 'rostest-doctors' ),
	);

	register_taxonomy(
		'city',
		array( 'doctors' ),
		array(
			'hierarchical'      => false,
			'labels'            => $city_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'city' ),
		)
	);
}

