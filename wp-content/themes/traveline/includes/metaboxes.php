<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
 */

/********************* META BOX DEFINITIONS ***********************/

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = 'rnr_';

global $meta_boxes;

$meta_boxes = array();

global $smof_data;


/* ----------------------------------------------------- */
// Page Sections Metaboxes
/* ----------------------------------------------------- */



/* Page Section Background Settings */

$grid_array = array('2 Columns','3 Columns','4 Columns');

$pagebg_type_array = array(
	'image' => 'Image',
	'gradient' => 'Gradient',
	'color' => 'Color'
);


/* ----------------------------------------------------- */
// Page Settings
/* ----------------------------------------------------- */
$meta_boxes[] = array(
	'id' => 'pagesettings',
	'title' => 'Page Slider Options',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',

	// List of meta fields
	// List of meta fields
	'fields' => array(

		array(
			'name'		=> 'Want Slider in your page ?',
			'id'		=> $prefix . "page_slider",
			'type'		=> 'select',
			'options'	=> array(
			    'select'		=> 'Select',
				'yes'	=> 'YES',
				
				
				
			),
			'multiple'	=> false,
			'std'		=> 'Select Custom Section'
		),

				
	)
);
$meta_boxes[] = array(
	'id' => 'pagelsettings',
	'title' => 'Page Layout Options',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',

	// List of meta fields
	// List of meta fields
	'fields' => array(

		array(
			'name'		=> 'Select Your Page Layout',
			'id'		=> $prefix . "page_layout",
			'type'		=> 'select',
			'options'	=> array(
			    'select'		=> 'Select',
			    'wt_sidebar'	=> 'Page using Container & Without Sidebar',
				'rt_sidebar'	=> 'Page With Right Sideber',
				'lt_sidebar'	=> 'Page With Lefy Sideber',
				'bt_sidebar'	=> 'Page With Both Sideber',
				'wt_side_con'	=> 'Page Without Sidebar & Container',
				
			),
			'multiple'	=> false,
			'std'		=> 'Select Custom Section'
		),

		
	)
);

$meta_boxes[] = array(
	'id' => 'pagehsettings',
	'title' => 'Page Hotel List Options',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',

	// List of meta fields
	// List of meta fields
	'fields' => array(

		array(
			'name'		=> 'Want Hotel List In This Page ?',
			'id'		=> $prefix . "page_hotel",
			'type'		=> 'select',
			'options'	=> array(
			    'select'		=> 'Select',
				'yes'	=> 'YES',
				
				
				
			),
			'multiple'	=> false,
			'std'		=> 'Select Custom Section'
		),

		array(
			'name'		=> 'If yes Than Write Title :',
			'id'		=> $prefix . 'hotelti',
			'desc'		=> 'Write title for Hotel List (Best Hotel Room )',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),

		array(
			'name'		=> 'Write some Content :',
			'id'		=> $prefix . 'hotelconten',
			'desc'		=> 'Write Some Content (Eg. Integer sollicitudin ligula non enim sodales, non lacinia nunc ornare. Sed commodo tempor dapibus.<br /> Duis convallis turpis in tortor volutpat, eget rhoncus nisi fringilla. Phasellus ornare risus in euismod varius nullam feugiat ultrices.<br /> Sed condimentum est libero, aliquet iaculis diam bibendum ullamcorper. )',
			'clone'		=> false,
			'type'		=> 'textarea',
			'std'		=> ''
		),

				
	)
);
/* ----------------------------------------------------- */
// Blog Post Metaboxes
/* ----------------------------------------------------- */

/*  Blog Link Post Settings */

$meta_boxes[] = array(
	'id' => 'rnr-blogmeta-link',
	'title' => 'Post Custom Field',
	'pages' => array( 'post'),
	'context' => 'normal',

	// List of meta fields
	'fields' => array(

		array(
			'name'		=> 'Blog Post Icon :',
			'id'		=> $prefix . 'posticon',
			'desc'		=> 'Write post icon from (Font-awesome)',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),

		array(
			'name'		=> 'Put your Vimeo Video Embeded Code :',
			'id'		=> $prefix . 'postvideosource',
			'desc'		=> 'Write Vimeo Video embeded code ',
			'clone'		=> false,
			'type'		=> 'textarea',
			'std'		=> ''
		),

		array(
			'name'		=> 'Put your Vimeo audio Embeded Code :',
			'id'		=> $prefix . 'postaudiosource',
			'desc'		=> 'Write Vimeo audio embeded code',
			'clone'		=> false,
			'type'		=> 'textarea',
			'std'		=> ''
		),

		

		
	)
);
/*  Blog Quote Post Settings */


