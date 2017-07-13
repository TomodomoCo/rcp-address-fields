<?php


/**
 * Overrides the default country listing
 *
 * @return array $countries
 */
function rcpaf_restrict_countries_filter() {
	$countries = array(
		'US' => __( 'United States (US)', 'rcp-address-fields' ),
		'CA' => __( 'Canada', 'rcp-address-fields' ),
		'GB' => __( 'United Kingdom (UK)', 'rcp-address-fields' )
	);

	return $countries;
}
// add_filter( 'rcpaf_restrict_countries', 'rcpaf_restrict_countries_filter' );

/**
 * Overrides the which fields are required for addresses
 *
 * @return array $required_fields
 */
function rcpaf_required_fields_filter() {
	$required_fields = array(
		'address_1',
		'city',
		'state',
		'country'
	);

	return $required_fields;
}
// add_filter( 'rcpaf_required_fields', 'rcpaf_required_fields_filter' );

/**
 * Overrides which fields to save for address fields
 *
 * @return array $fields_to_save
 */
function rcpaf_fields_to_save_filter() {
	$fields_to_save = array(
		'address_1',
		'address_2',
		'city',
		'state',
		'country'
	);
	
	return $fields_to_save;
}
// add_action( 'rcpaf_fields_to_save', 'rcpaf_fields_to_save_filter' );

/**
 * Determines which address fields should be select inputs `select > option`
 *
 * @return array $select_fields
 */
function rcpaf_select_field_names_filter() {
	$select_fields = array(
		'country'
	);

	return $select_fields;
}
// add_filter( 'rcpaf_select_field_names', 'rcpaf_select_field_names_filter' );