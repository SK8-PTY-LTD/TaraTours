<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Check if a given accommodation is available for a given day and return vacany number
 */
if ( ! function_exists( 'trav_acc_get_day_vacancy' ) ) {
	function trav_acc_get_day_vacancy( $acc_id, $date, $room_type_id = '' ) {
		//SELECT (vacancies.rooms - IFNULL(bookings.rooms,0)) AS rooms FROM (SELECT accommodation_id, SUM(rooms) AS rooms FROM {$wpdb->prefix}trav_accommodation_vacancies WHERE 1=1 AND accommodation_id='39' AND date_from <= '2014-06-24'  AND date_to > '2014-06-24') AS vacancies, (SELECT accommodation_id, SUM(rooms) AS rooms FROM {$wpdb->prefix}trav_accommodation_bookings WHERE 1=1 AND accommodation_id='39' AND date_from <= '2014-06-24'  AND date_to > '2014-06-24') AS bookings
		global $wpdb;
		$acc_id = esc_sql( trav_acc_org_id( $acc_id ) );
		$room_type_id = esc_sql( trav_room_org_id( $room_type_id ) );
		$date = esc_sql( $date );
		$where = '1=1';
		if ( ! empty( $acc_id ) ) $where .= " AND accommodation_id='{$acc_id}'";
		if ( ! empty( $room_type_id ) ) $where .= " AND room_type_id='{$room_type_id}'";
		if ( ! empty( $date ) ) $where .= " AND date_from <= '{$date}'  AND date_to > '{$date}'";

		$sql = "SELECT (vacancies.rooms - IFNULL(bookings.rooms,0)) AS rooms 
		FROM (SELECT SUM(rooms) AS rooms FROM " . TRAV_ACCOMMODATION_VACANCIES_TABLE . " WHERE {$where}) AS vacancies, 
		(SELECT SUM(rooms) AS rooms FROM " . TRAV_ACCOMMODATION_BOOKINGS_TABLE . " WHERE {$where} AND status!='0' ) AS bookings";
		$vacancy = $wpdb->get_var( $sql );
		return ( $vacancy > 0 )?$vacancy:0;
	}
}

/*
 * Check if a given accommodation is available for a given month and return vacancies array
 */
if ( ! function_exists( 'trav_acc_get_month_vacancies' ) ) {
	function trav_acc_get_month_vacancies( $acc_id, $year, $month, $room_type_id='' ) {
		$num = cal_days_in_month( CAL_GREGORIAN, $month, $year );
		$vacancies = array();
		$date = new DateTime();
		for ( $i = 1; $i <= $num; $i++ ) {
			$date->setDate( $year, $month, $i );
			$vacancies[$i] = trav_acc_get_day_vacancy( $acc_id, $date->format('Y-m-d'), $room_type_id );
		}
		return $vacancies;
	}
}

/*
 * Return matched accs to given data. It is used for check availability function
 */
if ( ! function_exists( 'trav_acc_get_available_rooms' ) ) {
	function trav_acc_get_available_rooms( $acc_id, $from_date, $to_date, $rooms=1, $adults=1, $kids, $child_ages, $except_booking_no=0, $pin_code=0 ) {

		// validation
		$acc_id = trav_acc_org_id( $acc_id );
		$minimum_stay = get_post_meta( $acc_id, 'trav_accommodation_minimum_stay', true );
		$minimum_stay = is_numeric($minimum_stay)?$minimum_stay:0;
		if ( ! trav_strtotime( $from_date ) || ! trav_strtotime( $to_date ) || ( trav_strtotime( $from_date .' + ' . $minimum_stay . ' days' ) > trav_strtotime( $to_date) ) || ( ( time()-(60*60*24) ) > trav_strtotime( $from_date ) ) ) {
			return __( 'Invalid date. Please check your booking date again.', 'trav' ); //invalid data
		}

		// initiate variables
		global $wpdb;
		if ( ! is_array($child_ages) ) $child_ages = unserialize($child_ages);

		$sql = "SELECT pm0.post_id FROM " . $wpdb->postmeta . " as pm0 INNER JOIN " . $wpdb->posts . " AS room ON (pm0.post_id = room.ID) AND (room.post_status = 'publish') AND (room.post_type = 'room_type') WHERE meta_key = 'trav_room_accommodation' AND meta_value = " . esc_sql( $acc_id );
		$all_room_ids = $wpdb->get_col( $sql );
		if ( empty( $all_room_ids ) ){
			return __( 'No Rooms', 'trav' ); //invalid data
		}

		$avg_adults = ceil( $adults / $rooms );
		$avg_kids = ceil( $kids / $rooms );

		// get available accommodation room_type_id based on max_adults and max_kids
		$sql = "SELECT pm0.post_id AS room_type_id FROM (SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key = 'trav_room_accommodation' AND meta_value = " . esc_sql( $acc_id ) . " ) AS pm0 
				INNER JOIN " . $wpdb->posts . " AS room ON (pm0.post_id = room.ID) AND (room.post_status = 'publish') AND (room.post_type = 'room_type')
				INNER JOIN " . $wpdb->postmeta . " AS pm1 ON (pm0.post_id = pm1.post_id) AND (pm1.meta_key = 'trav_room_max_adults')
				LEFT JOIN " . $wpdb->postmeta . " AS pm2 ON (pm0.post_id = pm2.post_id) AND (pm2.meta_key = 'trav_room_max_kids')
				WHERE ( pm1.meta_value >= " . esc_sql( $avg_adults ) . " ) AND ( pm1.meta_value + IFNULL(pm2.meta_value,0) >= " . esc_sql( $avg_adults + $avg_kids ) . " )";

		$matched_room_ids = $wpdb->get_col( $sql ); //object (room_type_id)

		if ( empty( $matched_room_ids ) ){
			$return_value = array(
				'all_room_type_ids' => $all_room_ids,
				'matched_room_type_ids' => array(),
				'bookable_room_type_ids' => array(),
				'check_dates' => array(),
				'prices' => array()
			);
			return $return_value;
		}

		// get available accommodation room_type_id and price based on date
		// initiate variables
		$check_dates = array();
		$price_data = array();
		$total_price_data = array();

		// prepare date for loop
		$from_date_obj = new DateTime( '@' . trav_strtotime( $from_date ) );
		$to_date_obj = new DateTime( '@' . trav_strtotime( $to_date ) );
		$date_interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($from_date_obj, $date_interval, $to_date_obj);

		$acc_id = esc_sql( $acc_id );
		$rooms = esc_sql( $rooms );
		$adults = esc_sql( $adults );
		$kids = esc_sql( $kids );
		$child_ages = esc_sql( $child_ages );
		$except_booking_no = esc_sql( $except_booking_no );
		$pin_code = esc_sql( $pin_code );

		$bookable_room_ids = $matched_room_ids;

		foreach ( $period as $dt ) {
			$check_date = esc_sql( $dt->format( "Y-m-d" ) );
			$check_dates[] = $check_date;

			$sql = "SELECT vacancies.room_type_id, vacancies.price_per_room , vacancies.price_per_person, vacancies.child_price
					FROM (SELECT room_type_id, rooms, price_per_room, price_per_person, child_price
							FROM " . TRAV_ACCOMMODATION_VACANCIES_TABLE . " 
							WHERE 1=1 AND accommodation_id='" . $acc_id . "' AND room_type_id IN (" . implode( ',', $bookable_room_ids ) . ") AND date_from <= '" . $check_date . "'  AND date_to > '" . $check_date . "' ) AS vacancies
					LEFT JOIN (SELECT room_type_id, SUM(rooms) AS rooms 
							FROM " . TRAV_ACCOMMODATION_BOOKINGS_TABLE . " 
							WHERE 1=1 AND status!='0' AND accommodation_id='" . $acc_id . "' AND date_to > '" . $check_date . "'  AND date_from <= '" . $check_date . "'" . ( ( empty( $except_booking_no ) || empty( $pin_code ) )?"":( " AND NOT ( booking_no = '" . $except_booking_no . "' AND pin_code = '" . $pin_code . "' )" ) ) . " GROUP BY room_type_id
					) AS bookings ON vacancies.room_type_id = bookings.room_type_id
					WHERE vacancies.rooms - IFNULL(bookings.rooms,0) >= " . $rooms . ";";

			$results = $wpdb->get_results( $sql ); // object (room_type_id, price_per_room, price_per_person, child_price)

			if ( empty( $results ) ) { //if no available rooms on selected date
				$return_value = array(
					'all_room_type_ids' => $all_room_ids,
					'matched_room_type_ids' => $matched_room_ids,
					'bookable_room_type_ids' => array(),
					'check_dates' => array(),
					'prices' => array(),
				);
				return $return_value;
			}

			$day_available_room_type_ids = array();

			foreach ( $results as $result ) {
				$day_available_room_type_ids[] = $result->room_type_id;
				$price_per_room = (float) $result->price_per_room;
				$price_per_person = (float) $result->price_per_person;
				$child_price_data = unserialize( $result->child_price );


				//calculate child price
				$child_price = array();
				$total_child_price = 0;

				if ( ( $kids > 0 ) && ( ! empty( $child_price_data ) ) && ( ! empty( $child_ages ) ) ) {

					usort($child_price_data, function($a, $b) { return $a[0] - $b[0]; });

					foreach ( $child_ages as $child_age ) {
						$is_child = false;
						foreach ( $child_price_data as $age_price_pair ) {
							if ( is_array( $age_price_pair ) && ( count( $age_price_pair ) >= 2 ) && ( (int) $child_age <= (int) $age_price_pair[0] ) ) {
								$is_child = true;
								$child_price[] = (float) $age_price_pair[1];
								$total_child_price += (float) $age_price_pair[1];
								break;
							}
						}

						//if child price for this age is not set, calculate as a adult
						if ( ! $is_child ) {
							$child_price[] = $price_per_person;
							$total_child_price += $price_per_person;
						}
					}
				}

				$total_price = $price_per_room * $rooms + $price_per_person * $adults + $total_child_price;
				$price_data[ $result->room_type_id ][ $check_date ] = array(
						'ppr' => $price_per_room,
						'ppp' => $price_per_person,
						'cp' => $child_price,
						'total' => $total_price
					);

			}

			$bookable_room_ids = $day_available_room_type_ids;
		}

		//$number_of_days = count( $check_dates );
		$return_value = array(
							'all_room_type_ids' => $all_room_ids,
							'matched_room_type_ids' => $matched_room_ids,
							'bookable_room_type_ids' => $bookable_room_ids,
							'check_dates' => $check_dates,
							'prices' => $price_data
			);

		return $return_value;
	}
}

