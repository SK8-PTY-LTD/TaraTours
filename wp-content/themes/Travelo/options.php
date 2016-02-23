<?php

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = wp_get_theme();
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

function optionsframework_options() {

	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	$options = array();

	$options[] = array(
		'name' => __('Basic Settings', 'trav'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Logo', 'trav'),
		'desc' => __('Please choose an image file for your logo.', 'trav'),
		'id' => 'logo',
		"std" => TRAV_TEMPLATE_DIRECTORY_URI . "/images/logo.png",
		'type' => 'upload');

	$options[] = array(
		'name' => __('Favicon', 'trav'),
		'desc' => __('Please choose an 16x16 ico image for your favicon.', 'trav'),
		'id' => 'favicon',
		"std" => TRAV_TEMPLATE_DIRECTORY_URI . "/images/favicon.ico",
		'type' => 'upload');

	$options[] = array(
		'name' => __('Copyright Text', 'trav'),
		'desc' => __('You can change Copyright text in footer by this field', 'trav'),
		'id' => 'copyright',
		'std' => '2014 Travelo',
		'type' => 'text');

	$options[] = array(
		'name' => __('Welcome Text', 'trav'),
		'desc' => __('Welcome Text on log in and sign up page.', 'trav'),
		'id' => 'welcome_txt',
		'std' => 'Welcome to Travelo!',
		'type' => 'text');

	$options[] = array(
		'name' => __('E-Mail Address', 'trav'),
		'desc' => __('Leave blank to hide e-mail field', 'trav'),
		'id' => 'email',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Phone Number', 'trav'),
		'desc' => __('Leave blank to hide phone number', 'trav'),
		'id' => 'phone_no',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Styling Options', 'trav'),
		'type' => 'heading');

	$options[] = array(
		'name' => "Site Skin",
		'desc' => "Select a Site Skin",
		'id' => "skin",
		'std' => "style-light-blue",
		'type' => 'select',
		'options' => array(
			'style-light-blue' => 'light blue',
			'style-dark-blue' => 'dark blue',
			'style-sea-blue' => 'sea blue',
			'style-sky-blue' => 'sky blue',
			'style-dark-orange' => 'dark orange',
			'style-light-orange' => 'light orange',
			'style-light-yellow' => 'light yellow',
			'style-orange' => 'orange',
			'style-purple' => 'purple',
			'style-red' => 'red',
			 )
	);

	$options[] = array(
		'name' => "Header Style",
		'desc' => "",
		'id' => "header_style",
		'std' => "header",
		'type' => "images",
		'options' => array(
			'header'  => TRAV_IMAGE_URL . '/admin/h-def.jpg',
			'header1' => TRAV_IMAGE_URL . '/admin/h1.jpg',
			'header2' => TRAV_IMAGE_URL . '/admin/h2.jpg',
			'header3' => TRAV_IMAGE_URL . '/admin/h3.jpg',
			'header4' => TRAV_IMAGE_URL . '/admin/h4.jpg',
			'header5' => TRAV_IMAGE_URL . '/admin/h5.jpg',
			'header6' => TRAV_IMAGE_URL . '/admin/h6.jpg',
			'header7' => TRAV_IMAGE_URL . '/admin/h7.jpg')
	);

	$options[] = array(
		'name' => "Footer Skin",
		'desc' => "Select a Footer Skin",
		'id' => "footer_skin",
		'std' => "style-def",
		'type' => 'select',
		'options' => array(
			'style-def' => 'default',
			'style1' => 'skin 1',
			'style2' => 'skin 2',
			'style3' => 'skin 3',
			'style4' => 'skin 4',
			'style5' => 'skin 5',
			'style6' => 'skin 6' )
	);

	$options[] = array(
		'name' => __('Page Load Progress Bar', 'trav'),
		'desc' => __('Enable page load progress bar while page loading.', 'trav'),
		'id' => 'pace_loading',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Currency Settings', 'trav'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Default Currency', 'trav'),
		'desc' => __('Select default currency', 'trav'),
		'id' => 'def_currency',
		'std' => 'usd',
		'type' => 'select',
		'options' => trav_get_all_available_currencies() );

	$options[] = array(
		'name' => __('Available Currencies', 'trav'),
		'desc' => __('You can select currencies that this site support. You can manage currency list <a href="admin.php?page=currencies">here</a>.', 'trav'),
		'id' => 'site_currencies',
		'std' => trav_get_default_available_currencies(), // These items get checked by default
		'type' => 'multicheck',
		'options' => trav_get_all_available_currencies() );

	$options[] = array(
		'name' => "Currency Symbol Position",
		'desc' => __( "Select a Curency Symbol Position for Frontend.", 'trav' ),
		'id' => "cs_pos",
		'std' => "before",
		'type' => 'radio',
		'options' => array(
			'before' => __( 'Insert currency symbol before price.', 'trav' ),
			'after' => __( 'Insert currency symbol after price.', 'trav' ),
			 )
	);

	$options[] = array(
		'name' => "Decimal Precision",
		'desc' => "Please choose desimal precision.",
		'id' => "desimal_prec",
		'std' => "2",
		'type' => 'select',
		'options' => array(
			'0' => '0',
			'1' => '1',
			'2' => '2',
			'3' => '3',
			 )
	);

	$options[] = array(
		'name' => "Currency Display Format",
		'desc' => "Please choose currency display format.",
		'id' => "currency_format",
		'std' => "before",
		'type' => 'select',
		'options' => array(
			'nodelimit-point' => '####.##',
			'nodelimit-comma' => '####,##',
			'cdelimit-point' => '#,###.##',
			'pdelimit-comma' => '#.###,##',
			'cbdelimit-point' => "#, ###.##",
			'bdelimit-point' => '# ###.##',
			'bdelimit-comma' => '# ###,##',
			'qdelimit-point' => "#'###.##",
			 )
	);

	$options[] = array(
		'name' => __('Main Page Settings', 'trav'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Dashboard Page', 'trav'),
		'desc' => __('User Dashboard Page.', 'trav'),
		'id' => 'dashboard_page',
		'type' => 'select',
		'options' => $options_pages);

	$options[] = array(
		'name' => __('Login Page', 'trav'),
		'desc' => __('You can leave this field blank if you don\'t need Custom Login Page', 'trav'),
		'id' => 'login_page',
		'type' => 'select',
		'options' => $options_pages);

	$options[] = array(
		'name' => __('Modal Login/Sign Up', 'trav'),
		'desc' => __('Enable modal login and modal signup.', 'trav'),
		'id' => 'modal_login',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Page to Redirect to on login', 'trav'),
		'desc' => __('Select a Page to Redirect to on login.', 'trav'),
		'id' => 'redirect_page',
		'type' => 'select',
		'options' => $options_pages);

	$options[] = array(
		'name' => __('Ajax Pagination', 'trav'),
		'desc' => __('Enable ajax pagination.', 'trav'),
		'id' => 'ajax_pagination',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Date Format', 'trav'),
		'desc' => __('Please select a date format for datepicker.', 'trav'),
		'id' => 'date_format',
		'std' => 'mm/dd/yy',
		'type' => 'select',
		'options' => array(
			'mm/dd/yy' => 'mm/dd/yy',
			'dd/mm/yy' => 'dd/mm/yy',
			'yy-mm-dd' => 'yy-mm-dd',
			)
		);

	$options[] = array(
		'name' => __('Accommodation', 'trav'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Disable Accommodation', 'trav'),
		'desc' => __('Disable accommodation feature.', 'trav'),
		'id' => 'disable_acc',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Accommodation Booking Page', 'trav'),
		'desc' => __('This sets the base page of your accommodation booking.', 'trav'),
		'id' => 'acc_booking_page',
		'type' => 'select',
		'options' => $options_pages);

	$options[] = array(
		'name' => __('Accommodation Booking Confirmation Page', 'trav'),
		'desc' => __('This sets the accommodation booking confirmation page.', 'trav'),
		'id' => 'acc_booking_confirmation_page',
		'type' => 'select',
		'options' => $options_pages);


	$options[] = array(
		'name' => __('Terms & Conditions Page', 'trav'),
		'desc' => __('Booking Terms and Conditions Page.', 'trav'),
		'id' => 'terms_page',
		'type' => 'select',
		'options' => $options_pages);

	$options[] = array(
		'name' => "Accommodations per page",
		'desc' => "Select a number of accommodations to show on Search Accommodation Result Page",
		'id' => "acc_posts",
		'std' => "12",
		'type' => 'number'
	);

	$options[] = array(
		'name' => "Price Filter Max Value",
		'desc' => "Set a max price value for price search filter",
		'id' => "acc_price_filter_max",
		'std' => "200",
		'type' => 'number'
	);

	$options[] = array(
		'name' => "Price Filter Step",
		'desc' => "Set a price step value for price search filter",
		'id' => "acc_price_filter_step",
		'std' => "50",
		'type' => 'number'
	);

	$options[] = array(
		'name' => __('Captcha validation on booking', 'trav'),
		'desc' => __('Use captcha validation while booking.', 'trav'),
		'id' => 'vld_captcha',
		'std' => '1',
		'type' => 'checkbox');

	/*
	$options[] = array(
		'name' => __('Credit Card Validation', 'trav'),
		'desc' => __('Use credit card validation form on booking.', 'trav'),
		'id' => 'vld_credit_card',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Credit Card Offline Charge', 'trav'),
		'desc' => __('Use credit card offline charge. You need to enable above credit card validation option. And credit card information will be saved with booking data if you enable this.', 'trav'),
		'id' => 'cc_off_charge',
		'std' => '0',
		'type' => 'checkbox');
	$options[] = array(
		'name' => __('Accommodation Mail', 'trav'),
		'type' => 'heading');*/

	$options[] = array(
		'name' => __('Enable Icalendar', 'trav'),
		'desc' => __('Send icalendar with booking confirmation email.', 'trav'),
		'id' => 'acc_confirm_email_ical',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Booking Confirmation Email Subject', 'trav'),
		'desc' => __( 'Accommodation booking confirmation email subject.', 'trav' ),
		'id' => 'acc_confirm_email_subject',
		'std' => 'Your booking at [accommodation_name]',
		'type' => 'text');

	$options[] = array(
		'name' => __('Booking Confirmation Email Description', 'trav'),
		'desc' => __( 'Accommodation booking confirmation email description.', 'trav' ),
		'id' => 'acc_confirm_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="width:580px;margin:0;padding:0;background:#ffffff;font-family:arial"><tbody>
	<tr>
		<td style="padding-top:20px;padding-left:10px;padding-right:10px" valign="top">
			<a href="[home_url]" title="[site_name]" target="_blank">
				<img alt="[site_name]" src="[logo_url]" style="outline:none;text-decoration:none;border:none" width="160" class="CToWUd">
			</a>
		</td>
	</tr>
	<tr>
		<td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
			<div style="font-size:18px;line-height:22px;font-family:arial;color:#003580;font-weight:bold" valign="top">Thanks, [customer_first_name] [customer_last_name]! Your reservation is now confirmed.</div>
		</td>
	</tr>
	<tr>
		<td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
			<div style="text-align:left;font-size:16px;line-height:21px;font-family:arial;color:#333;vertical-align:middle" valign="middle"><a href="[accommodation_url]" title="Hotel info" target="_blank"><b>[accommodation_name]</b></a></div>
		</td>
	</tr>
	<tr>
		<td style="padding-top:5px;text-align:left" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px"><tbody>
				<tr>
					<td style="text-align:left" valign="top" width="100">[accommodation_thumbnail]</td>
					<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-left:20px;" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
							<tr>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Country:</b></td>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_country]</span></td>
							</tr>
							<tr>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>City:</b></td>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_city]</span></td>
							</tr>
							<tr>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Address:</b></td>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_address]</span></td>
							</tr>
							<tr>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Phone:</b></td>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_phone]</span></td>
							</tr>
						</tbody></table>
					</td>
				</tr>
			</tbody></table>
		</td>
	</tr>
