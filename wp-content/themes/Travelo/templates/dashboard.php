<?php
 if ( ! is_user_logged_in() ) {
	wp_redirect( home_url() );
	exit;
}

$user_id = get_current_user_id();


if ( isset( $_POST['action'] ) && $_POST['action'] == 'update_profile' ) {
	if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'update_profile' ) ) {
		$update_data = array(
			'ID' => $user_id,
			'first_name' => isset($_POST['first_name']) ? sanitize_text_field( $_POST['first_name'] ) : '',
			'last_name' => isset($_POST['last_name']) ? sanitize_text_field( $_POST['last_name'] ) : '',
			'user_email' => isset($_POST['email']) ? sanitize_email( $_POST['email'] ) : '',
			'birthday' => isset($_POST['birthday']) ? sanitize_text_field( $_POST['birthday'] ) : '',
			'country_code' => isset($_POST['country_code']) ? sanitize_text_field( $_POST['country_code'] ) : '',
			'phone' => isset($_POST['phone']) ? sanitize_text_field( $_POST['phone'] ) : '',
			'address' => isset($_POST['address']) ? sanitize_text_field( $_POST['address'] ) : '',
			'city' => isset($_POST['city']) ? sanitize_text_field( $_POST['city'] ) : '',
			'country' => isset($_POST['country']) ? sanitize_text_field( $_POST['country'] ) : '',
			'description' => isset($_POST['description']) ? sanitize_text_field( $_POST['description'] ) : '',
			);
		if ( ! isset( $_FILES['photo'] ) || ( $_FILES['photo']['size'] == 0 ) ) {
			if ( ! empty( $_POST['remove_photo'] ) ) {
				$update_data['photo_url'] = '';
			}
		} else {
			if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
			$uploadedfile = $_FILES['photo'];
			$upload_overrides = array( 'test_form' => false );
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			$update_data['photo_url'] = $movefile['url'];
		}
		wp_update_user( $update_data );
		echo '<div class="alert alert-success">' . __( 'Your profile is updated successfully.', 'trav' ) . '<span class="close"></span></div>';
	} else {
		echo '<div class="alert alert-error">' . __( 'Sorry, your nonce did not verify.', 'trav' ) . '<span class="close"></span></div>';
	}
}

$current_user = wp_get_current_user();
$photo_url = '';
if ( isset( $current_user->photo_url ) && ! empty( $current_user->photo_url ) ) {
	$photo_url = $current_user->photo_url;
}
$photo = trav_get_avatar( array( 'id' => $user_id, 'email' => $current_user->user_email, 'size' => 270 ) );

$display_name = $current_user->display_name;
$user_login = $current_user->user_login;
$user_firstname = $current_user->user_firstname;
$user_lastname = $current_user->user_lastname;
$email = $current_user->user_email;
$country_code = get_user_meta( $user_id, 'country_code', true );
$phone = get_user_meta( $user_id, 'phone', true );
$birthday = get_user_meta( $user_id, 'birthday', true );
$address = get_user_meta( $user_id, 'address', true );
$city = get_user_meta( $user_id, 'city', true );
$zip = get_user_meta( $user_id, 'zip', true );
$country = get_user_meta( $user_id, 'country', true );
$description = $current_user->description;
$_countries = trav_get_all_countries();
?>

