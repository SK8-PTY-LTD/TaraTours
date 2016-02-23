<?php

if( !function_exists ('mi_enqueue_scripts') ) :
	function mi_enqueue_scripts() {
		//vendor
		$content_array1 = array( 'some_string1' => esc_attr(AfterSetupTheme::mi_return_theme_option('lat')), 'a_value' => '10' );
		$content_array2 = array( 'some_string2' => esc_attr(AfterSetupTheme::mi_return_theme_option('lng')), 'a_value' => '10' );
		wp_enqueue_script('migrate', get_template_directory_uri() . '/inc/js/jquery-migrate-1.2.1.js', array('jquery'), '1.0',true);
		wp_enqueue_script('modernizr', get_template_directory_uri() . '/inc/js/modernizr.custom.63321.js', array('jquery'), '1.0',true);
		wp_enqueue_script('flexslider', get_template_directory_uri() . '/inc/js/jquery.flexslider-min.js', array('jquery'), '1.0',true);
		wp_enqueue_script('catslider', get_template_directory_uri() . '/inc/js/jquery.catslider.js', array('jquery'), '1.0',true);
		wp_enqueue_script('datepicker', get_template_directory_uri() . '/inc/js/jquery.ui.datepicker.min.js', array('jquery'), '1.0',true);
		wp_enqueue_script('masonry', get_template_directory_uri() . '/inc/js/masonry.min.js', array('jquery'), '1.0',true);
		wp_enqueue_script('increase', get_template_directory_uri() . '/inc/js/increase-decrease-qty.js', array('jquery'), '1.0',true);
		wp_enqueue_script('mixitup', get_template_directory_uri() . '/inc/js/jquery.mixitup.min.js', array('jquery'), '1.0',true);
		wp_enqueue_script('googleapis', 'https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false', array('jquery'), '1.0',true);
		wp_enqueue_script('googlemapinf', get_template_directory_uri() . '/inc/js/google-map-infobox.js', array('jquery'), '1.0',true);
		wp_enqueue_script('fitmaps', get_template_directory_uri() . '/inc/js/jquery.fitmaps.js', array('jquery'), '1.0',true);
		wp_enqueue_script('chosen', get_template_directory_uri() . '/inc/js/chosen.jquery.js', array('jquery'), '1.0',true);
		wp_enqueue_script('screwdefaultbuttons', get_template_directory_uri() . '/inc/js/jquery.screwdefaultbuttonsV2.js', array('jquery'), '1.0',true);
		wp_enqueue_script('mousewheel', get_template_directory_uri() . '/inc/js/jquery.mousewheel.min.js', array('jquery'), '1.0',true);
		wp_enqueue_script('jQRangeSlider', get_template_directory_uri() . '/inc/js/jQRangeSlider-min.js', array('jquery'), '1.0',true);
		wp_enqueue_script('bootstrap', get_template_directory_uri() . '/inc/bootstrap/js/bootstrap.min.js', array('jquery'), '1.0',true);
		wp_enqueue_script('raty', get_template_directory_uri() . '/inc/js/jquery.raty.min.js', array('jquery'), '1.0',true);
		wp_enqueue_script('prettyPhoto', get_template_directory_uri() . '/inc/prettyphoto/js/jquery.prettyPhoto.js', array('jquery'), '1.0',true);
		wp_enqueue_script('carousel', get_template_directory_uri() . '/inc/owlcarousel2/owl.carousel.min.js', array('jquery'), '1.0',true);
		wp_enqueue_script('carouFredSel', get_template_directory_uri() . '/inc/js/jquery.carouFredSel-6.2.1-packed.js', array('jquery'), '1.0',true);
		wp_enqueue_script('sly', get_template_directory_uri() . '/inc/js/sly.min.js', array('jquery'), '1.0',true);
		wp_enqueue_script('custom', get_template_directory_uri() . '/inc/js/custom.js', array('jquery'), '1.0',true);
		wp_localize_script( 'custom', 'object_name1', $content_array1 );
		wp_localize_script( 'custom', 'object_name2', $content_array2 );
		//menu
		
		
}
	add_action('wp_enqueue_scripts', 'mi_enqueue_scripts');
endif;