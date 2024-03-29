<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * functions to manage booking
 */
if ( ! class_exists( 'Trav_Acc_Booking_List_Table') ) :
class Trav_Acc_Booking_List_Table extends WP_List_Table {

	function __construct() {
		global $status, $page;
		parent::__construct( array(
			'singular'  => 'booking',     //singular name of the listed records
			'plural'    => 'bookings',    //plural name of the listed records
			'ajax'      => false        //does this table support ajax?
		) );
	}

	function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'id':
			case 'date_from':
			case 'date_to':
			case 'rooms':
			case 'created':
			case 'total_price':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	function column_customer_name( $item ) {
		//Build row actions
		$link_pattern = '<a href="edit.php?post_type=%1s&page=%2$s&action=%3$s&booking_id=%4$s">%5$s</a>';
		$actions = array(
			'edit'      => sprintf( $link_pattern, sanitize_text_field( $_REQUEST['post_type'] ), 'bookings', 'edit', $item['id'], 'Edit' ),
			'delete'    => sprintf( $link_pattern, sanitize_text_field( $_REQUEST['post_type'] ), 'bookings', 'delete', $item['id'] . '&_wpnonce=' . wp_create_nonce( 'booking_delete' ) , 'Delete' )
		);
		$content = sprintf( $link_pattern, sanitize_text_field( $_REQUEST['post_type'] ), 'bookings', 'edit', $item['id'], esc_html( $item['first_name'] . ' ' . $item['last_name'] ) );
		//Return the title contents
		return sprintf( '%1$s %2$s', $content , $this->row_actions( $actions ) );
	}

	function column_accommodation_name( $item ) {
		return '<a href="' . get_edit_post_link( $item['acc_id'] ) . '">' . $item['accommodation_name'] . '</a>';
	}

	function column_room_type_name( $item ) {
		return '<a href="' . get_edit_post_link( $item['room_type_id'] ) . '">' . $item['room_type_name'] . '</a>';
	}

	function column_status( $item ) {
		switch( $item['status'] ) {
			/*case -1:
				return __( 'Pending', 'trav' );*/
			case 0:
				return __( 'Cancelled', 'trav' );
			case 1:
				return __( 'Upcoming', 'trav' );
			case 2:
				return __( 'Completed', 'trav' );
			}
		return $item['status'];
	}

