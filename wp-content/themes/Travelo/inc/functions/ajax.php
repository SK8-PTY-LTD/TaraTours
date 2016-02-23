<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Handle ajax user password update.
 */
if ( ! function_exists( 'trav_ajax_update_password' ) ) {
	function trav_ajax_update_password() {
		$result_json = array();
		//validation
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update_password' ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );
			wp_send_json( $result_json );
		}

		if ( ! is_user_logged_in() ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Please log in first.', 'trav' );
			wp_send_json( $result_json );
		}

		if ( ! isset( $_POST['pass1'] ) || ! isset( $_POST['pass2'] ) || ! isset( $_POST['old_pass'] ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Invalid input data.', 'trav' );
			wp_send_json( $result_json );
		}

		if ( $_POST['pass1'] != $_POST['pass2'] ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Password mismatch.', 'trav' );
			wp_send_json( $result_json );
		}

		$user = wp_get_current_user();
		if ( $user && wp_check_password( $_POST['old_pass'], $user->data->user_pass, $user->ID) ) {
			wp_set_password( $_POST['pass1'], $user->ID );
			wp_cache_delete( $user->ID, 'users');
			wp_cache_delete( $user->user_login, 'userlogins');
			wp_signon(array('user_login' => $user->user_login, 'user_password' => $_POST['pass1']));
			$result_json['success'] = 1;
			$result_json['result'] = __( 'Password is changed successfully.', 'trav' );
			wp_send_json( $result_json );
		} else {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Old password is incorrect.', 'trav' );
			wp_send_json( $result_json );
		}
	}
}

/*
 * Handle ajax user email update.
 */
if ( ! function_exists( 'trav_ajax_update_email' ) ) {
	function trav_ajax_update_email() {
		//validation
		$result_json = array();
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update_email' ) ) { 
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );
			wp_send_json( $result_json );
		}

		if ( ! is_user_logged_in() ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Please log in first.', 'trav' );
			wp_send_json( $result_json );
		}

		if ( ! isset( $_POST['email1'] ) || ! isset( $_POST['email2'] ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Invalid input data', 'trav' );
			wp_send_json( $result_json );
		}

		if ( $_POST['email1'] != $_POST['email2'] ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Email mismatch.', 'trav' );
			wp_send_json( $result_json );
		}

		$user = wp_get_current_user();
		if ( $user ) {
			$user_id = wp_update_user( array( 'ID' => $user->ID, 'user_email' => sanitize_email( $_POST['email1'] ) ) );
			if ( is_wp_error( $user_id ) ) {
				$result_json['success'] = 0;
				$result_json['result'] = __( 'An error occurred.', 'trav' );
				wp_send_json( $result_json );
			} else {
				$result_json['success'] = 1;
				$result_json['result'] = __( 'Success.', 'trav' );
				wp_send_json( $result_json );
			}
		}

		$result_json['success'] = 0;
		$result_json['result'] = __( 'An error occurred.', 'trav' );
		wp_send_json( $result_json );
	}
}

/*
 * Handle booking list filter and sorting action.
 */
if ( ! function_exists( 'trav_ajax_update_booking_list' ) ) {
	function trav_ajax_update_booking_list() {
		$result_json = array();
		$user_id = get_current_user_id();
		$status = isset($_POST['status']) ? sanitize_text_field( $_POST['status'] ) : -1;
		$sortby = isset($_POST['sort_by']) ? sanitize_text_field( $_POST['sort_by'] ) : 'created';
		$order = isset($_POST['order']) ? sanitize_text_field( $_POST['order'] ) : 'desc';
		$booking_list = trav_acc_get_user_booking_list( $user_id, $status, $sortby, $order );
		if ( ! empty( $booking_list ) ) {
			$result_json['success'] = 1;
			$result_json['result'] = $booking_list;
			wp_send_json( $result_json );
		} else {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'empty', 'trav' );
			wp_send_json( $result_json );
		}
	}
}