</tbody></table>

<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Your Reservation</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_nights] nights, [booking_rooms] rooms</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-In</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkin_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-Out</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkout_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]   <a href="mailto:[customer_email]" target="_blank">[customer_email]</a><br /></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>[accommodation_room_name]</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_room_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>VAT included</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_tax]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>
<div>You can update or cancel booking <a href="[booking_update_url]">here.</a></div>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Update Booking Email Subject', 'trav'),
		'desc' => __( 'Accommodation update booking email subject.', 'trav' ),
		'id' => 'acc_update_email_subject',
		'std' => 'Your booking at [accommodation_name] is now updated.',
		'type' => 'text');

	$options[] = array(
		'name' => __('Update Booking Email Description', 'trav'),
		'desc' => __( 'Accommodation update booking email description.', 'trav' ),
		'id' => 'acc_update_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="width:580px;margin:0;padding:0;background:#ffffff;font-family:arial"><tbody>
    <tr>
        <td style="padding-top:20px;padding-left:10px;padding-right:10px" valign="top">
            <a href="[home_url]" title="[site_name]" target="_blank">
                <img alt="[site_name]" src="[logo_url]" style="outline:none;text-decoration:none;border:none" width="160" class="CToWUd">
            </a>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
            <div style="font-size:18px;line-height:22px;font-family:arial;color:#003580;font-weight:bold" valign="top">Your reservation at [accommodation_name] is now updated.</div>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
            <div style="text-align:left;font-size:16px;line-height:21px;font-family:arial;color:#333;vertical-align:middle" valign="middle"><a href="[accommodation_url]" title="Hotel info" target="_blank"><b>[accommodation_name]</b></a></div>
        </td>
    </tr>
    <tr>
        <td style="padding-top:5px;text-align:left" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px"><tbody>
                <tr>
                    <td style="text-align:left" valign="top" width="100">[accommodation_thumbnail]</td>
                    <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-left:20px;" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" style="padding-left: 20px;margin:0px;padding:0px;border:0px" width="100%"><tbody>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Country:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_country]</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>City:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_city]</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Address:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_address]</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Phone:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_phone]</span></td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</tbody></table>
