<?php defined('ABSPATH') or die("No script kiddies please!");?>
<!DOCTYPE html>
<html
<?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" >
	<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<meta name="description"
	content="<?php if ( is_single() ) {
        single_post_title('', true); 
    } else {
        bloginfo('name'); echo " - "; bloginfo('description');
    }
    ?>" />
<!-- The favicon -->
<link rel="shortcut icon" href="<?php echo esc_url(AfterSetupTheme::mi_return_theme_option('favicon','url'));?>">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
 <div id="preloader">
            <div id="status">&nbsp;</div>
            <noscript><?php _e('JavaScript is off. Please enable to view full site.','traveline')?></noscript>
 </div>
<div id="site">	

	