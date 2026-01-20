<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'pre_get_posts', 'rostest_doctors_tune_archive_query' );

function rostest_doctors_tune_archive_query( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'doctors' ) ) {
		$query->set( 'posts_per_page', 9 );

		$specialization = isset( $_GET['specialization'] ) ? sanitize_text_field( wp_unslash( $_GET['specialization'] ) ) : '';
		$city           = isset( $_GET['city'] ) ? sanitize_text_field( wp_unslash( $_GET['city'] ) ) : '';
		$sort           = isset( $_GET['sort'] ) ? sanitize_text_field( wp_unslash( $_GET['sort'] ) ) : '';

		$tax_query = array();
		if ( $specialization !== '' ) {
			$tax_query[] = array(
				'taxonomy' => 'specialization',
				'field'    => 'slug',
				'terms'    => $specialization,
			);
		}
		if ( $city !== '' ) {
			$tax_query[] = array(
				'taxonomy' => 'city',
				'field'    => 'slug',
				'terms'    => $city,
			);
		}
		if ( $tax_query ) {
			$tax_query['relation'] = 'AND';
			$query->set( 'tax_query', $tax_query );
		}

		if ( $sort === 'rating' ) {
			rostest_doctors_apply_numeric_sort( $query, 'doctor_rating', 'DESC' );
		} elseif ( $sort === 'price' ) {
			rostest_doctors_apply_numeric_sort( $query, 'doctor_price_from', 'ASC' );
		} elseif ( $sort === 'experience' ) {
			rostest_doctors_apply_numeric_sort( $query, 'doctor_experience_years', 'DESC' );
		}
	}
}

function rostest_doctors_apply_numeric_sort( WP_Query $query, string $meta_key, string $order ): void {
	$query->set( 'meta_key', $meta_key );
	$query->set( 'orderby', 'meta_value_num' );
	$query->set( 'order', $order );

	$meta_query = (array) $query->get( 'meta_query' );
	$meta_query[] = array(
		'relation' => 'OR',
		array(
			'key'     => $meta_key,
			'compare' => 'EXISTS',
		),
		array(
			'key'     => $meta_key,
			'compare' => 'NOT EXISTS',
		),
	);
	$query->set( 'meta_query', $meta_query );
}