<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Your Reservation</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_nights] nights, [booking_rooms] rooms</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-In</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkin_time]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-Out</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkout_time]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]   <a href="mailto:[customer_email]" target="_blank">[customer_email]</a><br /></td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>[accommodation_room_name]</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_room_price]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>VAT included</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_tax]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
    </tr>
</tbody></table>
<div>You can update or cancel booking <a href="[booking_update_url]">here.</a></div>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Cancel Booking Email Subject', 'trav'),
		'desc' => __( 'Accommodation cancel booking email subject.', 'trav' ),
		'id' => 'acc_cancel_email_subject',
		'std' => 'Your booking at [accommodation_name] is now canceled.',
		'type' => 'text');

	$options[] = array(
		'name' => __('Cancel Booking Email Description', 'trav'),
		'desc' => __( 'Accommodation cancel booking email description.', 'trav' ),
		'id' => 'acc_cancel_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="width:580px;margin:0;padding:0;background:#ffffff;font-family:arial"><tbody>
    <tr>
        <td style="padding-top:20px;padding-left:10px;padding-right:10px" valign="top">
            <a href="[home_url]" title="[site_name]" target="_blank">
                <img alt="[site_name]" src="[logo_url]" style="outline:none;text-decoration:none;border:none" width="160" class="CToWUd">
            </a>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
            <div style="font-size:18px;line-height:22px;font-family:arial;color:#003580;font-weight:bold" valign="top">Your reservation at [accommodation_name] is now canceled.</div>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
            <div style="text-align:left;font-size:16px;line-height:21px;font-family:arial;color:#333;vertical-align:middle" valign="middle"><a href="[accommodation_url]" title="Hotel info" target="_blank"><b>[accommodation_name]</b></a></div>
        </td>
    </tr>
    <tr>
        <td style="padding-top:5px;text-align:left" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px"><tbody>
                <tr>
                    <td style="text-align:left" valign="top" width="100">[accommodation_thumbnail]</td>
                    <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-left:20px;" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" style="padding-left: 20px;margin:0px;padding:0px;border:0px" width="100%"><tbody>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Country:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_country]</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>City:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_city]</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Address:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_address]</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Phone:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[accommodation_phone]</span></td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</tbody></table>