/*
 * Calculate the price of selected accommodation room and return price array data
 */
if ( ! function_exists( 'trav_acc_get_room_price_data' ) ) {
	function trav_acc_get_room_price_data( $acc_id, $room_type_id, $from_date, $to_date, $rooms=1, $adults=1, $kids=0, $child_ages, $except_booking_no=0, $pin_code=0 ) {
		global $wpdb;

		$acc_id = trav_acc_org_id( $acc_id );
		$room_type_id = trav_room_org_id( $room_type_id );
		//validation
		if ( ! is_array( $child_ages ) ){$child_ages = unserialize($child_ages);}
		$room_accommodation_id = get_post_meta( $room_type_id, 'trav_room_accommodation', true );
		if ( $room_accommodation_id != $acc_id ) return false;

		$max_adults = get_post_meta( $room_type_id, 'trav_room_max_adults', true ); if ( empty($max_adults) ) $max_adults = 0;
		$max_kids = get_post_meta( $room_type_id, 'trav_room_max_kids', true ); if ( empty($max_adults) ) $max_kids = 0;
		$avg_adults = ceil( $adults / $rooms );
		$avg_kids = ceil( $kids / $rooms );
		if ( ( $avg_adults > $max_adults ) || ( ( $avg_adults + $avg_kids ) > ( $max_adults + $max_kids ) ) ) return false;

		if ( ( time()-( 60*60*24 ) ) > trav_strtotime( $from_date ) ) return false;
		$minimum_stay = get_post_meta( $acc_id, 'trav_accommodation_minimum_stay', true );
		$minimum_stay = is_numeric($minimum_stay)?$minimum_stay:0;
		if ( ! trav_strtotime( $from_date ) || ! trav_strtotime( $to_date ) || ( trav_strtotime( $from_date ) >= trav_strtotime( $to_date ) ) || ( trav_strtotime( $from_date .' + ' . $minimum_stay . ' days' ) > trav_strtotime( $to_date) ) ) return false;

		$from_date_obj = new DateTime( '@' . trav_strtotime( $from_date ) );
		$to_date_obj = new DateTime( '@' . trav_strtotime( $to_date ) );
		$date_interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($from_date_obj, $date_interval, $to_date_obj);

		$price_data = array();
		$total_price = 0.0;

		$acc_id = esc_sql( $acc_id );
		$room_type_id = esc_sql( $room_type_id );
		$rooms = esc_sql( $rooms );
		$adults = esc_sql( $adults );
		$kids = esc_sql( $kids );
		$child_ages = esc_sql( $child_ages );
		$except_booking_no = esc_sql( $except_booking_no );
		$pin_code = esc_sql( $pin_code );

		foreach ( $period as $dt ) {

			$check_date = esc_sql( $dt->format( "Y-m-d" ) );
			$check_dates[] = $check_date;

			$sql = "SELECT vacancies.room_type_id, vacancies.price_per_room , vacancies.price_per_person, vacancies.child_price
					FROM (SELECT room_type_id, rooms, price_per_room, price_per_person, child_price
							FROM " . TRAV_ACCOMMODATION_VACANCIES_TABLE . " 
							WHERE 1=1 AND accommodation_id='" . $acc_id . "' AND room_type_id = '" . $room_type_id . "' AND date_from <= '" . $check_date . "'  AND date_to > '" . $check_date . "' ) AS vacancies
					LEFT JOIN (SELECT room_type_id, SUM(rooms) AS rooms 
							FROM " . TRAV_ACCOMMODATION_BOOKINGS_TABLE . " 
							WHERE 1=1 AND status!='0' AND accommodation_id='" . $acc_id . "' AND room_type_id = '" . $room_type_id . "' AND date_to > '" . $check_date . "' AND date_from <= '" . $check_date . "'" . ( ( empty( $except_booking_no ) || empty( $pin_code ) )?"":( " AND NOT ( booking_no = '" . $except_booking_no . "' AND pin_code = '" . $pin_code . "' )" ) ) . "
					) AS bookings ON vacancies.room_type_id = bookings.room_type_id
					WHERE vacancies.rooms - IFNULL(bookings.rooms,0) >= " . $rooms . ";";

			$result = $wpdb->get_row( $sql ); // object (room_type_id, price_per_room, price_per_person, child_price)

			if ( empty( $result ) ) { //if no available rooms on selected date
				return false;
			} else {
				$price_per_room = (float) $result->price_per_room;
				$price_per_person = (float) $result->price_per_person;
				$child_price_data = unserialize( $result->child_price );

				$child_price = array();
				$total_child_price = 0;

				if ( ( $kids > 0 ) && ( ! empty( $child_price_data ) ) && ( ! empty( $child_ages ) ) ) {

					usort($child_price_data, function($a, $b) { return $a[0] - $b[0]; });

					foreach ( $child_ages as $child_age ) {
						$is_child = false;
						foreach ( $child_price_data as $age_price_pair ) {
							if ( is_array( $age_price_pair ) && ( count( $age_price_pair ) >= 2 ) && ( (int) $child_age <= (int) $age_price_pair[0] ) ) {
								$is_child = true;
								$child_price[] = (float) $age_price_pair[1];
								$total_child_price += (float) $age_price_pair[1];
								break;
							}
						}

						//if child price for this age is not set, calculate as a adult
						if ( ! $is_child ) {
							$child_price[] = $price_per_person;
							$total_child_price += $price_per_person;
						}
					}
				}

				$day_price = $price_per_room * $rooms + $price_per_person * $adults + $total_child_price;
				$price_data[ $check_date ] = array(
					'ppr' => $price_per_room,
					'ppp' => $price_per_person,
					'cp' => $child_price,
					'total' => $day_price
				);
				$total_price += $day_price;
			}
		}

		$return_value = array(
			'check_dates' => $check_dates,
			'prices'      => $price_data,
			'total_price' => $total_price
		);

		return $return_value;
	}
}

