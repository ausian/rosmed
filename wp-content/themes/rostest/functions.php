<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/inc/i18n.php';
require_once __DIR__ . '/inc/doctors.php';

add_action(
	'after_setup_theme',
	static function (): void {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'rostest' ),
			)
		);
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		rostest_theme_ensure_translations();
		load_theme_textdomain( 'rostest', get_template_directory() . '/languages' );
	}
);

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		$theme   = wp_get_theme();
		$version = $theme->exists() ? $theme->get( 'Version' ) : null;
		wp_enqueue_style( 'rostest-style', get_stylesheet_uri(), array(), $version );
	}
);