	function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item['id'] );
	}

	function get_columns() {
		$columns = array(
			'cb'                => '<input type="checkbox" />', //Render a checkbox instead of text
			'id'                => __( 'ID', 'trav' ),
			'customer_name'     => __( 'Customer Name', 'trav' ),
			'date_from'         => __( 'Date From', 'trav' ),
			'date_to'           => __( 'Date To', 'trav' ),
			'accommodation_name'=> __( 'Accommodation Name', 'trav' ),
			'room_type_name'    => __( 'Room Type', 'trav' ),
			'rooms'             => __( 'Rooms', 'trav' ),
			'total_price'       => __( 'Price', 'trav' ),
			'created'           => __( 'Created Date', 'trav' ),
			'status'            => __( 'Status', 'trav' ),
		);
		return $columns;
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'id'           => array( 'id', false ),
			'date_from'    => array( 'date_from', false ),
			'date_to'      => array( 'date_to', false ),
			'accommodation_name'    => array( 'accommodation_name', false ),
			'room_type_name'        => array( 'room_type_name', false ),
			'status'       => array( 'status', false ),
		);
		return $sortable_columns;
	}

	function get_bulk_actions() {
		$actions = array(
			'bulk_delete'    => 'Delete'
		);
		return $actions;
	}

	function process_bulk_action() {
		global $wpdb;
		//Detect when a bulk action is being triggered...

		if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

			$nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
			$action = 'bulk-' . $this->_args['plural'];

			if ( ! wp_verify_nonce( $nonce, $action ) )
				wp_die( 'Sorry, your nonce did not verify' );
		}
		if ( 'bulk_delete'===$this->current_action() ) {
			$selected_ids = $_GET[ $this->_args['singular'] ];

			$how_many = count($selected_ids);
			$placeholders = array_fill(0, $how_many, '%d');
			$format = implode(', ', $placeholders);
			$current_user_id = get_current_user_id();
			$post_table_name  = esc_sql( $wpdb->prefix . 'posts' );
			$sql = '';

			if ( current_user_can( 'manage_options' ) ) {
				$sql = sprintf('DELETE FROM %1$s WHERE id IN (%2$s)', TRAV_ACCOMMODATION_BOOKINGS_TABLE, "$format" );
			} else {
				$sql = sprintf('DELETE %1$s FROM %1$s INNER JOIN %2$s as accommodation ON accommodation_id=accommodation.ID WHERE %1$s.id IN (%3$s) AND accommodation.post_author = %4$d', TRAV_ACCOMMODATION_BOOKINGS_TABLE, $post_table_name, "$format", $current_user_id );
			}

			$wpdb->query( $wpdb->prepare( $sql, $selected_ids ) );
			wp_redirect( admin_url( 'edit.php?post_type=accommodation&page=bookings&bulk_delete=true') );
		}
	}

	function prepare_items() {
		global $wpdb;
		$per_page = 10;
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->process_bulk_action();
		
		$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? sanitize_sql_orderby( $_REQUEST['orderby'] ) : 'id'; //If no sort, default to title
		$order = ( ! empty( $_REQUEST['order'] ) ) ? sanitize_text_field( $_REQUEST['order'] ) : 'desc'; //If no order, default to desc
		$current_page = $this->get_pagenum();
		$post_table_name  = esc_sql( $wpdb->prefix . 'posts' );

		$where = "1=1";
		if ( ! empty( $_REQUEST['accommodation_id'] ) ) $where .= " AND Trav_Bookings.accommodation_id = '" . esc_sql( trav_acc_org_id( $_REQUEST['accommodation_id'] ) ) . "'";
		if ( ! empty( $_REQUEST['room_type_id'] ) ) $where .= " AND Trav_Bookings.room_type_id = '" . esc_sql( trav_room_org_id( $_REQUEST['room_type_id'] ) ) . "'";
		if ( ! empty( $_REQUEST['date_from'] ) ) $where .= " AND Trav_Bookings.date_from = '" . esc_sql( $_REQUEST['date_from'] ) . "'";
		if ( ! empty( $_REQUEST['date_to'] ) ) $where .= " AND Trav_Bookings.date_to = '" . esc_sql( $_REQUEST['date_to'] ) . "'";
		if ( ! empty( $_REQUEST['booking_no'] ) ) $where .= " AND Trav_Bookings.booking_no = '" . esc_sql( $_REQUEST['booking_no'] ) . "'";
		if ( isset( $_REQUEST['status'] ) ) $where .= " AND Trav_Bookings.status = '" . esc_sql( $_REQUEST['status'] ) . "'";
		if ( ! current_user_can( 'manage_options' ) ) { $where .= " AND accommodation.post_author = '" . get_current_user_id() . "' "; }

		$sql = $wpdb->prepare( 'SELECT Trav_Bookings.*, accommodation.ID as acc_id, accommodation.post_title as accommodation_name, room_type.ID as room_type_id, room_type.post_title as room_type_name FROM %1$s as Trav_Bookings
						INNER JOIN %2$s as accommodation ON Trav_Bookings.accommodation_id=accommodation.ID
						INNER JOIN %2$s as room_type ON Trav_Bookings.room_type_id=room_type.ID
						WHERE ' . $where . ' ORDER BY %3$s %4$s
						LIMIT %5$s, %6$s' , TRAV_ACCOMMODATION_BOOKINGS_TABLE, $post_table_name, $orderby, $order, $per_page * ( $current_page - 1 ), $per_page );
		$data = $wpdb->get_results( $sql, ARRAY_A );

		$sql = sprintf( 'SELECT COUNT(*) FROM %1$s as Trav_Bookings INNER JOIN %2$s as accommodation ON Trav_Bookings.accommodation_id=accommodation.ID WHERE %3$s' , TRAV_ACCOMMODATION_BOOKINGS_TABLE, $post_table_name, $where );
		$total_items = $wpdb->get_var( $sql );

		$this->items = $data;
		$this->set_pagination_args( array(
			'total_items' => $total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
			'total_pages' => ceil( $total_items/$per_page )   //WE have to calculate the total number of pages
		) );
	}
}
endif;

/*
 * add booking list page to menu
 */