/*
 * Get special( latest or featured ) accommodations and return data
 */
if ( ! function_exists( 'trav_acc_get_special_accs' ) ) {
	function trav_acc_get_special_accs( $type='latest', $count=10, $exclude_ids=array(),  $country='', $city='', $acc_type=array() ) {
		$args = array(
				'post_type'  => 'accommodation',
				'suppress_filters' => 0,
				'posts_per_page' => $count,
			);
		if ( ! empty( $country ) ) {
			if ( is_numeric( $country ) ) {
				$args['meta_query'][] = array(
					'key'     => 'trav_accommodation_country',
					'value'   => $country,
				);
			} else {
				$term = get_term_by( 'name', $country, 'location' );
				if ( $term ) {
					$country = $term->term_id;
					$args['meta_query'][] = array(
						'key'     => 'trav_accommodation_country',
						'value'   => $country,
					);
				}
			}
		}

		if ( ! empty( $city ) ) {
			if ( is_numeric( $city ) ) {
				$args['meta_query'][] = array(
					'key'     => 'trav_accommodation_city',
					'value'   => $city,
				);
			} else {
				$term = get_term_by( 'name', $city, 'location' );
				if ( $term ) {
					$city = $term->term_id;
					$args['meta_query'][] = array(
						'key'     => 'trav_accommodation_city',
						'value'   => $city,
					);
				}
			}
		}

		if ( ! empty( $exclude_ids ) ) {
			$args['post__not_in'] = $exclude_ids;
		}

		if ( ! empty( $acc_type ) ) {
			$args['tax_query'] = array(
					array(
						'taxonomy' => 'accommodation_type',
						'field' => 'term_id',
						'terms' => $acc_type
						)
				);
		}

		if ( $type == 'featured'  ) {
			$args = array_merge( $args, array(
				'orderby'    => 'rand',
				'meta_key'     => 'trav_accommodation_featured',
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
			$sql = 'SELECT accommodation_id, COUNT(*) AS booking_count FROM ' . TRAV_ACCOMMODATION_BOOKINGS_TABLE . ' AS booking';
			$where = ' WHERE (booking.status <> 0) AND (booking.created > %s)';
			if ( ! empty( $country ) ) {
				$sql .= " INNER JOIN {$tbl_postmeta} AS meta_c1 ON (meta_c1.meta_key = 'trav_accommodation_country') AND (booking.accommodation_id = meta_c1.post_id)";
				$where .= " AND meta_c1.meta_value like '%%{$country}%%'";
			}
			if ( ! empty( $city ) ) {
				$sql .= " INNER JOIN {$tbl_postmeta} AS meta_c1 ON (meta_c1.meta_key = 'trav_accommodation_city') AND (booking.accommodation_id = meta_c1.post_id)";
				$where .= " AND meta_c1.meta_value like '%%{$city}%%'";
			}
			if ( ! empty( $acc_type ) ) {
				$sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.acc_id 
						INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
				$where .= " AND tt.taxonomy = 'accommodation_type' AND tt.term_id IN (" . esc_sql( implode( ',', $acc_type ) ) . ")";
			}
			$sql .= $where . ' GROUP BY booking.accommodation_id ORDER BY booking_count desc LIMIT %d';
			$popular_accs = $wpdb->get_results( $wpdb->prepare( $sql, $date, $count ) );
			$result = array();
			if ( ! empty( $popular_accs ) ) {
				foreach ( $popular_accs as $acc ) {
					$result[] = get_post( trav_acc_clang_id( $acc->accommodation_id ) );
				}
			}
			// if booked room number in last month is smaller than count then add latest accs
			if ( count( $popular_accs ) < $count ) {
				foreach ( $popular_accs as $acc ) {
					$exclude_ids[] = trav_acc_clang_id( $acc->accommodation_id );
				}
				$result = array_merge( $result, trav_acc_get_special_accs( 'latest', $count - count( $popular_accs ), $exclude_ids, $country, $city, $acc_type ) );
			}
			return $result;
		}
	}
}

/*
 * Get discounted(hot) accommodations and return data
 */
if ( ! function_exists( 'trav_acc_get_hot_accs' ) ) {
	function trav_acc_get_hot_accs( $count = 10, $country='', $city = '', $acc_type=array() ) {
		$args = array(
			'post_type'  => 'accommodation',
			'orderby'    => 'rand',
			'posts_per_page' => $count,
			'suppress_filters' => 0,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => 'trav_accommodation_hot',
					'value'   => '1',
				),
				array(
					'key'     => 'trav_accommodation_discount_rate',
					'value'   => array( 0, 100 ),
					'type'    => 'numeric',
					'compare' => 'BETWEEN',
				),
				array(
					'key'     => 'trav_accommodation_edate',
					'value'   => date('Y-m-d'),
					'compare' => '>=',
				),
			),
		);

		if ( ! empty( $country ) ) {
			if ( is_numeric( $country ) ) {
				$args['meta_query'][] = array(
					'key'     => 'trav_accommodation_country',
					'value'   => $country,
				);
			} else {
				$term = get_term_by( 'name', $country, 'location' );
				if ( $term ) {
					$args['meta_query'][] = array(
						'key'     => 'trav_accommodation_country',
						'value'   => $term->term_id,
					);
				}
			}
		}

		if ( ! empty( $city ) ) {
			if ( is_numeric( $city ) ) {
				$args['meta_query'][] = array(
					'key'     => 'trav_accommodation_city',
					'value'   => $city,
				);
			} else {
				$term = get_term_by( 'name', $city, 'location' );
				if ( $term ) {
					$args['meta_query'][] = array(
						'key'     => 'trav_accommodation_city',
						'value'   => $term->term_id,
					);
				}
			}
		}

		if ( ! empty( $acc_type ) ) {
			$args['tax_query'] = array(
					array(
						'taxonomy' => 'accommodation_type',
						'field' => 'term_id',
						'terms' => $acc_type
						)
				);
		}
		return get_posts( $args );
	}
}

