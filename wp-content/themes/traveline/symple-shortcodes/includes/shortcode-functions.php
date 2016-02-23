<?php
/**
*
*
*
 * Allow shortcodes in widgets
 * @since v1.0
 */
add_filter('widget_text', 'do_shortcode');

// Traveline All Shortcode Start Here


// Traveline About Us Shortcode

if(! function_exists('tl_about_border_shortcode')){
	function tl_about_border_shortcode($atts, $content = null){
		extract(shortcode_atts( array(
			'class'=>'',
			'id'=>'',
			'title'=>'',
			'htitle'=>'',
			'acontent'=>'',
			
			), $atts) );
		$html='';
		
		$html .= '<section id="about-us" class="homepage section wide-fat">';
		$html .= '<div class="container">';
		$html .= '<article id="post-1" class="about-us section-intro">';
		$html .= '<h1 class="page-title">'.$title.' <span class="higlight">'.$htitle.'</span></h1>';
		$html .= '<div class="entry-content">';
		$html .= '<p>'.$acontent.'</p>';
		$html .= '</div>';
		$html .= '</article>';
		$html .= '<div class="about-details grid col-3">';
		$html .= '<div class="row">';
		$html .= do_shortcode($content);
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</section>';
		

				
		return $html	;
	}
	add_shortcode('tl_about_border', 'tl_about_border_shortcode');
}

if(! function_exists('tl_about_content_shortcode')){
	function tl_about_content_shortcode($atts, $content = null){
		extract(shortcode_atts( array(
			'class'=>'',
			'id'=>'',
			'aftitle'=>'',
			'afcontent'=>'',
			'alink'=>''
			
			), $atts) );
		$html='';
		
		$html .= '<div class="col-md-4 col-sm-4 about-detail-1 honey-moon">';
		$html .= '<article>';
		$html .= '<h2>'.$aftitle.'</h2>';	
		$html .= '<p>'.$afcontent.'</p>';
		$html .= '<a href="'.$alink.'" class="button">Read More</a>';
		$html .= '</article>';
		$html .= '</div>';
					
		return $html	;
	}
	add_shortcode('tl_about_content', 'tl_about_content_shortcode');
}

// Traveline Destination Shortcode

if(! function_exists('tl_destination_home_shortcode')){
	function tl_destination_home_shortcode($atts, $content = null){
		extract(shortcode_atts( array(
			'class'=>'',
			'id'=>'',
			'dtitle1'=>'',
			'dtitle2'=>'',
			'd_content'=>''
			
			), $atts) );
		$html='';
		
		$html .= '<section id="awesome-destinations" class="section wide-fat">';
		$html .= '<div class="container">';
		$html .= '<article id="post-2" class="awesome-destinations section-intro">';	
		$html .= '<h1 class="page-title">'.$dtitle1.' <span class="higlight">'.$dtitle2.'</span></h1>';
		$html .= '<div class="entry-content">';
		$html .= '<p>'.$d_content.'</p>';
		$html .= '</div>';
		$html .= '<div class="destinations">';
		$html .= '<ul class="destination-lists">';
		query_posts(array(
		'post_type' => 'destination',
		'showposts' => 6
		));
		while ( have_posts() ) : the_post();
		$html .= '<li class="destination post">';
		$html .= '<div class="post-thumbz">';
		if ( has_post_thumbnail() ) {
			$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') );
		$html .= '<img class="curl-top-right responsive-image" src="';
		$html .= esc_url($url);
		$html .= '" alt="Destination 1" />';
		}
		$html .= '<span class="info">';
		$html .= '<span class="title">';
		$html .= '<span class="small">Explore Amazing Trails</span><br />';
		$html .= '<span>';
		$html .= get_the_title();
		$html .= '</span>';
		$html .= '</span>';
		$html .= '<a href="';
		$html .= get_the_permalink();
		$html .= '" class="button green"><i class="arrow_right "></i></a>';
		$html .= '</span>';
		$html .= '</div>';
		$html .= '</li>';
		endwhile;
		wp_reset_postdata();
		$html .= '<li class="destination">';
		$html .= '<div class="post-navigation">';
		
		$html .= '<div class="more-destinations">';
		$html .= '<a href="#" class="button green">More Destinations</a>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</li>';
		$html .= '</ul>';
		$html .= '</div>';
		$html .= '</article>';
		$html .= '</div>';
		$html .= '</section>';
			
		return $html	;
	}
	add_shortcode('tl_destination_home', 'tl_destination_home_shortcode');
}

