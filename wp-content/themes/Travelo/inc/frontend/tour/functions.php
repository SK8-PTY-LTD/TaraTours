<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Get Tour City Data
 */
if ( ! function_exists( 'trav_tour_get_city' ) ) {
	function trav_tour_get_city( $tour_id ) {
		return trav_post_get_location( $tour_id, 'tour', 'city' );
	}
}

/*
 * Get Tour Country Data
 */
if ( ! function_exists( 'trav_tour_get_country' ) ) {
	function trav_tour_get_country( $tour_id ) {
		return trav_post_get_location( $tour_id, 'tour', 'country' );
	}
}

/*
 * Get Schedule Types in Tour
 */
if ( ! function_exists( 'trav_tour_get_schedule_types' ) ) {
	function trav_tour_get_schedule_types( $tour_id ) {
		$schedule_types = get_post_meta( $tour_id, 'trav_tour_schedule_types', true );
		$return_value = array();
		if ( ! empty( $schedule_types ) ) {
			foreach ( $schedule_types as $st_id => $values ) {
				$title = empty( $values[0] ) ? '' : $values[0];
				$desc = empty( $values[1] ) ? '' : $values[1];
				$time = empty( $values[2] ) ? '' : $values[2];
				$return_value[] = array( 'title' => $title, 'description' => $desc, 'time' => $time );
			}
		}
		return $return_value;
	}
}

/*
 * Get Schedule Type Data from tour_id and schedule type id
 */
if ( ! function_exists( 'trav_tour_get_schedule_type_data' ) ) {
	function trav_tour_get_schedule_type_data( $tour_id, $st_id ) {
		$schedule_types = trav_tour_get_schedule_types( $tour_id );
		if ( ! empty( $schedule_types ) && ! empty( $schedule_types[$st_id] ) ) {
			return $schedule_types[$st_id];
		}
		return '';
	}
}

/*
 * Get Schedule Type title from tour_id and schedule type id
 */
if ( ! function_exists( 'trav_tour_get_schedule_type_title' ) ) {
	function trav_tour_get_schedule_type_title( $tour_id, $st_id ) {
		$schedule_type_data = trav_tour_get_schedule_type_data( $tour_id, $st_id );
		if ( ! empty( $schedule_type_data['title'] ) ) {
			return $schedule_type_data['title'];
		}
		return '';
	}
}

/*
 * Get Schedules in Tour
 * input : array( 'tour_id', 'date_from', 'date_to', 'st_id' ); if date_from null set current date, date_to null set 30 days after
 * output : array( 'st_id' =>array( 'check_date' => array( 'available_seat', 'schedule_data' ... ) ) )
 */
if ( ! function_exists( 'trav_tour_get_available_schedules' ) ) {
	function trav_tour_get_available_schedules( $tour_data ) {
		// validation
		if ( empty( $tour_data['tour_id'] ) ) return false;
		$date_from = isset( $tour_data['date_from'] ) ? trav_sanitize_date( $tour_data['date_from'] ) : '';
		$date_to = isset( $tour_data['date_to'] ) ? trav_sanitize_date( $tour_data['date_to'] ) : '';
		if ( trav_strtotime( $date_from ) > trav_strtotime( $date_to ) ) {
			$date_from = '';
			$date_to = '';
		}

		// init variables
		global $wpdb;
		$tour_id = esc_sql( trav_tour_org_id( $tour_data['tour_id'] ) );
		$st_id = empty( $tour_data['st_id'] ) ? '' : esc_sql( $tour_data['st_id'] );
		$repeated = get_post_meta( $tour_id, 'trav_tour_repeated', true );
		$where = "1=1";
		if ( ! empty( $tour_id ) ) $where .= " AND schedules.tour_id={$tour_id}";
		if ( ! empty( $st_id ) ) $where .= " AND schedules.st_id={$st_id}";

		if ( ! empty( $repeated ) ) {
			$date_from = empty( $date_from ) ? date('Y-m-d') : date('Y-m-d', trav_strtotime( $date_from ) );
			$date_to = empty( $date_to ) ? date( 'Y-m-d', strtotime( '30 days' ) ) : date('Y-m-d', trav_strtotime( $date_to ) + 86400 );

			$from_date_obj = date_create_from_format( 'Y-m-d', $date_from );
			$to_date_obj = date_create_from_format( 'Y-m-d', $date_to );

			// has specified date
			$date_interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod( $from_date_obj, $date_interval, $to_date_obj );
			$sql_check_date_parts = array();
			$days = 0;
			foreach ( $period as $dt ) {
				$check_date = $dt->format( "Y-m-d" );
				$sql_check_date_parts[] = "SELECT '{$check_date}' AS check_date";
				$days++;
			}
			$sql_check_date = implode( ' UNION ', $sql_check_date_parts );

			$sql = "SELECT schedules.*, check_dates.check_date, schedules.max_people - SUM( IFNULL(bookings.adults,0) ) - SUM( IFNULL(bookings.kids,0) ) AS available_seat FROM " . TRAV_TOUR_SCHEDULES_TABLE . " AS schedules
					INNER JOIN ({$sql_check_date}) AS check_dates
					ON ( schedules.is_daily = 0 AND check_dates.check_date = schedules.tour_date ) OR ( schedules.is_daily = 1 AND check_dates.check_date >= schedules.tour_date AND check_dates.check_date <= schedules.date_to )
					LEFT JOIN " . TRAV_TOUR_BOOKINGS_TABLE .  " AS bookings
					ON bookings.tour_id = schedules.tour_id AND bookings.st_id = schedules.st_id AND bookings.tour_date = check_dates.check_date AND bookings.status<>0
					WHERE {$where}
					GROUP BY tour_id, st_id, check_date
					ORDER BY st_id ASC, tour_date ASC";

		} else {
			$today = date('Y-m-d', time() );
			$sql = "SELECT schedules.*, schedules.tour_date AS check_date, schedules.max_people - SUM( IFNULL(bookings.adults,0) ) - SUM( IFNULL(bookings.kids,0) ) AS available_seat FROM " . TRAV_TOUR_SCHEDULES_TABLE . " AS schedules
					LEFT JOIN " . TRAV_TOUR_BOOKINGS_TABLE .  " AS bookings
					ON schedules.tour_id = bookings.tour_id AND schedules.st_id = bookings.st_id AND bookings.tour_date = schedules.tour_date AND bookings.status<>0
					WHERE {$where} AND schedules.tour_date >= '{$today}'
					GROUP BY tour_id, st_id, tour_date
					ORDER BY st_id ASC";
		}
		$raw_schedules = $wpdb->get_results( $sql, ARRAY_A );
		$schedules = array();
		if ( ! empty( $raw_schedules ) ) {
			foreach ( $raw_schedules as $schedule ) {
				$schedules[$schedule['st_id']][$schedule['check_date']] = $schedule;
			}
		}
		return $schedules;
	}
}