/*
 * Get accommodations from ids
 */
if ( ! function_exists( 'trav_acc_get_accs_from_id' ) ) {
	function trav_acc_get_accs_from_id( $ids ) {
		if ( ! is_array( $ids ) ) return false;
		$results = array();
		foreach( $ids as $id ) {
			$result = get_post( $id );
			if ( ! empty( $result ) && ! is_wp_error( $result ) ) {
				if ( $result->post_type == 'accommodation' ) $results[] = $result;
			}
		}
		return $results;
	}
}

/*
 * Get Accommodation City Data
 */
if ( ! function_exists( 'trav_acc_get_city' ) ) {
	function trav_acc_get_city( $acc_id ) {
		return trav_post_get_location( $acc_id, 'accommodation', 'city' );
	}
}

/*
 * Get Accommodation Country Data
 */
if ( ! function_exists( 'trav_acc_get_country' ) ) {
	function trav_acc_get_country( $acc_id ) {
		return trav_post_get_location( $acc_id, 'accommodation', 'country' );
	}
}

/*
 * Get Accommodation Search Result
 */
if ( ! function_exists( 'trav_acc_get_search_result' ) ) {
	function trav_acc_get_search_result( $s='', $date_from='', $date_to='', $rooms=1, $adults=1, $kids=0, $order_by='acc_title', $order='ASC', $last_no=0, $per_page=12, $min_price=0, $max_price='no_max', $rating=0, $acc_type, $amenities ) {

		// if wrong date return false
		if ( ! empty( $date_from ) && ! empty( $date_to ) && ( trav_strtotime( $date_from ) >= trav_strtotime( $date_to ) ) ) return false;
		global $wpdb, $language_count;
		$tbl_posts = esc_sql( $wpdb->posts );
		$tbl_postmeta = esc_sql( $wpdb->postmeta );
		$tbl_terms = esc_sql( $wpdb->prefix . 'terms' );
		$tbl_term_taxonomy = esc_sql( $wpdb->prefix . 'term_taxonomy' );
		$tbl_term_relationships = esc_sql( $wpdb->prefix . 'term_relationships' );
		$tbl_icl_translations = esc_sql( $wpdb->prefix . 'icl_translations' );

		$temp_tbl_name = esc_sql( 'Search_' . session_id() );
		$sql = '';
		$rooms = esc_sql( $rooms );
		$adults = esc_sql( $adults );
		$kids = esc_sql( $kids );
		$order_by = esc_sql( $order_by );
		$order = esc_sql( $order );
		$last_no = esc_sql( $last_no );
		$per_page = esc_sql( $per_page );
		$min_price = esc_sql( $min_price );
		$max_price = esc_sql( $max_price );
		$rating = esc_sql( $rating );
		if ( empty( $acc_type ) || ! is_array( $acc_type ) ) $acc_type = array();
		if ( empty( $amenities ) || ! is_array( $amenities ) ) $amenities = array();
		foreach ( $acc_type as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $acc_type[$key] );
		}
		foreach ( $amenities as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $amenities[$key] );
		}

		//mysql escape sting and like escape
		if ( floatval( get_bloginfo( 'version' ) ) >= 4.0 ) {
			$s = esc_sql( $wpdb->esc_like( $s ) );
		} else {
			$s = esc_sql( like_escape( $s ) );
		}

		$from_date_obj = date_create_from_format( trav_get_date_format('php'), $date_from );
		$to_date_obj = date_create_from_format( trav_get_date_format('php'), $date_to );

		$s_query = ''; // sql for search keyword
		$c_query = ''; // sql for conditions ( review, avg_price, user_rating )
		$v_query = ''; // sql for vacancy check

		if ( ! empty( $s ) ) {
			$s_query = "SELECT DISTINCT post_s1.ID AS acc_id FROM {$tbl_posts} AS post_s1 
						LEFT JOIN {$tbl_postmeta} AS meta_s1 ON post_s1.ID = meta_s1.post_id
						LEFT JOIN {$tbl_terms} AS terms_s1 ON (meta_s1.meta_key IN('trav_accommodation_country','trav_accommodation_city')) AND (terms_s1.term_id = meta_s1.meta_value)
						WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'accommodation')
						  AND ((post_s1.post_title LIKE '%{$s}%') 
							OR (post_s1.post_content LIKE '%{$s}%')
							OR (meta_s1.meta_value LIKE '%{$s}%')
							OR (terms_s1.name LIKE '%{$s}%'))";
		} else {
			$s_query = "SELECT post_s1.ID AS acc_id FROM {$tbl_posts} AS post_s1 
						WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'accommodation')";
		}

		// if wpml is enabled do search by default language post
		if ( defined('ICL_LANGUAGE_CODE') && ( $language_count > 1 ) ) {
			$s_query = "SELECT DISTINCT it2.element_id AS acc_id FROM ({$s_query}) AS t0
						INNER JOIN {$tbl_icl_translations} it1 ON (it1.element_type = 'post_accommodation') AND it1.element_id = t0.acc_id
						INNER JOIN {$tbl_icl_translations} it2 ON (it2.element_type = 'post_accommodation') AND it2.language_code='" . trav_get_default_language() . "' AND it2.trid = it1.trid ";
		}

		$c_query = "SELECT t1.*, meta_c1.post_id AS room_id, meta_c2.meta_value AS max_adults, meta_c3.meta_value AS max_kids, meta_c4.meta_value AS minimum_stay
					FROM ( {$s_query} ) AS t1
					INNER JOIN {$tbl_postmeta} AS meta_c1 ON (meta_c1.meta_key = 'trav_room_accommodation') AND (t1.acc_id = meta_c1.meta_value)
					INNER JOIN {$tbl_postmeta} AS meta_c2 ON (meta_c1.post_id = meta_c2.post_id) AND (meta_c2.meta_key='trav_room_max_adults')
					LEFT JOIN {$tbl_postmeta} AS meta_c3 ON (meta_c1.post_id = meta_c3.post_id) AND (meta_c3.meta_key='trav_room_max_kids')
					LEFT JOIN {$tbl_postmeta} AS meta_c4 ON (t1.acc_id = meta_c4.post_id) AND (meta_c4.meta_key='trav_accommodation_minimum_stay')";

		// if this searh has specified date then check vacancy and booking data, but if it doesn't have specified date then only check other search factors
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

			$v_query = "SELECT t3.acc_id, t3.room_id, t3.max_adults, t3.max_kids, t3.minimum_stay, MIN(rooms) AS min_rooms FROM (
							SELECT t2.*, (IFNULL(vacancies.rooms,0) - IFNULL(SUM(bookings.rooms),0)) AS rooms, check_dates.check_date 
							FROM ({$c_query}) AS t2
							JOIN ( {$sql_check_date} ) AS check_dates
							LEFT JOIN " . TRAV_ACCOMMODATION_VACANCIES_TABLE . " AS vacancies ON (vacancies.room_type_id = t2.room_id) AND (vacancies.date_from <= check_dates.check_date AND vacancies.date_to > check_dates.check_date)
							LEFT JOIN " . TRAV_ACCOMMODATION_BOOKINGS_TABLE . " AS bookings ON bookings.status!='0' AND (bookings.room_type_id = t2.room_id) AND (bookings.date_from <= check_dates.check_date AND bookings.date_to > check_dates.check_date)
							GROUP BY t2.room_id, check_dates.check_date
						  ) AS t3
						  GROUP BY t3.room_id";

			// if rooms == 1 do specific search and if rooms > 1 do overal search for vacancies
			if ( $rooms == 1 ) {
				$sql = "SELECT t4.acc_id, SUM(t4.min_rooms) AS rooms FROM ({$v_query}) AS t4
					WHERE ((t4.minimum_stay IS NULL) OR (t4.minimum_stay <= {$days}))
					  AND (t4.max_adults >= {$adults})
					  AND (t4.max_adults + IFNULL(t4.max_kids,0) >= {$adults} + {$kids})
					GROUP BY t4.acc_id
					HAVING rooms >= {$rooms}";
			} else {
				$sql = "SELECT t4.acc_id, SUM(t4.min_rooms) AS rooms, SUM(IFNULL(t4.max_adults,0) * t4.min_rooms) as acc_max_adults, SUM(IFNULL(t4.max_kids,0) * t4.min_rooms) as acc_max_kids FROM ({$v_query}) AS t4
					WHERE ((t4.minimum_stay IS NULL) OR (t4.minimum_stay <= {$days}))
					GROUP BY t4.acc_id
					HAVING rooms >= {$rooms} AND acc_max_adults >= {$adults} AND acc_max_kids >= {$kids}";
			}
		} else {
			// without specified date
			$avg_adults = ceil( $adults / $rooms );
			$avg_kids = ceil( $kids / $rooms );
			$sql = "{$c_query} WHERE (meta_c2.meta_value >= {$avg_adults}) AND (meta_c2.meta_value + IFNULL(meta_c3.meta_value,0) >= {$avg_adults} + {$avg_kids}) GROUP BY acc_id";
		}

		// if wpml is enabled return current language posts
		if ( defined('ICL_LANGUAGE_CODE') && ( $language_count > 1 ) && ( trav_get_default_language() != ICL_LANGUAGE_CODE ) ) {
			$sql = "SELECT it4.element_id AS acc_id FROM ({$sql}) AS t5
					INNER JOIN {$tbl_icl_translations} it3 ON (it3.element_type = 'post_accommodation') AND it3.element_id = t5.acc_id
					INNER JOIN {$tbl_icl_translations} it4 ON (it4.element_type = 'post_accommodation') AND it4.language_code='" . ICL_LANGUAGE_CODE . "' AND it4.trid = it3.trid";
		}

		// var_dump($sql);
		$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS {$temp_tbl_name} AS " . $sql;
		$wpdb->query( $sql );

		$sql = "SELECT t1.*, post_l1.post_title as acc_title, meta_rating.meta_value as review, meta_price.meta_value as avg_price FROM {$temp_tbl_name} as t1
				INNER JOIN {$tbl_posts} post_l1 ON (t1.acc_id = post_l1.ID) AND (post_l1.post_status = 'publish') AND (post_l1.post_type = 'accommodation')
				LEFT JOIN {$tbl_postmeta} AS meta_rating ON (t1.acc_id = meta_rating.post_id) AND (meta_rating.meta_key = 'review')
				LEFT JOIN {$tbl_postmeta} AS meta_price ON (t1.acc_id = meta_price.post_id) AND (meta_price.meta_key = 'trav_accommodation_avg_price')";
		$where = ' 1=1';

		if ( $min_price != 0 ) {
			$where .= " AND cast(meta_price.meta_value as unsigned) >= {$min_price}";
		}
		if ( $max_price != 'no_max' ) {
			$where .= " AND cast(meta_price.meta_value as unsigned) <= {$max_price} ";
		}

		// rating filter
		if ( $rating != 0 ) {
			$where .= " AND cast(meta_rating.meta_value as DECIMAL(2,1)) >= {$rating} ";
		}

		if ( ! empty( $acc_type ) ) {
			$sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.acc_id 
					INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
			$where .= " AND tt.taxonomy = 'accommodation_type' AND tt.term_id IN (" . esc_sql( implode( ',', $acc_type ) ) . ")";
		}

		if ( ! empty( $amenities ) ) {
			$where .= " AND (( SELECT COUNT(1) FROM {$tbl_term_relationships} AS tr1 
					INNER JOIN {$tbl_term_taxonomy} AS tt1 ON ( tr1.term_taxonomy_id= tt1.term_taxonomy_id )
					WHERE tt1.taxonomy = 'amenity' AND tt1.term_id IN (" . esc_sql( implode( ',', $amenities ) ) . ") AND tr1.object_id = t1.acc_id ) = " . count( $amenities ) . ")";
		}

		$sql .= " WHERE {$where} GROUP BY acc_id ORDER BY {$order_by} {$order} LIMIT {$last_no}, {$per_page};";

		$results = $wpdb->get_results( $sql );
		return $results;
	}
}

