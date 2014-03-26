<?php
/*
Plugin Name: Content Dialog
Plugin URI:  https://github.com/mcascardi/wp-content-dialog
Description: The Content Dialog plugin
Version:     0.1
Author:      Matthew Cascardi
Author URI:  http://contentdialog.com
*/

/*
 * This plugin was built on top of WordPress-Plugin-Skeleton by Ian Dunn.
 * See https://github.com/iandunn/WordPress-Plugin-Skeleton for details.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

define( 'WPCD_NAME',                 'Content Dialog' );
define( 'WPCD_REQUIRED_PHP_VERSION', '5.3' );                          // because of get_called_class()
define( 'WPCD_REQUIRED_WP_VERSION',  '3.1' );                          // because of esc_textarea()

/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
function wpcd_requirements_met() {
	global $wp_version;
	//require_once( ABSPATH . '/wp-admin/includes/plugin.php' );		// to get is_plugin_active() early

	if ( version_compare( PHP_VERSION, WPCD_REQUIRED_PHP_VERSION, '<' ) ) {
		return false;
	}

	if ( version_compare( $wp_version, WPCD_REQUIRED_WP_VERSION, '<' ) ) {
		return false;
	}

	/*
	if ( ! is_plugin_active( 'plugin-directory/plugin-file.php' ) ) {
		return false;
	}
	*/

	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 */
function wpcd_requirements_error() {
	global $wp_version;

	require_once( dirname( __FILE__ ) . '/views/requirements-error.php' );
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if ( cd_requirements_met() ) {
	require_once( __DIR__ . '/classes/wpcd-module.php' );
	require_once( __DIR__ . '/classes/wordpress-plugin-skeleton.php' );
	require_once( __DIR__ . '/includes/IDAdminNotices/id-admin-notices.php' );
	require_once( __DIR__ . '/classes/wpcd-custom-post-type.php' );
	require_once( __DIR__ . '/classes/wpcd-cpt-example.php' );
	require_once( __DIR__ . '/classes/wpcd-settings.php' );
	require_once( __DIR__ . '/classes/wpcd-cron.php' );
	require_once( __DIR__ . '/classes/wpcd-instance-class.php' );

	if ( class_exists( 'WordPress_Content_Dialog' ) ) {
		$GLOBALS['wpcd'] = WordPress_Content_Dialog::get_instance();
		register_activation_hook(   __FILE__, array( $GLOBALS['wpcd'], 'activate' ) );
		register_deactivation_hook( __FILE__, array( $GLOBALS['wpcd'], 'deactivate' ) );
	}
} else {
	add_action( 'admin_notices', 'wpcd_requirements_error' );
}