/*
 * Get Tour Price data
 * input : array( 'tour_id', 'st_id', 'tour_date', 'adults', 'kids' );
 * output : array( 'st_id' =>array( 'check_date' => array( 'available_seat', 'price_data' ... ) ) )
 */
if ( ! function_exists( 'trav_tour_get_price_data' ) ) {
	function trav_tour_get_price_data( $tour_data ) {
		$return_value = array();
		$return_value['success'] = 0;
		// validation
		if ( empty( $tour_data['tour_id'] ) || ! isset( $tour_data['st_id'] ) || empty( $tour_data['tour_date'] ) ) {
			$return_value['message'] = __( 'Validation Error', 'trav' );
			return $return_value;
		}

		// variable initialize
		$tour_id = esc_sql( $tour_data['tour_id'] );
		$st_id = esc_sql( $tour_data['st_id'] );
		$tour_date = esc_sql( empty( $tour_data['tour_date'] ) ? date('Y-m-d') : date('Y-m-d', trav_strtotime( $tour_data['tour_date'] ) ) );
		$adults = ( ! empty( $tour_data['adults'] ) && is_numeric( $tour_data['adults'] ) ) ? $tour_data['adults'] : 1;
		$kids = ( ! empty( $tour_data['kids'] ) && is_numeric( $tour_data['kids'] ) ) ? $tour_data['kids'] : 0;

		global $wpdb;

		$sql = "SELECT schedules.*, schedules.max_people - SUM( IFNULL(bookings.adults,0) ) - SUM( IFNULL(bookings.kids,0) ) AS available_seat FROM " . TRAV_TOUR_SCHEDULES_TABLE . " AS schedules
				LEFT JOIN " . TRAV_TOUR_BOOKINGS_TABLE . " AS bookings 
				ON schedules.tour_id = bookings.tour_id AND schedules.st_id = bookings.st_id AND bookings.tour_date = '{$tour_date}' AND bookings.status<>0
				WHERE schedules.tour_id={$tour_id} AND schedules.st_id={$st_id} AND ( (schedules.is_daily=0 AND schedules.tour_date='{$tour_date}' ) OR ( schedules.is_daily=1 AND schedules.tour_date <= '{$tour_date}' AND schedules.date_to >= '{$tour_date}' ) )
				GROUP BY tour_id, st_id";

		$price_data = $wpdb->get_row( $sql, ARRAY_A );
		if ( ! empty( $price_data ) ) {
			if ( $price_data['available_seat'] < $adults + $kids ) {
				if ( (int) $price_data['available_seat'] <= 0 ) {
					$return_value['message'] = __( 'Sold Out', 'trav' );
				} else {
					$return_value['message'] = __( 'Exceed Persons', 'trav' );
				}
				return $return_value;
			}
			$price = $price_data['price'] * $adults + $price_data['child_price'] * $kids;
			$return_value['success'] = 1;
			$return_value['price'] = $price;
			$return_value['price_data'] = $price_data;
			return $return_value;
		} else {
			$return_value['message'] = __( 'Schedule does not exist.', 'trav' );
			return $return_value;
		}
	}
}


/*
 * Get Tour Schedule Data
 * input : array( 'tour_id', 'st_id', 'tour_date' ); if date_from null set current date
 * output : array( 'st_id' =>array( 'check_date' => array( 'available_seat', schedule_data ... ) ) )
 */
