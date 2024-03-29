<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Currency converter
 */
if ( ! function_exists( 'trav_currency_converter' ) ) {
	function trav_currency_converter( $amount, $from_currency, $to_currency ) {
		if ( $from_currency == $to_currency ) return $amount;
		$converter = 'google_converter';
		$converted_amount = 0.0;

		if ( $converter == 'google_converter' ) {
			//google currency convert
			$amount = urlencode($amount);
			$from_currency = urlencode($from_currency);
			$to_currency = urlencode($to_currency);
			$remote_get_raw = wp_remote_get( "https://www.google.com/finance/converter?a=$amount&from={$from_currency}&to={$to_currency}" );
			$result = '';
			if ( ! is_wp_error( $remote_get_raw ) ) {
				$result = $remote_get_raw['body'];
				$result = explode("<span class=bld>",$result);
				if ( is_array( $result ) && isset( $result[1] ) ) {
					$result = explode("</span>",$result[1]);
				} else {
					return false;
				}
			} else {
				return false;
			}

			$converted_amount = floatval( preg_replace("/[^0-9\.]/", null, $result[0]) );
		} else {
			//
		}

		return $converted_amount;
	}
}

/*
 * Get all currencies from DB
 */
if ( ! function_exists( 'trav_get_all_available_currencies' ) ) {
	function trav_get_all_available_currencies() {
		/*$all_currencies = array(
			  'usd' => __( 'US Dollars', 'trav' ),
			  'aed' => __( 'United Arab Emirates Dirham', 'trav' ),
			  'ars' => __( 'Argentine Pesos', 'trav' ),
			  'aud' => __( 'Australian Dollars', 'trav' ),
			  'bgn' => __( 'Bulgarian Lev', 'trav' ),
			  'bob' => __( 'Bolivian Boliviano', 'trav' ),
			  'brl' => __( 'Brazilian Real', 'trav' ),
			  'cad' => __( 'Canadian Dollars', 'trav' ),
			  'chf' => __( 'Swiss Francs', 'trav' ),
			  'clp' => __( 'Chilean Peso', 'trav' ),
			  'cny' => __( 'Yuan Renminbi', 'trav' ),
			  'cop' => __( 'Colombian Peso', 'trav' ),
			  'czk' => __( 'Czech Koruna', 'trav' ),
			  'dkk' => __( 'Denmark Kroner', 'trav' ),
			  'egp' => __( 'Egyptian Pound', 'trav' ),
			  'eur' => __( 'Euros', 'trav' ),
			  'frf' => __( 'French Francs', 'trav' ),
			  'gbp' => __( 'British Pounds', 'trav' ),
			  'hkd' => __( 'Hong Kong Dollars', 'trav' ),
			  'hrk' => __( 'Croatian Kuna', 'trav' ),
			  'huf' => __( 'Hungarian Forint', 'trav' ),
			  'idr' => __( 'Indonesian Rupiah', 'trav' ),
			  'ils' => __( 'Israeli Shekel', 'trav' ),
			  'inr' => __( 'Indian Rupee', 'trav' ),
			  'jpy' => __( 'Japanese Yen', 'trav' ),
			  'krw' => __( 'South Korean Won', 'trav' ),
			  'ltl' => __( 'Lithuanian Litas', 'trav' ),
			  'mad' => __( 'Moroccan Dirham', 'trav' ),
			  'mxn' => __( 'Mexican Peso', 'trav' ),
			  'myr' => __( 'Malaysian Ringgit', 'trav' ),
			  'nok' => __( 'Norway Kroner', 'trav' ),
			  'nzd' => __( 'New Zealand Dollars', 'trav' ),
			  'pen' => __( 'Peruvian Nuevo Sol', 'trav' ),
			  'php' => __( 'Philippine Peso', 'trav' ),
			  'pkr' => __( 'Pakistan Rupee', 'trav' ),
			  'pln' => __( 'Polish New Zloty', 'trav' ),
			  'ron' => __( 'New Romanian Leu', 'trav' ),
			  'rsd' => __( 'Serbian Dinar', 'trav' ),
			  'rub' => __( 'Russian Ruble', 'trav' ),
			  'sar' => __( 'Saudi Riyal', 'trav' ),
			  'sek' => __( 'Sweden Kronor', 'trav' ),
			  'sgd' => __( 'Singapore Dollars', 'trav' ),
			  'thb' => __( 'Thai Baht', 'trav' ),
			  'trl' => __( 'Turkish Lira', 'trav' ),
			  'twd' => __( 'New Taiwan Dollar', 'trav' ),
			  'uah' => __( 'Ukrainian Hryvnia', 'trav' ),
			  'vef' => __( 'Venezuela Bolivar Fuerte', 'trav' ),
			  'vnd' => __( 'Vietnamese Dong', 'trav' ),
			  'zar' => __( 'South African Rand', 'trav' ),
			 );*/
		global $wpdb;
		$sql = "SELECT * FROM " . TRAV_CURRENCIES_TABLE;
		$results = $wpdb->get_results( $sql, ARRAY_A );
		$all_currencies = array();
		foreach ( $results as $result ) {
			if ( ! empty( $result['currency_code'] ) ) $all_currencies[ strtolower( $result['currency_code'] ) ] = $result['currency_label'];
		}
		return $all_currencies;
	}
}

