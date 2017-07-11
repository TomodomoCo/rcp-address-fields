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
 * Fetches address form field labels
 *
 * @param string    $field_slug
 * @return string
 */
function rcpaf_get_field_label( $field_slug ) {
	switch ( $field_slug ) {
		
		case 'address_1':
			return __( 'Address Line 1', 'rcp-address-fields' );
			break;

		case 'address_2':
			return __( 'Address Line 2', 'rcp-address-fields' );
			break;

		case 'city':
			return __( 'City', 'rcp-address-fields' );
			break;

		case 'state':
			return __( 'State/Province', 'rcp-address-fields' );
			break;

		case 'country':
			return __( 'Country', 'rcp-address-fields' );
			break;
	}
}

function rcpaf_get_field_data( $field_slug, $user_id ) {
	$data = get_user_meta( $user_id, 'rcp_' . $field_slug, true );
	$label = rcpaf_get_field_label( $field_slug );

	// @todo: build in type of field (text, select)
	return array(
		'slug'  => $field_slug,
		'label' => $label,
		'data'  => $data
	);
}

/**
 * Adds the custom fields to the registration form and profile editor
 *
 * @param string|null   $user_id
 */
function rcpaf_add_user_fields( $user_id = null ) {
	if ( is_null( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	// Retrieve field data
	$address_1 = rcpaf_get_field_data( 'address_1', $user_id );
	$address_2 = rcpaf_get_field_data( 'address_2', $user_id );
	$city      = rcpaf_get_field_data( 'city', $user_id );
	$state     = rcpaf_get_field_data( 'state', $user_id );
	$country   = rcpaf_get_field_data( 'country', $user_id );

	// Loop over fields for output: slug, label data
	$fields   = [ $address_1, $address_2, $city, $state, $country ];
	$template = '<p><label for="rcp_profession">%2$s</label><input name="rcp_%1$s" id="rcp_profession" type="text" value="%3$s"></p>';

	foreach ( $fields as $field ) {
		_e( sprintf( $template, $field['slug'], $field['label'], $field['data'] ), 'rcp-address-fields' );
	}
}
add_action( 'rcp_before_subscription_form_fields', 'rcpaf_add_user_fields' );
add_action( 'rcp_profile_editor_after', 'rcpaf_add_user_fields' );