/*
 * Get Accommodation Search Result Count
 */
if ( ! function_exists( 'trav_acc_get_search_result_count' ) ) {
	function trav_acc_get_search_result_count( $min_price, $max_price, $rating, $acc_type, $amenities ) {
		global $wpdb;
		$tbl_term_taxonomy = $wpdb->prefix . 'term_taxonomy';
		$tbl_term_relationships = $wpdb->prefix . 'term_relationships';
		$temp_tbl_name = 'Search_' . session_id();
		$tbl_postmeta = esc_sql( $wpdb->prefix . 'postmeta' );
		if ( empty( $acc_type ) || ! is_array( $acc_type ) ) $acc_type = array();
		if ( empty( $amenities ) || ! is_array( $amenities ) ) $amenities = array();
		foreach ( $acc_type as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $acc_type[$key] );
		}
		foreach ( $amenities as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $amenities[$key] );
		}

		$sql = "SELECT COUNT(*) FROM {$temp_tbl_name} as t1";
		$where = " 1=1";



		// price filter
		if ( $min_price != 0 || $max_price != 'no_max' ) {
			$sql .= " INNER JOIN {$tbl_postmeta} AS meta_price ON (t1.acc_id = meta_price.post_id) AND (meta_price.meta_key = 'trav_accommodation_avg_price')";
		}
		if ( $min_price != 0 ) {
			$where .= " AND cast(meta_price.meta_value as unsigned) >= {$min_price}";
		}
		if ( $max_price != 'no_max' ) {
			$where .= " AND cast(meta_price.meta_value as unsigned) <= {$max_price} ";
		}

		// rating filter
		if ( $rating != 0 ) {
			$sql .= " INNER JOIN {$tbl_postmeta} AS meta_rating ON (t1.acc_id = meta_rating.post_id) AND (meta_rating.meta_key = 'review')";
			$where .= " AND cast(meta_rating.meta_value as DECIMAL(2,1)) >= {$rating} ";
		}

		if ( ! empty( $acc_type ) ) {
			$sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.acc_id 
					INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
			$where .= " AND tt.taxonomy = 'accommodation_type' AND tt.term_id IN (" . esc_sql( implode( ',', $acc_type ) ) . ")";
		}

		if ( ! empty( $amenities ) ) {
			$where .= " AND (( SELECT COUNT(1) FROM {$tbl_term_relationships} AS tr1 
					INNER JOIN {$tbl_term_taxonomy} AS tt1 ON ( tr1.term_taxonomy_id= tt1.term_taxonomy_id )
					WHERE tt1.taxonomy = 'amenity' AND tt1.term_id IN (" . esc_sql( implode( ',', $amenities ) ) . ") AND tr1.object_id = t1.acc_id ) = " . count( $amenities ) . ")";
		}

		$sql .= " WHERE {$where}";
		$count = $wpdb->get_var( $sql );
		return $count;
	}
}