if ( ! function_exists( 'trav_acc_booking_add_menu_items' ) ) {
	function trav_acc_booking_add_menu_items() {
		//add accommodation bookings list page
		$page = add_submenu_page( 'edit.php?post_type=accommodation', 'Accommodation Bookings', 'Bookings', 'edit_accommodations', 'bookings', 'trav_acc_booking_render_pages' );
		add_action( 'admin_print_scripts-' . $page, 'trav_acc_booking_admin_enqueue_scripts' );
	}
}

/*
 * booking admin main actions
 */
if ( ! function_exists( 'trav_acc_booking_render_pages' ) ) {
	function trav_acc_booking_render_pages() {
		if ( ( ! empty( $_REQUEST['action'] ) ) && ( ( 'add' == $_REQUEST['action'] ) || ( 'edit' == $_REQUEST['action'] ) ) ) {
			trav_acc_booking_render_manage_page();
		} elseif ( ( ! empty( $_REQUEST['action'] ) ) && ( 'delete' == $_REQUEST['action'] ) ) {
			trav_acc_booking_delete_action();
		} else {
			trav_acc_booking_render_list_page();
		}
	}
}

/*
 * render booking list page
 */
if ( ! function_exists( 'trav_acc_booking_render_list_page' ) ) {
	function trav_acc_booking_render_list_page() {
		global $wpdb;
		$travBookingTable = new Trav_Acc_Booking_List_Table();
		$travBookingTable->prepare_items();
		
		?>

		<div class="wrap">

			<h2>Accommodation Bookings <a href="edit.php?post_type=accommodation&amp;page=bookings&amp;action=add" class="add-new-h2">Add New</a></h2>
			<?php if ( isset( $_REQUEST['bulk_delete'] ) ) echo '<div id="message" class="updated below-h2"><p>Bookings deleted</p></div>'?>
			<select id="accommodation_filter">
				<option></option>
				<?php
				$args = array(
						'post_type'         => 'accommodation',
						'posts_per_page'    => -1,
						'orderby'           => 'title',
						'order'             => 'ASC'
				);
				if ( ! current_user_can( 'manage_options' ) ) {
					$args['author'] = get_current_user_id();
				}
				$accommodation_query = new WP_Query( $args );

				if ( $accommodation_query->have_posts() ) {
					while ( $accommodation_query->have_posts() ) {
						$accommodation_query->the_post();
						$selected = '';
						$id = $accommodation_query->post->ID;
						if ( ! empty( $_REQUEST['accommodation_id'] ) && ( $_REQUEST['accommodation_id'] == $id ) ) $selected = ' selected ';
						echo '<option ' . esc_attr( $selected ) . 'value="' . esc_attr( $id ) .'">' . wp_kses_post( get_the_title( $id ) ) . '</option>';
					}
				} else {
					// no posts found
				}
				/* Restore original Post Data */
				wp_reset_postdata();
				?>
			</select>
			<select id="room_type_filter">
				<option></option>
				<?php
					$args = array(
							'post_type'         => 'room_type',
							'posts_per_page'    => -1,
							'orderby'           => 'title',
							'order'             => 'ASC'
					);
					if ( ! current_user_can( 'manage_options' ) ) {
						$args['author'] = get_current_user_id();
					}
					if ( ! empty( $_REQUEST['accommodation_id'] ) ) {
						$args['meta_query'] = array(
								array(
									'key'     => 'trav_room_accommodation',
									'value'   => sanitize_text_field( $_REQUEST['accommodation_id'] ),
								),
							);
					}
					$room_type_query = new WP_Query( $args );

					if ( $room_type_query->have_posts() ) {
						while ( $room_type_query->have_posts() ) {
							$room_type_query->the_post();
							$selected = '';
							$id = $room_type_query->post->ID;
							if ( ! empty( $_REQUEST['room_type_id'] ) && ( $_REQUEST['room_type_id'] == $id ) ) $selected = ' selected ';
							echo '<option ' . esc_attr( $selected ) . 'value="' . esc_attr( $id ) .'">' . wp_kses_post( get_the_title( $id ) ) . '</option>';
						}
					} else {
						// no posts found
					}
					/* Restore original Post Data */
					wp_reset_postdata();
				?>
			</select>
			<input type="text" id="date_from_filter" name="date_from" placeholder="Filter by Date from" value="<?php if ( ! empty( $_REQUEST['date_from'] ) ) echo esc_attr( $_REQUEST['date_from'] ); ?>">
			<input type="text" id="date_to_filter" name="date_to" placeholder="Filter by Date to" value="<?php if ( ! empty( $_REQUEST['date_to'] ) ) echo esc_attr( $_REQUEST['date_to'] ); ?>">
			<input type="text" id="booking_no_filter" name="booking_no" placeholder="Filter by Booking No" value="<?php if ( ! empty( $_REQUEST['booking_no'] ) ) echo esc_attr( $_REQUEST['booking_no'] ); ?>">
			<select name="status" id="status_filter">
				<option value="">select a status</option>
				<?php
					$statuses = array( '0' => 'cancelled', '1' => 'upcomming', '2' => 'completed', /*'-1' => 'pending'*/ );
					foreach( $statuses as $key=>$status ) { ?>
						<option value="<?php echo esc_attr( $key ) ?>" <?php selected( $key, isset( $_REQUEST['status'] ) ? esc_attr( $_REQUEST['status'] ) : '' ); ?>><?php echo esc_attr( $status ) ?></option>
				<?php } ?>
			</select>
			<input type="button" name="booking_filter" id="booking-filter" class="button" value="Filter">
			<a href="edit.php?post_type=accommodation&amp;page=bookings" class="button-secondary">Show All</a>
			<form id="accomo-bookings-filter" method="get">
				<input type="hidden" name="post_type" value="<?php echo esc_attr( $_REQUEST['post_type'] ) ?>" />
				<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ) ?>" />
				<?php $travBookingTable->display() ?>
			</form>
			
		</div>
		<style>#date_from_filter, #date_to_filter {width:150px;}</style>
		<?php
	}
}