if ( ! function_exists( 'trav_tour_get_schedule_data' ) ) {
	function trav_tour_get_schedule_data( $tour_data ) {
		$return_value = array();
		$return_value['success'] = 0;
		// validation
		if ( empty( $tour_data['tour_id'] ) || ! isset( $tour_data['st_id'] ) || empty( $tour_data['tour_date'] ) ) {
			$return_value['message'] = __( 'Validation Error', 'trav' );
			return $return_value;
		}

		// variable initialize
		$tour_id = esc_sql( $tour_data['tour_id'] );
		$st_id = esc_sql( $tour_data['st_id'] );
		$tour_date = esc_sql( empty( $tour_data['tour_date'] ) ? date('Y-m-d') : date('Y-m-d', trav_strtotime( $tour_data['tour_date'] ) ) );

		global $wpdb;

		$sql = "SELECT schedules.* FROM " . TRAV_TOUR_SCHEDULES_TABLE . " AS schedules
				WHERE schedules.tour_id={$tour_id} AND schedules.st_id={$st_id} AND ( (schedules.is_daily=0 AND schedules.tour_date='{$tour_date}' ) OR ( schedules.is_daily=1 AND schedules.tour_date <= '{$tour_date}' AND schedules.date_to >= '{$tour_date}' ) )";

		$schedule_data = $wpdb->get_row( $sql, ARRAY_A );
		if ( ! empty( $schedule_data ) ) {
			$return_value['success'] = 1;
			$return_value['schedule_data'] = $schedule_data;
			return $return_value;
		} else {
			$return_value['message'] = __( 'Schedule does not exist.', 'trav' );
			return $return_value;
		}
	}
}

/*
 * send confirmation email
 */
if ( ! function_exists( 'trav_tour_conf_send_mail' ) ) {
	function trav_tour_conf_send_mail( $booking_data ) {
		global $wpdb;
		$mail_sent = 0;
		if ( trav_tour_send_confirmation_email( $booking_data['booking_no'], $booking_data['pin_code'], 'new' ) ) {
			$mail_sent = 1;
			$wpdb->update( TRAV_TOUR_BOOKINGS_TABLE, array( 'mail_sent' => $mail_sent ), array( 'booking_no' => $booking_data['booking_no'], 'pin_code' => $booking_data['pin_code'] ), array( '%d' ), array( '%d','%d' ) );
		}
	}
}

/*
 * send booking confirmation email function
 */
