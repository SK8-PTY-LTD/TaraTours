<?php

class AfterSetupTheme{
	
	static function mi_custom_sidebar(){
		register_sidebar(array(
			'name' => 'Blog Sidebar',
			'id'            => 'blog_sidebar',
			'description'   => 'This area for All Blog Widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'));
		register_sidebar(array(
			'name' => 'woocommerce Sidebar',
			'id'            => 'woocommerce_sidebar',
			'description'   => 'This area for All woocommerce Widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'));
		  register_sidebar(array(
		  	'name' => 'Footer Area 1',
		  	'id'            => 'footer_area_1_sidebar',
		  	'description'   => 'This area for 1st 6 colmn footer area',
		  	'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		  	'after_widget'  => '</div>',
		  	'before_title'  => '<h3 class="widget-title">',
		  	'after_title'   => '</h3>'));
		  register_sidebar(array(
		  	'name' => 'Footer Area 2',
		  	'id'            => 'footer_area_2_sidebar',
		  	'description'   => 'This area for 3 colmn footer area',
		  	'before_widget' => '<div id="%1$s" class="newsletter-holder %2$s">',
		  	'after_widget'  => '</div>',
		  	'before_title'  => '<h3>',
		  	'after_title'   => '</h3>'));
		  register_sidebar(array('name' => 'Footer Area 3',
		  	'id'            => 'footer_area_3_sidebar',
		  	'description'   => 'This area for 3 colmn footer area',
		  	'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		  	'after_widget'  => '</div>',
		  	'before_title'  => '<h3 class="widget-title">',
		  	'after_title'   => '</h3>'));
		  	  	 
			}
	
	static function mi_add_theme_support(){
	
		add_theme_support( 'post-formats', array(
				'image', 'video', 'audio'
		) );
	
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-header' );
		add_theme_support( 'custom-background' );
		add_theme_support( 'woocommerce' );
		add_theme_support('post-thumbnails');
    	add_theme_support('title-tag');
		add_editor_style();
		add_image_size( 'work_thumb', 337, 225, true );
		add_theme_support( 'automatic-feed-links' );
		add_image_size( 'work_popup', 800, 640, true );
		add_image_size( 'work_details', 720, 404, true );
 		add_image_size( 'work_related', 150, 150, true );
 		add_image_size( 'why_us_square', 530, 530, true );
 		add_image_size( 'team_image', 223, 265, true );
 		add_image_size( 'blog_front', 340, 227, true );
 		add_image_size( 'blogs', 720, 405, true );
 		add_image_size( 'blog_circle', 75, 75, true );
 		add_image_size( 'page_title_bg', 1600, 210, true );
 		if ( ! isset( $content_width ) ) $content_width = 900;
		
		/********** Enque script **********/
		add_action('wp_enqueue_scripts', 'IncludeCssJs::mi_add_css_js');
		add_action( 'admin_enqueue_scripts', 'IncludeCssJs::mi_add_admin_css_js' );
		
		/*********** TGMPA REGISTER**********/
		add_action( 'tgmpa_register', 'ThemePlugins::mi_register_required_plugins' );
		
		/************* WIDGET INITIALIZ ************/
		add_action( 'widgets_init', 'AfterSetupTheme::mi_custom_sidebar' );
		
		
		
		/************ META BOX HOOK *******************/
		define( 'RWMB_URL', trailingslashit( get_template_directory_uri() . '/includes/metaboxes' ) );
		define( 'RWMB_DIR', trailingslashit( get_template_directory() . '/includes/metaboxes' ) );

		require_once RWMB_DIR . 'meta-box.php';
	
		
		/********* SHORTCODE INITIALIZE ************/
		
		
		/**************** ADMIN INITIALIZE ***********/
		
		
		/************ REGISTER SUB-MENU ************/
		register_nav_menus( array(
		'main-menu' => __('Traveline Primary Menu','Traveline'),
		'footer_menu' => __('Traveline Footer Menu','Traveline'),
		) );
		
		/********** PRE GET POST *******/
		add_filter('pre_get_posts', 'ResetQuery::mi_my_query_post_type');
		
		/*********** ADD EXTRA FIELDS IN USER PROFILE *********/
		add_filter( 'user_contactmethods', 'AfterSetupTheme::mi_add_to_author_profile', 10, 1);
		
		/************** OVERRIDE SEARCH FORM *****************/
		add_filter('get_search_form', 'OverrideWidgets::mi_get_search_form');
		
		/********** WP TITLE FILTER ************/
		add_filter( 'wp_title', 'AfterSetupTheme::mi_wp_title', 10, 2 );
	}
	static function mi_add_to_author_profile($contactmethods ){
		$contactmethods['google_profile'] = esc_html__('Google Profile URL','voyo');
		$contactmethods['twitter_profile'] = esc_html__('Twitter Profile URL','voyo');
		$contactmethods['facebook_profile'] = esc_html__('Facebook Profile URL','voyo');
		$contactmethods['linkedin_profile'] = esc_html__('Linkedin Profile URL','voyo');
		
		return $contactmethods;
	}
	
	static function mi_return_theme_option($string,$str=null){
		global $traveline_theme;
		if($str!=null)
		return isset($traveline_theme[''.$string.''][''.$str.'']) ? $traveline_theme[''.$string.''][''.$str.''] : null;
		else
		return isset($traveline_theme[''.$string.'']) ? $traveline_theme[''.$string.''] : null;
	}
	
	
	
	
	static function mi_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}
	
	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title .= " $sep " . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );
	}

	return $title;
}
}
?>