if(! function_exists('tl_offer_home_shortcode')){
	function tl_offer_home_shortcode($atts, $content = null){
		extract(shortcode_atts( array(
			'class'=>'',
			'id'=>'',
			'icon'=>'',
			
			), $atts) );
		$html='';
		$html .= '<section id="special-offers" class="section wide-fat">';
		$html .= '<div id="mi-slider" class="mi-slider">';
		query_posts(array(
        'post_type' => 'offer',
        'showposts' => 6
        ));
        while ( have_posts() ) : the_post();
		$html .= '<ul>';
		$html .= '<li>';
		$html .= '<a href="';
		$html .= get_the_permalink();
		$html .= '">';
		if ( has_post_thumbnail() ) {
			$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') );
		$html .= '<img src="';
		$html .= esc_url($url);
		$html .= '" alt="Special Offer" />';
		}
		$html .= '<div class="container">';
		$html .= '<article class="special-offers section-intro">';
		$html .= '<h1 class="page-title">Special Offers</h1>';
		$html .= '<div class="star-ratings">';
		$html .= '<span>';
		$html .= '<i class="icon_star"></i>';
		$html .= '<i class="icon_star"></i>';
		$html .= '<i class="icon_star"></i>';
		$html .= '<i class="icon_star"></i>';
		$html .= '<i class="icon_star"></i>';
		$html .= '</span>';
		$html .= '</div>';
		$html .= '<h2>';
		$html .= get_the_title();
		$html .= '</h2>';
		$html .= '<span class="price">$3250</span>';
		$html .= '</article>';
		$html .= '</div>';
		$html .= '</a>';
		$html .= '</li>';
		$html .= '</ul>';
		endwhile;
        wp_reset_postdata();
        $html .= '<nav id="mi-nav">';
        query_posts(array(
        'post_type' => 'offer',
        'showposts' => 6
        ));
        while ( have_posts() ) : the_post();
        $html .= '<a href="#"><span>';
        $html .= get_the_title();
        $html .= '</span></a>';
        endwhile;
        wp_reset_postdata();
        $html .= '</nav>';
        $html .= '<nav class="small-nav">';
        query_posts(array(
        'post_type' => 'offer',
        'showposts' => 6
        ));
        while ( have_posts() ) : the_post();
        $html .= '<a href="#"><span>';
        $html .= get_the_title();
        $html .= '</span></a>';
        endwhile;
        wp_reset_postdata();
        $html .= '</nav>';
        $html .= '</div>';
        $html .= '<div class="clearfix"></div>';
        $html .= '</section>';		
			
		return $html;
	}
	add_shortcode('tl_offer_home', 'tl_offer_home_shortcode');
}

// Traveline testimonial Home shortcode

if(! function_exists('tl_testimonial_home_shortcode')){
	function tl_testimonial_home_shortcode($atts, $content = null){
		extract(shortcode_atts( array(
			'class'=>'',
			'id'=>'',
			'tetitle'=>'',
			'teftitle'=>'',
						
			), $atts) );
		$html='';
		
		$html .= '<section id="testimonials" class="section wide-fat">';
		$html .= '<div class="container">';
		$html .= '<article id="post-44" class="testimonials section-intro">';	
		$html .='<h1 class="page-title">'.$tetitle.'</h1>';
		$html .= '</article>';
		$html .= '</div>';
		$html .= '<div id="sliding-testimony" class="flexslider">';
		$html .= '<ul class="slides">';
		global $post;
        query_posts(array(
        'post_type' => 'testimonial',
        'showposts' => -1
        ));
        while ( have_posts() ) : the_post();
		$html .= '<li class="post">';
		$html .= '<div class="container">';
		$html .= '<div class="entry-content">';
		$html .= '<h2 class="testimony-author">';
		$html .= get_the_title();
		$html .= '</h2>';
		$html .= '<div class="star-ratings">';
		$html .= '<span>';
		$html .= '<i class="icon_star"></i>';
		$html .= '<i class="icon_star"></i>';
		$html .= '<i class="icon_star"></i>';
		$html .= '<i class="icon_star"></i>';
		$html .= '<i class="icon_star"></i>';
		$html .= '</span>';
		$html .= '</div>';
		$html .= get_the_content();
		$html .= '<h3 class="impressive-figures-heading">'.$teftitle.'</h3>';
		$html .= '<div class="criteria">';
		$html .= '<div class="criteria-item">';
		$html .= '<div class="circle"><i class="icon_map_alt"></i></div>';
		$html .= '<div class="stats">';
		$html .= '<span class="number">2.36545</span> <span>Tours</span>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="criteria-item">';
		$html .= '<div class="circle"><i class="icon_group"></i></div>';
		$html .= '<div class="stats">';
		$html .= '<span class="number">2.36545</span> <span>Tourists</span>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="criteria-item">';
		$html .= '<div class="circle"><i class="icon_pin"></i></div>';
		$html .= '<div class="stats">';
		$html .= '<span class="number">2.36545</span> <span>Destinations</span>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="criteria-item">';
		$html .= '<div class="circle"><i class="icon_building_alt"></i></div>';
		$html .= '<div class="stats">';
		$html .= '<span class="number">2.36545</span> <span>Hotels</span>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</li>';
		endwhile;
        wp_reset_postdata();
		$html .= '</ul>';
		$html .= '</div>';
		$html .= '</section>';

		return $html	;
	}
	add_shortcode('tl_testimonial_home', 'tl_testimonial_home_shortcode');
}

