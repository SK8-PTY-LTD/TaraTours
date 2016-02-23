<?php
global $trav_options;

// prevent direct access
if ( ! isset( $_REQUEST['booking_data'] ) ) {
	do_action('trav_acc_booking_wrong_data');
	exit;
}

// thank you page url
$acc_book_conf_url = '';
if ( ! empty( $trav_options['acc_booking_confirmation_page'] ) ) {
	$acc_book_conf_url = trav_get_permalink_clang( $trav_options['acc_booking_confirmation_page'] );
} else {
	// thank you page is not set
}

// init booking data : array( 'accommodation_id', 'room_type_id', 'date_from', 'date_to', 'rooms', 'adults', 'kids', 'child_ages' );
$raw_booking_data = '';
parse_str( $_REQUEST['booking_data'], $raw_booking_data );

//verify nonce
if ( ! isset( $raw_booking_data['_wpnonce'] ) || ! wp_verify_nonce( $raw_booking_data['_wpnonce'], 'post-' . $raw_booking_data['accommodation_id'] ) ) {
	do_action('trav_acc_booking_wrong_data');
	exit;
}

// init booking_data fields
$booking_fields = array( 'accommodation_id', 'room_type_id', 'date_from', 'date_to', 'rooms', 'adults', 'kids', 'child_ages' );
$booking_data = array();
foreach ( $booking_fields as $field ) {
	if ( ! isset( $raw_booking_data[ $field ] ) ) {
		do_action('trav_acc_booking_wrong_data');
		exit;
	} else {
		$booking_data[ $field ] = $raw_booking_data[ $field ];
	}
}

// date validation
if ( trav_strtotime( $booking_data['date_from'] ) >= trav_strtotime( $booking_data['date_to'] ) ) {
	do_action('trav_acc_booking_wrong_data');
	exit;
}

// make an array for redirect url generation
$query_args = array(
				'date_from' => $booking_data['date_from'],
				'date_to' => $booking_data['date_to'],
				'rooms' => $booking_data['rooms'],
				'adults' => $booking_data['adults'],
				'kids' => $booking_data['kids'],
				'child_ages' => $booking_data['child_ages'],
	);

// calculate price data : array( 'check_dates' => $check_dates, 'prices' => $price_data,'total_price' => $total_price_incl_tax );
$room_price_data = trav_acc_get_room_price_data( $booking_data['accommodation_id'], $booking_data['room_type_id'], $booking_data['date_from'], $booking_data['date_to'], $booking_data['rooms'], $booking_data['adults'], $booking_data['kids'], $booking_data['child_ages'] );
$acc_url = get_permalink( $booking_data['accommodation_id'] );
$edit_url = add_query_arg( $query_args, $acc_url );

// redirect if $room_price_data is not valid
if ( ! $room_price_data || ! is_array( $room_price_data ) ) {
	$query_args['error']=1;
	wp_redirect( $edit_url );
}

if ( ! isset( $_SESSION['exchange_rate'] ) ) trav_init_currency();
$deposit_rate = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_security_deposit', true );
$tax_rate = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_tax_rate', true );
$tax = 0;
if ( ! empty( $tax_rate ) ) $tax = $tax_rate * $room_price_data['total_price'] / 100;
$total_price_incl_tax = $room_price_data['total_price'] + $tax;
$booking_data['room_price'] = $room_price_data['total_price'];
$booking_data['tax'] = $tax;
$booking_data['total_price'] = $total_price_incl_tax;
$booking_data['currency_code'] = trav_get_user_currency();
$booking_data['exchange_rate'] = $_SESSION['exchange_rate'];
if ( ! empty( $trav_options['acc_pay_paypal'] ) && ! empty( $deposit_rate ) ) {
	$booking_data['deposit_price'] = $deposit_rate / 100 * $booking_data['total_price'] * $_SESSION['exchange_rate'];
}

// initialize session values
$transaction_id = mt_rand( 100000, 999999 );
$_SESSION['booking_data'][$transaction_id] = $booking_data; //'accommodation_id', 'room_type_id', 'date_from', 'date_to', 'rooms', 'adults', 'kids', 'child_ages', room_price, tax, total_price, currency_code, exchange_rate, deposit_price
$_countries = trav_get_all_countries();

$review = get_post_meta( trav_acc_org_id( $booking_data['accommodation_id'] ), 'review', true );
$review = ( ! empty( $review ) )?round( $review, 1 ):0;

