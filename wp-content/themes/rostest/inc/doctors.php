<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function rostest_get_doctor_fields( int $post_id ): array {
	$experience = null;
	$price_from = null;
	$rating     = null;

	if ( function_exists( 'get_field' ) ) {
		$experience = get_field( 'doctor_experience_years', $post_id );
		$price_from = get_field( 'doctor_price_from', $post_id );
		$rating     = get_field( 'doctor_rating', $post_id );
	} else {
		$experience = get_post_meta( $post_id, 'doctor_experience_years', true );
		$price_from = get_post_meta( $post_id, 'doctor_price_from', true );
		$rating     = get_post_meta( $post_id, 'doctor_rating', true );
	}

	$experience = is_numeric( $experience ) ? max( 0, (int) $experience ) : 0;
	$price_from = is_numeric( $price_from ) ? max( 0, (float) $price_from ) : 0.0;
	$rating     = is_numeric( $rating ) ? max( 0, min( 5, (float) $rating ) ) : 0.0;

	return array(
		'experience' => $experience,
		'price_from' => $price_from,
		'rating'     => $rating,
	);
}

function rostest_get_doctor_terms_preview( int $post_id ): array {
	$specializations = get_the_terms( $post_id, 'specialization' );
	$cities          = get_the_terms( $post_id, 'city' );

	$specialization_names = array();
	$city_names           = array();

	if ( ! is_wp_error( $specializations ) && ! empty( $specializations ) ) {
		$specialization_names = array_slice( wp_list_pluck( $specializations, 'name' ), 0, 2 );
	}

	if ( ! is_wp_error( $cities ) && ! empty( $cities ) ) {
		$city_names = array_slice( wp_list_pluck( $cities, 'name' ), 0, 1 );
	}

	return array(
		'specializations' => $specialization_names,
		'cities'          => $city_names,
	);
}