/* ----------------------------------------------------- */
/* Portfolio Post Type Metaboxes
/* ----------------------------------------------------- */
$meta_boxes[] = array(
	'id' => 'portfolio_info',
	'title' => 'Project Details',
	'pages' => array( 'portfolio' ),
	'context' => 'normal',	

	'fields' => array(

		array(
			'name'		=> 'Released Date :',
			'id'		=> $prefix . 'r_date',
			'desc'		=> 'Wrte Released Date (Eg. 15.03.2014)',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),

		array(
			'name'		=> 'Client/Company Name',
			'id'		=> $prefix . 'project_client_name',
			'desc'		=> 'Leave empty if you do not want to show this.',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),

		array(
			'name'		=> 'Something More:',
			'id'		=> $prefix . 'p_more',
			'desc'		=> 'Write Something more about Project .',
			'clone'		=> false,
			'type'		=> 'textarea',
			'std'		=> ''
		),

	)
);

/* ----------------------------------------------------- */
/* Destination Post Type Metaboxes
/* ----------------------------------------------------- */
$meta_boxes[] = array(
	'id' => 'destination_info',
	'title' => 'Destination Info',
	'pages' => array( 'destination' ),
	'context' => 'normal',	

	'fields' => array(
		array(
			'name'		=> 'Destination Slogan :',
			'id'		=> $prefix . 'd_slogan',
			'desc'		=> 'Write your Destination Slogan:',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),
		array(
			'name'		=> 'Destination Short Description:',
			'id'		=> $prefix . 'd_description',
			'desc'		=> 'Write your Destination Package Per Person .',
			'clone'		=> false,
			'type'		=> 'textarea',
			'std'		=> ''
		),

		

		array(
			'name'		=> 'Contact Hotline:',
			'id'		=> $prefix . 'd_hotline',
			'desc'		=> 'Write Your contact Hotline (Eg. +84 123 456 789 ).',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),

		array(
			'name'		=> 'Contact E-mail:',
			'id'		=> $prefix . 'd_email',
			'desc'		=> 'Write your Contact E-mail (Eg.info@example.com).',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),

		array(
			'name'		=> 'Contact LiveChat:',
			'id'		=> $prefix . 'd_chat',
			'desc'		=> 'Write your any messenger Id: (Eg. support (Skype ID) ).',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),
		
	)
);

/* ----------------------------------------------------- */
/* Special Offer Post Type Metaboxes
/* ----------------------------------------------------- */
$meta_boxes[] = array(
	'id' => 'offer_info',
	'title' => 'Offer Info',
	'pages' => array( 'offer' ),
	'context' => 'normal',	

	'fields' => array(
		array(
			'name'		=> 'Offer Title :',
			'id'		=> $prefix . 'o_title',
			'desc'		=> 'Write your Special Offer:',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),
		array(
			'name'		=> 'Offer Package Price:',
			'id'		=> $prefix . 'o_price',
			'desc'		=> 'Write your Destination Package Per Person .',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),

		array(
			'name'		=> 'Destination Duration:',
			'id'		=> $prefix . 'o_duration',
			'desc'		=> 'Write your Destination Package Duration .',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),


		array(
			'name'		=> 'Select Offer Review',
			'id'		=> $prefix . "o_review",
			'type'		=> 'select',
			'options'	=> array(
			    'def'		=> 'Select',
				'footer_2'	=> '1 Star',
				'footer_31'	=> '2 Star',
				'footer_32'	=> '3 Star',
				'footer_33'	=> '4 Star',
				'footer_4'	=> '5 Star',
				
				
			),
			'multiple'	=> false,
			'std'		=> 'Select Review'
		),

		array(
			'name'		=> 'Contact Hotline:',
			'id'		=> $prefix . 'o_hotline',
			'desc'		=> 'Write Your contact Hotline (Eg. +84 123 456 789 ).',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),

		array(
			'name'		=> 'Contact E-mail:',
			'id'		=> $prefix . 'o_email',
			'desc'		=> 'Write your Contact E-mail (Eg.info@example.com).',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),

		array(
			'name'		=> 'Contact LiveChat:',
			'id'		=> $prefix . 'o_chat',
			'desc'		=> 'Write your any messenger Id: (Eg. support (Skype ID) ).',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),
		
	)
);

/* ----------------------------------------------------- */
/* Testimonial Post Type Metaboxes
/* ----------------------------------------------------- */
$meta_boxes[] = array(
	'id' => 'testimonial_info',
	'title' => 'Testimonial Details',
	'pages' => array( 'testimonial' ),
	'context' => 'normal',	

	'fields' => array(
		array(
			'name'		=> 'Org: Nmae :',
			'id'		=> $prefix . 'organi_name',
			'desc'		=> 'Write Your Organization Name (Eg. Google Inc., Microsoft Inc. etc.).',
			'clone'		=> false,
			'type'		=> 'text',
			'std'		=> ''
		),
		
			
		
	)
);


/********************* META BOX REGISTERING ***********************/

/**
 * Register meta boxes
 *
 * @return void
 */
function rocknrolla_register_meta_boxes()
{
	global $meta_boxes;

	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( class_exists( 'RW_Meta_Box' ) )
	{
		foreach ( $meta_boxes as $meta_box )
		{
			new RW_Meta_Box( $meta_box );
		}
	}
}

// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action( 'admin_init', 'rocknrolla_register_meta_boxes' );