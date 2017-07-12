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

/**
 * Fetches all available countries for sale
 *
 * @return array
 */
function rcpaf_get_all_countries() {

	// todo: build in filter to omit certain countries
	return array(
		'AF' => __( 'Afghanistan', 'rcp-address-fields' ),
		'AX' => __( '&#197;land Islands', 'rcp-address-fields' ),
		'AL' => __( 'Albania', 'rcp-address-fields' ),
		'DZ' => __( 'Algeria', 'rcp-address-fields' ),
		'AS' => __( 'American Samoa', 'rcp-address-fields' ),
		'AD' => __( 'Andorra', 'rcp-address-fields' ),
		'AO' => __( 'Angola', 'rcp-address-fields' ),
		'AI' => __( 'Anguilla', 'rcp-address-fields' ),
		'AQ' => __( 'Antarctica', 'rcp-address-fields' ),
		'AG' => __( 'Antigua and Barbuda', 'rcp-address-fields' ),
		'AR' => __( 'Argentina', 'rcp-address-fields' ),
		'AM' => __( 'Armenia', 'rcp-address-fields' ),
		'AW' => __( 'Aruba', 'rcp-address-fields' ),
		'AU' => __( 'Australia', 'rcp-address-fields' ),
		'AT' => __( 'Austria', 'rcp-address-fields' ),
		'AZ' => __( 'Azerbaijan', 'rcp-address-fields' ),
		'BS' => __( 'Bahamas', 'rcp-address-fields' ),
		'BH' => __( 'Bahrain', 'rcp-address-fields' ),
		'BD' => __( 'Bangladesh', 'rcp-address-fields' ),
		'BB' => __( 'Barbados', 'rcp-address-fields' ),
		'BY' => __( 'Belarus', 'rcp-address-fields' ),
		'BE' => __( 'Belgium', 'rcp-address-fields' ),
		'PW' => __( 'Belau', 'rcp-address-fields' ),
		'BZ' => __( 'Belize', 'rcp-address-fields' ),
		'BJ' => __( 'Benin', 'rcp-address-fields' ),
		'BM' => __( 'Bermuda', 'rcp-address-fields' ),
		'BT' => __( 'Bhutan', 'rcp-address-fields' ),
		'BO' => __( 'Bolivia', 'rcp-address-fields' ),
		'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'rcp-address-fields' ),
		'BA' => __( 'Bosnia and Herzegovina', 'rcp-address-fields' ),
		'BW' => __( 'Botswana', 'rcp-address-fields' ),
		'BV' => __( 'Bouvet Island', 'rcp-address-fields' ),
		'BR' => __( 'Brazil', 'rcp-address-fields' ),
		'IO' => __( 'British Indian Ocean Territory', 'rcp-address-fields' ),
		'VG' => __( 'British Virgin Islands', 'rcp-address-fields' ),
		'BN' => __( 'Brunei', 'rcp-address-fields' ),
		'BG' => __( 'Bulgaria', 'rcp-address-fields' ),
		'BF' => __( 'Burkina Faso', 'rcp-address-fields' ),
		'BI' => __( 'Burundi', 'rcp-address-fields' ),
		'KH' => __( 'Cambodia', 'rcp-address-fields' ),
		'CM' => __( 'Cameroon', 'rcp-address-fields' ),
		'CA' => __( 'Canada', 'rcp-address-fields' ),
		'CV' => __( 'Cape Verde', 'rcp-address-fields' ),
		'KY' => __( 'Cayman Islands', 'rcp-address-fields' ),
		'CF' => __( 'Central African Republic', 'rcp-address-fields' ),
		'TD' => __( 'Chad', 'rcp-address-fields' ),
		'CL' => __( 'Chile', 'rcp-address-fields' ),
		'CN' => __( 'China', 'rcp-address-fields' ),
		'CX' => __( 'Christmas Island', 'rcp-address-fields' ),
		'CC' => __( 'Cocos (Keeling) Islands', 'rcp-address-fields' ),
		'CO' => __( 'Colombia', 'rcp-address-fields' ),
		'KM' => __( 'Comoros', 'rcp-address-fields' ),
		'CG' => __( 'Congo (Brazzaville)', 'rcp-address-fields' ),
		'CD' => __( 'Congo (Kinshasa)', 'rcp-address-fields' ),
		'CK' => __( 'Cook Islands', 'rcp-address-fields' ),
		'CR' => __( 'Costa Rica', 'rcp-address-fields' ),
		'HR' => __( 'Croatia', 'rcp-address-fields' ),
		'CU' => __( 'Cuba', 'rcp-address-fields' ),
		'CW' => __( 'Cura&ccedil;ao', 'rcp-address-fields' ),
		'CY' => __( 'Cyprus', 'rcp-address-fields' ),
		'CZ' => __( 'Czech Republic', 'rcp-address-fields' ),
		'DK' => __( 'Denmark', 'rcp-address-fields' ),
		'DJ' => __( 'Djibouti', 'rcp-address-fields' ),
		'DM' => __( 'Dominica', 'rcp-address-fields' ),
		'DO' => __( 'Dominican Republic', 'rcp-address-fields' ),
		'EC' => __( 'Ecuador', 'rcp-address-fields' ),
		'EG' => __( 'Egypt', 'rcp-address-fields' ),
		'SV' => __( 'El Salvador', 'rcp-address-fields' ),
		'GQ' => __( 'Equatorial Guinea', 'rcp-address-fields' ),
		'ER' => __( 'Eritrea', 'rcp-address-fields' ),
		'EE' => __( 'Estonia', 'rcp-address-fields' ),
		'ET' => __( 'Ethiopia', 'rcp-address-fields' ),
		'FK' => __( 'Falkland Islands', 'rcp-address-fields' ),
		'FO' => __( 'Faroe Islands', 'rcp-address-fields' ),
		'FJ' => __( 'Fiji', 'rcp-address-fields' ),
		'FI' => __( 'Finland', 'rcp-address-fields' ),
		'FR' => __( 'France', 'rcp-address-fields' ),
		'GF' => __( 'French Guiana', 'rcp-address-fields' ),
		'PF' => __( 'French Polynesia', 'rcp-address-fields' ),
		'TF' => __( 'French Southern Territories', 'rcp-address-fields' ),
		'GA' => __( 'Gabon', 'rcp-address-fields' ),
		'GM' => __( 'Gambia', 'rcp-address-fields' ),
		'GE' => __( 'Georgia', 'rcp-address-fields' ),
		'DE' => __( 'Germany', 'rcp-address-fields' ),
		'GH' => __( 'Ghana', 'rcp-address-fields' ),
		'GI' => __( 'Gibraltar', 'rcp-address-fields' ),
		'GR' => __( 'Greece', 'rcp-address-fields' ),
		'GL' => __( 'Greenland', 'rcp-address-fields' ),
		'GD' => __( 'Grenada', 'rcp-address-fields' ),
		'GP' => __( 'Guadeloupe', 'rcp-address-fields' ),
		'GU' => __( 'Guam', 'rcp-address-fields' ),
		'GT' => __( 'Guatemala', 'rcp-address-fields' ),
		'GG' => __( 'Guernsey', 'rcp-address-fields' ),
		'GN' => __( 'Guinea', 'rcp-address-fields' ),
		'GW' => __( 'Guinea-Bissau', 'rcp-address-fields' ),
		'GY' => __( 'Guyana', 'rcp-address-fields' ),
		'HT' => __( 'Haiti', 'rcp-address-fields' ),
		'HM' => __( 'Heard Island and McDonald Islands', 'rcp-address-fields' ),
		'HN' => __( 'Honduras', 'rcp-address-fields' ),
		'HK' => __( 'Hong Kong', 'rcp-address-fields' ),
		'HU' => __( 'Hungary', 'rcp-address-fields' ),
		'IS' => __( 'Iceland', 'rcp-address-fields' ),
		'IN' => __( 'India', 'rcp-address-fields' ),
		'ID' => __( 'Indonesia', 'rcp-address-fields' ),
		'IR' => __( 'Iran', 'rcp-address-fields' ),
		'IQ' => __( 'Iraq', 'rcp-address-fields' ),
		'IE' => __( 'Ireland', 'rcp-address-fields' ),
		'IM' => __( 'Isle of Man', 'rcp-address-fields' ),
		'IL' => __( 'Israel', 'rcp-address-fields' ),
		'IT' => __( 'Italy', 'rcp-address-fields' ),
		'CI' => __( 'Ivory Coast', 'rcp-address-fields' ),
		'JM' => __( 'Jamaica', 'rcp-address-fields' ),
		'JP' => __( 'Japan', 'rcp-address-fields' ),
		'JE' => __( 'Jersey', 'rcp-address-fields' ),
		'JO' => __( 'Jordan', 'rcp-address-fields' ),
		'KZ' => __( 'Kazakhstan', 'rcp-address-fields' ),
		'KE' => __( 'Kenya', 'rcp-address-fields' ),
		'KI' => __( 'Kiribati', 'rcp-address-fields' ),
		'KW' => __( 'Kuwait', 'rcp-address-fields' ),
		'KG' => __( 'Kyrgyzstan', 'rcp-address-fields' ),
		'LA' => __( 'Laos', 'rcp-address-fields' ),
		'LV' => __( 'Latvia', 'rcp-address-fields' ),
		'LB' => __( 'Lebanon', 'rcp-address-fields' ),
		'LS' => __( 'Lesotho', 'rcp-address-fields' ),
		'LR' => __( 'Liberia', 'rcp-address-fields' ),
		'LY' => __( 'Libya', 'rcp-address-fields' ),
		'LI' => __( 'Liechtenstein', 'rcp-address-fields' ),
		'LT' => __( 'Lithuania', 'rcp-address-fields' ),
		'LU' => __( 'Luxembourg', 'rcp-address-fields' ),
		'MO' => __( 'Macao S.A.R., China', 'rcp-address-fields' ),
		'MK' => __( 'Macedonia', 'rcp-address-fields' ),
		'MG' => __( 'Madagascar', 'rcp-address-fields' ),
		'MW' => __( 'Malawi', 'rcp-address-fields' ),
		'MY' => __( 'Malaysia', 'rcp-address-fields' ),
		'MV' => __( 'Maldives', 'rcp-address-fields' ),
		'ML' => __( 'Mali', 'rcp-address-fields' ),
		'MT' => __( 'Malta', 'rcp-address-fields' ),
		'MH' => __( 'Marshall Islands', 'rcp-address-fields' ),
		'MQ' => __( 'Martinique', 'rcp-address-fields' ),
		'MR' => __( 'Mauritania', 'rcp-address-fields' ),
		'MU' => __( 'Mauritius', 'rcp-address-fields' ),
		'YT' => __( 'Mayotte', 'rcp-address-fields' ),
		'MX' => __( 'Mexico', 'rcp-address-fields' ),
		'FM' => __( 'Micronesia', 'rcp-address-fields' ),
		'MD' => __( 'Moldova', 'rcp-address-fields' ),
		'MC' => __( 'Monaco', 'rcp-address-fields' ),
		'MN' => __( 'Mongolia', 'rcp-address-fields' ),
		'ME' => __( 'Montenegro', 'rcp-address-fields' ),
		'MS' => __( 'Montserrat', 'rcp-address-fields' ),
		'MA' => __( 'Morocco', 'rcp-address-fields' ),
		'MZ' => __( 'Mozambique', 'rcp-address-fields' ),
		'MM' => __( 'Myanmar', 'rcp-address-fields' ),
		'NA' => __( 'Namibia', 'rcp-address-fields' ),
		'NR' => __( 'Nauru', 'rcp-address-fields' ),
		'NP' => __( 'Nepal', 'rcp-address-fields' ),
		'NL' => __( 'Netherlands', 'rcp-address-fields' ),
		'NC' => __( 'New Caledonia', 'rcp-address-fields' ),
		'NZ' => __( 'New Zealand', 'rcp-address-fields' ),
		'NI' => __( 'Nicaragua', 'rcp-address-fields' ),
		'NE' => __( 'Niger', 'rcp-address-fields' ),
		'NG' => __( 'Nigeria', 'rcp-address-fields' ),
		'NU' => __( 'Niue', 'rcp-address-fields' ),
		'NF' => __( 'Norfolk Island', 'rcp-address-fields' ),
		'MP' => __( 'Northern Mariana Islands', 'rcp-address-fields' ),
		'KP' => __( 'North Korea', 'rcp-address-fields' ),
		'NO' => __( 'Norway', 'rcp-address-fields' ),
		'OM' => __( 'Oman', 'rcp-address-fields' ),
		'PK' => __( 'Pakistan', 'rcp-address-fields' ),
		'PS' => __( 'Palestinian Territory', 'rcp-address-fields' ),
		'PA' => __( 'Panama', 'rcp-address-fields' ),
		'PG' => __( 'Papua New Guinea', 'rcp-address-fields' ),
		'PY' => __( 'Paraguay', 'rcp-address-fields' ),
		'PE' => __( 'Peru', 'rcp-address-fields' ),
		'PH' => __( 'Philippines', 'rcp-address-fields' ),
		'PN' => __( 'Pitcairn', 'rcp-address-fields' ),
		'PL' => __( 'Poland', 'rcp-address-fields' ),
		'PT' => __( 'Portugal', 'rcp-address-fields' ),
		'PR' => __( 'Puerto Rico', 'rcp-address-fields' ),
		'QA' => __( 'Qatar', 'rcp-address-fields' ),
		'RE' => __( 'Reunion', 'rcp-address-fields' ),
		'RO' => __( 'Romania', 'rcp-address-fields' ),
		'RU' => __( 'Russia', 'rcp-address-fields' ),
		'RW' => __( 'Rwanda', 'rcp-address-fields' ),
		'BL' => __( 'Saint Barth&eacute;lemy', 'rcp-address-fields' ),
		'SH' => __( 'Saint Helena', 'rcp-address-fields' ),
		'KN' => __( 'Saint Kitts and Nevis', 'rcp-address-fields' ),
		'LC' => __( 'Saint Lucia', 'rcp-address-fields' ),
		'MF' => __( 'Saint Martin (French part)', 'rcp-address-fields' ),
		'SX' => __( 'Saint Martin (Dutch part)', 'rcp-address-fields' ),
		'PM' => __( 'Saint Pierre and Miquelon', 'rcp-address-fields' ),
		'VC' => __( 'Saint Vincent and the Grenadines', 'rcp-address-fields' ),
		'SM' => __( 'San Marino', 'rcp-address-fields' ),
		'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'rcp-address-fields' ),
		'SA' => __( 'Saudi Arabia', 'rcp-address-fields' ),
		'SN' => __( 'Senegal', 'rcp-address-fields' ),
		'RS' => __( 'Serbia', 'rcp-address-fields' ),
		'SC' => __( 'Seychelles', 'rcp-address-fields' ),
		'SL' => __( 'Sierra Leone', 'rcp-address-fields' ),
		'SG' => __( 'Singapore', 'rcp-address-fields' ),
		'SK' => __( 'Slovakia', 'rcp-address-fields' ),
		'SI' => __( 'Slovenia', 'rcp-address-fields' ),
		'SB' => __( 'Solomon Islands', 'rcp-address-fields' ),
		'SO' => __( 'Somalia', 'rcp-address-fields' ),
		'ZA' => __( 'South Africa', 'rcp-address-fields' ),
		'GS' => __( 'South Georgia/Sandwich Islands', 'rcp-address-fields' ),
		'KR' => __( 'South Korea', 'rcp-address-fields' ),
		'SS' => __( 'South Sudan', 'rcp-address-fields' ),
		'ES' => __( 'Spain', 'rcp-address-fields' ),
		'LK' => __( 'Sri Lanka', 'rcp-address-fields' ),
		'SD' => __( 'Sudan', 'rcp-address-fields' ),
		'SR' => __( 'Suriname', 'rcp-address-fields' ),
		'SJ' => __( 'Svalbard and Jan Mayen', 'rcp-address-fields' ),
		'SZ' => __( 'Swaziland', 'rcp-address-fields' ),
		'SE' => __( 'Sweden', 'rcp-address-fields' ),
		'CH' => __( 'Switzerland', 'rcp-address-fields' ),
		'SY' => __( 'Syria', 'rcp-address-fields' ),
		'TW' => __( 'Taiwan', 'rcp-address-fields' ),
		'TJ' => __( 'Tajikistan', 'rcp-address-fields' ),
		'TZ' => __( 'Tanzania', 'rcp-address-fields' ),
		'TH' => __( 'Thailand', 'rcp-address-fields' ),
		'TL' => __( 'Timor-Leste', 'rcp-address-fields' ),
		'TG' => __( 'Togo', 'rcp-address-fields' ),
		'TK' => __( 'Tokelau', 'rcp-address-fields' ),
		'TO' => __( 'Tonga', 'rcp-address-fields' ),
		'TT' => __( 'Trinidad and Tobago', 'rcp-address-fields' ),
		'TN' => __( 'Tunisia', 'rcp-address-fields' ),
		'TR' => __( 'Turkey', 'rcp-address-fields' ),
		'TM' => __( 'Turkmenistan', 'rcp-address-fields' ),
		'TC' => __( 'Turks and Caicos Islands', 'rcp-address-fields' ),
		'TV' => __( 'Tuvalu', 'rcp-address-fields' ),
		'UG' => __( 'Uganda', 'rcp-address-fields' ),
		'UA' => __( 'Ukraine', 'rcp-address-fields' ),
		'AE' => __( 'United Arab Emirates', 'rcp-address-fields' ),
		'GB' => __( 'United Kingdom (UK)', 'rcp-address-fields' ),
		'US' => __( 'United States (US)', 'rcp-address-fields' ),
		'UM' => __( 'United States (US) Minor Outlying Islands', 'rcp-address-fields' ),
		'VI' => __( 'United States (US) Virgin Islands', 'rcp-address-fields' ),
		'UY' => __( 'Uruguay', 'rcp-address-fields' ),
		'UZ' => __( 'Uzbekistan', 'rcp-address-fields' ),
		'VU' => __( 'Vanuatu', 'rcp-address-fields' ),
		'VA' => __( 'Vatican', 'rcp-address-fields' ),
		'VE' => __( 'Venezuela', 'rcp-address-fields' ),
		'VN' => __( 'Vietnam', 'rcp-address-fields' ),
		'WF' => __( 'Wallis and Futuna', 'rcp-address-fields' ),
		'EH' => __( 'Western Sahara', 'rcp-address-fields' ),
		'WS' => __( 'Samoa', 'rcp-address-fields' ),
		'YE' => __( 'Yemen', 'rcp-address-fields' ),
		'ZM' => __( 'Zambia', 'rcp-address-fields' ),
		'ZW' => __( 'Zimbabwe', 'rcp-address-fields' ),
	);
}