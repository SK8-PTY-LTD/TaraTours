<?php
/**
 * Booking Confirmation Template
 */
global $trav_options, $wpdb, $logo_url, $search_max_rooms, $search_max_adults, $search_max_kids;
if ( ! isset( $_REQUEST['booking_no'] ) || ! isset( $_REQUEST['pin_code'] ) ) {
	do_action('trav_tour_conf_wrong_data');
	exit;
}

if ( ! $booking_data = $wpdb->get_row( 'SELECT * FROM ' . TRAV_TOUR_BOOKINGS_TABLE . ' WHERE booking_no="' . esc_sql( $_REQUEST['booking_no'] ) . '" AND pin_code="' . esc_sql( $_REQUEST['pin_code'] ) . '"', ARRAY_A ) ) {
	do_action('trav_tour_conf_wrong_data');
	exit;
}

$tour_id = trav_tour_clang_id( $booking_data['tour_id'] );
$st_id = $booking_data['st_id'];
$tour_meta = get_post_meta( $tour_id );
$deposit_rate = get_post_meta( $tour_id, 'trav_tour_security_deposit', true ); $deposit_rate = empty( $deposit_rate ) ? 0 : $deposit_rate;
$tour_date = trav_tophptime( $booking_data['tour_date'] );
$city = trav_tour_get_city( $tour_id );
$country = trav_tour_get_country( $tour_id );

// if deposit is required and it is not paid process payment
if ( ! empty( $deposit_rate ) && empty( $booking_data['deposit_paid'] ) ) {
	// init payment variables
	$ItemName = '';
	if ( $deposit_rate < 100 ) {
		$ItemName = sprintf( __( 'Deposit(%d%%) for ', 'trav' ), $deposit_rate );
	} else {
		$ItemName = __( 'Deposit for ', 'trav' );
	}
	$ItemName .= get_the_title( $tour_id ) . ' ' . trav_tour_get_schedule_type_title( $tour_id, $st_id );

	$payment_data = array();
	$payment_data['item_name'] = $ItemName;
	$payment_data['item_number'] = $tour_id . '-' . $st_id;
	$payment_data['item_desc'] = __( 'Tour Date', 'trav' ) . ' ' . $tour_date . ' ' . get_the_title( $tour_id ) . ' ' . trav_tour_get_schedule_type_title( $tour_id, $st_id );
	$payment_data['item_qty'] = 1;
	$payment_data['item_price'] = $booking_data['deposit_price'];
	$payment_data['item_total_price'] = $payment_data['item_qty'] * $payment_data['item_price'];
	$payment_data['grand_total'] = $payment_data['item_total_price'];
	$payment_data['currency'] = strtoupper( $booking_data['currency_code'] );
	$payment_data['return_url'] = trav_get_current_page_url() . '?booking_no=' . $booking_data['booking_no'] . '&pin_code=' . $booking_data['pin_code'] . '&payment=success';
	$payment_data['cancel_url'] = trav_get_current_page_url() . '?booking_no=' . $booking_data['booking_no'] . '&pin_code=' . $booking_data['pin_code'] . '&payment=failed';
	$payment_data['status'] = '';
	$payment_data['deposit_rate'] = $deposit_rate;

	if ( ! empty( $_REQUEST['transaction_id'] ) && ! empty( $_SESSION['booking_data'][$_REQUEST['transaction_id']] ) ) $payment_data['status'] = 'before';
	$payment_result = trav_process_payment( $payment_data );

	if ( $payment_result ) {
		if ( ! empty( $payment_result['success'] ) && ( $payment_result['method'] == 'paypal' ) ) {
			$other_booking_data = array();
			if ( ! empty( $booking_data['other'] ) ) {
				$other_booking_data = unserialize( $booking_data['other'] );
			}
			$other_booking_data['pp_transaction_id'] = $payment_result['transaction_id'];
			$booking_data['deposit_paid'] = 1;
			$result = $wpdb->update( TRAV_TOUR_BOOKINGS_TABLE, array( 'deposit_paid' => $booking_data['deposit_paid'], 'status' => 1, 'other' => serialize( $other_booking_data ) ), array( 'booking_no' => $booking_data['booking_no'], 'pin_code' => $booking_data['pin_code'] ) );
		}
	}
}