<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Your Reservation</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_nights] nights, [booking_rooms] rooms</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-In</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkin_time]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-Out</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkout_time]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]   <a href="mailto:[customer_email]" target="_blank">[customer_email]</a><br /></td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>[accommodation_room_name]</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_room_price]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>VAT included</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_tax]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
    </tr>
</tbody></table>
<div>You can update or cancel booking <a href="[booking_update_url]">here.</a></div>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Administrator Notification', 'trav'),
		'desc' => __('enable individual booked email notification to site administrator.', 'trav'),
		'id' => 'acc_booked_notify_admin',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Administrator Booking Notification Email Subject', 'trav'),
		'desc' => __( 'Administrator Notification Email Subject for Accommodation Booking.', 'trav' ),
		'id' => 'acc_admin_email_subject',
		'std' => 'Received a booking at [accommodation_name]',
		'type' => 'text');

	$options[] = array(
		'name' => __('Administrator Booking Notification Email Description', 'trav'),
		'desc' => __( 'Administrator Notification Email Description for Accommodation Booking.', 'trav' ),
		'id' => 'acc_admin_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Accommodation</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><a href="[accommodation_url]" title="Hotel info" target="_blank"><b>[accommodation_name]</b></a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Rooms and Nights</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_nights] nights, [booking_rooms] rooms</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-in</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkin_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-out</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkout_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]  <a href="mailto:[customer_email]" target="_blank">[customer_email]</a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Phone</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_phone]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Address</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_address]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>City</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_city]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Zip</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_zip]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Country</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_country]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Special Requirements</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_special_requirements]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>[accommodation_room_name]</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_room_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>VAT included</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_tax]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Administrator Booking Update Notification Email Subject', 'trav'),
		'desc' => __( 'Administrator notification email subject for accommodation booking update.', 'trav' ),
		'id' => 'acc_update_admin_email_subject',
		'std' => 'A booking at [accommodation_name] is updated.',
		'type' => 'text');

	$options[] = array(
		'name' => __('Administrator Booking Update Notification Email Description', 'trav'),
		'desc' => __( 'Administrator notification email description for accommodation booking update.', 'trav' ),
		'id' => 'acc_update_admin_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Accommodation</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><a href="[accommodation_url]" title="Hotel info" target="_blank"><b>[accommodation_name]</b></a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Rooms and Nights</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_nights] nights, [booking_rooms] rooms</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-in</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkin_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-out</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkout_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]  <a href="mailto:[customer_email]" target="_blank">[customer_email]</a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Phone</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_phone]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Address</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_address]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>City</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_city]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Zip</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_zip]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Country</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_country]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Special Requirements</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_special_requirements]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>[accommodation_room_name]</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_room_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>VAT included</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_tax]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Administrator Booking Cancel Notification Email Subject', 'trav'),
		'desc' => __( 'Administrator notification email subject for accommodation booking cancel.', 'trav' ),
		'id' => 'acc_cancel_admin_email_subject',
		'std' => 'A booking at [accommodation_name] is canceled.',
		'type' => 'text');

	$options[] = array(
		'name' => __('Administrator Booking Cancel Notification Email Description', 'trav'),
		'desc' => __( 'Administrator notification email description for accommodation booking cancel.', 'trav' ),
		'id' => 'acc_cancel_admin_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Accommodation</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><a href="[accommodation_url]" title="Hotel info" target="_blank"><b>[accommodation_name]</b></a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Rooms and Nights</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_nights] nights, [booking_rooms] rooms</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-in</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkin_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-out</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkout_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]  <a href="mailto:[customer_email]" target="_blank">[customer_email]</a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Phone</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_phone]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Address</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_address]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>City</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_city]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Zip</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_zip]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Country</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_country]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Special Requirements</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_special_requirements]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>[accommodation_room_name]</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_room_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>VAT included</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_tax]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Accommodation Owner Notification', 'trav'),
		'desc' => __('enable individual booked email notification to accommodation owner.', 'trav'),
		'id' => 'acc_booked_notify_bowner',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Accommodation Owner Notification Email Subject', 'trav'),
		'desc' => __( 'Accommodation Owner Notification Email Subject for Accommodation Booking.', 'trav' ),
		'id' => 'acc_bowner_email_subject',
		'std' => 'Received a booking at [accommodation_name]',
		'type' => 'text');

	$options[] = array(
		'name' => __('Accommodation Owner Notification Email Description', 'trav'),
		'desc' => __( 'Accommodation Owner Notification Email Description for Accommodation Booking.', 'trav' ),
		'id' => 'acc_bowner_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Accommodation</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><a href="[accommodation_url]" title="Hotel info" target="_blank"><b>[accommodation_name]</b></a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Rooms and Nights</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_nights] nights, [booking_rooms] rooms</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-in</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkin_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-out</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkout_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name] <a href="mailto:[customer_email]" target="_blank">[customer_email]</a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Phone</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_phone]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Address</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_address]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>City</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_city]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Zip</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_zip]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Country</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_country]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Special Requirements</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_special_requirements]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>[accommodation_room_name]</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_room_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>VAT included</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_tax]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Accommodation Owner Booking Update Notification Email Subject', 'trav'),
		'desc' => __( 'Accommodation Owner notification email subject for accommodation booking update.', 'trav' ),
		'id' => 'acc_update_bowner_email_subject',
		'std' => 'A booking at [accommodation_name] is updated.',
		'type' => 'text');

	$options[] = array(
		'name' => __('Accommodation Owner Booking Update Notification Email Description', 'trav'),
		'desc' => __( 'Accommodation Owner notification email description for accommodation booking update.', 'trav' ),
		'id' => 'acc_update_bowner_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Accommodation</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><a href="[accommodation_url]" title="Hotel info" target="_blank"><b>[accommodation_name]</b></a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Rooms and Nights</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_nights] nights, [booking_rooms] rooms</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-in</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkin_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-out</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkout_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]  <a href="mailto:[customer_email]" target="_blank">[customer_email]</a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Phone</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_phone]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Address</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_address]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>City</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_city]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Zip</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_zip]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Country</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_country]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Special Requirements</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_special_requirements]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>[accommodation_room_name]</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_room_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>VAT included</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_tax]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Accommodation Owner Booking Cancel Notification Email Subject', 'trav'),
		'desc' => __( 'Accommodation Owner notification email subject for accommodation booking cancel.', 'trav' ),
		'id' => 'acc_cancel_bowner_email_subject',
		'std' => 'A booking at [accommodation_name] is canceled.',
		'type' => 'text');

	$options[] = array(
		'name' => __('Accommodation Owner Booking Cancel Notification Email Description', 'trav'),
		'desc' => __( 'Accommodation Owner notification email description for accommodation booking cancel.', 'trav' ),
		'id' => 'acc_cancel_bowner_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Accommodation</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><a href="[accommodation_url]" title="Hotel info" target="_blank"><b>[accommodation_name]</b></a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Rooms and Nights</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_nights] nights, [booking_rooms] rooms</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-in</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkin_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Check-out</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_checkout_time]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]  <a href="mailto:[customer_email]" target="_blank">[customer_email]</a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Phone</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_phone]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Address</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_address]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>City</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_city]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Zip</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_zip]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Country</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_country]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Special Requirements</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_special_requirements]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>[accommodation_room_name]</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_room_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>VAT included</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_tax]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Tour Packages', 'trav'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Disable Tour Packages', 'trav'),
		'desc' => __('Disable tour packages feature.', 'trav'),
		'id' => 'disable_tour',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Tour Booking Page', 'trav'),
		'desc' => __('This sets the base page of your tour booking.', 'trav'),
		'id' => 'tour_booking_page',
		'type' => 'select',
		'options' => $options_pages);

	$options[] = array(
		'name' => __('Tour Booking Confirmation Page', 'trav'),
		'desc' => __('This sets the tour booking confirmation page.', 'trav'),
		'id' => 'tour_booking_confirmation_page',
		'type' => 'select',
		'options' => $options_pages);


	$options[] = array(
		'name' => __('Terms & Conditions Page', 'trav'),
		'desc' => __('Booking Terms and Conditions Page.', 'trav'),
		'id' => 'tour_terms_page',
		'type' => 'select',
		'options' => $options_pages);

	$options[] = array(
		'name' => "Tours per page",
		'desc' => "Select a number of tours to show on Search Tour Result Page",
		'id' => "tour_posts",
		'std' => "12",
		'type' => 'number'
	);

	$options[] = array(
		'name' => "Price Filter Max Value",
		'desc' => "Set a max price value for price search filter",
		'id' => "tour_price_filter_max",
		'std' => "200",
		'type' => 'number'
	);

	$options[] = array(
		'name' => "Price Filter Step",
		'desc' => "Set a price step value for price search filter",
		'id' => "tour_price_filter_step",
		'std' => "50",
		'type' => 'number'
	);

	$options[] = array(
		'name' => __('Captcha validation on booking', 'trav'),
		'desc' => __('Use captcha validation while booking.', 'trav'),
		'id' => 'tour_vld_captcha',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Booking Confirmation Email Subject', 'trav'),
		'desc' => __( 'Tour booking confirmation email subject.', 'trav' ),
		'id' => 'tour_confirm_email_subject',
		'std' => 'Your booking at [tour_name]',
		'type' => 'text');

	$options[] = array(
		'name' => __('Booking Confirmation Email Description', 'trav'),
		'desc' => __( 'Tour booking confirmation email description.', 'trav' ),
		'id' => 'tour_confirm_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="width:580px;margin:0;padding:0;background:#ffffff;font-family:arial"><tbody>
	<tr>
		<td style="padding-top:20px;padding-left:10px;padding-right:10px" valign="top">
			<a href="[home_url]" title="[site_name]" target="_blank">
				<img alt="[site_name]" src="[logo_url]" style="outline:none;text-decoration:none;border:none" width="160" class="CToWUd">
			</a>
		</td>
	</tr>
	<tr>
		<td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
			<div style="font-size:18px;line-height:22px;font-family:arial;color:#003580;font-weight:bold" valign="top">Thanks, [customer_first_name] [customer_last_name]! Your reservation is now confirmed.</div>
		</td>
	</tr>
	<tr>
		<td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
			<div style="text-align:left;font-size:16px;line-height:21px;font-family:arial;color:#333;vertical-align:middle" valign="middle"><a href="[tour_url]" title="Tour info" target="_blank"><b>[tour_name], [tour_st_title]</b></a></div>
		</td>
	</tr>
	<tr>
		<td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
			<div style="text-align:left;font-size:16px;line-height:21px;font-family:arial;color:#333;vertical-align:middle" valign="middle">[tour_date] [tour_st_time], [tour_duration] </div>
		</td>
	</tr>
	<tr>
		<td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
			<div style="text-align:left;font-size:16px;line-height:21px;font-family:arial;color:#333;vertical-align:middle" valign="middle">[tour_st_description]</div>
		</td>
	</tr>
	<tr>
		<td style="padding-top:5px;text-align:left" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px"><tbody>
				<tr>
					<td style="text-align:left" valign="top" width="100">[tour_thumbnail]</td>
					<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-left:20px;" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
							<tr>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Country:</b></td>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[tour_country]</span></td>
							</tr>
							<tr>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>City:</b></td>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[tour_city]</span></td>
							</tr>
							<tr>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Address:</b></td>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[tour_address]</span></td>
							</tr>
							<tr>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Phone:</b></td>
								<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[tour_phone]</span></td>
							</tr>
						</tbody></table>
					</td>
				</tr>
			</tbody></table>
		</td>
	</tr>
</tbody></table>

<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]   <a href="mailto:[customer_email]" target="_blank">[customer_email]</a><br /></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>
<div>You can cancel booking <a href="[booking_update_url]">here.</a></div>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Cancel Booking Email Subject', 'trav'),
		'desc' => __( 'Tour cancel booking email subject.', 'trav' ),
		'id' => 'tour_cancel_email_subject',
		'std' => 'Your booking at [tour_name] is now canceled.',
		'type' => 'text');

	$options[] = array(
		'name' => __('Cancel Booking Email Description', 'trav'),
		'desc' => __( 'Tour cancel booking email description.', 'trav' ),
		'id' => 'tour_cancel_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="width:580px;margin:0;padding:0;background:#ffffff;font-family:arial"><tbody>
    <tr>
        <td style="padding-top:20px;padding-left:10px;padding-right:10px" valign="top">
            <a href="[home_url]" title="[site_name]" target="_blank">
                <img alt="[site_name]" src="[logo_url]" style="outline:none;text-decoration:none;border:none" width="160" class="CToWUd">
            </a>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
            <div style="font-size:18px;line-height:22px;font-family:arial;color:#003580;font-weight:bold" valign="top">Your reservation at [tour_name] is now canceled.</div>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px;padding-bottom:20px;padding-left:10px;padding-right:10px" valign="top">
            <div style="text-align:left;font-size:16px;line-height:21px;font-family:arial;color:#333;vertical-align:middle" valign="middle"><a href="[tour_url]" title="Tour info" target="_blank"><b>[tour_name]</b></a></div>
        </td>
    </tr>
    <tr>
        <td style="padding-top:5px;text-align:left" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px"><tbody>
                <tr>
                    <td style="text-align:left" valign="top" width="100">[tour_thumbnail]</td>
                    <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-left:20px;" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" style="padding-left: 20px;margin:0px;padding:0px;border:0px" width="100%"><tbody>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Country:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[tour_country]</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>City:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[tour_city]</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Address:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[tour_address]</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;padding-right:10px" valign="top"><b>Phone:</b></td>
                                <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px" valign="top"><span>[tour_phone]</span></td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</tbody></table>
<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]   <a href="mailto:[customer_email]" target="_blank">[customer_email]</a><br /></td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
    </tr>
    <tr>
        <td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
        <td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
    </tr>