/*
 * Get default site currencies after theme setup
 */
if ( ! function_exists( 'trav_get_default_available_currencies' ) ) {
	function trav_get_default_available_currencies() {
		return array(
			 'usd' => '1',
			 'eur' => '1'
		   );
	}
}

/*
 * Get currency symbol from currency code
 */
if ( ! function_exists( 'trav_get_currency_symbol' ) ) {
	function trav_get_currency_symbol( $currency_code ) {
		global $wpdb;
		if ( empty( $currency_code ) ) return false;
		$sql = "SELECT currency_symbol FROM " . TRAV_CURRENCIES_TABLE . " where currency_code = '" . esc_sql( $currency_code ) . "' limit 1";
		$result = $wpdb->get_var( $sql );
		return $result;
	}
}

/*
 * Get current user currency
 */
if ( ! function_exists( 'trav_get_user_currency' ) ) {
	function trav_get_user_currency() {
		global $trav_options;
		$currency = isset( $trav_options['def_currency'] )?$trav_options['def_currency']:'usd';
		if ( ! empty( $_GET['selected_currency'] ) ) {
			$currency = sanitize_text_field( $_GET['selected_currency'] );
		} elseif ( ! empty( $_SESSION['user_currency'] ) ) {
			$currency = $_SESSION['user_currency'];
		} elseif ( ! empty( $_COOKIE['selected_currency'] ) ) {
			$currency = sanitize_text_field( $_COOKIE['selected_currency'] );
		}
		if ( ! array_key_exists( $currency, $trav_options['site_currencies'] ) ) {
			$currency = isset( $trav_options['def_currency'] )?$trav_options['def_currency']:'usd';
		}
		return $currency;
	}
}

/*
 * Return currency field with exchanged value and currency symbol
 */
if ( ! function_exists( 'trav_get_price_field' ) ) {
	function trav_get_price_field( $amount, $currency = '', $convert = 1 ) {
		global $trav_options;
		$exchange_rate = 1;
		$currency_symbol = '';
		$def_currency = isset( $trav_options['def_currency'] )?$trav_options['def_currency']:'usd';
		if ( empty( $currency ) ) {
			if ( ! isset( $_SESSION['exchange_rate'] ) ) trav_init_currency();
			$exchange_rate = $_SESSION['exchange_rate'];
			$currency_symbol = $_SESSION['currency_symbol'];
		} else {
			$exchange_rate = trav_currency_converter( 1 , $def_currency, $currency );
			$currency_symbol = trav_get_currency_symbol( $currency );
		}
		if ( $convert ) { $amount *= $exchange_rate; }

		$cf_data = trav_get_currency_format_data();
		$price_label = number_format( $amount, $cf_data['desimal_prec'], $cf_data['dec_point'], $cf_data['thousands_sep'] );

		if ( $cf_data['cs_pos'] == 'after' ) {
			return $price_label . $currency_symbol;
		}
		return $currency_symbol . $price_label;
	}
}

/*
 * Return site defaul currency symbol
 */