if ( ! empty( $deposit_rate ) && empty( $booking_data['deposit_paid'] ) ) {
	do_action('trav_tour_deposit_payment_not_paid', $booking_data ); // deposit payment not paid
}

if ( empty( $booking_data['mail_sent'] ) && ! empty( $booking_data['status'] ) && ( empty( $deposit_rate ) || ! empty( $booking_data['deposit_paid'] ) ) ) {
	do_action('trav_tour_conf_mail_not_sent', $booking_data); // mail is not sent
}

if ( ! empty( $_REQUEST['transaction_id'] ) && ! empty( $_SESSION['booking_data'] ) ) {
	unset( $_SESSION['booking_data'][$_REQUEST['transaction_id']] ); // unset session data for further action
}

$dt_dd = '<dt>%s:</dt><dd>%s</dd>';

if ( $booking_data['status'] == 1 ) { // if upcomming ?>

	<div class="row">
		<div class="col-sm-8 col-md-9">
			<div class="booking-information travelo-box">

				<?php do_action( 'trav_tour_conf_form_before', $booking_data ); ?>

				<?php if ( ( isset( $_REQUEST['payment'] ) && ( $_REQUEST['payment'] == 'success' ) ) || ( isset( $_REQUEST['message'] ) && ( $_REQUEST['message'] == 1 ) ) ): ?>
					<h2><?php _e( 'Booking Confirmation', 'trav' ); ?></h2>
					<hr />
					<div class="booking-confirmation clearfix">
						<i class="soap-icon-recommend icon circle"></i>
						<div class="message">
							<h4 class="main-message"><?php _e( 'Thank You. Your Booking Order is Confirmed Now.', 'trav' ); ?></h4>
							<p><?php _e( 'A confirmation email has been sent to your provided email address.', 'trav' ); ?></p>
						</div>
						<!-- <a href="#" class="button btn-small print-button uppercase">print Details</a> -->
					</div>
					<hr />
				<?php endif; ?>

				<h3><?php echo __( 'Check Your Details' , 'trav' ) ?></h3>
				<dl class="term-description">
					<?php
					$booking_detail = array('booking_no' => array( 'label' => __('Booking Number', 'trav'), 'pre' => '', 'sur' => '' ),
											'pin_code' => array( 'label' => __('Pin Code', 'trav'), 'pre' => '', 'sur' => '' ),
											'email' => array( 'label' => __('E-mail address', 'trav'), 'pre' => '', 'sur' => '' ),
											'tour_date' => array( 'label' => __('Tour Date', 'trav'), 'pre' => '', 'sur' => '' ),
											'adults' => array( 'label' => __('Adults', 'trav'), 'pre' => '', 'sur' => '' ),
											'kids' => array( 'label' => __('Kids', 'trav'), 'pre' => '', 'sur' => '' ),
										);

					foreach ( $booking_detail as $field => $value ) {
						if ( empty( $$field ) ) $$field = empty( $booking_data[ $field ] )?'':$booking_data[ $field ];
						if ( ! empty( $$field ) ) {
							$content = $value['pre'] . $$field . $value['sur'];
							echo sprintf( $dt_dd, esc_html( $value['label'] ), esc_html( $content ) );
						}
					}
					?>
				</dl>
				<hr />
				<?php if ( ! ( $booking_data['deposit_price'] == 0 ) ) : ?>
					<dl class="term-description">
							<dt><?php printf( __('Security Deposit(%d%%)', 'trav' ), $deposit_rate ) ?>:</dt><dd><?php echo esc_html( trav_get_price_field( $booking_data['deposit_price'], $booking_data['currency_code'], 0 ) ) ?></dd>
					</dl>
				<?php endif; ?>
				<dl class="term-description" style="font-size: 16px;" >
					<dt style="text-transform: none;"><?php echo __( 'Total Room Price', 'trav' ) ?></dt><dd><b style="color: #2d3e52;"><?php echo esc_html( trav_get_price_field( $booking_data['total_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) ) ?></b></dd>
				</dl>
				<hr />

				<h3><?php echo __( 'Tour Details', 'trav' ) ?></h3>

				<h4><a href="<?php echo esc_url( get_permalink( $tour_id ) ); ?>"><?php echo esc_html( get_the_title( $tour_id ) ) ?></a></h4>
				<dl class="term-description">
					<?php
					$tour_detail_fields = array( 'security_deposit' => array( 'label' => __('Security Deposit', 'trav'), 'pre' => '', 'sur' => ' ' . '%' ),
											'country' => array( 'label' => __('Country', 'trav'), 'pre' => '', 'sur' => '' ),
											'city' => array( 'label' => __('City', 'trav'), 'pre' => '', 'sur' => '' ),
											'address' => array( 'label' => __('Address', 'trav'), 'pre' => '', 'sur' => '' ),
											'phone' => array( 'label' => __('Phone No', 'trav'), 'pre' => '', 'sur' => '' ),
											'cancellation' => array( 'label' => __('Cancellation', 'trav'), 'pre' => '', 'sur' => '' ),
										);

					foreach ( $tour_detail_fields as $field => $value ) {
						if ( empty( $$field ) ) $$field = empty( $tour_meta["trav_tour_$field"] )?'':$tour_meta["trav_tour_$field"][0];
						if ( ! empty( $$field ) ) {
							$content = $value['pre'] . $$field . $value['sur'];
							echo sprintf( $dt_dd, esc_html( $value['label'] ), esc_html( $content ) );
						}
					} ?>
				</dl>
				<hr />

				<h4><?php echo esc_html( trav_tour_get_schedule_type_title( $tour_id, $st_id ) ) ?></h4>
				<dl class="term-description">
					<?php
					$st_data = trav_tour_get_schedule_type_data( $tour_id, $st_id );
					if ( ! empty( $st_data ) ) {
						$room_detail_fields = array('description' => __('Description', 'trav'),
													'time' => __('Time', 'trav'),
													);
						foreach ( $room_detail_fields as $field => $label ) {
							$$field = empty( $st_data["$field"] )?'':$st_data["$field"];
							if ( ! empty( $$field ) ) {
								echo sprintf( $dt_dd, esc_html( $label ), esc_html( $$field ) );
							}
						}
					}

					?>
				</dl>
				<?php do_action( 'trav_tour_conf_form_after', $booking_data ); ?>
			</div>
		</div>
		<div class="sidebar col-sm-4 col-md-3">
			<?php if ( empty( $tour_meta["trav_tour_d_edit_booking"] ) || empty( $tour_meta["trav_tour_d_edit_booking"][0] ) || empty( $tour_meta["trav_tour_d_cancel_booking"] ) || empty( $tour_meta["trav_tour_d_cancel_booking"][0] ) ) { ?>
				<div class="travelo-box edit-booking">

					<?php do_action( 'trav_tour_conf_sidebar_before', $booking_data ); ?>

					<h4><?php echo __('Is Everything Correct?','trav')?></h4>
					<p><?php echo __( 'You can always view or cancel your booking online - no registration required', 'trav' ) ?></p>
					<ul class="triangle hover box">
						<?php if ( empty( $tour_meta["trav_tour_d_cancel_booking"] ) || empty( $tour_meta["trav_tour_d_cancel_booking"][0] ) ) { ?>
							<li><a href="<?php $query_args['pbsource'] = 'cancel_booking'; echo esc_url( add_query_arg( $query_args ,get_permalink( $tour_id ) ) );?>" class="btn-cancel-booking"><?php echo __('Cancel your booking','trav')?></a></li>
						<?php } ?>
					</ul>

					<?php do_action( 'trav_tour_conf_sidebar_after', $booking_data ); ?>

				</div>
			<?php } ?>
			<?php generated_dynamic_sidebar(); ?>
		</div>
	</div>
	<script>
		tjq = jQuery;
		tjq(document).ready(function(){
			tjq('.btn-cancel-booking').click(function(e){
				e.preventDefault();
				var r = confirm("<?php echo __('Do you really want to cancel this booking?', 'trav') ?>");
				if (r == true) {
					tjq.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							action : 'tour_cancel_booking',
							edit_booking_no : '<?php echo esc_js( $booking_data['booking_no'] ) ?>',
							pin_code : '<?php echo esc_js( $booking_data['pin_code'] ) ?>'
						},
						success: function ( response ) {
							if ( response.success == 1 ) {
								trav_show_modal(1,response.result);
								setTimeout(function(){ window.location.href = tjq('.btn-cancel-booking').attr('href'); }, 3000);
							} else {
								alert( response.result );
							}
						}
					});
				}
				return false;
			});
		});
	</script>

<?php } elseif ( $booking_data['status'] == 0 ) { // if canceled ?>

	<div class="row">
		<div id="main" class="col-sm-8 col-md-9">
			<div class="booking-information travelo-box">

				<?php do_action( 'trav_tour_conf_cancel_form_before', $booking_data ); ?>

				<h3><?php _e( 'Cancelled', 'trav' ); ?></h3>
				<hr />
				<dl class="term-description">
					<?php
					$booking_detail = array(
											'booking_no' => __('Booking Number', 'trav'),
											'pin_code' => __('Pin Code', 'trav'),
											);

					foreach ( $booking_detail as $field => $label ) {
						$$field = empty( $booking_data[ $field ] )?'':$booking_data[ $field ];
						if ( ! empty( $$field ) ) {
							echo sprintf( $dt_dd, esc_html( $label ), esc_html( $$field ) );
						}
					} ?>
				</dl>
				<a href="<?php echo esc_url( get_permalink( $tour_id ) ); ?>" class="button btn-small green"><?php _e( "BOOK AGAIN", "trav" ); ?></a>
				<hr />

				<h3><?php echo __( 'Tour Details', 'trav' ) ?></h3>

				<h4><a href="<?php echo esc_url( get_permalink( $tour_id ) ); ?>"><?php echo esc_html( get_the_title( $tour_id ) ) ?></a></h4>
				<dl class="term-description">
					<?php
					$tour_detail_fields = array( 'security_deposit' => array( 'label' => __('Security Deposit', 'trav'), 'pre' => '', 'sur' => ' ' . '%' ),
											'country' => array( 'label' => __('Country', 'trav'), 'pre' => '', 'sur' => '' ),
											'city' => array( 'label' => __('City', 'trav'), 'pre' => '', 'sur' => '' ),
											'address' => array( 'label' => __('Address', 'trav'), 'pre' => '', 'sur' => '' ),
											'phone' => array( 'label' => __('Phone No', 'trav'), 'pre' => '', 'sur' => '' ),
											'cancellation' => array( 'label' => __('Cancellation', 'trav'), 'pre' => '', 'sur' => '' ),
										);

					foreach ( $tour_detail_fields as $field => $value ) {
						if ( empty( $$field ) ) $$field = empty( $tour_meta["trav_tour_$field"] )?'':$tour_meta["trav_tour_$field"][0];
						if ( ! empty( $$field ) ) {
							$content = $value['pre'] . $$field . $value['sur'];
							echo sprintf( $dt_dd, esc_html( $value['label'] ), esc_html( $content ) );
						}
					} ?>
				</dl>
				<hr />

				<h4><?php echo esc_html( trav_tour_get_schedule_type_title( $tour_id, $st_id ) ) ?></h4>
				<dl class="term-description">
					<?php
					$st_data = trav_tour_get_schedule_type_data( $tour_id, $st_id );
					if ( ! empty( $st_data ) ) {
						$room_detail_fields = array('description' => __('Description', 'trav'),
													'time' => __('Time', 'trav'),
													);
						foreach ( $room_detail_fields as $field => $label ) {
							$$field = empty( $st_data["$field"] )?'':$st_data["$field"];
							if ( ! empty( $$field ) ) {
								echo sprintf( $dt_dd, esc_html( $label ), esc_html( $$field ) );
							}
						}
					}

					?>
				</dl>
				<?php do_action( 'trav_tour_conf_cancel_form_after', $booking_data ); ?>
			</div>
		</div>
		<div class="sidebar col-sm-4 col-md-3">
			<?php do_action( 'trav_tour_conf_cancel_sidebar_before', $booking_data ); ?>
			<?php generated_dynamic_sidebar(); ?>
			<?php do_action( 'trav_tour_conf_cancel_sidebar_after', $booking_data ); ?>
		</div>
	</div>

<?php } elseif ( $booking_data['status'] == 2 ) { // if completed ?>

	<div class="row">
		<div id="main" class="col-sm-8 col-md-9">
			<div class="booking-information travelo-box">

				<?php do_action( 'trav_tour_conf_completed_form_before', $booking_data ); ?>

				<h3><?php _e( 'Completed', 'trav' ); ?></h3>
				<hr />
				<dl class="term-description">
					<?php
					$booking_detail = array(
											'booking_no' => __('Booking Number', 'trav'),
											'pin_code' => __('Pin Code', 'trav'),
											);

					foreach ( $booking_detail as $field => $label ) {
						$$field = empty( $booking_data[ $field ] )?'':$booking_data[ $field ];
						if ( ! empty( $$field ) ) {
							echo sprintf( $dt_dd, esc_html( $label ), esc_html( $$field ) );
						}
					} ?>
				</dl>
				<a href="<?php echo esc_url( get_permalink( $tour_id ) ); ?>" class="button btn-small green"><?php _e( "BOOK AGAIN", "trav" ); ?></a>
				<hr />

				<h3><?php echo __( 'Tour Details', 'trav' ) ?></h3>

				<h4><a href="<?php echo esc_url( get_permalink( $tour_id ) ); ?>"><?php echo esc_html( get_the_title( $tour_id ) ) ?></a></h4>
				<dl class="term-description">
					<?php
					$tour_detail_fields = array( 'security_deposit' => array( 'label' => __('Security Deposit', 'trav'), 'pre' => '', 'sur' => ' ' . '%' ),
											'country' => array( 'label' => __('Country', 'trav'), 'pre' => '', 'sur' => '' ),
											'city' => array( 'label' => __('City', 'trav'), 'pre' => '', 'sur' => '' ),
											'address' => array( 'label' => __('Address', 'trav'), 'pre' => '', 'sur' => '' ),
											'phone' => array( 'label' => __('Phone No', 'trav'), 'pre' => '', 'sur' => '' ),
											'cancellation' => array( 'label' => __('Cancellation', 'trav'), 'pre' => '', 'sur' => '' ),
										);

					foreach ( $tour_detail_fields as $field => $value ) {
						if ( empty( $$field ) ) $$field = empty( $tour_meta["trav_tour_$field"] )?'':$tour_meta["trav_tour_$field"][0];
						if ( ! empty( $$field ) ) {
							$content = $value['pre'] . $$field . $value['sur'];
							echo sprintf( $dt_dd, esc_html( $value['label'] ), esc_html( $content ) );
						}
					} ?>
				</dl>
				<hr />

				<h4><?php echo esc_html( trav_tour_get_schedule_type_title( $tour_id, $st_id ) ) ?></h4>
				<dl class="term-description">
					<?php
					$st_data = trav_tour_get_schedule_type_data( $tour_id, $st_id );
					if ( ! empty( $st_data ) ) {
						$room_detail_fields = array('description' => __('Description', 'trav'),
													'time' => __('Time', 'trav'),
													);
						foreach ( $room_detail_fields as $field => $label ) {
							$$field = empty( $st_data["$field"] )?'':$st_data["$field"];
							if ( ! empty( $$field ) ) {
								echo sprintf( $dt_dd, esc_html( $label ), esc_html( $$field ) );
							}
						}
					}

					?>
				</dl>
				<?php do_action( 'trav_tour_conf_completed_form_after', $booking_data ); ?>
			</div>
		</div>
		<div class="sidebar col-sm-4 col-md-3">
			<?php do_action( 'trav_tour_conf_completed_sidebar_before', $booking_data ); ?>
			<?php generated_dynamic_sidebar(); ?>
			<?php do_action( 'trav_tour_conf_completed_sidebar_after', $booking_data ); ?>
		</div>
	</div>

<?php }