/*
 * Get Similar Accommodation
 */
if ( ! function_exists( 'trav_acc_get_similar' ) ) {
	function trav_acc_get_similar( $acc_id, $limit ) {
		global $wpdb, $language_count;
		$tbl_icl_translations = esc_sql( $wpdb->prefix . 'icl_translations' );
		$city = esc_sql( get_post_meta( $acc_id, 'trav_accommodation_city', true ) );
		$country = esc_sql( get_post_meta( $acc_id, 'trav_accommodation_country', true ) );
		$star_rating = esc_sql( get_post_meta( $acc_id, 'trav_accommodation_star_rating', true ) );

		$avg_price = esc_sql( get_post_meta( $acc_id, 'trav_accommodation_avg_price', true ) );
		$avg_price = empty($avg_price) ? 0 : esc_sql( $avg_price );

		$accommodation_type = wp_get_post_terms( $acc_id, 'accommodation_type' );
		$acc_type_id = ( ! empty( $accommodation_type ) ) ? esc_sql( $accommodation_type[0]->term_id ) : 0;
		$acc_id = esc_sql( $acc_id );
		$limit = esc_sql( $limit );

		$tbl_posts = $wpdb->posts;
		$tbl_postmeta = $wpdb->postmeta;
		$tbl_terms = $wpdb->prefix . 'terms';
		$tbl_term_taxonomy = $wpdb->prefix . 'term_taxonomy';
		$tbl_term_relationships = $wpdb->prefix . 'term_relationships';

		$sql = "SELECT DISTINCT post1.ID, ( IF( IFNULL(meta1.meta_value,'')='{$city}', 8, 0) + IF( IFNULL(meta2.meta_value,'')='{$country}', 4, 0) + IF( IFNULL(meta3.meta_value,'')='{$star_rating}', 1, 0) + IF( IFNULL(tt.term_id,0)={$acc_type_id}, 2, 0) ) AS similarity, ABS( IFNULL(meta4.meta_value,0) - {$avg_price} ) as price_dist
				FROM {$tbl_posts} AS post1
				LEFT JOIN {$tbl_postmeta}  AS meta1 ON meta1.meta_key = 'trav_accommodation_city' AND post1.ID = meta1.post_id
				LEFT JOIN {$tbl_postmeta}  AS meta2 ON meta2.meta_key = 'trav_accommodation_country' AND post1.ID = meta2.post_id
				LEFT JOIN {$tbl_postmeta}  AS meta3 ON meta3.meta_key = 'trav_accommodation_star_rating' AND post1.ID = meta3.post_id
				LEFT JOIN {$tbl_postmeta}  AS meta4 ON meta4.meta_key = 'trav_accommodation_avg_price' AND post1.ID = meta4.post_id
				LEFT JOIN {$tbl_term_relationships} AS tr ON post1.ID = tr.object_id
				INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.taxonomy = 'accommodation_type' AND tr.term_taxonomy_id = tt.term_taxonomy_id";

		// if wpml is enabled do search by default language post
		if ( defined('ICL_LANGUAGE_CODE') && ( $language_count > 1 ) ) {
			$sql .= " INNER JOIN {$tbl_icl_translations} AS it2 ON it2.element_type = 'post_accommodation' AND it2.language_code='" . ICL_LANGUAGE_CODE . "' AND it2.element_id = post1.ID";
		}
		$sql .= " WHERE post_type = 'accommodation' AND post_status='publish' AND post1.ID != {$acc_id} ORDER BY similarity DESC, price_dist ASC LIMIT {$limit}";

		$results = $wpdb->get_col( $sql );
		return $results;
	}
}

/*
 * function to update booking
 */
if ( ! function_exists( 'trav_acc_update_booking' ) ) {
	function trav_acc_update_booking( $booking_no, $pin_code, $new_data, $action='update' ) {
		global $wpdb, $trav_options;
		$result = $wpdb->update( TRAV_ACCOMMODATION_BOOKINGS_TABLE, $new_data, array( 'booking_no' => $booking_no, 'pin_code' => $pin_code ) );
		if ( $result ) {
			trav_acc_send_confirmation_email( $booking_no, $pin_code, $action );
			return $result;
		}
		return false;
	}
}

/*
 * get star rating label
 */
if ( ! function_exists( 'trav_acc_get_star_rating' ) ) {
	function trav_acc_get_star_rating( $acc_id ) {
		$accommodation_type = wp_get_post_terms( $acc_id, 'accommodation_type' );
		$hotel_star_rating = get_post_meta( $acc_id, 'trav_accommodation_star_rating', true );
		$acc_type_name = '';
		$result = '';
		if ( ! empty ( $accommodation_type ) ) {
			$acc_type_name =  $accommodation_type[0]->name;
		}
		if ( ! empty( $hotel_star_rating ) ) {
			$result = ' <div title="' . $hotel_star_rating . '-' . __( 'star', 'trav') . ' ' . $acc_type_name . '" class="five-stars-container no-back-star" data-toggle="tooltip" data-placement="bottom"><span class="five-stars" style="width: ' . ( $hotel_star_rating / 5 * 100 ) . '%;"></span></div>';
		}
		return $result;
	}
}

/*
 * return user booking list
 */