// user info
$user_info = trav_get_current_user_info();
?>

<div class="row">
	<div class="col-sms-6 col-sm-8 col-md-9">
		<div class="booking-section travelo-box">

			<?php do_action( 'trav_acc_booking_form_before', $booking_data ); ?>

			<form class="booking-form" method="POST" action="<?php echo esc_url( $acc_book_conf_url ); ?>">
				<input type="hidden" name="action" value="acc_submit_booking">
				<input type="hidden" name="transaction_id" value='<?php echo esc_attr( $transaction_id ) ?>'>
				<?php wp_nonce_field( 'post-' . $booking_data['room_type_id'], '_wpnonce', false ); ?>
				<div class="person-information">
					<h2><?php _e( 'Your Personal Information', 'trav'); ?></h2>
					<div class="form-group row">
						<div class="col-sm-6 col-md-5">
							<label><?php _e( 'first name', 'trav'); ?></label>
							<input type="text" name="first_name" class="input-text full-width" value="<?php echo $user_info['first_name'] ?>" placeholder="" />
						</div>
						<div class="col-sm-6 col-md-5">
							<label><?php _e( 'last name', 'trav'); ?></label>
							<input type="text" name="last_name" class="input-text full-width" value="<?php echo $user_info['last_name'] ?>" placeholder="" />
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-6 col-md-5">
							<label><?php _e( 'email address', 'trav'); ?></label>
							<input type="text" name="email" class="input-text full-width" value="<?php echo $user_info['email'] ?>" placeholder="" />
						</div>
						<div class="col-sm-6 col-md-5">
							<label><?php _e( 'Verify E-mail Address', 'trav'); ?></label>
							<input type="text" name="email2" class="input-text full-width" value="<?php echo $user_info['email'] ?>" placeholder="" />
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-6 col-md-5">
							<label><?php _e( 'Country code', 'trav'); ?></label>
							<div class="selector">
								<select class="full-width" name="country_code">
									<?php foreach ( $_countries as $_country ) { ?>
										<option value="<?php echo esc_attr( $_country['d_code'] ) ?>" <?php selected( $user_info['country_code'], $_country['name'] . ' (' . $_country['d_code'] . ')' ); ?>><?php echo esc_html( $_country['name'] . ' (' . $_country['d_code'] . ')' ) ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-5">
							<label><?php _e( 'Phone number', 'trav'); ?></label>
							<input type="text" name="phone" class="input-text full-width" value="<?php echo $user_info['phone'] ?>" placeholder="" />
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-6 col-md-5">
							<label><?php _e( 'address', 'trav'); ?></label>
							<input type="text" name="address" class="input-text full-width" value="<?php echo $user_info['address'] ?>" placeholder="" />
						</div>
						<div class="col-sm-6 col-md-5">
							<label><?php _e( 'city', 'trav'); ?></label>
							<input type="text" name="city" class="input-text full-width" value="<?php echo $user_info['city'] ?>" placeholder="" />
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-6 col-md-5">
							<label><?php _e( 'zip code', 'trav'); ?></label>
							<input type="text" name="zip" class="input-text full-width" value="<?php echo $user_info['zip'] ?>" placeholder="" />
						</div>
						<div class="col-sm-6 col-md-5">
							<label><?php _e( 'Country', 'trav'); ?></label>
							<div class="selector">
								<select class="full-width" name="country">
									<?php foreach ( $_countries as $_country ) { ?>
										<option value="<?php echo esc_attr( $_country['name'] ) ?>" <?php selected( $user_info['country'], $_country['name'] ); ?>><?php echo esc_html( $_country['name'] ) ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-12 col-md-10">
							<label><?php _e( 'Special requirements', 'trav'); ?></label>
							<textarea name="special_requirements" class="full-width" rows="4"></textarea>
						</div>
					</div>
				</div>
				<hr />

				<?php if ( ! empty( $trav_options['vld_credit_card'] ) ) : ?>
					<div class="card-information">
						<h2><?php echo __( 'Your Card Information', 'trav' ) ?></h2>
						<div class="form-group row">
							<div class="col-sm-6 col-md-5">
								<label><?php echo __( 'Credit Card Type', 'trav' ) ?></label>
								<div class="selector">
									<select id="cc_type" name="cc_type" class="full-width">
										<option value="">Select a Card</option>
										<option value="American Express">American Express</option>
										<option value="Visa">Visa</option>
										<option value="MasterCard">MasterCard</option>
										<option value="Diners Club">Diners Club</option>
										<option value="JCB">JCB</option>
									</select>
								</div>
							</div>
							<div class="col-sm-6 col-md-5">
								<label><?php echo __( 'Card holder name', 'trav' ) ?></label>
								<input name="cc_holder_name" type="text" class="input-text full-width" value="" placeholder="" />
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-6 col-md-5">
								<label><?php echo __( 'Card number', 'trav' ) ?></label>
								<input name="cc_number" type="text" class="input-text full-width" value="" placeholder="" />
							</div>
							<div class="col-sm-6 col-md-5">
								<label><?php echo __( 'Card identification number', 'trav' ) ?></label>
								<input name="cc_cid" type="text" class="input-text full-width" value="" placeholder="">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-6 col-md-5">
								<label>Expiration Date</label>
								<div class="constant-column-2">
									<div class="selector">
										<select name="cc_exp_month" class="full-width">
											<?php for ( $i = 1; $i <= 12; $i++ ) { ?>
											<option value="<?php echo esc_attr( $i ) ?>"><?php echo esc_html( sprintf( "%02d", $i ) ); ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="selector">
										<select name="cc_exp_year" class="full-width">
											<?php for ( $i = 0; $i<10; $i++ ) { ?>
											<option value="<?php echo esc_attr( date("Y") + $i ) ?>"><?php echo esc_html( date("Y") + $i ) ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<hr />
				<?php endif; ?>

				<?php if ( ! empty( $trav_options['vld_captcha'] ) ) : ?>
					<div class="form-group row">
						<div class="col-sm-12 col-md-12">
							<img src="<?php echo esc_url( TRAV_TEMPLATE_DIRECTORY_URI . '/captcha.php?width=400&amp;height=100&amp;characters=5' ) ?>" class="col-sm-6 col-md-5" alt="captcha"/>
							<div class="col-sm-6 col-md-5">
								<label><?php _e( 'Security Code', 'trav'); ?></label>
								<input id="security_code" class="input-text" name="security_code" type="text" />
							</div>
						</div>
					</div>
					<hr />
				<?php endif; ?>

				<?php if ( ! empty( $trav_options['terms_page'] ) ) : ?>
					<div class="form-group">
						<div class="checkbox">
							<label>
								<input name="agree" value="agree" type="checkbox" checked><?php printf( __('By continuing, you agree to the <a href="%s" target="_blank"><span class="skin-color">Terms and Conditions</span></a>.', 'trav' ), trav_get_permalink_clang( $trav_options['terms_page'] ) ) ?>
							</label>
						</div>
					</div>
				<?php endif; ?>

				<div class="form-group row confirm-booking-btn">
					<div class="col-sm-6 col-md-5">
						<button type="submit" class="full-width btn-large">
							<?php if ( ! empty( $trav_options['acc_pay_paypal'] ) && ! empty( $deposit_rate ) ) {
								_e( 'CONFIRM AND DEPOSIT VIA PAYPAL', 'trav');
							} else {
								_e( 'CONFIRM BOOKING', 'trav');
							}?>
						</button>
					</div>
				</div>
			</form>

			<?php do_action( 'trav_acc_booking_form_after', $booking_data ); ?>

		</div>
	</div>
	<div class="sidebar col-sms-6 col-sm-4 col-md-3">
		<div class="booking-details travelo-box">

			<?php do_action( 'trav_acc_booking_sidebar_before', $booking_data ); ?>

			<h4><?php _e( 'Booking Details', 'trav'); ?></h4>
			<article class="image-box hotel listing-style1">
				<figure class="clearfix">
					<a href="<?php echo esc_url( $acc_url ); ?>" class="hover-effect middle-block">
						<?php echo get_the_post_thumbnail( $booking_data['accommodation_id'], 'thumbnail', array( 'class'=>'middle-item' ) ); ?>
					</a>
					<div class="travel-title">
						<h5 class="box-title"><a href="<?php echo esc_url( $acc_url ); ?>"><?php echo esc_html( get_the_title( $booking_data['accommodation_id'] ) );?></a>
							<small>
								<?php echo esc_html( trav_acc_get_city( $booking_data['accommodation_id'] ) ); ?>
								<br />
								<?php echo esc_html( trav_acc_get_country( $booking_data['accommodation_id'] ) ); ?>
							</small>
						</h5>
						<a href="<?php echo esc_url( $edit_url );?>" class="button"><?php _e( 'EDIT', 'trav'); ?></a>
					</div>
				</figure>
				<div class="details">
					<div class="feedback">
						<div data-placement="bottom" data-toggle="tooltip" class="five-stars-container" title="<?php echo esc_attr( $review . ' ' . __( 'stars', 'trav' ) ) ?>"><span style="width: <?php echo esc_attr( $review / 5 * 100 ) ?>%;" class="five-stars"></span></div>
						<span class="review"><?php echo esc_html( trav_get_review_count( $booking_data['accommodation_id'] ) . ' ' .  __('reviews', 'trav') ); ?></span>
					</div>
					<div class="constant-column-3 timing clearfix">
						<div class="check-in">
							<label><?php _e( 'Check in', 'trav'); ?></label>
							<span><?php echo date( "M j, Y", trav_strtotime( $booking_data['date_from'] ) );?><br /><?php $date_from_time = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_check_in', true ); if ( isset($date_from_time) ) echo esc_html( $date_from_time );?></span>
						</div>
						<div class="duration text-center">
							<i class="soap-icon-clock"></i>
							<span>
								<?php echo esc_html( trav_get_day_interval( $booking_data['date_from'], $booking_data['date_to'] ) . ' ' . __( 'Nights', 'trav' ) ); ?>
							</span>
						</div>
						<div class="check-out">
							<label><?php _e( 'Check out', 'trav'); ?></label>
							<span><?php echo date( "M j, Y", trav_strtotime( $booking_data['date_to'] ) );?><br /><?php $date_to_time = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_check_out', true ); if ( isset($date_to_time) ) echo esc_html( $date_to_time );?></span>
						</div>
					</div>
					<div class="guest">
						<small class="uppercase"><?php echo esc_html( $booking_data['rooms'] . ' ' . get_the_title( $booking_data['room_type_id'] ) ); ?> for <span class="skin-color"><?php echo esc_html( $booking_data['adults'] . ' ' . __( 'Adults', 'trav' ) ); if ( ! empty( $booking_data['kids'] ) ) echo ' &amp; ' . esc_html( $booking_data['kids'] . ' ' . __( 'Kids', 'trav' ) );?></span></small>
					</div>
				</div>
			</article>
			
			<h4><?php _e( 'Other Details', 'trav' ); ?></h4>
			<dl class="other-details">
				<dt class="feature"><?php _e( 'Room Type', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( get_the_title( $booking_data['room_type_id'] ) );?></dd>
				<dt class="feature"><?php echo esc_html( trav_get_day_interval( $booking_data['date_from'], $booking_data['date_to'] ) . ' ' .__( 'night Stay', 'trav') ); ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $room_price_data['total_price'] ) ) ?></dd>
				<?php if ( ! empty( $tax_rate ) ) : ?>
					<dt class="feature"><?php echo __( 'taxes and fees', 'trav') ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $tax ) ) ?></dd>
				<?php endif; ?>
				<?php if ( ! empty( $trav_options['acc_pay_paypal'] ) && ! empty( $deposit_rate ) ) : ?>
					<dt class="feature"><?php _e( 'Security Deposit', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $booking_data['deposit_price'], $booking_data['currency_code'], 0 ) ) ?></dd>
				<?php endif; ?>
				<dt class="total-price"><?php _e( 'Total Price', 'trav'); ?></dt><dd class="total-price-value"><?php echo esc_html( trav_get_price_field( $total_price_incl_tax ) ) ?></dd>
			</dl>
			<a href="#" class="show-price-detail" data-show-desc="<?php _e( 'Show Price Detail', 'trav' ) ?>" data-hide-desc="<?php _e( 'Hide Price Detail', 'trav' ) ?>"><?php _e( 'Show Price Detail', 'trav' ) ?></a><br />
			<dl class="price-details clearer">
				<?php
					foreach ( $room_price_data['check_dates'] as $check_date ) {
						echo '<dt class="feature">' . esc_html( $check_date ) . ':</dt><dd class="value clearfix"><table>';
						if ( ! empty( $room_price_data['prices'][ $check_date ]['ppr'] ) ) {
							echo '<tr><td>';
							echo __('price per room', 'trav') . '</td><td>' . esc_html( trav_get_price_field( $room_price_data['prices'][ $check_date ]['ppr'] ) );
							echo '</td></tr>';
						}
						if ( ! empty( $room_price_data['prices'][ $check_date ]['ppp'] ) ) {
							echo '<tr><td>';
							echo __('price per person', 'trav') . '</td><td>' . esc_html( trav_get_price_field( $room_price_data['prices'][ $check_date ]['ppp'] ) ) ;
							echo '</td></tr>';
						}
						if ( ! empty( $room_price_data['prices'][ $check_date ]['cp'] ) ) {
							$i = 0;
							foreach ( $room_price_data['prices'][ $check_date ]['cp'] as $child_price) {
								$i++;
								echo '<tr><td>';
								echo __( 'child', 'trav' ) . esc_html( $i ) . ' ' . __( 'price', 'trav') . '</td><td>' . esc_html( trav_get_price_field( $child_price ) );
								echo '</td></tr>';
							}
						}
						echo '<tr><td>';
						echo __( 'total', 'trav' ) . '</td><td>' . esc_html( trav_get_price_field( $room_price_data['prices'][ $check_date ]['total'] ) ) ;
						echo '</td></tr></table></dd>';
					}
				?>
			</dl>

			<?php do_action( 'trav_acc_booking_sidebar_after', $booking_data ); ?>

		</div>
		<?php
			generated_dynamic_sidebar();
		?>
	</div>