// Traveline Travel Home Shortcode 
if(! function_exists('tl_travel_home_shortcode')){
	function tl_travel_home_shortcode($atts, $content = null){
		extract(shortcode_atts( array(
			'class'=>'',
			'id'=>'',
			'tr_title'=>'',
			'tr_content'=>'',
			
			), $atts) );
		$html='';
		
		$html .= '<section id="our-travel" class="section wide-fat">';
		$html .= '<div class="container">';
		$html .= '<article id="post-4" class="our-travel section-intro">';
		$html .= '<h1 class="page-title">'.$tr_title.'</h1>';
		$html .= '<div class="entry-content">';
		$html .= '<p>'.$tr_content.'</p>';
		$html .= '</div>';
		$html .= '</article>';
		$html .= '<div class="controls">';
		if(!get_post_meta(get_the_ID(), 'product_cat', true)):
        $product_cat = get_terms('product_cat');
        if($product_cat):
		$html .= '<ul>';
		$html .= '<li class="filter active" data-filter="all">All</li>';
		foreach($product_cat as $product_cat):
		$html .= '<li class="filter" data-filter="';
		$html .= $product_cat->slug;
		$html .= '">';
		$html .= $product_cat->name;
		$html .= '</li>';
		endforeach;
		$html .= '</ul>';
		endif;
        endif;
		$html .= '</div>';
		$html .= '<div id="Grid" class="row">';
		global $post;
        query_posts(array(
        'post_type' => 'product',
        'showposts' => 6
        ));
        while ( have_posts() ) : the_post();
        $product_cat = wp_get_post_terms($post->ID,'product_cat');
		$html .= '<div class="mix ';
		foreach ($product_cat as $item)
		$html .= $item->slug .' '; 
		$html .= ' col-md-6 col-sm-12 col-xs-12 tour-category-item">';
		$html .= '<div class="inner">';
		$html .= '<div class="part">';
		$html .= '<h3 class="category-heading">';
		$html .= get_the_title();
		$html .= '</h3>';
		$html .= '<div class="featured-tour">';
		if ( has_post_thumbnail() ) {
			$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') );
		$html .= '<a href="';
		$html .= get_the_permalink();
		$html .= '" title="Siam Paragon, Bangkok"><img src="';
		$html .= $url;
		$html .= '" alt="Siam Paragon, Bangkok" class="responsive-image" /></a>';
		}
		$html .= '<div class="entry">';
		$html .= '<article class="entry-content">';
		$html .= '<h1><a href="';
		$html .= get_the_permalink();
		$html .= '" title="Siam Paragon, Bangkok">';
		$html .= get_the_title();
		$html .= '</a></h1>';
		$html .= '<p>';
		$html .= get_the_excerpt();
		$html .= '</p>';
		$html .= '</article>';
		$html .= '<div class="entry-meta">';
		$html .= '<span class="go-detail"><a href="';
		$html .= get_the_permalink();
		$html .= '">More</a></span>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="part">';
		$html .= '<h3 class="list-heading">Related Hotel List</h3>';
		$html .= '<div class="hotel-lists">';
		$html .= '<ul>';
		$result = $categories[0]->cat_name;
        $port=array( 'post_type' =>'product', 'category_name' => $result, 'showposts' => 3   );
        $loop=new WP_Query($port);
        while ( $loop->have_posts() ) : $loop->the_post();
		$html .= '<li class="hotel-list-item">';
		if ( has_post_thumbnail() ) {
		$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') );
		$html .= '<a class="thumbnailz mini" href="';
		$html .= get_the_permalink();
		$html .= '" title="Diamond Hotel">';
		$html .= '<img src="';
		$html .= esc_url($url);
		$html .= '" alt="Diamond Hotel" />';
		$html .= '<span class="overlay"></span>';
		$html .= '</a>';
		}
		$html .= '<article class="entry-content">';
		$html .= '<h3><a href="';
		$html .= get_the_permalink();
		$html .= '" title="Diamond Hotel">';
		$html .= get_the_title();
		$html .= '</a></h3>';
		$html .= '<span class="price"><span class="higlight emphasize value">$150</span> /Night</span><br />';
		$html .= '<a href="';
		$html .= get_the_permalink();
		$html .= '" class="button mini">Details</a>';
		$html .= '</article>';
		$html .= '</li>';
		endwhile;
		$html .= '</ul>';
		$html .= '<div class="load-more-hotel">';
		$html .= '<a href="#" class="button wide-fat">Load More Hotel</a>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		endwhile;
		wp_reset_postdata();
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</section>';
		
			
		return $html	;
	}
	add_shortcode('tl_travel_home', 'tl_travel_home_shortcode');
}

