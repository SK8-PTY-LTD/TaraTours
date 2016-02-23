<?php
global $trav_options;

// thank you page url
$tour_book_conf_url = '';
if ( ! empty( $trav_options['tour_booking_confirmation_page'] ) ) {
	$tour_book_conf_url = trav_get_permalink_clang( $trav_options['tour_booking_confirmation_page'] );
} else {
	// thank you page is not set
}

// init booking_data fields
$booking_fields = array( 'tour_id', 'st_id', 'tour_date', 'adults' );
$booking_data = array();
foreach ( $booking_fields as $field ) {
	if ( ! isset( $_REQUEST[ $field ] ) ) {
		do_action('trav_tour_booking_wrong_data');
		exit;
	} else {
		$booking_data[ $field ] = $_REQUEST[ $field ];
	}
}
if ( isset( $_REQUEST[ 'kids' ] ) ) {
	$booking_data['kids'] = $_REQUEST['kids'];
}

//verify nonce
if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'post-' . $_REQUEST['tour_id'] ) ) {
	do_action('trav_tour_booking_wrong_data');
	exit;
}

$schedule_data = trav_tour_get_price_data( $booking_data );
$tour_url = get_permalink( $booking_data['tour_id'] );

// redirect if $room_price_data is not valid
if ( empty( $schedule_data ) || empty( $schedule_data['success'] ) ) {
	wp_redirect( add_query_arg( array( 'error' => 1 ), $tour_url ) );
}

if ( ! isset( $_SESSION['exchange_rate'] ) ) trav_init_currency();
$deposit_rate = get_post_meta( $booking_data['tour_id'], 'trav_tour_security_deposit', true );
$booking_data['total_price'] = $schedule_data['price'];
$booking_data['currency_code'] = trav_get_user_currency();
$booking_data['exchange_rate'] = $_SESSION['exchange_rate'];
if ( ! empty( $trav_options['acc_pay_paypal'] ) && ! empty( $deposit_rate ) ) {
	$booking_data['deposit_price'] = $deposit_rate / 100 * $booking_data['total_price'] * $_SESSION['exchange_rate'];
}
$price_data = $schedule_data['price_data'];

// initialize session values
$transaction_id = mt_rand( 100000, 999999 );
$_SESSION['booking_data'][$transaction_id] = $booking_data; //'tour_id', 'st_id', 'date_from', 'date_to', 'rooms', 'adults', 'kids', price, currency_code, exchange_rate, deposit_price
$_countries = trav_get_all_countries();

$multi_book = get_post_meta( $booking_data['tour_id'], 'trav_tour_multi_book', true );

// user info
$user_info = trav_get_current_user_info();
?>

<div class="row">
	<div class="col-sms-6 col-sm-8 col-md-9">
		<div class="booking-section travelo-box">

			<?php do_action( 'trav_tour_booking_form_before', $booking_data ); ?>

			<form class="booking-form" method="POST" action="<?php echo esc_url( $tour_book_conf_url ); ?>">
				<input type="hidden" name="action" value="tour_submit_booking">
				<input type="hidden" name="transaction_id" value='<?php echo esc_attr( $transaction_id ) ?>'>
				<?php wp_nonce_field( 'post-' . $booking_data['tour_id'], '_wpnonce', false ); ?>
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

			<?php do_action( 'trav_tour_booking_form_after', $booking_data ); ?>

		</div>
	</div>
	<div class="sidebar col-sms-6 col-sm-4 col-md-3">
		<div class="booking-details travelo-box">

			<?php do_action( 'trav_tour_booking_sidebar_before', $booking_data ); ?>

			<h4><?php _e( 'Booking Details', 'trav'); ?></h4>
			<article class="tour-detail">
				<figure class="clearfix">
					<a href="<?php echo esc_url( $tour_url ); ?>" class="hover-effect middle-block">
						<?php echo get_the_post_thumbnail( $booking_data['tour_id'], 'thumbnail', array( 'class'=>'middle-item' ) ); ?>
					</a>
					<div class="travel-title">
						<h5 class="box-title"><a href="<?php echo esc_url( $tour_url ); ?>"><?php echo esc_html( get_the_title( $booking_data['tour_id'] ) );?></a>
							<small>
								<?php echo trav_tour_get_schedule_type_title( $booking_data['tour_id'], $booking_data['st_id'] ) ?>
							</small>
						</h5>
						<a href="<?php echo esc_url( $tour_url );?>" class="button"><?php _e( 'EDIT', 'trav'); ?></a>
					</div>
				</figure>
				<div class="details">
					<div class="icon-box style12 style13 full-width">
						<div class="icon-wrapper">
							<i class="soap-icon-calendar"></i>
						</div>
						<dl class="details">
							<dt class="skin-color"><?php _e( 'Date', 'trav' ) ?></dt>
							<dd><?php echo date( "M j, Y", trav_strtotime( $booking_data['tour_date'] ) );?></dd>
						</dl>
					</div>
					<div class="icon-box style12 style13 full-width">
						<div class="icon-wrapper">
							<i class="soap-icon-clock"></i>
						</div>
						<dl class="details">
							<dt class="skin-color"><?php _e( 'Duration', 'trav' ) ?></dt>
							<dd><?php echo $price_data['duration'] ?></dd>
						</dl>
					</div>
					<div class="icon-box style12 style13 full-width">
						<div class="icon-wrapper">
							<i class="soap-icon-departure"></i>
						</div>
						<dl class="details">
							<dt class="skin-color"><?php _e( 'Location', 'trav' ) ?></dt>
							<dd><?php echo esc_html( trav_tour_get_city( $booking_data['tour_id'] ) ); ?>, <?php echo esc_html( trav_tour_get_country( $booking_data['tour_id'] ) ); ?></dd>
						</dl>
					</div>
				</div>
			</article>
			
			<h4><?php _e( 'Other Details', 'trav' ); ?></h4>
			<dl class="other-details">
				<?php if ( ! empty( $multi_book ) ) : ?>
					<dt class="feature"><?php _e( 'Price Per Adult', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $price_data['price'] ) ) ?></dd>
					<?php if ( ! empty( $price_data['child_price'] ) && ( (float) $price_data['child_price'] ) > 0 ) : ?>
						<dt class="feature"><?php _e( 'Price Per Child', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $price_data['child_price'] ) ) ?></dd>
					<?php endif; ?>
					<dt class="feature"><?php _e( 'Adults', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( $booking_data['adults'] ) ?></dd>
					<?php if ( ! empty( $booking_data['kids'] ) ) : ?>
						<dt class="feature"><?php _e( 'Kids', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( $booking_data['kids'] ) ?></dd>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( ! empty( $trav_options['acc_pay_paypal'] ) && ! empty( $deposit_rate ) ) : ?>
					<dt class="feature"><?php _e( 'Security Deposit', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $booking_data['deposit_price'], $booking_data['currency_code'], 0 ) ) ?></dd>
				<?php endif; ?>
				<dt class="total-price"><?php _e( 'Total Price', 'trav'); ?></dt><dd class="total-price-value"><?php echo esc_html( trav_get_price_field( $booking_data['total_price'] ) ) ?></dd>
			</dl>
			<?php do_action( 'trav_tour_booking_sidebar_after', $booking_data ); ?>

		</div>
		<?php generated_dynamic_sidebar(); ?>
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
							window.location.href = '<?php echo esc_js( $tour_url ); ?>';
						} else {
							console.log( response );
							trav_show_modal( 0, response.result, '' );
						}
					}
				});
				return false;
			}
		});
	});
</script>