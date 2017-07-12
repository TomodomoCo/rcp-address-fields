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
	switch( $field_slug ) {
		
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
 * @param string    $field_slug
 * @param int       $user_id
 *
 * @return array
 */
function rcpaf_get_field_data( $field_slug, $user_id ) {
	$data  = get_user_meta( $user_id, 'rcp_' . $field_slug, true );
	$label = rcpaf_get_field_label( $field_slug );
	$type  = $field_slug == 'country' ? 'select' : 'text';

	// @todo: build in type of field (text, select)
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
 * @param int   $user_id
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
 * Print address fields to the registration form and profile editor
 *
 * @param string|null   $user_id
 */
function rcpaf_print_address_fields( $user_id = null ) {
	if( is_null( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	// Retrieve all the address fields
	$fields   = rcpaf_get_all_fields_data( $user_id );

	$text_field_markup = '<p><label for="rcp_profession">%2$s</label><input name="rcp_%1$s" id="rcp_profession" type="text" value="%3$s"></p>';

	foreach ( $fields as $field ) {

		// Text fields
		// todo: extract out to new function to add_filter/apply_filters
		if ( $field['type'] != 'select' ) {
			_e( sprintf( $text_field_markup, $field['slug'], $field['label'], $field['data'] ), 'rcp-address-fields' );

		// Select menus
		// todo: extract out to new function to add_filter/apply_filters
		} else {
			$countries = rcpaf_get_all_countries();

			$wrap = '<p><label for="rcp_country">%2$s</label><select name="rcp_country" id="rcp_country">%1$s</select></p>';
			$option = '<option value="%1$s">%2$s</option>';

			// todo: get current user's saved value and select if available
			$inner = '';
			foreach ( $countries as $country_code => $country_name ) {
				$inner .= sprintf( $option, $country_code, $country_name );
			}

			echo sprintf( $wrap, $inner, $field['label'] );
		}
	}
}
add_action( 'rcp_before_subscription_form_fields', 'rcpaf_print_address_fields' );
add_action( 'rcp_profile_editor_after', 'rcpaf_print_address_fields' );

/**
 * Print address fields to the member edit screen in wp-admin
 *
 * @param int|null  $user_id
 */
function rcpaf_print_address_fields_admin( $user_id = null ) {
	if( is_null( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$fields = rcpaf_get_all_fields_data( $user_id );

	// @todo: build in support for select menus
	foreach( $fields as $field ): ?>
		<tr valign="top">
			<th scope="row" valign="top">
				<label for="rcp_<?php echo $field['slug']; ?>">
					<?php echo $field['label']; ?>
				</label>
			</th>
			<td>
				<input name="rcp_<?php echo $field['slug']; ?>" id="rcp_<?php echo $field['slug']; ?>" type="text" value="<?php echo esc_attr( $field['data'] ); ?>">
			</td>
		</tr>
	<?php endforeach;
}
add_action( 'rcp_edit_member_after', 'rcpaf_print_address_fields_admin' );

/**
 * Validates address fields during registration
 *
 * @param array     $posted_data
 */
function rcpaf_validates_address_fields_on_register( $posted_data ) {
	$required_fields = array(
		'address_1',
		'city',
		'state',
		'country'
	);

	// Checks for empty fields
	foreach( $required_fields as $field ) {

		$field_slug = 'rcp_' . $field;

		if( empty( $posted_data[ $field_slug ] ) ) {
			$label = rcpaf_get_field_label( $field );
			rcp_errors()->add( 'invalid_address', __( 'Please enter your ' . $label, 'rcp-address-fields' ), 'register' );
		}
	}
}
add_action( 'rcp_form_errors', 'rcpaf_validates_address_fields_on_register', 10 );