if(! function_exists('tl_home_hotel_shortcode')){
	function tl_home_hotel_shortcode($atts, $content = null){
		extract(shortcode_atts( array(
			'class'=>'',
			'id'=>'',
			'title1'=>'',
			'title2'=>'',
			'f1_c'=>'',
			'f2_c'=>''
			
			), $atts) );
		$html='';
		
		$html .= '<section id="hotels" class="section wide-fat">';
		$html .= '<div class="container">';
		$html .= '<article id="post-5" class="hotels section-intro">';
		$html .= '<h1 class="page-title">Best <span class="higlight">Hotels</span></h1>';
		$html .= '<div class="entry-content">';
		$html .= '<p>Integer sollicitudin ligula non enim sodales, non lacinia nunc ornare. Sed commodo tempor dapibus.<br /> Duis convallis turpis in tortor volutpat, eget rhoncus nisi fringilla. Phasellus ornare risus in euismod varius nullam feugiat ultrices.<br /> Sed condimentum est libero, aliquet iaculis diam bibendum ullamcorper.</p>';
		$html .= '</div>';
		$html .= '</article>';
		$html .= '<div class="hotels-filter">';
		$html .= '<div class="container">';
		$html .= '<div class="search-heading col-md-3 col-sm-6">';
		$html .= '<h3>';
		$args .= array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1 );
        $products = new WP_Query( $args );
		$html .= $products->found_posts;
		$html .= 'Hotels in our List </h3>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="contents-wrapper">';
		$html .= '<div class="row">';
		$html .= '<div class="sidebar col-md-3 col-sm-6">';
		if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('woocommerce Sidebar')): 
        endif;
		$html .= '</div>';
		$html .= '<div class="contents grid-contents col-md-9 col-sm-6">';
		$html .= '<div class="row">';
		
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</section>';

		
			
		return $html	;
	}
	add_shortcode('tl_home_hotel', 'tl_home_hotel_shortcode');
}

// Traveline Contact Shortcode

if(! function_exists('tl_contact_shortcode')){
	function tl_contact_shortcode($atts, $content = null){
		extract(shortcode_atts( array(
			'class'=>'',
			'id'=>'',
			'bg_image'=>''
					
				
			), $atts) );
		$html='';
		$html.= '<section id="contact" class="section wide-fat" style="background:#e9eff4 url('.$bg_image.')">';
		$html .= '<div class="container">';
		$html .= '<article id="post-6" class="contact section-intro">';
		$html .= '<h1 class="page-title">';
		$html .= esc_attr(AfterSetupTheme::mi_return_theme_option('contacttitle'));
		$html .= '</h1>';
		$html .= '<div class="entry-content">';
		$html .= '<p>';
		$html .= esc_attr(AfterSetupTheme::mi_return_theme_option('shortcontent'));
		$html .= '</p>';
		$html .= '</div>';
		$html .= '</article>';
		$html .= '<div id="map_canvas" class="home-map"></div>';
		$html .= '<div class="main-contact-form">';
		$html .= do_shortcode($content);
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</section>';
				
				
		return $html	;
	}
	add_shortcode('tl_contact', 'tl_contact_shortcode');
}