if ( ! function_exists( 'trav_tour_send_confirmation_email' ) ) {
	function trav_tour_send_confirmation_email( $booking_no, $booking_pincode, $type='new', $subject='', $description='' ) {
		global $wpdb, $logo_url, $trav_options;
		$booking_data = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . TRAV_TOUR_BOOKINGS_TABLE . ' WHERE booking_no=%d AND pin_code=%d', $booking_no, $booking_pincode ), ARRAY_A );
		if ( ! empty( $booking_data ) ) {

			// server variables
			$admin_email = get_option('admin_email');
			$home_url = esc_url( home_url() );
			$site_name = $_SERVER['SERVER_NAME'];
			$logo_url = esc_url( $logo_url );
			$tour_book_conf_url = '';
			if ( isset( $trav_options['tour_booking_confirmation_page'] ) && ! empty( $trav_options['tour_booking_confirmation_page'] ) ) {
				$tour_book_conf_url = trav_get_permalink_clang( $trav_options['tour_booking_confirmation_page'] );
			}
			$booking_data['tour_id'] = trav_tour_clang_id( $booking_data['tour_id'] );
			$st_data = trav_tour_get_schedule_type_data( $booking_data['tour_id'], $booking_data['st_id'] );
			$schedule_data = trav_tour_get_schedule_data( $booking_data );

			// tour info
			$tour_name = get_the_title( $booking_data['tour_id'] );
			$tour_url = esc_url( trav_get_permalink_clang( $booking_data['tour_id'] ) );
			$tour_thumbnail = get_the_post_thumbnail( $booking_data['tour_id'], 'list-thumb' );
			$tour_address = get_post_meta( $booking_data['tour_id'], 'trav_tour_address', true );
			$tour_city = trav_tour_get_city($booking_data['tour_id']);
			$tour_country = trav_tour_get_country($booking_data['tour_id']);
			$tour_phone = get_post_meta( $booking_data['tour_id'], 'trav_tour_phone', true );
			$tour_email = get_post_meta( $booking_data['tour_id'], 'trav_tour_email', true );
			$tour_duration = '';
			if ( ! empty( $schedule_data ) && $schedule_data['success'] == 1 ) {
				$tour_duration = $schedule_data['schedule_data']['duration'];
			}
			$tour_st_title = esc_html( trav_tour_get_schedule_type_title( $booking_data['tour_id'], $booking_data['st_id'] ) );
			$tour_st_description = esc_html( $st_data['description'] );
			$tour_st_time = esc_html( $st_data['time'] );
			if ( empty( $tour_address ) ) {
				$tour_address = $tour_city . ' ' . $tour_country;
			}
			$tour_date = date( 'l, F, j, Y', trav_strtotime( $booking_data['tour_date'] ) );

			// booking info
			$booking_no = $booking_data['booking_no'];
			$booking_pincode = $booking_data['pin_code'];
			$booking_adults = $booking_data['adults'];
			$booking_kids = $booking_data['kids'];
			$booking_total_price = esc_html( trav_get_price_field( $booking_data['total_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) );
			$booking_deposit_price = esc_html( $booking_data['deposit_price'] . $booking_data['currency_code'] );
			$booking_deposit_paid = esc_html( empty( $booking_data['deposit_paid'] ) ? 'No' : 'Yes' );
			$booking_update_url = esc_url( add_query_arg( array( 'booking_no'=>$booking_data['booking_no'], 'pin_code'=>$booking_data['pin_code'] ), $tour_book_conf_url ) );

			// customer info
			$customer_first_name = $booking_data['first_name'];
			$customer_last_name = $booking_data['last_name'];
			$customer_email = $booking_data['email'];
			$customer_country_code = $booking_data['country_code'];
			$customer_phone = $booking_data['phone'];
			$customer_address = $booking_data['address'];
			$customer_city = $booking_data['city'];
			$customer_zip = $booking_data['zip'];
			$customer_country = $booking_data['country'];
			$customer_special_requirements = $booking_data['special_requirements'];

			$variables = array( 'home_url',
								'site_name',
								'logo_url',
								'tour_name',
								'tour_url',
								'tour_thumbnail',
								'tour_country',
								'tour_city',
								'tour_address',
								'tour_phone',
								'tour_email',
								'tour_date',
								'tour_duration',
								'tour_st_title',
								'tour_st_description',
								'tour_st_time',
								'booking_no',
								'booking_pincode',
								'booking_adults',
								'booking_kids',
								'booking_total_price',
								'booking_deposit_price',
								'booking_deposit_paid',
								'booking_update_url',
								'customer_first_name',
								'customer_last_name',
								'customer_email',
								'customer_country_code',
								'customer_phone',
								'customer_address',
								'customer_city',
								'customer_zip',
								'customer_country',
								'customer_special_requirements'
							);

			if ( empty( $subject ) ) {
				if ( $type == 'new' ) {
					$subject = empty( $trav_options['tour_confirm_email_subject'] ) ? 'Booking Confirmation Email Subject' : $trav_options['tour_confirm_email_subject'];
				} elseif ( $type == 'update' ) {
					$subject = empty( $trav_options['tour_update_email_subject'] ) ? 'Booking Updated Email Subject' : $trav_options['tour_update_email_subject'];
				} elseif ( $type == 'cancel' ) {
					$subject = empty( $trav_options['tour_cancel_email_subject'] ) ? 'Booking Canceled Email Subject' : $trav_options['tour_cancel_email_subject'];
				}
			}

			if ( empty( $description ) ) {
				if ( $type == 'new' ) {
					$description = empty( $trav_options['tour_confirm_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['tour_confirm_email_description'];
				} elseif ( $type == 'update' ) {
					$description = empty( $trav_options['tour_update_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['tour_update_email_description'];
				} elseif ( $type == 'cancel' ) {
					$description = empty( $trav_options['tour_cancel_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['tour_cancel_email_description'];
				}
			}

			foreach ( $variables as $variable ) {
				$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
				$description = str_replace( "[" . $variable . "]", $$variable, $description );
			}

			$mail_sent = trav_send_mail( $site_name, $admin_email, $customer_email, $subject, $description );

			/* mailing function to business owner */
			$bowner_address = '';
			if ( ! empty( $trav_options['tour_booked_notify_bowner'] ) ) {

				if ( $type == 'new' ) {
					$subject = empty( $trav_options['tour_bowner_email_subject'] ) ? 'You received a booking' : $trav_options['tour_bowner_email_subject'];
					$description = empty( $trav_options['tour_bowner_email_description'] ) ? 'Booking Details' : $trav_options['tour_bowner_email_description'];
				} elseif ( $type == 'update' ) {
					$subject = empty( $trav_options['tour_update_bowner_email_subject'] ) ? 'A booking is updated' : $trav_options['tour_update_bowner_email_subject'];
					$description = empty( $trav_options['tour_update_bowner_email_description'] ) ? 'Booking Details' : $trav_options['tour_update_bowner_email_description'];
				} elseif ( $type == 'cancel' ) {
					$subject = empty( $trav_options['tour_cancel_bowner_email_subject'] ) ? 'A booking is canceled' : $trav_options['tour_cancel_bowner_email_subject'];
					$description = empty( $trav_options['tour_cancel_bowner_email_description'] ) ? 'Booking Details' : $trav_options['tour_cancel_bowner_email_description'];
				}

				foreach ( $variables as $variable ) {
					$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
					$description = str_replace( "[" . $variable . "]", $$variable, $description );
				}

				if ( ! empty( $tour_email ) ) {
					$bowner_address = $tour_email;
				} else {
					$post_author_id = get_post_field( 'post_author', $booking_data['tour_id'] );
					$bowner = get_user_by( 'id', $post_author_id );
					if ( ! empty( $bowner ) ) {
						$bowner_address = $bowner->user_email;
					}
				}

				if ( ! empty( $bowner_address ) ) {
					trav_send_mail( $site_name, $admin_email, $bowner_address, $subject, $description );
				}
			}

			/* mailing function to admin */
			if ( ! empty( $trav_options['tour_booked_notify_admin'] ) ) {
				if ( $bowner_address != $admin_email ) {
					if ( $type == 'new' ) {
						$subject = empty( $trav_options['tour_admin_email_subject'] ) ? 'You received a booking' : $trav_options['tour_admin_email_subject'];
						$description = empty( $trav_options['tour_admin_email_description'] ) ? 'Booking Details' : $trav_options['tour_admin_email_description'];
					} elseif ( $type == 'update' ) {
						$subject = empty( $trav_options['tour_update_admin_email_subject'] ) ? 'A booking is updated' : $trav_options['tour_update_admin_email_subject'];
						$description = empty( $trav_options['tour_update_admin_email_description'] ) ? 'Booking Details' : $trav_options['tour_update_admin_email_description'];
					} elseif ( $type == 'cancel' ) {
						$subject = empty( $trav_options['tour_cancel_admin_email_subject'] ) ? 'A booking is canceled' : $trav_options['tour_cancel_admin_email_subject'];
						$description = empty( $trav_options['tour_cancel_admin_email_description'] ) ? 'Booking Details' : $trav_options['tour_cancel_admin_email_description'];
					}

					foreach ( $variables as $variable ) {
						$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
						$description = str_replace( "[" . $variable . "]", $$variable, $description );
					}

					trav_send_mail( $site_name, $admin_email, $admin_email, $subject, $description );
				}
			}
			return true;
		}
		return false;
	}
}

/*
 * echo deposit payment not paid notice on confirmation page
 */
if ( ! function_exists( 'trav_tour_deposit_payment_not_paid' ) ) {
	function trav_tour_deposit_payment_not_paid( $booking_data ) {
		echo '<div class="alert alert-notice">' . __( 'Deposit payment is not paid.', 'trav' ) . '<span class="close"></span></div>';
	}
}

/*
 * function to update booking
 */
if ( ! function_exists( 'trav_tour_update_booking' ) ) {
	function trav_tour_update_booking( $booking_no, $pin_code, $new_data, $action='update' ) {
		global $wpdb, $trav_options;
		$result = $wpdb->update( TRAV_TOUR_BOOKINGS_TABLE, $new_data, array( 'booking_no' => $booking_no, 'pin_code' => $pin_code ) );
		if ( $result ) {
			trav_tour_send_confirmation_email( $booking_no, $pin_code, $action );
			return $result;
		}
		return false;
	}
}

/*
 * Get special( latest or featured ) tour and return data
 */
if ( ! function_exists( 'trav_tour_get_special_tours' ) ) {
	function trav_tour_get_special_tours( $type='latest', $count=10, $exclude_ids=array(),  $country='', $city='', $tour_type=array() ) {
		$args = array(
				'post_type'  => 'tour',
				'suppress_filters' => 0,
				'posts_per_page' => $count,
			);
		if ( ! empty( $country ) ) {
			if ( is_numeric( $country ) ) {
				$args['meta_query'][] = array(
					'key'     => 'trav_tour_country',
					'value'   => $country,
				);
			} else {
				$term = get_term_by( 'name', $country, 'location' );
				if ( $term ) {
					$country = $term->term_id;
					$args['meta_query'][] = array(
						'key'     => 'trav_tour_country',
						'value'   => $country,
					);
				}
			}
		}

		if ( ! empty( $city ) ) {
			if ( is_numeric( $city ) ) {
				$args['meta_query'][] = array(
					'key'     => 'trav_tour_city',
					'value'   => $city,
				);
			} else {
				$term = get_term_by( 'name', $city, 'location' );
				if ( $term ) {
					$city = $term->term_id;
					$args['meta_query'][] = array(
						'key'     => 'trav_tour_city',
						'value'   => $city,
					);
				}
			}
		}

		if ( ! empty( $exclude_ids ) ) {
			$args['post__not_in'] = $exclude_ids;
		}

		if ( ! empty( $tour_type ) ) {
			$args['tax_query'] = array(
					array(
						'taxonomy' => 'tour_type',
						'field' => 'term_id',
						'terms' => $tour_type
						)
				);
		}

		if ( $type == 'featured'  ) {
			$args = array_merge( $args, array(
				'orderby'    => 'rand',
				'meta_key'     => 'trav_tour_featured',
				'meta_value'   => '1',
			) );
			return get_posts( $args );
		} elseif ( $type == 'latest' ) {
			$args = array_merge( $args, array(
				'orderby' => 'post_date',
				'order' => 'DESC',
			) );
			return get_posts( $args );
		} elseif ( $type == 'popular' ) {
			global $wpdb;
			$tbl_postmeta = esc_sql( $wpdb->prefix . 'postmeta' );
			$tbl_terms = esc_sql( $wpdb->prefix . 'terms' );
			$tbl_term_taxonomy = esc_sql( $wpdb->prefix . 'term_taxonomy' );
			$tbl_term_relationships = esc_sql( $wpdb->prefix . 'term_relationships' );

			$date = date( 'Y-m-d', strtotime( '-30 days' ) );
			$sql = 'SELECT tour_id, COUNT(*) AS booking_count FROM ' . TRAV_TOUR_BOOKINGS_TABLE . ' AS booking';
			$where = ' WHERE (booking.status <> 0) AND (booking.created > %s)';
			if ( ! empty( $country ) ) {
				$sql .= " INNER JOIN {$tbl_postmeta} AS meta_c1 ON (meta_c1.meta_key = 'trav_tour_country') AND (booking.tour_id = meta_c1.post_id)";
				$where .= " AND meta_c1.meta_value like '%%{$country}%%'";
			}
			if ( ! empty( $city ) ) {
				$sql .= " INNER JOIN {$tbl_postmeta} AS meta_c1 ON (meta_c1.meta_key = 'trav_tour_city') AND (booking.tour_id = meta_c1.post_id)";
				$where .= " AND meta_c1.meta_value like '%%{$city}%%'";
			}
			if ( ! empty( $tour_type ) ) {
				$sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.tour_id 
						INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
				$where .= " AND tt.taxonomy = 'tour_type' AND tt.term_id IN (" . esc_sql( implode( ',', $tour_type ) ) . ")";
			}
			$sql .= $where . ' GROUP BY booking.tour_id ORDER BY booking_count desc LIMIT %d';
			$popular_tours = $wpdb->get_results( $wpdb->prepare( $sql, $date, $count ) );
			$result = array();
			if ( ! empty( $popular_tours ) ) {
				foreach ( $popular_tours as $tour ) {
					$result[] = get_post( trav_tour_clang_id( $tour->tour_id ) );
				}
			}
			// if booked room number in last month is smaller than count then add latest tours
			if ( count( $popular_tours ) < $count ) {
				foreach ( $popular_tours as $tour ) {
					$exclude_ids[] = trav_tour_clang_id( $tour->tour_id );
				}
				$result = array_merge( $result, trav_tour_get_special_tours( 'latest', $count - count( $popular_tours ), $exclude_ids ) );
			}
			return $result;
		}
	}
}

/*
 * Get Tour Search Result
 */
if ( ! function_exists( 'trav_tour_get_search_result' ) ) {
	function trav_tour_get_search_result( $search_data=array() ) { 
		//$search_data = array('s'=>$s, 'date_from'=>$date_from, 'date_to'=>$date_to, 'order_by'=>$order_by_array[$order_by], 'order'=>$order, 'last_no'=>( $page - 1 ) * $per_page, 'per_page'=>$per_page, 'min_price'=>$min_price, 'max_price'=>$max_price, 'tour_type'=>$tour_type )

		// if wrong date return false
		if ( ! empty( $search_data['date_from'] ) && ! empty( $search_data['date_to'] ) && ( trav_strtotime( $search_data['date_from'] ) > trav_strtotime( $search_data['date_to'] ) ) ) return false;
		global $wpdb, $language_count;
		$tbl_posts = esc_sql( $wpdb->posts );
		$tbl_postmeta = esc_sql( $wpdb->postmeta );
		$tbl_terms = esc_sql( $wpdb->prefix . 'terms' );
		$tbl_term_taxonomy = esc_sql( $wpdb->prefix . 'term_taxonomy' );
		$tbl_term_relationships = esc_sql( $wpdb->prefix . 'term_relationships' );
		$tbl_icl_translations = esc_sql( $wpdb->prefix . 'icl_translations' );
		$temp_tbl_name = esc_sql( 'Search_' . session_id() );

		$order_by = esc_sql( empty( $search_data['order_by'] ) ? 'tour_title' : $search_data['order_by'] );
		$order = esc_sql( empty( $search_data['order'] ) ? 'ASC' : $search_data['order'] );
		$last_no = esc_sql( empty( $search_data['last_no'] ) ? 0 : $search_data['last_no'] );
		$per_page = esc_sql( empty( $search_data['per_page'] ) ? 10 : $search_data['per_page'] );
		$max_price = esc_sql( empty( $search_data['max_price'] ) ? 'no_max' : $search_data['max_price'] );
		$min_price = esc_sql( empty( $search_data['min_price'] ) ? 0 : $search_data['min_price'] );
		$tour_type = ( empty( $search_data['tour_type'] ) || ! is_array( $search_data['tour_type'] ) ) ? array() : $search_data['tour_type'];
		foreach ( $tour_type as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $tour_type[$key] );
		}

		$s = '';
		if ( floatval( get_bloginfo( 'version' ) ) >= 4.0 ) {
			$s = esc_sql( $wpdb->esc_like( $search_data['s'] ) );
		} else {
			$s = esc_sql( like_escape( $search_data['s'] ) );
		}

		$date_from = ''; $date_to = '';
		if ( ! empty( $search_data['date_from'] ) && trav_strtotime( $search_data['date_from'] ) ) {
			$date_from = trav_strtotime( $search_data['date_from'] );
		} else {
			$date_from = time();
		}
		if ( ! empty( $search_data['date_to'] ) && trav_strtotime( $search_data['date_to'] ) ) {
			$date_to = trav_strtotime( $search_data['date_to'] ) + 86400;
		} else {
			$date_to = strtotime('+30 days');
		}
		$from_date_obj = new DateTime();
		$from_date_obj->setTimestamp($date_from);
		$to_date_obj = new DateTime();
		$to_date_obj->setTimestamp($date_to);

		$sql = ''; $s_query = ''; // sql for search keyword

		if ( ! empty( $s ) ) {
			$s_query = "SELECT DISTINCT post_s1.ID AS tour_id FROM {$tbl_posts} AS post_s1 
						LEFT JOIN {$tbl_postmeta} AS meta_s1 ON post_s1.ID = meta_s1.post_id
						LEFT JOIN {$tbl_terms} AS terms_s1 ON (meta_s1.meta_key IN('trav_tour_country','trav_tour_city')) AND (terms_s1.term_id = meta_s1.meta_value)
						WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'tour')
						  AND ((post_s1.post_title LIKE '%{$s}%') 
							OR (post_s1.post_content LIKE '%{$s}%')
							OR (meta_s1.meta_value LIKE '%{$s}%')
							OR (terms_s1.name LIKE '%{$s}%'))";
		} else {
			$s_query = "SELECT post_s1.ID AS tour_id FROM {$tbl_posts} AS post_s1 
						WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'tour')";
		}

		// if wpml is enabled do search by default language post
		if ( defined('ICL_LANGUAGE_CODE') && ( $language_count > 1 ) ) {
			$s_query = "SELECT DISTINCT it2.element_id AS tour_id FROM ({$s_query}) AS t0
						INNER JOIN {$tbl_icl_translations} it1 ON (it1.element_type = 'post_tour') AND it1.element_id = t0.tour_id
						INNER JOIN {$tbl_icl_translations} it2 ON (it2.element_type = 'post_tour') AND it2.language_code='" . trav_get_default_language() . "' AND it2.trid = it1.trid ";
		}

		// if this searh has specified date then check schedule and booking data, but if it doesn't have specified date then only check other search factors
		if ( $from_date_obj && $to_date_obj ) {
			// has specified date
			$date_interval = DateInterval::createFromDateString('1 day');
			$period = new DatePeriod( $from_date_obj, $date_interval, $to_date_obj );
			$sql_check_date_parts = array();
			$days = 0;
			foreach ( $period as $dt ) {
				$check_date = $dt->format( "Y-m-d" );
				$sql_check_date_parts[] = "SELECT '{$check_date}' AS check_date";
				$days++;
			}
			$sql_check_date = implode( ' UNION ', $sql_check_date_parts );

			$sql = "SELECT available_schedules.tour_id, MIN(available_schedules.check_date) as min_date, MAX(available_schedules.check_date) as max_date
					FROM ( SELECT schedules.*, check_dates.check_date, schedules.max_people - SUM( IFNULL(bookings.adults,0) ) - SUM( IFNULL(bookings.kids,0) ) AS available_seat
						FROM ({$s_query}) AS tours
						INNER JOIN " . TRAV_TOUR_SCHEDULES_TABLE . " AS schedules
						ON tours.tour_id = schedules.tour_id
						INNER JOIN ({$sql_check_date}) AS check_dates
						ON ( schedules.is_daily = 0 AND check_dates.check_date = schedules.tour_date ) OR ( schedules.is_daily = 1 AND check_dates.check_date >= schedules.tour_date AND check_dates.check_date <= schedules.date_to )
						LEFT JOIN " . TRAV_TOUR_BOOKINGS_TABLE .  " AS bookings
						ON bookings.tour_id = schedules.tour_id AND bookings.st_id = schedules.st_id AND bookings.tour_date = check_dates.check_date AND bookings.status<>0
						GROUP BY schedules.tour_id, schedules.st_id, check_dates.check_date
						HAVING available_seat > 0
						ORDER BY st_id ASC, tour_date ASC ) AS available_schedules
					GROUP BY available_schedules.tour_id";

		} else {
			return false;
		}

		// if wpml is enabled return current language posts
		if ( defined('ICL_LANGUAGE_CODE') && ( $language_count > 1 ) && ( trav_get_default_language() != ICL_LANGUAGE_CODE ) ) {
			$sql = "SELECT it4.element_id AS tour_id, t5.min_date, t5.max_date FROM ({$sql}) AS t5
					INNER JOIN {$tbl_icl_translations} it3 ON (it3.element_type = 'post_tour') AND it3.element_id = t5.tour_id
					INNER JOIN {$tbl_icl_translations} it4 ON (it4.element_type = 'post_tour') AND it4.language_code='" . ICL_LANGUAGE_CODE . "' AND it4.trid = it3.trid";
		}

		$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS {$temp_tbl_name} AS " . $sql;
		$wpdb->query( $sql );

		$sql = "SELECT DISTINCT t1.*, post_l1.post_title as tour_title, meta_price.meta_value as min_price FROM {$temp_tbl_name} as t1
				INNER JOIN {$tbl_posts} post_l1 ON (t1.tour_id = post_l1.ID) AND (post_l1.post_status = 'publish') AND (post_l1.post_type = 'tour')
				LEFT JOIN {$tbl_postmeta} AS meta_price ON (t1.tour_id = meta_price.post_id) AND (meta_price.meta_key = 'trav_tour_min_price')";
		$where = ' 1=1';

		if ( $min_price != 0 ) {
			$where .= " AND cast(meta_price.meta_value as unsigned) >= {$min_price}";
		}
		if ( $max_price != 'no_max' ) {
			$where .= " AND cast(meta_price.meta_value as unsigned) <= {$max_price} ";
		}

		if ( ! empty( $tour_type ) ) {
			$sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.tour_id 
					INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
			$where .= " AND tt.taxonomy = 'tour_type' AND tt.term_id IN (" . esc_sql( implode( ',', $tour_type ) ) . ")";
		}

		$sql .= " WHERE {$where} ORDER BY {$order_by} {$order} LIMIT {$last_no}, {$per_page};";

		$results = $wpdb->get_results( $sql );
		return $results;
	}
}

/*
 * Get tour Search Result Count
 */
if ( ! function_exists( 'trav_tour_get_search_result_count' ) ) {
	function trav_tour_get_search_result_count( $search_data = array() ) {
		// array( 'min_price'=>$min_price, 'max_price'=>$max_price, 'tour_type'=>$tour_type )
		global $wpdb;
		$tbl_term_taxonomy = $wpdb->prefix . 'term_taxonomy';
		$tbl_term_relationships = $wpdb->prefix . 'term_relationships';
		$temp_tbl_name = 'Search_' . session_id();
		$tbl_postmeta = esc_sql( $wpdb->prefix . 'postmeta' );
		$min_price = empty( $search_data['min_price'] ) ? 0 : $search_data['min_price'];
		$max_price = empty( $search_data['max_price'] ) ? 0 : $search_data['max_price'];
		$tour_type = ( empty( $search_data['tour_type'] ) || ! is_array( $search_data['tour_type'] ) ) ? array() : $search_data['tour_type'];
		foreach ( $tour_type as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $tour_type[$key] );
		}

		$sql = "SELECT COUNT(*) FROM {$temp_tbl_name} as t1";
		$where = " 1=1";

		// price filter
		if ( $min_price != 0 || $max_price != 'no_max' ) {
			$sql .= " INNER JOIN {$tbl_postmeta} AS meta_price ON (t1.tour_id = meta_price.post_id) AND (meta_price.meta_key = 'trav_tour_min_price')";
		}
		if ( $min_price != 0 ) {
			$where .= " AND cast(meta_price.meta_value as unsigned) >= {$min_price}";
		}
		if ( $max_price != 'no_max' ) {
			$where .= " AND cast(meta_price.meta_value as unsigned) <= {$max_price} ";
		}

		if ( ! empty( $tour_type ) && is_array( $tour_type ) ) {
			$sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.tour_id 
					INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
			$where .= " AND tt.taxonomy = 'tour_type' AND tt.term_id IN (" . esc_sql( implode( ',', $tour_type ) ) . ")";
		}

		$sql .= " WHERE {$where}";
		$count = $wpdb->get_var( $sql );
		return $count;
	}
}

/*
 * Get tours from ids
 */
if ( ! function_exists( 'trav_tour_get_tours_from_id' ) ) {
	function trav_tour_get_tours_from_id( $ids ) {
		if ( ! is_array( $ids ) ) return false;
		$results = array();
		foreach( $ids as $id ) {
			$result = get_post( $id );
			if ( ! empty( $result ) && ! is_wp_error( $result ) ) {
				if ( $result->post_type == 'tour' ) $results[] = $result;
			}
		}
		return $results;
	}
}

/*
 * Get discounted(hot) tours and return data
 */
if ( ! function_exists( 'trav_tour_get_hot_tours' ) ) {
	function trav_tour_get_hot_tours( $count = 10, $country='', $city = '', $tour_type=array() ) {
		$args = array(
			'post_type'  => 'tour',
			'orderby'    => 'rand',
			'posts_per_page' => $count,
			'suppress_filters' => 0,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => 'trav_tour_hot',
					'value'   => '1',
				),
				array(
					'key'     => 'trav_tour_discount_rate',
					'value'   => array( 0, 100 ),
					'type'    => 'numeric',
					'compare' => 'BETWEEN',
				)
			),
		);

		if ( ! empty( $country ) ) {
			if ( is_numeric( $country ) ) {
				$args['meta_query'][] = array(
					'key'     => 'trav_tour_country',
					'value'   => $country,
				);
			} else {
				$term = get_term_by( 'name', $country, 'location' );
				if ( $term ) {
					$args['meta_query'][] = array(
						'key'     => 'trav_tour_country',
						'value'   => $term->term_id,
					);
				}
			}
		}

		if ( ! empty( $city ) ) {
			if ( is_numeric( $city ) ) {
				$args['meta_query'][] = array(
					'key'     => 'trav_tour_city',
					'value'   => $city,
				);
			} else {
				$term = get_term_by( 'name', $city, 'location' );
				if ( $term ) {
					$args['meta_query'][] = array(
						'key'     => 'trav_tour_city',
						'value'   => $term->term_id,
					);
				}
			}
		}

		if ( ! empty( $tour_type ) ) {
			$args['tax_query'] = array(
					array(
						'taxonomy' => 'tour_type',
						'field' => 'term_id',
						'terms' => $tour_type
						)
				);
		}
		return get_posts( $args );
	}
}

/*
 * Get discounted(hot) tours and return data
 */
if ( ! function_exists( 'trav_tour_get_tour_duration' ) ) {
	function trav_tour_get_tour_duration( $tour_id ) {
		global $wpdb;
		$tour_id = esc_sql( trav_tour_org_id( $tour_id ) );
		if ( empty( $tour_id ) ) return false;
		$sql = "SELECT MIN(tour_date) AS start_date, MAX( IF((is_daily=1 AND date_to IS NOT NULL ),date_to,tour_date)) AS end_date FROM " . TRAV_TOUR_SCHEDULES_TABLE . " WHERE tour_id={$tour_id}";
		$duration_data = $wpdb->get_row( $sql, ARRAY_A );
		if ( empty( $duration_data ) ) return false;
		$duration = date( 'd M Y', strtotime( $duration_data['start_date'] ) );
		if ( $duration_data['start_date'] != $duration_data['end_date'] ) $duration .= ' - ' . date( 'd M Y', strtotime( $duration_data['end_date'] ) );
		return $duration;
	}
}