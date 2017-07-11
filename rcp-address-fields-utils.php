<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Returns the absolute path of the WordPress plugins directory
 *
 * @todo: test on multisite to ensure paths still work
 * @return string   $path
 */
function rcpaf_get_plugins_path() {
	$path = WP_PLUGIN_DIR;

	if ( ! $path ) {
		$path = plugin_dir_path( __FILE__ );
		$path = str_replace( '/rcp-custom-user-fields/', '', $path );
	}

	return $path;
}

/**
 * Builds and prints notice for use in wp-admin dashboard
 *
 * @param string    $message
 * @param string    $type
 */
function rcpaf_build_admin_notice( $message, $type = 'error' ) {

	// @todo: remove is-dismissible or finish relevant work on dismissed state
	$wrap = '<div class="%2$s notice is-dismissible"><p>%1$s</p></div>';

	// Print in i18n friendly form
	_e( sprintf( $wrap, $message, $type ), 'rcp-address-fields' );
}

/**
 * Display notice to install and activate RCP
 */
function rcpaf_notice_activate_rcp(){
	$message = '<strong>Warning!</strong> Restrict Content Pro needs to be installed and activated for Address User Fields for RCP.';
	$message .= rcpaf_get_plugins_path() . '/restrict-content-pro/restrict-content-pro.php';

	// Builds and prints notice
	rcpaf_build_admin_notice( $message, 'error' );
}

/**
 * Checks if RCP is active. Self de-activates with notice if RCP unavailable
 *
 * @return bool
 */
function rcpaf_is_rcp_active() {
	if ( ! is_plugin_active( 'restrict-content-pro/restrict-content-pro.php' ) ) {

		// Display notice for RCP plugin requirement
		add_action('admin_notices', 'rcpaf_notice_activate_rcp');

		// Self deactivate for safety if RCP is unavailable
		deactivate_plugins( plugin_basename( __FILE__ ) );

		return false;
	}

	return true;
}
add_action( 'admin_init', 'rcpaf_is_rcp_active' );