if ( ! function_exists( 'trav_acc_get_user_booking_list' ) ) {
	function trav_acc_get_user_booking_list( $user_id, $status = 1, $sortby = 'created', $order='desc' ) {

		global $wpdb, $trav_options;
		$sql = '';
		$order = ( $order == 'desc' ) ? 'desc' : 'asc';
		$order_by = ' ORDER BY ' . $sortby . ' ' . $order;
		$where = ' WHERE 1=1';
		$where .= ' AND user_id=%d';
		if ( $status == 0 ) {
			// cancelled
			$where .= ' AND status=' . $status;
		} elseif ( $status == 1 ) {
			// upcomming
			$where .= ' AND status=' . $status . ' AND date_to > "' . date('Y-m-d') . '"';
		} elseif ( $status == 2 ) {
			// completed
			$where .= ' AND ( status=' . $status . ' OR (status=1 AND date_from < "' . date('Y-m-d') . '") )';
		}
		$sql = $wpdb->prepare( 'SELECT * FROM ' . TRAV_ACCOMMODATION_BOOKINGS_TABLE . $where . $order_by, $user_id );
		//return $sql;

		$booking_list = $wpdb->get_results( $sql );

		if ( empty( $booking_list ) ) return __( 'You don\'t have any booked trips yet.', 'trav' ); // if empty return false

		$acc_book_conf_url = '';
		if ( isset( $trav_options['acc_booking_confirmation_page'] ) && ! empty( $trav_options['acc_booking_confirmation_page'] ) ) {
			$acc_book_conf_url = trav_get_permalink_clang( $trav_options['acc_booking_confirmation_page'] );
		} else {
			// thank you page is not set
		}

		$html = '';
		foreach ( $booking_list as $booking_data ) {
			$class = '';
			$label = 'UPCOMMING';
			if ( $booking_data->status == 0 ) { $class = ' cancelled'; $label = 'CANCELLED'; }
			if ( $booking_data->status == 2 ) { $class = ' completed'; $label = 'COMPLETED'; }
			if ( ( $booking_data->status == 1 ) && ( trav_strtotime( $booking_data->date_from ) < trav_strtotime(date('Y-m-d')) ) ) { $class = ' completed'; $label = 'COMPLETED'; }
			$html .= '<div class="booking-info clearfix' . $class . '">';
			$html .= '<div class="date">
							<label class="month">' . date( 'M', trav_strtotime( $booking_data->date_from ) ) . '</label>
							<label class="date">' . date( 'd', trav_strtotime( $booking_data->date_from ) ) . '</label>
							<label class="day">' . date( 'D', trav_strtotime( $booking_data->date_from ) ) . '</label>
						</div>';
			$url = empty($acc_book_conf_url)?'':( add_query_arg( array( 'booking_no' => $booking_data->booking_no, 'pin_code' => $booking_data->pin_code ), $acc_book_conf_url ) );
			$html .= '<h4 class="box-title"><i class="icon soap-icon-hotel blue-color circle"></i><a href="' . esc_url( $url ) . '">' . get_the_title( trav_acc_clang_id( $booking_data->accommodation_id ) ) . '</a><small>' . $booking_data->rooms . __( 'rooms', 'trav' ) . ' ' . $booking_data->adults . __( 'adults', 'trav' ) .  '</small></h4>';
			$html .= '<button class="btn-mini status">' . __( $label, 'trav' ) . '</button>';
			$html .= '<dl class="info">';
			$html .= '<dt>' . __( 'booked on', 'trav') . '</dt>';
			$html .= '<dd>' . date( 'l, M, j, Y', trav_strtotime( $booking_data->created ) ) . '</dd>';
			$html .= '</dl>';
			$html .= '<dl class="info">';
			$html .= '<dt>' . __( 'BOOKING NO', 'trav' ) . '</dt><dd>' . $booking_data->booking_no . '</dd>';
			$html .= '<dt>' . __( 'PIN CODE', 'trav' ) . '</dt><dd>' . $booking_data->pin_code . '</dd>';
			$html .= '</dl>';
			$html .= '</div>';
		}
		return $html;
	}
}

/*
 * send booking confirmation email function
 */
