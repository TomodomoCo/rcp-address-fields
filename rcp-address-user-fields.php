<?php
/*
Plugin Name: RCP Address Fields
Plugin URI: http://www.vanpattenmedia.com/
Description: Adds address fields to user registration, edit, and admin interfaces.
Version: 0.0.1
Text Domain: rcp-address-fields
Domain Path: /languages
Author: Van Patten Media Inc.
Author URI: https://www.vanpattenmedia.com/
Contributors: chrisvanpatten, mcfarlan
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load dependencies
require_once __DIR__ . '/rcp-address-fields-utils.php';

/**
 * Fetches address form field labels
 *
 * @param string $field_slug
 *
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

/**
 * Fetches a field's label and any saved data for the current user
 *
 * @param string $field_slug
 * @param int    $user_id
 *
 * @return array
 */
function rcpaf_get_field_data( $field_slug, $user_id ) {
	$data          = get_user_meta( $user_id, 'rcp_' . $field_slug, true );
	$label         = rcpaf_get_field_label( $field_slug );
	$type          = 'text';
	$select_fields = apply_filters( 'rcpaf_select_field_names', [ 'country' ] );

	if ( in_array( $field_slug, $select_fields ) ) {
		$type = 'select';
	}

	return array(
		'slug'  => $field_slug,
		'label' => $label,
		'data'  => $data,
		'type'  => $type
	);
}

/**
 * Returns the all user address fields data
 *
 * @param int $user_id
 *
 * @return array
 */
function rcpaf_get_all_fields_data( $user_id ) {
	$address_1 = rcpaf_get_field_data( 'address_1', $user_id );
	$address_2 = rcpaf_get_field_data( 'address_2', $user_id );
	$city      = rcpaf_get_field_data( 'city', $user_id );
	$state     = rcpaf_get_field_data( 'state', $user_id );
	$country   = rcpaf_get_field_data( 'country', $user_id );

	return [ $address_1, $address_2, $city, $state, $country ];
}

/**
 * Outputs address form fields for all instances of edit/registration forms
 *
 * @param null|int $user_id
 */