/*
 * render booking detail page
 */
if ( ! function_exists( 'trav_acc_booking_render_manage_page' ) ) {
	function trav_acc_booking_render_manage_page() {
		global $wpdb, $trav_options;
		if ( ! empty( $_POST['save'] ) ) {
			trav_acc_booking_save_action();
			return;
		}

		$booking_data = array();

		if ( 'edit' == $_REQUEST['action'] ) {

			if ( empty( $_REQUEST['booking_id'] ) ) {
				echo "<h2>You attempted to edit an item that doesn't exist. Perhaps it was deleted?</h2>";
				return;
			}

			$booking_id = $_REQUEST['booking_id'];
			$post_table_name = $wpdb->prefix . 'posts';

			$where = 'Trav_Bookings.id = %3$d';
			if ( ! current_user_can( 'manage_options' ) ) { $where .= " AND accommodation.post_author = '" . get_current_user_id() . "' "; }

			$sql = $wpdb->prepare( 'SELECT Trav_Bookings.* , accommodation.post_title as accommodation_name, room_type.post_title as room_type_name FROM %1$s as Trav_Bookings
							INNER JOIN %2$s as accommodation ON Trav_Bookings.accommodation_id=accommodation.ID
							INNER JOIN %2$s as room_type ON Trav_Bookings.room_type_id=room_type.ID
							WHERE ' . $where , TRAV_ACCOMMODATION_BOOKINGS_TABLE, $post_table_name, $booking_id );

			$booking_data = $wpdb->get_row( $sql, ARRAY_A );

			if ( empty( $booking_data ) ) {
				echo "<h2>You attempted to edit an item that doesn't exist. Perhaps it was deleted?</h2>";
				return;
			}
		}

		$default_booking_data = trav_acc_default_booking_data();
		$booking_data = array_replace( $default_booking_data , $booking_data );
		$site_currency_symbol = trav_get_site_currency_symbol();
		$user_currency_symbol = trav_get_currency_symbol( $booking_data['currency_code'] );
		if ( empty( $user_currency_symbol ) ) $user_currency_symbol = $site_currency_symbol;
		?>

		<div class="wrap">
			<?php $page_title = ( 'edit' == $_REQUEST['action'] ) ? 'Edit Accommodation Booking<a href="edit.php?post_type=accommodation&amp;page=bookings&amp;action=add" class="add-new-h2">Add New</a>' : 'Add New Accommodation booking'; ?>
			<h2><?php echo wp_kses_post( $page_title ); ?></h2>
			<?php if ( isset( $_REQUEST['updated'] ) ) echo '<div id="message" class="updated below-h2"><p>Booking saved</p></div>'?>
			<form method="post" onsubmit="return manage_booking_validateForm();">
				<input type="hidden" name="id" value="<?php echo esc_attr( $booking_data['id'] ); ?>">
				<div class="one-half">
					<h3>Booking Detail</h3>
					<table class="trav_admin_table trav_booking_manage_table">
						<tr>
							<th>Accommodation</th>
							<td>
								<select name="accommodation_id" id="accommodation_id">
									<option></option>
									<?php
										$args = array(
												'post_type'         => 'accommodation',
												'posts_per_page'    => -1,
												'orderby'           => 'title',
												'order'             => 'ASC'
										);
										if ( ! current_user_can( 'manage_options' ) ) {
											$args['author'] = get_current_user_id();
										}
										$accommodation_query = new WP_Query( $args );

										if ( $accommodation_query->have_posts() ) {
											while ( $accommodation_query->have_posts() ) {
												$accommodation_query->the_post();
												$selected = '';
												$id = $accommodation_query->post->ID;
												if ( $booking_data['accommodation_id'] == $id ) $selected = ' selected ';
												echo '<option ' . esc_attr( $selected ) . 'value="' . esc_attr( $id ) .'">' . wp_kses_post( get_the_title( $id ) ) . '</option>';
											}
										}
										wp_reset_postdata();
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th>Room Type</th>
							<td>
								<select name="room_type_id" id="room_type_id">
									<option></option>
									<?php
										$args = array(
												'post_type'         => 'room_type',
												'posts_per_page'    => -1,
												'orderby'           => 'title',
												'order'             => 'ASC'
										);
										if ( ! empty( $booking_data['accommodation_id'] ) ) {
											$args['meta_query'] = array(
													array(
														'key'     => 'trav_room_accommodation',
														'value'   => $booking_data['accommodation_id']
													),
												);
										}
										if ( ! current_user_can( 'manage_options' ) ) {
											$args['author'] = get_current_user_id();
										}
										$room_type_query = new WP_Query( $args );

										if ( $room_type_query->have_posts() ) {
											while ( $room_type_query->have_posts() ) {
												$room_type_query->the_post();
												$selected = '';
												$id = $room_type_query->post->ID;
												if ( $booking_data['room_type_id'] == $id ) $selected = ' selected ';
												echo '<option ' . esc_attr( $selected ) . 'value="' . esc_attr( $id ) .'">' . wp_kses_post( get_the_title( $id ) ) . '</option>';
											}
										}
										wp_reset_postdata();
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th>Number of Rooms</th>
							<td><input type="number" name="rooms" min="1" value="<?php echo esc_attr( $booking_data['rooms'] ) ?>"></td>
						</tr>
						<tr>
							<th>Date From</th>
							<td><input type="text" name="date_from" id="date_from" value="<?php echo esc_attr( $booking_data['date_from'] ) ?>"></td>
						</tr>
						<tr>
							<th>Date To</th>
							<td><input type="text" name="date_to" id="date_to" value="<?php if ( $booking_data['date_to'] != '9999-12-31' ) echo esc_attr( $booking_data['date_to'] ) ?>"></td>
						</tr>
						<tr>
							<th>Adults</th>
							<td><input type="number" name="adults" value="<?php echo esc_attr( $booking_data['adults'] ) ?>"></td>
						</tr>
						<tr>
							<th>Children</th>
							<td><input type="number" name="kids" value="<?php echo esc_attr( $booking_data['kids'] ) ?>"></td>
						</tr>
						<tr class="child_ages">
							<th>Child Ages</th>
							<td>
								<table>
									<?php if ( $booking_data['kids'] > 0 ) { ?>
										<tr class="clone-field">
											<td>
												<?php
												$child_ages = array();
												if ( ! empty( $booking_data['child_ages'] ) ) { $child_ages = unserialize( $booking_data['child_ages'] ); }
												for ( $i = 0; $i < $booking_data['kids']; $i++ ) { ?>
													<input type="number" name="child_ages[]" value="<?php echo esc_attr( isset( $child_ages[$i] ) ? $child_ages[$i] : '' ); ?>"><a href="#" class="button remove-clone">-</a>
												<?php } ?>
											</td>
										</tr>
									<?php } else { ?>
										<tr class="clone-field">
											<td><input type="text" name="child_ages[0]"><a href="#" class="button remove-clone">-</a></td>
										</tr>
									<?php } ?>
								</table>
								<a href="#" class="button-primary add-clone">+</a>
							</td>
						</tr>
						<tr>
							<th>Room Price</th>
							<td><input type="text" name="room_price" value="<?php echo esc_attr( $booking_data['room_price'] ) ?>"> <?php echo esc_html( $site_currency_symbol ) ?></td>
						</tr>
						<tr>
							<th>Tax</th>
							<td><input type="text" name="tax" value="<?php echo esc_attr( $booking_data['tax'] ) ?>"> <?php echo esc_html( $site_currency_symbol ) ?></td>
						</tr>
						<tr>
							<th>Total Price</th>
							<td><input type="text" name="total_price" value="<?php echo esc_attr( $booking_data['total_price'] ) ?>"> <?php echo esc_html( $site_currency_symbol ) ?></td>
						</tr>
						<?php if ( ! empty( $trav_options['site_currencies'] ) && count( array_filter( $trav_options['site_currencies'] ) ) > 1 ) { ?>
							<tr>
								<th>User Currency</th>
								<td>
									<select name="currency_code">
										<?php foreach ( array_filter( $trav_options['site_currencies'] ) as $key => $content) { ?>
											<option value="<?php echo esc_attr( $key ) ?>" <?php selected( $key, $booking_data['currency_code'] ); ?>><?php echo esc_html( $key ) ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th>Exchange Rate</th>
								<td><input type="text" name="exchange_rate" value="<?php echo esc_attr( $booking_data['exchange_rate'] ) ?>"></td>
							</tr>
							<tr>
								<th>Total Price in User Currency</th>
								<td><label> <?php if ( ! empty( $booking_data['total_price'] ) && ! empty( $booking_data['exchange_rate'] ) ) echo esc_attr( $booking_data['total_price'] * $booking_data['exchange_rate'] ) . esc_html( $user_currency_symbol ) ?></td>
							</tr>
						<?php } ?>
						<tr>
							<th>Deposit Amount</th>
							<td><input type="text" name="deposit_price" value="<?php echo esc_attr( $booking_data['deposit_price'] ) ?>"> <?php echo esc_html( $user_currency_symbol ) ?></td>
						</tr>
						<?php if ( 'add' == $_REQUEST['action'] || ( ! empty( $booking_data['deposit_price'] ) && ! ( $booking_data['deposit_price'] == 0 ) ) ) { ?>
							<tr>
								<th>Deposit Paid</th>
								<td>
									<select name="deposit_paid">
										<?php $deposit_paid = array( '1' => 'yes', '0' => 'no' ); ?>
										<?php foreach ( $deposit_paid as $key => $content) { ?>
											<option value="<?php echo esc_attr( $key ) ?>" <?php selected( $key, $booking_data['deposit_paid'] ); ?>><?php echo esc_html( $content ) ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<?php if ( ! empty( $booking_data['deposit_paid'] ) ) {
								$other_data = unserialize( $booking_data['other'] );
								if ( ! empty( $other_data['pp_transaction_id'] ) ) { ?>
								<tr>
									<th>Paypal Payment Transaction ID</th>
									<td><label><?php echo $other_data['pp_transaction_id'] ?></label></td>
								</tr>
							<?php } } ?>
						<?php } else { ?>
							<input type="hidden" name="deposit_paid" value="1">
						<?php } ?>
						<tr>
							<th>Status</th>
							<td>
								<select name="status">
									<?php $statuses = array( '0' => 'cancelled', '1' => 'upcomming', '2' => 'completed', /*'-1' => 'pending'*/ );
										if ( ! isset( $booking_data['status'] ) ) {
											$booking_data['status'] = 1;
										}
									?>
									<?php foreach ( $statuses as $key => $content) { ?>
										<option value="<?php echo esc_attr( $key ) ?>" <?php selected( $key, $booking_data['status'] ); ?>><?php echo esc_html( $content ) ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div class="one-half">
					<h3>Customer Infomation</h3>
					<table  class="trav_admin_table trav_booking_manage_table">
						<tr>
							<th>First Name</th>
							<td><input type="text" name="first_name" value="<?php echo esc_attr( $booking_data['first_name'] ) ?>"></td>
						</tr>
						<tr>
							<th>Last Name</th>
							<td><input type="text" name="last_name" value="<?php echo esc_attr( $booking_data['last_name'] ) ?>"></td>
						</tr>
						<tr>
							<th>Email</th>
							<td><input type="email" name="email" value="<?php echo esc_attr( $booking_data['email'] ) ?>"></td>
						</tr>
						<tr>
							<th>Country Code</th>
							<td><input type="text" name="country_code" value="<?php echo esc_attr( $booking_data['country_code'] ) ?>"></td>
						</tr>
						<tr>
							<th>Phone</th>
							<td><input type="text" name="phone" value="<?php echo esc_attr( $booking_data['phone'] ) ?>"></td>
						</tr>
						<tr>
							<th>Address</th>
							<td><input type="text" name="address" value="<?php echo esc_attr( $booking_data['address'] ) ?>"></td>
						</tr>
						<tr>
							<th>City</th>
							<td><input type="text" name="city" value="<?php echo esc_attr( $booking_data['city'] ) ?>"></td>
						</tr>
						<tr>
							<th>Zip</th>
							<td><input type="text" name="zip" value="<?php echo esc_attr( $booking_data['zip'] ) ?>"></td>
						</tr>
						<tr>
							<th>Country</th>
							<td><input type="text" name="country" value="<?php echo esc_attr( $booking_data['country'] ) ?>"></td>
						</tr>
						<tr>
							<th>Special Requirements</th>
							<td><textarea name="special_requirements"><?php echo esc_textarea( stripslashes( $booking_data['special_requirements'] ) ) ?></textarea></td>
						</tr>
						<tr>
							<th>Booking No</th>
							<td><input type="text" name="booking_no" value="<?php echo esc_attr( $booking_data['booking_no'] ) ?>"></td>
						</tr>
						<tr>
							<th>Pin Code</th>
							<td><input type="text" name="pin_code" value="<?php echo esc_attr( $booking_data['pin_code'] ) ?>"></td>
						</tr>
					</table>
				</div>
				<input type="submit" class="button-primary button_save_booking" name="save" value="Save booking">
				<a href="edit.php?post_type=accommodation&amp;page=bookings" class="button-secondary">Cancel</a>
				<?php wp_nonce_field('trav_manage_bookings','booking_save'); ?>
			</form>
		</div>
		<?php if ( ! empty( $trav_options['vld_credit_card'] ) && ! empty( $trav_options['cc_off_charge'] ) && ! empty( $booking_data['other'] ) ) {
			$cc_fields = array( 'cc_type' => 'CREDIT CARD TYPE', 'cc_holder_name' => 'CARD HOLDER NAME', 'cc_number'=>'CARD NUMBER', 'cc_cid'=>'CARD IDENTIFICATION NUMBER', 'cc_exp_year'=>'EXPIRATION YEAR', 'cc_exp_month'=>'EXPIRATION MONTH' );
			$cc_infos = unserialize( $booking_data['other'] );
			echo '<style>.cc_table{background:#fff;margin-top:30px;}.cc_table td{padding:10px;}.cc_table,.cc_table tr,.cc_table td{border:1px solid #000; border-collapse: collapse;}</style>';
			echo '<div style="clear:both"></div><h3>Credit Card Info</h3><table class="cc_table"><tbody>';
			foreach ($cc_fields as $key => $label) {
				if ( ! empty( $cc_infos[ $key ] ) ) {
					echo '<tr><td><label>' . $label . '</label></td><td>' . $cc_infos[ $key ] . '</td></tr>';
				}
			}
			echo '</tbody></table>';
		}
	}
}

/*
 * booking delete action
 */
if ( ! function_exists( 'trav_acc_booking_delete_action' ) ) {
	function trav_acc_booking_delete_action() {
		global $wpdb;
		// data validation
		if ( empty( $_REQUEST['booking_id'] ) ) {
			print 'Sorry, you tried to remove nothing.';
			exit;
		}

		// nonce check
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'booking_delete' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		}

		// check ownership if user is not admin
		if ( ! current_user_can( 'manage_options' ) ) {
			$sql = $wpdb->prepare( 'SELECT Trav_Bookings.accommodation_id FROM %1$s as Trav_Bookings WHERE Trav_Bookings.id = %2$d' , TRAV_ACCOMMODATION_BOOKINGS_TABLE, $_REQUEST['booking_id'] );
			$acc_id = $wpdb->get_var( $sql );
			$post_author_id = get_post_field( 'post_author', $acc_id );
			if ( get_current_user_id() != $post_author_id ) {
				print 'You don\'t have permission to remove other\'s item.';
				exit;
			}
		}

		// do action
		$wpdb->delete( TRAV_ACCOMMODATION_BOOKINGS_TABLE, array( 'id' => $_REQUEST['booking_id'] ) );
		wp_redirect( admin_url( 'edit.php?post_type=accommodation&page=bookings') );
		exit;
	}
}

/*
 * booking save action
 */
if ( ! function_exists( 'trav_acc_booking_save_action' ) ) {
	function trav_acc_booking_save_action() {
		if ( ! isset( $_POST['booking_save'] ) || ! wp_verify_nonce( $_POST['booking_save'], 'trav_manage_bookings' ) ) {
		   print 'Sorry, your nonce did not verify.';
		   exit;
		} else {
			global $wpdb;
			$default_booking_data = trav_acc_default_booking_data( 'update' );
			$data = array();
			foreach ( $default_booking_data as $table_field => $def_value ) {
				if ( ! empty( $_POST[ $table_field ] ) ) {
					$data[ $table_field ] = $_POST[ $table_field ];
					if ( ! is_array( $_POST[ $table_field ] ) ) {
						$data[ $table_field ] = sanitize_text_field( $data[ $table_field ] );
					} else {
						$data[ $table_field ] = serialize( $data[ $table_field ] );
					}
				}
			}

			$data = array_replace( $default_booking_data, $data );
			$data['accommodation_id'] = trav_acc_org_id( $data['accommodation_id'] );
			$data['room_type_id'] = trav_room_org_id( $data['room_type_id'] );
			if ( empty( $_POST['id'] ) ) {
				//insert
				$data['created'] = date( 'Y-m-d H:i:s' );
				$wpdb->insert( TRAV_ACCOMMODATION_BOOKINGS_TABLE, $data );
				$id = $wpdb->insert_id;
			} else {
				//update
				$wpdb->update( TRAV_ACCOMMODATION_BOOKINGS_TABLE, $data, array( 'id' => sanitize_text_field( $_POST['id'] ) ) );
				$id = sanitize_text_field( $_POST['id'] );
			}
			wp_redirect( admin_url( 'edit.php?post_type=accommodation&page=bookings&action=edit&booking_id=' . $id . '&updated=true') );
			exit;
		}
	}
}

/*
 * booking admin enqueue script action
 */
if ( ! function_exists( 'trav_acc_booking_admin_enqueue_scripts' ) ) {
	function trav_acc_booking_admin_enqueue_scripts() {

		// support select2
		wp_enqueue_style( 'select2', RWMB_URL . 'css/select2/select2.css', array(), '3.2' );
		wp_enqueue_script( 'select2', RWMB_URL . 'js/select2/select2.min.js', array(), '3.2', true );
		

		// datepicker
		$url = RWMB_URL . 'css/jqueryui';
		wp_register_style( 'jquery-ui-core', "{$url}/jquery.ui.core.css", array(), '1.8.17' );
		wp_register_style( 'jquery-ui-theme', "{$url}/jquery.ui.theme.css", array(), '1.8.17' );
		wp_enqueue_style( 'jquery-ui-datepicker', "{$url}/jquery.ui.datepicker.css", array( 'jquery-ui-core', 'jquery-ui-theme' ), '1.8.17' );

		// Load localized scripts
		$locale = str_replace( '_', '-', get_locale() );
		$file_path = 'jqueryui/datepicker-i18n/jquery.ui.datepicker-' . $locale . '.js';
		$deps = array( 'jquery-ui-datepicker' );
		if ( file_exists( RWMB_DIR . 'js/' . $file_path ) )
		{
			wp_register_script( 'jquery-ui-datepicker-i18n', RWMB_URL . 'js/' . $file_path, $deps, '1.8.17', true );
			$deps[] = 'jquery-ui-datepicker-i18n';
		}

		wp_enqueue_script( 'rwmb-date', RWMB_URL . 'js/' . 'date.js', $deps, RWMB_VER, true );
		wp_localize_script( 'rwmb-date', 'RWMB_Datepicker', array( 'lang' => $locale ) );

		// custom style and js
		wp_enqueue_style( 'trav_admin_acc_stype' , get_template_directory_uri() . '/inc/admin/css/style.css' ); 
		wp_enqueue_script( 'trav_admin_acc_script' , TRAV_TEMPLATE_DIRECTORY_URI . '/inc/admin/accommodation/js/script.js', array('jquery'), '1.0', true );
	}
}

add_action( 'admin_menu', 'trav_acc_booking_add_menu_items' );