<div class="tab-container full-width-style arrow-left dashboard">
	<ul class="tabs">
		<li class="active"><a data-toggle="tab" href="#dashboard"><i class="soap-icon-anchor circle"></i><?php echo __( 'Dashboard','trav' ) ?></a></li>
		<li class=""><a data-toggle="tab" href="#profile"><i class="soap-icon-user circle"></i><?php echo __( 'Profile','trav' ) ?></a></li>
		<li class=""><a data-toggle="tab" href="#booking"><i class="soap-icon-businessbag circle"></i><?php echo __( 'Booking','trav' ) ?></a></li>
		<li class=""><a data-toggle="tab" href="#wishlist"><i class="soap-icon-wishlist circle"></i><?php echo __( 'Wishlist','trav' ) ?></a></li>
		<li class=""><a data-toggle="tab" href="#settings"><i class="soap-icon-settings circle"></i><?php echo __( 'Settings','trav' ) ?></a></li>
	</ul>
	<div class="tab-content">
		<div id="dashboard" class="tab-pane fade in active">
			<h1 class="no-margin skin-color"><?php printf( __( 'Hi %s, Welcome to %s', 'trav' ), $display_name, get_bloginfo('name') ) ?></h1>
			<br />
			<div class="row block">
				<div class="col-md-6 notifications">
					<h2><?php printf( __( 'What\'s New On %s' ,'trav' ), get_bloginfo('name') ) ?></h2>
					<?php 
						$list_size = 8;
						$args = array(
								'posts_per_page' => $list_size,
								'orderby' => 'date',
								'order' => 'desc',
								'post_status' => 'publish',
								'post_type' => array( 'post', 'accommodation' )
							);
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								$post_type = get_post_type();
					?>
								<a href="<?php the_permalink(); ?>">
									<div class="icon-box style1 fourty-space">
										<?php if ( $post_type == 'accommodation' ) { ?>

											<i class="soap-icon-hotel blue-bg"></i>
											<span class="time pull-right"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
											<p class="box-title"><?php the_title(); ?> in <span class="price"><?php echo esc_html( trav_get_price_field( get_post_meta( $the_query->post->ID, 'trav_accommodation_avg_price', true ) ) ) ?></span></p>

										<?php } else { ?>
											<i class="soap-icon-magazine green-bg"></i>
											<span class="time pull-right"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
											<p class="box-title"><?php the_title(); ?></p>
										<?php } ?>
									</div>
								</a>
					<?php
							}
						} else {
							echo __( "Nothing New.", "trav" );
						}
						wp_reset_postdata();
					?>
				</div>
				<div class="col-md-6">
					<h2><?php echo __( 'Recent Activity', 'trav' ) ?></h2>
					<div class="recent-activity">
						<ul>

							<?php
								$recent_activity = get_user_meta( $user_id, 'recent_activity', true );
								if ( ! empty( $recent_activity ) ) {
									$recent_activity_array = unserialize( $recent_activity );
									foreach ( $recent_activity_array as $post_id ) {
										$post_id = trav_acc_clang_id( $post_id );
										$post_type = get_post_type( $post_id );
										if ( $post_type == 'accommodation' ) {
											$_country = get_post_meta( $post_id, 'trav_accommodation_country', true );
											if ( ! empty( $_country ) ) {
												if ( $country_obj = get_term_by( 'id', $_country, 'location' ) ) $_country = __( $country_obj->name, 'trav');
											}

											$_city = get_post_meta( $post_id, 'trav_accommodation_city', true );
											if ( ! empty( $_city ) ) {
												if ( $city_obj = get_term_by( 'id', $_city, 'location' ) ) $_city = __( $city_obj->name, 'trav');
											}
							?>

											<li>
												<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
													<i class="icon soap-icon-hotel circle blue-color"></i>
													<span class="price"><small><?php _e( 'avg/night', 'trav' ); ?></small><?php echo esc_html( trav_get_price_field( get_post_meta( $post_id, 'trav_accommodation_avg_price', true ) ) ); ?></span>
													<h4 class="box-title"><?php echo esc_html( get_the_title( $post_id ) ); ?><small><?php echo esc_html( $_city . ' ' . $_country ) ?></small></h4>
												</a>
											</li>

							<?php
										} elseif ( $post_type == 'post' ) {
											$post_author_id = get_post_field( 'post_author', $post_id );
							?>
											<li>
												<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
													<i class="icon soap-icon-magazine circle green-color"></i>
													<span class="price"><small><?php _e( 'posted by', 'trav' ); ?>: <span><?php echo esc_html( get_the_author_meta( 'display_name', $post_author_id ) ); ?></span></small></span>
													<h4 class="box-title"><?php echo esc_html( get_the_title( $post_id ) ); ?><small><?php echo wp_kses_post( get_the_date( '', $post_id ) ) ?></small></h4>
												</a>
											</li>
							<?php
										}
									}
								} else {
									echo __( "You don't have any recent activities.", "trav" );
								}
							?>
						</ul>
					</div>
				</div>
			</div>
			<hr>
		</div>
		<div id="profile" class="tab-pane fade">
			<div class="view-profile">
				<article class="image-box style2 box innerstyle personal-details">
					<figure>
						<a title="" href="#"><?php echo wp_kses_post( $photo ) ?></a>
					</figure>
					<div class="details">
						<a href="#" class="button btn-mini pull-right edit-profile-btn"><?php echo __( 'EDIT PROFILE', 'trav' ) ?></a>
						<h2 class="box-title fullname"><?php echo esc_html( $display_name ) ?></h2>
						<dl class="term-description">
							<?php if ( ! empty( $user_login ) ) { ?><dt><?php echo __( 'user name', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_login ) ?></dd><?php } ?>
							<?php if ( ! empty( $user_firstname ) ) { ?><dt><?php echo __( 'first name', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_firstname ) ?></dd><?php } ?>
							<?php if ( ! empty( $user_lastname ) ) { ?><dt><?php echo __( 'last name', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_lastname ) ?></dd><?php } ?>
							<?php if ( ! empty( $phone ) ) { ?><dt><?php echo __( 'phone number', 'trav' ) ?>:</dt><dd><?php echo esc_html( $phone ) ?></dd><?php } ?>
							<?php if ( ! empty( $birthday ) ) { ?><dt><?php echo __( 'Date of birth', 'trav' ) ?>:</dt><dd><?php echo esc_html( $birthday ) ?></dd><?php } ?>
							<?php if ( ! empty( $address ) ) { ?><dt><?php echo __( 'Street Address and number', 'trav' ) ?>:</dt><dd><?php echo esc_html( $address ) ?></dd><?php } ?>
							<?php if ( ! empty( $city ) ) { ?><dt><?php echo __( 'Town / City', 'trav' ) ?>:</dt><dd><?php echo esc_html( $city ) ?></dd><?php } ?>
							<?php if ( ! empty( $zip ) ) { ?><dt><?php echo __( 'ZIP code', 'trav' ) ?>:</dt><dd><?php echo esc_html( $zip ) ?></dd><?php } ?>
							<?php if ( ! empty( $country ) ) { ?><dt><?php echo __( 'Country', 'trav' ) ?>:</dt><dd><?php echo esc_html( $country ) ?></dd><?php } ?>
						</dl>
					</div>
				</article>
				<hr>
				<h2><?php echo __( 'About You', 'trav' ) ?></h2>
					<div class="intro">
					<p><?php echo esc_html( $description ); ?></p>
				</div>
			</div>
			<div class="edit-profile">
				<a href="#" class="button btn-mini pull-right view-profile-btn"><?php echo __( 'VIEW PROFILE', 'trav' ) ?></a>
				<form class="edit-profile-form" method="post" enctype='multipart/form-data'>
					<h2><?php echo __( 'Personal Details', 'trav' ) ?></h2>
					<input type="hidden" name="action" value="update_profile">
					<?php wp_nonce_field( 'update_profile' ); ?>
					<div class="col-sm-9 no-padding no-float">
						<div class="row form-group">
							<div class="col-sms-6 col-sm-6">
								<label><?php echo __( 'First Name', 'trav' ) ?></label>
								<input name="first_name" type="text" class="input-text full-width" placeholder="" value="<?php echo esc_attr( $user_firstname ) ?>">
							</div>
							<div class="col-sms-6 col-sm-6">
								<label><?php echo __( 'Last Name', 'trav' ) ?></label>
								<input name="last_name" type="text" class="input-text full-width" placeholder="" value="<?php echo esc_attr( $user_lastname ) ?>">
							</div>
						</div>
						<div class="row form-group">
							<div class="col-sms-6 col-sm-6">
								<label><?php echo __( 'Email Address', 'trav' ) ?></label>
								<input name="email" type="email" class="input-text full-width" placeholder="" value="<?php echo esc_attr( $email ) ?>">
							</div>
							<div class="col-sms-6 col-sm-6">
								<label><?php echo __( 'Date of Birth', 'trav' ) ?></label>
								<div class="datepicker-wrap to-today">
									<input name="birthday" type="text" placeholder="<?php echo trav_get_date_format('html') ?>" class="input-text full-width" value="<?php echo esc_attr( $birthday ) ?>">
								</div>
							</div>
						</div>
						<div class="row form-group">
							<div class="col-sms-6 col-sm-6">
								<label><?php echo __( 'Country Code', 'trav' ) ?></label>
								<div class="selector">
									<select name="country_code" class="full-width">
										<?php foreach ( $_countries as $_country ) {
											$selected = '';
											if ( $_country['name'] . ' (' . $_country['d_code'] . ')' == $country_code ) $selected = " selected";
											?>
											<option<?php echo esc_attr( $selected ) ?> value="<?php echo esc_attr( $_country['name'] . ' (' . $_country['d_code'] . ')' ) ?>"><?php echo esc_html( $_country['name'] . ' (' . $_country['d_code'] . ')' ) ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sms-6 col-sm-6">
								<label><?php echo __( 'Phone Number', 'trav' ) ?></label>
								<input name="phone" type="text" class="input-text full-width" placeholder="" value="<?php echo esc_attr( $phone ) ?>">
							</div>
						</div>
						<div class="row form-group">
							<div class="col-sms-6 col-sm-6">
								<label><?php echo __( 'Address', 'trav' ) ?></label>
								<input name="address" type="text" class="input-text full-width" value="<?php echo esc_attr( $address ) ?>">
							</div>
							<div class="col-sms-6 col-sm-6">
								<label><?php echo __( 'City', 'trav' ) ?></label>
								<input name="city" type="text" class="input-text full-width" value="<?php echo esc_attr( $city ) ?>">
							</div>
						</div>
						<div class="row form-group">
							<div class="col-sms-6 col-sm-6">
								<label><?php echo __( 'Country', 'trav' ) ?></label>
								<div class="selector">
									<select name="country" class="full-width">
										<?php foreach ( $_countries as $_country ) {
											$selected = '';
											if ( $_country['name'] == $country ) $selected = "selected"; ?>
												<option <?php echo wp_kses_post( $selected ) ?> value="<?php echo esc_attr( $_country['name'] ) ?>"><?php echo esc_html( $_country['name'] ) ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<hr>
						<h2><?php echo __( 'Upload Profile Photo', 'trav' ) ?></h2>
						<div class="row form-group">
							<div class="col-sms-12 col-sm-6">
								<div class="fileinput full-width small-box">
									<input name="photo" type="file" class="input-text" data-placeholder="select image/s" accept="image/*">
								</div>
							</div>
							<div class="col-sms-12 col-sm-6">
								<div id="photo_preview" class="image-close-box"<?php if ( empty( $photo_url ) ) { echo ' style="display:none"'; } ?>>
									<input type="hidden" name="remove_photo">
									<div class="close-banner"></div>
									<span class="close"></span>
									<img src="<?php echo esc_url( $photo_url ) ?>" alt="your photo">
								</div>
							</div>
						</div>
						<hr>
						<h2><?php echo __( 'Describe Yourself', 'trav' ) ?></h2>
						<div class="form-group">
							<textarea name="description" rows="5" class="input-text full-width" placeholder="please tell us about you"><?php echo esc_textarea( $description ); ?></textarea>
						</div>
						<div class="from-group">
							<button type="submit" class="btn-medium col-sms-6 col-sm-4"><?php echo __( 'UPDATE SETTINGS', 'trav' ) ?></button>
						</div>

					</div>
				</form>
			</div>
		</div>
		<div id="booking" class="tab-pane fade">
			<h2><?php echo __( 'Trips You have Booked!', 'trav' ) ?></h2>
			<div class="filter-section gray-area clearfix">
				<form class="booking-status-filter">
					<input type="hidden" name="action" value="update_booking_list">
					<label class="radio radio-inline">
						<input type="radio" name="status" checked="checked" value="-1" />
						<?php echo __( 'All Types', 'trav' ) ?>
					</label>
					<label class="radio radio-inline">
						<input type="radio" name="status" value="1" />
						<?php echo __( 'UPCOMING', 'trav' ) ?>
					</label>
					<label class="radio radio-inline">
						<input type="radio" name="status" value="0" />
						<?php echo __( 'CANCELLED', 'trav' ) ?>
					</label>
					<label class="radio radio-inline">
						<input type="radio" name="status"  value="2"/>
						<?php echo __( 'COMPLETED', 'trav' ) ?>
					</label>
					<div class="pull-right col-md-6 action">
						<h5 class="pull-left no-margin col-md-4"><?php echo __( 'Sort results by' ,'trav' ) ?>:</h5>
						<input type="hidden" name="sort_by" value="created">
						<input type="hidden" name="order" value="desc">
						<button class="btn-small white gray-color" value="created"><?php echo __( 'DATE', 'trav' ) ?></button>
						<button class="btn-small white gray-color" value="total_price"><?php echo __( 'PRICE', 'trav' ) ?></button>
					</div>
				</form>
			</div>
			<div class="booking-history">
				<?php
					echo trav_acc_get_user_booking_list( $user_id, -1, 'created', 'desc' );
				?>
			</div>
		</div>
		<div id="wishlist" class="tab-pane fade">
			<h2><?php echo __( 'Your Wish List', 'trav' ); ?></h2>
			<div class="row image-box listing-style2 add-clearfix">
				<?php
				global $acc_list, $before_article, $after_article, $current_view;
				$acc_list = get_user_meta( $user_id, 'wishlist', true );
				if ( ! empty( $acc_list ) ) {
					$current_view = 'block';
					$before_article = '<div class="col-sm-6 col-md-4">';
					$after_article = '</div>';
					trav_get_template( 'accommodation-list.php', '/templates/accommodation/');
				} else {
					echo __( 'Your wishlist is empty.', 'trav' );
				}
				?>
			</div>
		</div>
		<div id="settings" class="tab-pane fade">
			<h2><?php echo __( 'Account Settings', 'trav' ); ?></h2>
			<h5 class="skin-color"><?php echo __( 'Change Your Password', 'trav' ); ?></h5>
			<!-- <div class="alert alert-success">Success Message. Your Message Comes Here<span class="close"></span></div> -->
			<form id="update_password_form" method="post">
				<div class="alert alert-error" style="display:none;" data-success="<?php echo __( 'Password successfully changed.', 'trav' ) ?>" data-mismatch="<?php echo __( 'Password mismatch.', 'trav' ) ?>" data-empty="<?php echo __( 'Password cannot be empty.', 'trav' ) ?>"><span class="message"></span><span class="close"></span></div>
				<input type="hidden" name="action" value="update_password">
				<?php wp_nonce_field( 'update_password' ); ?>
				<div class="row form-group">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<label><?php echo __( 'Old Password', 'trav' ); ?></label>
						<input name="old_pass" type="password" class="input-text full-width">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<label><?php echo __( 'Enter New Password', 'trav' ); ?></label>
						<input name="pass1" type="password" class="input-text full-width">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<label><?php echo __( 'Confirm New password', 'trav' ); ?></label>
						<input name="pass2" type="password" class="input-text full-width">
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn-medium"><?php echo __( 'UPDATE PASSWORD', 'trav' ); ?></button>
				</div>
			</form>
			<hr>
			<h5 class="skin-color"><?php echo __( 'Change Your Email', 'trav' ); ?></h5>
			<form id="update_email_form" method="post">
				<div class="alert alert-error" style="display:none;" data-success="<?php echo __( 'Email successfully changed.', 'trav' ) ?>" data-mismatch="<?php echo __( 'Email mismatch.', 'trav' ) ?>" data-empty="<?php echo __( 'Email cannot be empty.', 'trav' ) ?>"><span class="message"></span><span class="close"></span></div>
				<input type="hidden" name="action" value="update_email">
				<?php wp_nonce_field( 'update_email' ); ?>
				<div class="row form-group">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<label><?php echo __( 'Enter New Email', 'trav' ); ?></label>
						<input name="email1" type="email" class="input-text full-width">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<label><?php echo __( 'Confirm New Email', 'trav' ); ?></label>
						<input name="email2" type="email" class="input-text full-width">
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn-medium"><?php echo __( 'UPDATE EMAIL ADDRESS', 'trav' ); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
tjq = jQuery;
tjq(document).ready(function(){
	tjq('#update_password_form input').change(function(){
		tjq('#update_password_form .alert').hide();
	});
	tjq('#update_password_form').submit(function(){

		var pass1 = tjq('input[name="pass1"]').val();
		var pass2 = tjq('input[name="pass2"]').val();

		// validation
		if ( pass1 == '' || pass2 == '' ) {
			tjq('#update_password_form .alert').removeClass('alert-success');
			tjq('#update_password_form .alert').addClass('alert-error');
			tjq('#update_password_form .alert .message').text( tjq('#update_password_form .alert').data('empty') );
			tjq('#update_password_form .alert').show();
			return false;
		}

		if ( pass1 != pass2 ) {
			tjq('#update_password_form .alert').removeClass('alert-success');
			tjq('#update_password_form .alert').addClass('alert-error');
			tjq('#update_password_form .alert .message').text( tjq('#update_password_form .alert').data('mismatch') );
			tjq('#update_password_form .alert').show();
			return false;
		}

		update_data = tjq("#update_password_form").serialize();
		jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			data: update_data,
			success: function(response){
				if ( response.success == 1 ) {
					tjq('#update_password_form .alert').removeClass('alert-error');
					tjq('#update_password_form .alert').addClass('alert-success');
					tjq('#update_password_form .alert .message').text( tjq('#update_password_form .alert').data('success') );
					tjq('#update_password_form .alert').show();
				} else {
					tjq('#update_password_form .alert').removeClass('alert-success');
					tjq('#update_password_form .alert').addClass('alert-error');
					tjq('#update_password_form .alert .message').text( response.result );
					tjq('#update_password_form .alert').show();
				}
			}
		});
		return false;
	});

	tjq('#update_email_form input').change(function(){
		tjq('#update_email_form .alert').hide();
	});
	tjq('#update_email_form').submit(function(){

		var email1 = tjq('input[name="email1"]').val();
		var email2 = tjq('input[name="email2"]').val();

		// validation
		if ( email1 == '' || email2 == '' ) {
			tjq('#update_email_form .alert').removeClass('alert-success');
			tjq('#update_email_form .alert').addClass('alert-error');
			tjq('#update_email_form .alert .message').text( tjq('#update_email_form .alert').data('empty') );
			tjq('#update_email_form .alert').show();
			return false;
		}

		if ( email1 != email2 ) {
			tjq('#update_email_form .alert').removeClass('alert-success');
			tjq('#update_email_form .alert').addClass('alert-error');
			tjq('#update_email_form .alert .message').text( tjq('#update_email_form .alert').data('mismatch') );
			tjq('#update_email_form .alert').show();
			return false;
		}

		update_data = tjq("#update_email_form").serialize();
		jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			data: update_data,
			success: function(response){
				if ( response.success == 1 ) {
					tjq('#update_email_form .alert').removeClass('alert-error');
					tjq('#update_email_form .alert').addClass('alert-success');
					tjq('#update_email_form .alert .message').text( tjq('#update_email_form .alert').data('success') );
					tjq('#update_email_form .alert').show();
				} else {
					tjq('#update_email_form .alert').removeClass('alert-success');
					tjq('#update_email_form .alert').addClass('alert-error');
					tjq('#update_email_form .alert .message').text( response.result );
					tjq('#update_email_form .alert').show();
				}
			}
		});
		return false;
	});

	tjq("#profile .edit-profile-btn").click(function(e) {
		e.preventDefault();
		tjq(".view-profile").fadeOut();
		tjq(".edit-profile").fadeIn();
	});
	tjq("#profile .view-profile-btn").click(function(e) {
		e.preventDefault();
		tjq(".edit-profile").fadeOut();
		tjq(".view-profile").fadeIn();
	});

	tjq('a[href="#profile"]').on('shown.bs.tab', function (e) {
		tjq(".view-profile").show();
		tjq(".edit-profile").hide();
	});

	function readURL(input) {

		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				tjq('#photo_preview img').attr('src', e.target.result);
				tjq('#photo_preview').show();
			}
			reader.readAsDataURL(input.files[0]);
		} else {
			tjq('#photo_preview').hide();
		}
	}

	tjq('.edit-profile input[name="photo"]').change(function(){
		readURL(this);
	});

	var photo_upload = tjq('input[name="photo"]');
	tjq('#photo_preview .close').click(function(){
		photo_upload.replaceWith( photo_upload = photo_upload.clone( true ) );
		tjq('#photo_preview').hide();
		tjq('input[name="remove_photo"').val('1');
	});

	tjq('.booking-status-filter input[name="status"]').change(function(){
		update_booking_list();
	});

	tjq('.booking-status-filter button').click(function(e){
		e.preventDefault();
		if ( tjq(this).siblings('input[name="sort_by"]').val() == tjq(this).val() ) {
			if ( tjq(this).siblings('input[name="order"]').val() == 'desc' ) {
				tjq(this).siblings('input[name="order"]').val('asc');
			} else {
				tjq(this).siblings('input[name="order"]').val('desc');
			}
		} else {
			tjq(this).siblings('input[name="sort_by"]').val(tjq(this).val());
			tjq(this).siblings('input[name="order"]').val('desc');
		}
		update_booking_list();
		return false;
	});

	function update_booking_list(){
		jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			data: tjq('.booking-status-filter').serialize(),
			success: function(response){
				if ( response.success == 1 ) {
					tjq('.booking-history').html(response.result);
				} else {
					tjq('.booking-history').html(response.result);
				}
			}
		});
	}
});
</script>