<?php
/*
Plugin Name: RCP Address Fields
Plugin URI: http://www.vanpattenmedia.com/
Description: Adds address fields to user registration, edit, and admin interfaces.
Version: 0.1--dev
Text Domain: rcp-address-fields
Domain Path: /languages
Author: Van Patten Media Inc.
Author URI: https://www.vanpattenmedia.com/
Contributors: chrisvanpatten, mcfarlan
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Load dependencies
require_once __DIR__ . '/rcp-address-fields-utils.php';

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

/**
 * Display notice to install and activate RCP
 */
function rcpaf_notice_activate_rcp(){
	$message = '<strong>Warning!</strong> Restrict Content Pro needs to be installed and activated for Address User Fields for RCP.';
	$message .= rcpaf_get_plugins_path() . '/restrict-content-pro/restrict-content-pro.php';

	rcpaf_build_admin_notice( $message, 'error' );
}

