<?php
/**
 * Plugin Name: Rostest Doctors
 * Description: Custom post type "Doctor" (doctors) and related functionality for the test task.
 * Version: 0.1.0
 * Author: Rostest
 * Text Domain: rostest-doctors
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ROSTEST_DOCTORS_PLUGIN_FILE', __FILE__ );
define( 'ROSTEST_DOCTORS_PLUGIN_DIR', __DIR__ );

require_once ROSTEST_DOCTORS_PLUGIN_DIR . '/includes/i18n.php';
require_once ROSTEST_DOCTORS_PLUGIN_DIR . '/includes/post-types.php';
require_once ROSTEST_DOCTORS_PLUGIN_DIR . '/includes/taxonomies.php';
require_once ROSTEST_DOCTORS_PLUGIN_DIR . '/includes/query.php';

add_action(
	'plugins_loaded',
	static function (): void {
		rostest_doctors_ensure_translations();
		load_plugin_textdomain(
			'rostest-doctors',
			false,
			dirname( plugin_basename( ROSTEST_DOCTORS_PLUGIN_FILE ) ) . '/languages'
		);
	}
);

register_activation_hook(
	ROSTEST_DOCTORS_PLUGIN_FILE,
	static function (): void {
		rostest_doctors_register_post_types();
		rostest_doctors_register_taxonomies();
		flush_rewrite_rules();
	}
);

register_deactivation_hook(
	ROSTEST_DOCTORS_PLUGIN_FILE,
	static function (): void {
		flush_rewrite_rules();
	}
);