function rcpaf_print_address_fields( $user_id = null ) {
	if ( is_null( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$fields = rcpaf_get_all_fields_data( $user_id );

	foreach ( $fields as $field ) {

		// todo: expand for other field types (email)
		// field type detection
		switch ( $field['type'] ) {

			case 'select':
				rcpaf_build_select_field( $field, false );
				break;

			case 'text':
				rcpaf_build_text_field( $field, false );
				break;

			default:
				rcpaf_build_text_field( $field, false );
		}
	}
}

add_action( 'rcp_edit_member_after', 'rcpaf_print_address_fields' );                   // admin ui
add_action( 'rcp_before_subscription_form_fields', 'rcpaf_print_address_fields' );     // front-facing register
add_action( 'rcp_profile_editor_after', 'rcpaf_print_address_fields' );                // front-facing register > edit my profile

/**
 * Prints a front-facing and admin text field
 *
 * @param array $field
 * @param bool  $frontend
 * @param bool  $print
 *
 * @return string    $field_html
 */
function rcpaf_build_text_field( $field, $frontend = true, $print = true ) {

	// todo: markup override filter

	// Front-facing text field
	if ( $frontend != false ) {
		$template   = '<p><label for="rcp_profession">%2$s</label><input name="rcp_%1$s" id="rcp_profession" type="%4$s" value="%3$s"></p>';
		$field_html = sprintf( $template, $field['slug'], $field['label'], $field['data'], $field['type'] );

		// Admin text field
	} else {
		$wrap = '<tr valign="top"><th scope="row" valign="top">%1$s</th><td>%2$s</td></tr>';

		$label = '<label for="rcp_%1$s">%2$s</label>';
		$label = sprintf( $label, $field['slug'], $field['label'] );
		$input = '<input name="rcp_%1$s" id="rcp_%1$s" type="%3$s" value="%2$s">';
		$input = sprintf( $input, $field['slug'], $field['data'], $field['type'] );

		$field_html = sprintf( $wrap, $label, $input );
	}

	if ( $print != true ) {
		return $field_html;
	}

	echo $field_html;
}

/**
 * Prints a front-facing and admin select field
 *
 * @param array $field
 * @param bool  $frontend
 * @param bool  $print
 *
 * @return string    $field_html
 */
function rcpaf_build_select_field( $field, $frontend = true, $print = true ) {

	// todo: extend to support than just countries
	$data = rcpaf_get_all_countries();

	// Front-facing select field
	if ( $frontend != false ) {
		$wrap   = '<p><label for="rcp_country">%2$s</label><select name="rcp_country" id="rcp_country">%1$s</select></p>';
		$option = '<option value="%1$s" %3$s>%2$s</option>';

		$inner = '';
		foreach ( $data as $key => $value ) {

			// selects the option if saved value available
			if ( $field['data'] == $key ) {
				$inner .= sprintf( $option, $key, $value, 'selected' );
			} else {
				$inner .= sprintf( $option, $key, $value, false );
			}
		}

		$output = sprintf( $wrap, $inner, $field['label'] );

		// Admin select field
	} else {
		$wrap   = '<tr valign="top"><th scope="row" valign="top">%1$s</th><td>%2$s</td></tr>';
		$option = '<option value="%1$s" %3$s>%2$s</option>';

		$label = '<label for="rcp_%1$s">%2$s</label>';
		$label = sprintf( $label, $field['slug'], $field['label'] );

		$output = '<select name="rcp_country" id="rcp_country">';
		foreach ( $data as $key => $value ) {

			// selects the option if saved value available
			if ( $field['data'] == $key ) {
				$output .= sprintf( $option, $key, $value, 'selected' );

			} else {
				$output .= sprintf( $option, $key, $value, false );
			}
		}
		$output .= '</select>';

		// Wrap the form field in a table row
		$output = sprintf( $wrap, $label, $output );
	}

	if ( $print != true ) {
		return $output;
	}

	echo $output;
}

/**
 * Validates address fields during registration
 *
 * @param array $posted_data
 */
function rcpaf_validates_address_fields_on_register( $posted_data ) {
	$required_fields = array(
		'address_1',
		'city',
		'state',
		'country'
	);

	$required_fields = apply_filters( 'rcpaf_required_fields', $required_fields );

	// Checks for empty fields
	foreach ( $required_fields as $field ) {

		$field_slug = 'rcp_' . $field;

		if ( empty( $posted_data[$field_slug] ) ) {
			$label = rcpaf_get_field_label( $field );
			rcp_errors()->add( 'invalid_address', __( 'Please enter your ' . $label, 'rcp-address-fields' ), 'register' );
		}
	}

	// todo: add more validation on a per field basis; maybe pass attr like `required`, `email`, etc. maybe http://respect.github.io/Validation/
}

add_action( 'rcp_form_errors', 'rcpaf_validates_address_fields_on_register', 10 );

/**
 * Save custom form values during registration, in admin UIs, and front-facing UIs (see `rcpaf_save_on_front_facing_submission`)
 *
 * @param array $posted_data
 * @param int   $user_id
 */
function rcpaf_save_form_fields( $posted_data, $user_id = null ) {
	if ( is_null( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$fields_to_save = array(
		'address_1',
		'address_2',
		'city',
		'state',
		'country'
	);

	$fields_to_save = apply_filters( 'rcpaf_fields_to_save', $fields_to_save );

	foreach ( $posted_data as $field_name => $value ) {

		// normalize field slug (remove `rcp_` prefix)
		$field_slug = substr( $field_name, 4 );

		// save if field name is flagged
		if ( in_array( $field_slug, $fields_to_save ) && isset( $value ) ) {

			// Save field to user meta
			update_user_meta(
				$user_id,
				$field_name,
				sanitize_text_field( $value )
			);
		}
	}
}

add_action( 'rcp_form_processing', 'rcpaf_save_form_fields', 10, 2 );

/**
 * Saves custom form fields for front-facing UIs
 *
 * @param $user_id
 */
function rcpaf_save_on_front_facing_submission( $user_id ) {
	$posted_data = $_POST;

	// Saves address fields in user meta
	rcpaf_save_form_fields( $posted_data, $user_id );
}

add_action( 'rcp_user_profile_updated', 'rcpaf_save_on_front_facing_submission', 10 );
add_action( 'rcp_edit_member', 'rcpaf_save_on_front_facing_submission', 10 );