</div>

<script>
	tjq = jQuery;

	tjq(document).ready(function(){

		//validation form
		tjq('.booking-form').validate({
			rules: {
				first_name: { required: true},
				last_name: { required: true},
				email: { required: true, email: true},
				email2: { required: true, equalTo: 'input[name="email"]'},
				phone: { required: true},
				address: { required: true},
				city: { required: true},
				zip: { required: true},
				<?php if ( ! empty( $trav_options['vld_captcha'] ) ) : ?>
					security_code: { required: true},
				<?php endif; ?>
				<?php if ( ! empty( $trav_options['vld_credit_card'] ) ) : ?>
					cc_type: { required: true},
					cc_holder_name: { required: true},
					cc_number: { required: true, creditcard: true},
				<?php endif; ?>
			},
			submitHandler: function (form) {
				<?php if ( ! empty( $trav_options['terms_page'] ) ) : ?>
				if ( tjq('input[name="agree"]:checked').length == 0 ) {
					alert("<?php echo esc_js( __( 'Agree to terms&conditions is required' ,'trav' ) ); ?>");
					return false;
				}
				<?php endif; ?>
				var booking_data = tjq('.booking-form').serialize();
				tjq.ajax({
					type: "POST",
					url: ajaxurl,
					data: booking_data,
					success: function ( response ) {
						if ( response.success == 1 ) {
							var confirm_url = tjq('.booking-form').attr('action')
							if ( confirm_url.indexOf('?') > -1 ) {
								confirm_url = confirm_url + '&';
							} else {
								confirm_url = confirm_url + '?';
							}
							confirm_url = confirm_url + 'booking_no=' + response.result.booking_no + '&pin_code=' + response.result.pin_code + '&transaction_id=' + response.result.transaction_id + '&message=1';
							if ( response.payment == 'paypal' ) {
								tjq('.confirm-booking-btn').before('<div class="alert alert-success"><?php echo esc_js( __( 'You will be redirected to paypal.', 'trav' ) ) ?><span class="close"></span></div>');
							}
							tjq('.confirm-booking-btn').hide();
							setTimeout( function(){ tjq('.opacity-ajax-overlay').show(); }, 500 );
							window.location.href = confirm_url;
						} else if ( response.success == -1 ) {
							alert( response.result );
							setTimeout( function(){ tjq('.opacity-ajax-overlay').show(); }, 500 );
							window.location.href = '<?php echo esc_js( $edit_url ); ?>';
						} else {
							console.log( response );
							trav_show_modal( 0, response.result, '' );
						}
					}
				});
				return false;
			}
		});

		tjq('.show-price-detail').click( function(e){
			e.preventDefault();
			tjq('.price-details').toggle();
			if (tjq('.price-details').is(':visible')) {
				tjq(this).html( tjq(this).data('hide-desc') );
			} else {
				tjq(this).html( tjq(this).data('show-desc') );
			}
			return false;
		});
	});
</script>