</tbody></table>
<div>You can update or cancel booking <a href="[booking_update_url]">here.</a></div>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Administrator Notification', 'trav'),
		'desc' => __('enable individual booked email notification to site administrator.', 'trav'),
		'id' => 'tour_booked_notify_admin',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Administrator Booking Notification Email Subject', 'trav'),
		'desc' => __( 'Administrator Notification Email Subject for Tour Booking.', 'trav' ),
		'id' => 'tour_admin_email_subject',
		'std' => 'Received a booking at [tour_name]',
		'type' => 'text');

	$options[] = array(
		'name' => __('Administrator Booking Notification Email Description', 'trav'),
		'desc' => __( 'Administrator Notification Email Description for Tour Booking.', 'trav' ),
		'id' => 'tour_admin_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Tour</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><a href="[tour_url]" title="Tour info" target="_blank"><b>[tour_name]</b></a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]  <a href="mailto:[customer_email]" target="_blank">[customer_email]</a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Phone</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_phone]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Address</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_address]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>City</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_city]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Zip</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_zip]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Country</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_country]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Special Requirements</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_special_requirements]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Administrator Booking Cancel Notification Email Subject', 'trav'),
		'desc' => __( 'Administrator notification email subject for tour booking cancel.', 'trav' ),
		'id' => 'tour_cancel_admin_email_subject',
		'std' => 'A booking at [tour_name] is canceled.',
		'type' => 'text');

	$options[] = array(
		'name' => __('Administrator Booking Cancel Notification Email Description', 'trav'),
		'desc' => __( 'Administrator notification email description for tour booking cancel.', 'trav' ),
		'id' => 'tour_cancel_admin_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Tour</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><a href="[tour_url]" title="Tour info" target="_blank"><b>[tour_name]</b></a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]  <a href="mailto:[customer_email]" target="_blank">[customer_email]</a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Phone</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_phone]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Address</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_address]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>City</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_city]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Zip</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_zip]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Country</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_country]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Special Requirements</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_special_requirements]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Tour Owner Notification', 'trav'),
		'desc' => __('enable individual booked email notification to tour owner.', 'trav'),
		'id' => 'tour_booked_notify_bowner',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Tour Owner Notification Email Subject', 'trav'),
		'desc' => __( 'Tour Owner Notification Email Subject for Tour Booking.', 'trav' ),
		'id' => 'tour_bowner_email_subject',
		'std' => 'Received a booking at [tour_name]',
		'type' => 'text');

	$options[] = array(
		'name' => __('Tour Owner Notification Email Description', 'trav'),
		'desc' => __( 'Tour Owner Notification Email Description for Tour Booking.', 'trav' ),
		'id' => 'tour_bowner_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Tour</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><a href="[tour_url]" title="Tour info" target="_blank"><b>[tour_name]</b></a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name] <a href="mailto:[customer_email]" target="_blank">[customer_email]</a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Phone</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_phone]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Address</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_address]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>City</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_city]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Zip</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_zip]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Country</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_country]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Special Requirements</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_special_requirements]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Tour Owner Booking Cancel Notification Email Subject', 'trav'),
		'desc' => __( 'Tour Owner notification email subject for tour booking cancel.', 'trav' ),
		'id' => 'tour_cancel_bowner_email_subject',
		'std' => 'A booking at [tour_name] is canceled.',
		'type' => 'text');

	$options[] = array(
		'name' => __('Tour Owner Booking Cancel Notification Email Description', 'trav'),
		'desc' => __( 'Tour Owner notification email description for tour booking cancel.', 'trav' ),
		'id' => 'tour_cancel_bowner_email_description',
		'std' => '<table border="0" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;border:0px" width="100%"><tbody>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Tour</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><a href="[tour_url]" title="Tour info" target="_blank"><b>[tour_name]</b></a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booked by</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_first_name] [customer_last_name]  <a href="mailto:[customer_email]" target="_blank">[customer_email]</a></td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Booking Number</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_no]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Pin Code</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_pincode]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Phone</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_phone]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Address</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_address]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>City</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_city]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Zip</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_zip]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Country</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_country]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Special Requirements</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[customer_special_requirements]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b style="font-size:18px;">Total Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_total_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Price</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_price]</td>
	</tr>
	<tr>
		<td style="text-align:left;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top"><b>Deposit Paid</b></td>
		<td style="text-align:right;font-family:arial;color:#333;font-size:12px;line-height:17px;border-bottom:1px dotted #aaaaaa;padding-top:5px;padding-bottom:5px" valign="top">[booking_deposit_paid]</td>
	</tr>
</tbody></table>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Payment Integration', 'trav'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('PayPal Integration', 'trav'),
		'desc' => __('Enable payment through PayPal in booking step.', 'trav'),
		'id' => 'acc_pay_paypal',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Sandbox Mode', 'trav'),
		'desc' => __('Enable PayPal sandbox for testing.', 'trav'),
		'id' => 'acc_pay_paypal_sandbox',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('PayPal API Username', 'trav'),
		'desc' => __('Your PayPal Account API Username.', 'trav'),
		'id' => 'acc_pay_paypal_api_username',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('PayPal API Password', 'trav'),
		'desc' => __('Your PayPal Account API Password.', 'trav'),
		'id' => 'acc_pay_paypal_api_password',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('PayPal API Signature', 'trav'),
		'desc' => __('Your PayPal Account API Signature.', 'trav'),
		'id' => 'acc_pay_paypal_api_signature',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Social Sharing Links', 'trav'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Facebook', 'trav'),
		'desc' => __( 'Insert your custom link to show the Facebook icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'facebook',
		'type' => 'text');

	$options[] = array(
		'name' => __('Twitter', 'trav'),
		'desc' => __( 'Insert your custom link to show the Twitter icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'twitter',
		'type' => 'text');

	$options[] = array(
		'name' => __('Google+', 'trav'),
		'desc' => __( 'Insert your custom link to show the Google+ icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'googleplus',
		'type' => 'text');

	$options[] = array(
		'name' => __('LinkedIn', 'trav'),
		'desc' => __( 'Insert your custom link to show the LinkedIn icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'linkedin',
		'type' => 'text');

	$options[] = array(
		'name' => __('YouTube', 'trav'),
		'desc' => __( 'Insert your custom link to show the YouTube icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'youtube',
		'type' => 'text');

	$options[] = array(
		'name' => __('Vimeo', 'trav'),
		'desc' => __( 'Insert your custom link to show the Vimeo icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'vimeo',
		'type' => 'text');

	$options[] = array(
		'name' => __('Pinterest', 'trav'),
		'desc' => __( 'Insert your custom link to show the Pinterest icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'pinterest',
		'type' => 'text');

	$options[] = array(
		'name' => __('Skype', 'trav'),
		'desc' => __( 'Insert your custom link to show the Skype icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'skype',
		'type' => 'text');

	$options[] = array(
		'name' => __('Instagram', 'trav'),
		'desc' => __( 'Insert your custom link to show the Instagram icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'instagram',
		'type' => 'text');

	$options[] = array(
		'name' => __('Dribbble', 'trav'),
		'desc' => __( 'Insert your custom link to show the Dribbble icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'dribble',
		'type' => 'text');

	$options[] = array(
		'name' => __('Flickr', 'trav'),
		'desc' => __( 'Insert your custom link to show the Flickr icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'flickr',
		'type' => 'text');

	$options[] = array(
		'name' => __('Tumblr', 'trav'),
		'desc' => __( 'Insert your custom link to show the Tumblr icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'tumblr',
		'type' => 'text');

	$options[] = array(
		'name' => __('Behance', 'trav'),
		'desc' => __( 'Insert your custom link to show the Behance icon. Leave blank to hide icon.', 'trav' ),
		'id' => 'behance',
		'type' => 'text');

	return $options;
}