if ( ! function_exists( 'trav_get_site_currency_symbol' ) ) {
	function trav_get_site_currency_symbol() {
		global $trav_options;
		$def_currency_code = ( empty( $trav_options['def_currency'] ) )?'usd':$trav_options['def_currency'];
		return trav_get_currency_symbol( $def_currency_code );
	}
}

/*
 * init curency function
 */
if ( ! function_exists( 'trav_init_currency' ) ) {
	function trav_init_currency() {
		global $def_currency, $trav_options;

		if ( isset( $_GET['selected_currency'] ) ) {
			if ( ! array_key_exists( $_GET['selected_currency'] , $trav_options['site_currencies'] ) ) {
				$_GET['selected_currency'] = $def_currency;
			}
			$_SESSION['user_currency'] = sanitize_text_field( $_GET['selected_currency'] );
			$_SESSION['exchange_rate'] = trav_currency_converter( 1 , $def_currency, $_SESSION['user_currency'] );
			$_SESSION['currency_symbol'] = trav_get_currency_symbol( $_SESSION['user_currency'] );

			setcookie("selected_currency", $_SESSION['user_currency'], time()+3600*24*365);
			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				update_user_meta( $current_user->ID, 'selected_currency', $_SESSION['user_currency'] );
			}

		}

		//user_currency init
		if ( ! isset( $_SESSION['user_currency'] ) ) {
			$user_currency = '';
			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				$user_currency = get_user_meta( $current_user->ID, 'selected_currency', true );
			}

			if ( ! empty( $user_currency ) ) {
				$_SESSION['user_currency'] = $user_currency;
			} elseif ( isset( $_COOKIE['selected_currency'] ) ) {
				$_SESSION['user_currency'] = $_COOKIE['selected_currency'];
			} else {
				$_SESSION['user_currency'] = $def_currency;
			}
		}

		//exchange_rate init
		if ( ! isset( $_SESSION['exchange_rate'] ) ) $_SESSION['exchange_rate'] = trav_currency_converter( 1 , $def_currency, $_SESSION['user_currency'] );
		//currency_symbol init
		if ( ! isset( $_SESSION['currency_symbol'] ) ) $_SESSION['currency_symbol'] = trav_get_currency_symbol( $_SESSION['user_currency'] );
	}
}

/*
 * Return currency format data
 */
if ( ! function_exists( 'trav_get_currency_format_data' ) ) {
	function trav_get_currency_format_data( ) {
		global $trav_options;
		$return_data = array();

		$return_data['desimal_prec'] = isset( $trav_options['desimal_prec'] ) ? $trav_options['desimal_prec'] : 2;

		$currency_format = isset( $trav_options['currency_format'] ) ? $trav_options['currency_format'] : 'nodelimit-point';
		$dec_point = '.';
		$thousands_sep = '';
		switch ( $currency_format ) {
			case 'nodelimit-comma':
				$dec_point = ',';
				$thousands_sep = '';
				break;
			case 'pdelimit-comma':
				$dec_point = ',';
				$thousands_sep = '.';
				break;
			case 'cdelimit-point':
				$dec_point = '.';
				$thousands_sep = ',';
				break;
			case 'cbdelimit-point':
				$dec_point = '.';
				$thousands_sep = ', ';
				break;
			case 'bdelimit-point':
				$dec_point = '.';
				$thousands_sep = ' ';
				break;
			case 'bdelimit-comma':
				$dec_point = ',';
				$thousands_sep = ' ';
				break;
			case 'qdelimit-point':
				$dec_point = '.';
				$thousands_sep = "'";
				break;
			case 'nodelimit-point':
			default:
				$dec_point = '.';
				$thousands_sep = '';
				break;
		}

		$return_data['dec_point'] = $dec_point;
		$return_data['thousands_sep'] = $thousands_sep;

		if ( isset( $trav_options['cs_pos'] ) && ( $trav_options['cs_pos'] == 'after' ) ) {
			$return_data['cs_pos'] = 'after';
		} else {
			$return_data['cs_pos'] = 'before';
		}

		return $return_data; // array( desimal_prec, dec_point, thousands_sep, cs_pos )

	}
}
?>