<?php
session_start();
//constants
define( 'TRAV_VERSION', '1.3.4' );
define( 'TRAV_DB_VERSION', '1.3' );
define( 'TRAV_TEMPLATE_DIRECTORY_URI', get_template_directory_uri() );
define( 'TRAV_INC_DIR', get_template_directory() . '/inc' );
define( 'TRAV_IMAGE_URL', TRAV_TEMPLATE_DIRECTORY_URI . '/images' );
define( 'TRAV_TAX_META_DIR_URL', TRAV_TEMPLATE_DIRECTORY_URI . '/inc/lib/tax-meta-class/' );
define( 'RWMB_URL', TRAV_TEMPLATE_DIRECTORY_URI . '/inc/lib/meta-box/' );
define( 'OPTIONS_FRAMEWORK_DIRECTORY_URL', TRAV_TEMPLATE_DIRECTORY_URI . '/inc/lib/options-framework/' );

global $wpdb;
define( 'TRAV_ACCOMMODATION_VACANCIES_TABLE', $wpdb->prefix . 'trav_accommodation_vacancies' );
define( 'TRAV_ACCOMMODATION_BOOKINGS_TABLE', $wpdb->prefix . 'trav_accommodation_bookings' );
define( 'TRAV_CURRENCIES_TABLE', $wpdb->prefix . 'trav_currencies' );
define( 'TRAV_REVIEWS_TABLE', $wpdb->prefix . 'trav_reviews' );
define( 'TRAV_MODE', 'product' );
define( 'TRAV_TOUR_SCHEDULES_TABLE', $wpdb->prefix . 'trav_tour_schedule' );
define( 'TRAV_TOUR_BOOKINGS_TABLE', $wpdb->prefix . 'trav_tour_bookings' );
// define( 'TRAV_MODE', 'dev' );

//global variables
$config = get_option( 'optionsframework' );
$trav_options = get_option( $config['id'] );
$def_currency = isset( $trav_options['def_currency'] )?$trav_options['def_currency']:'usd';
$search_max_rooms = 30;
$search_max_adults = 30;
$search_max_kids = 10;
$logo_url = '';
if ( ! empty( $trav_options['logo'] ) ) {
    $logo_url = $trav_options['logo'];
} else {
    $logo_url = TRAV_IMAGE_URL . '/logo.png';
}

$language_count = 1;
// wpml variables
if ( defined('ICL_LANGUAGE_CODE') ) {
	$languages = icl_get_languages('skip_missing=1');
	$language_count = count( $languages );
}

// Content Width
if (!isset( $content_width )) $content_width = 1000;

// Translation
load_theme_textdomain('trav', get_template_directory() . '/languages');

//theme supports
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
add_image_size( 'list-thumb', 230, 160, true );
add_image_size( 'gallery-thumb', 270, 160, true );
add_image_size( 'biggallery-thumb', 500, 300, true );
add_image_size( 'widget-thumb', 64, 64, true );

//require files
require_once( TRAV_INC_DIR . '/functions/main.php' );
require_once( TRAV_INC_DIR . '/admin/main.php');
require_once( TRAV_INC_DIR . '/frontend/accommodation/main.php');
require_once( TRAV_INC_DIR . '/frontend/tour/main.php');

$login_url = '';
$signup_url = '';
$redirect_url_on_login = '';
if ( ! empty( $trav_options['redirect_page'] ) ) {
	$redirect_url_on_login = trav_get_permalink_clang( $trav_options['redirect_page'] );
} else {
    $redirect_url_on_login = trav_get_current_page_url();
}

if ( ! empty( $trav_options['modal_login'] ) ) {
    $login_url = '#travelo-login';
    $signup_url = '#travelo-signup';
} else {
    if ( ! empty( $trav_options['login_page'] ) ) {
		$login_url = trav_get_permalink_clang( $trav_options['login_page'] );
		$signup_url = add_query_arg( 'action', 'register', trav_get_permalink_clang( $trav_options['login_page'] ) );
    } else {
        $login_url = wp_login_url( $redirect_url_on_login );
        $signup_url = wp_registration_url();
    }
}

$my_account_page = '';
if ( ! empty( $trav_options['dashboard_page'] ) ) {
    if ( is_user_logged_in() ) {
		$my_account_page = trav_get_permalink_clang( $trav_options['dashboard_page'] );
    } else {
        $my_account_page = $login_url;
    }
}