if ( ! function_exists( 'trav_acc_send_confirmation_email' ) ) {
	function trav_acc_send_confirmation_email( $booking_no, $booking_pincode, $type='new', $subject='', $description='' ) {
		global $wpdb, $logo_url, $trav_options;
		$booking_data = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . TRAV_ACCOMMODATION_BOOKINGS_TABLE . ' WHERE booking_no=%d AND pin_code=%d', $booking_no, $booking_pincode ), ARRAY_A );
		if ( ! empty( $booking_data ) ) {

			// server variables
			$admin_email = get_option('admin_email');
			$home_url = esc_url( home_url() );
			$site_name = $_SERVER['SERVER_NAME'];
			$logo_url = esc_url( $logo_url );
			$acc_book_conf_url = '';
			if ( isset( $trav_options['acc_booking_confirmation_page'] ) && ! empty( $trav_options['acc_booking_confirmation_page'] ) ) {
				$acc_book_conf_url = trav_get_permalink_clang( $trav_options['acc_booking_confirmation_page'] );
			}
			$booking_data['accommodation_id'] = trav_acc_clang_id( $booking_data['accommodation_id'] );
			$booking_data['room_type_id'] = trav_room_clang_id( $booking_data['room_type_id'] );

			// accommodation info
			$accommodation_name = get_the_title( $booking_data['accommodation_id'] );
			$accommodation_url = esc_url( trav_get_permalink_clang( $booking_data['accommodation_id'] ) );
			$accommodation_thumbnail = get_the_post_thumbnail( $booking_data['accommodation_id'], 'list-thumb' );
			$accommodation_address = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_address', true );
			$accommodation_city = trav_acc_get_city($booking_data['accommodation_id']);
			$accommodation_country = trav_acc_get_country($booking_data['accommodation_id']);
			$accommodation_phone = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_phone', true );
			$accommodation_email = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_email', true );
			$accommodation_room_name = esc_html( get_the_title( $booking_data['room_type_id'] ) );
			if ( empty( $accommodation_address ) ) {
				$accommodation_address = $accommodation_city . ' ' . $accommodation_country;
			}

			$check_in = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_check_in', true );
			$check_out = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_check_out', true );
			$check_in_time = empty( $check_in )?$booking_data['date_from']:( $booking_data['date_from'] . ' ' . $check_in );
			$check_out_time = empty( $check_out )?$booking_data['date_to']:( $booking_data['date_to'] . ' ' . $check_out );

			// booking info
			$booking_no = $booking_data['booking_no'];
			$booking_pincode = $booking_data['pin_code'];
			$date_from = new DateTime( $booking_data['date_from'] );
			$date_to = new DateTime( $booking_data['date_to'] );
			$number1 = $date_from->format('U');
			$number2 = $date_to->format('U');
			$booking_nights = ($number2 - $number1)/(3600*24);
			$booking_checkin_time = date( 'l, F, j, Y', trav_strtotime($booking_data['date_from']) );
			$booking_checkout_time = date( 'l, F, j, Y', trav_strtotime($booking_data['date_to']) );
			$booking_rooms = $booking_data['rooms'];
			$booking_adults = $booking_data['adults'];
			$booking_kids = $booking_data['kids'];
			$booking_room_price = esc_html( trav_get_price_field( $booking_data['room_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) );
			$booking_tax = esc_html( trav_get_price_field( $booking_data['tax'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) );
			$booking_total_price = esc_html( trav_get_price_field( $booking_data['total_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) );
			$booking_deposit_price = esc_html( $booking_data['deposit_price'] . $booking_data['currency_code'] );
			$booking_deposit_paid = esc_html( empty( $booking_data['deposit_paid'] ) ? 'No' : 'Yes' );
			$booking_update_url = esc_url( add_query_arg( array( 'booking_no'=>$booking_data['booking_no'], 'pin_code'=>$booking_data['pin_code'] ), $acc_book_conf_url ) );

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
								'accommodation_name',
								'accommodation_url',
								'accommodation_thumbnail',
								'accommodation_country',
								'accommodation_city',
								'accommodation_address',
								'accommodation_phone',
								'accommodation_email',
								'accommodation_room_name',
								'booking_no',
								'booking_pincode',
								'booking_nights',
								'booking_checkin_time',
								'booking_checkout_time',
								'booking_rooms',
								'booking_adults',
								'booking_kids',
								'booking_room_price',
								'booking_tax',
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
					$subject = empty( $trav_options['acc_confirm_email_subject'] ) ? 'Booking Confirmation Email Subject' : $trav_options['acc_confirm_email_subject'];
				} elseif ( $type == 'update' ) {
					$subject = empty( $trav_options['acc_update_email_subject'] ) ? 'Booking Updated Email Subject' : $trav_options['acc_update_email_subject'];
				} elseif ( $type == 'cancel' ) {
					$subject = empty( $trav_options['acc_cancel_email_subject'] ) ? 'Booking Canceled Email Subject' : $trav_options['acc_cancel_email_subject'];
				}
			}

			if ( empty( $description ) ) {
				if ( $type == 'new' ) {
					$description = empty( $trav_options['acc_confirm_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['acc_confirm_email_description'];
				} elseif ( $type == 'update' ) {
					$description = empty( $trav_options['acc_update_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['acc_update_email_description'];
				} elseif ( $type == 'cancel' ) {
					$description = empty( $trav_options['acc_cancel_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['acc_cancel_email_description'];
				}
			}

			foreach ( $variables as $variable ) {
				$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
				$description = str_replace( "[" . $variable . "]", $$variable, $description );
			}

			if ( ! empty( $trav_options['acc_confirm_email_ical'] ) && ( $type == 'new' ) ) {
				$mail_sent = trav_send_ical_event( $site_name, $admin_email, $customer_first_name . ' ' . $customer_last_name, $customer_email, $check_in_time, $check_out_time, $subject, $description, $accommodation_address);
			} else {
				$mail_sent = trav_send_mail( $site_name, $admin_email, $customer_email, $subject, $description );
			}

			/* mailing function to business owner */
			$bowner_address = '';
			if ( ! empty( $trav_options['acc_booked_notify_bowner'] ) ) {

				if ( $type == 'new' ) {
					$subject = empty( $trav_options['acc_bowner_email_subject'] ) ? 'You received a booking' : $trav_options['acc_bowner_email_subject'];
					$description = empty( $trav_options['acc_bowner_email_description'] ) ? 'Booking Details' : $trav_options['acc_bowner_email_description'];
				} elseif ( $type == 'update' ) {
					$subject = empty( $trav_options['acc_update_bowner_email_subject'] ) ? 'A booking is updated' : $trav_options['acc_update_bowner_email_subject'];
					$description = empty( $trav_options['acc_update_bowner_email_description'] ) ? 'Booking Details' : $trav_options['acc_update_bowner_email_description'];
				} elseif ( $type == 'cancel' ) {
					$subject = empty( $trav_options['acc_cancel_bowner_email_subject'] ) ? 'A booking is canceled' : $trav_options['acc_cancel_bowner_email_subject'];
					$description = empty( $trav_options['acc_cancel_bowner_email_description'] ) ? 'Booking Details' : $trav_options['acc_cancel_bowner_email_description'];
				}

				foreach ( $variables as $variable ) {
					$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
					$description = str_replace( "[" . $variable . "]", $$variable, $description );
				}

				if ( ! empty( $accommodation_email ) ) {
					$bowner_address = $accommodation_email;
				} else {
					$post_author_id = get_post_field( 'post_author', $booking_data['accommodation_id'] );
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
			if ( ! empty( $trav_options['acc_booked_notify_admin'] ) ) {
				if ( $bowner_address != $admin_email ) {
					if ( $type == 'new' ) {
						$subject = empty( $trav_options['acc_admin_email_subject'] ) ? 'You received a booking' : $trav_options['acc_admin_email_subject'];
						$description = empty( $trav_options['acc_admin_email_description'] ) ? 'Booking Details' : $trav_options['acc_admin_email_description'];
					} elseif ( $type == 'update' ) {
						$subject = empty( $trav_options['acc_update_admin_email_subject'] ) ? 'A booking is updated' : $trav_options['acc_update_admin_email_subject'];
						$description = empty( $trav_options['acc_update_admin_email_description'] ) ? 'Booking Details' : $trav_options['acc_update_admin_email_description'];
					} elseif ( $type == 'cancel' ) {
						$subject = empty( $trav_options['acc_cancel_admin_email_subject'] ) ? 'A booking is canceled' : $trav_options['acc_cancel_admin_email_subject'];
						$description = empty( $trav_options['acc_cancel_admin_email_description'] ) ? 'Booking Details' : $trav_options['acc_cancel_admin_email_description'];
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
 * send confirmation email
 */
if ( ! function_exists( 'trav_acc_conf_send_mail' ) ) {
	function trav_acc_conf_send_mail( $booking_data ) {
		global $wpdb;
		$mail_sent = 0;
		if ( trav_acc_send_confirmation_email( $booking_data['booking_no'], $booking_data['pin_code'], 'new' ) ) {
			$mail_sent = 1;
			$wpdb->update( TRAV_ACCOMMODATION_BOOKINGS_TABLE, array( 'mail_sent' => $mail_sent ), array( 'booking_no' => $booking_data['booking_no'], 'pin_code' => $booking_data['pin_code'] ), array( '%d' ), array( '%d','%d' ) );
		}
	}
}

/*
 * echo deposit payment not paid notice on confirmation page
 */
if ( ! function_exists( 'trav_acc_deposit_payment_not_paid' ) ) {
	function trav_acc_deposit_payment_not_paid( $booking_data ) {
		echo '<div class="alert alert-notice">' . __( 'Deposit payment is not paid.', 'trav' ) . '<span class="close"></span></div>';
	}
}

/*
 * get booking default values
 */
if ( ! function_exists( 'trav_acc_default_booking_data' ) ) {
	function trav_acc_default_booking_data( $type='new' ) {
		$default_booking_data = array(  'first_name'        => '',
										'last_name'         => '',
										'email'             => '',
										'country_code'      => '',
										'phone'             => '',
										'address'           => '',
										'city'              => '',
										'zip'               => '',
										'country'           => '',
										'special_requirements' => '',
										'accommodation_id'  => '',
										'room_type_id'      => '',
										'rooms'             => '',
										'adults'            => '',
										'kids'              => '',
										'child_ages'        => '',
										'room_price'        => '',
										'tax'               => '',
										'total_price'       => '',
										'currency_code'     => '',
										'exchange_rate'     => 1,
										'deposit_price'     => 0,
										'deposit_paid'      => 1,
										'date_from'         => '',
										'date_to'           => '',
										'booking_no'        => '',
										'pin_code'          => '',
										'status'            => 1,
										'updated'           => date( 'Y-m-d H:i:s' ),
									);
		if ( $type == 'new' ) {
			$a = array( 'user_id' => '',
						'created' => date( 'Y-m-d H:i:s' ),
						'mail_sent' => '',
						'other' => '',
						'id' => '' );
			$default_booking_data = array_merge( $default_booking_data, $a );
		}

		return $default_booking_data;
	}
}