<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Returns the absolute path of the WordPress plugins directory
 *
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
	$wrap = '<div class="%2$s notice is-dismissible"><p>%1$s</p></div>';
	_e( sprintf( $wrap, $message, $type ), 'rcp-address-fields' );
}