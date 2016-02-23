<?php
defined('ABSPATH') or die("No script kiddies please!");

include_once 'includes/include-css.php';
include_once 'includes/include-js.php';
include_once 'includes/after-setup-theme.php';
include_once 'includes/navigation.php';
include_once 'includes/theme-nav-menu.php';

include_once 'includes/metaboxes.php';


include_once 'includes/override-widgets.php';
include_once 'includes/reset-query.php';
include_once 'symple-shortcodes/symple-shortcodes.php';

include_once 'includes/sample-config.php';
include_once 'includes/class-tgm-plugin-activation.php';
include_once 'includes/theme-plugins.php';
///// ********** add actions ************* ////


add_action('after_setup_theme', 'AfterSetupTheme::mi_add_theme_support');

// How comments are displayed
function mi_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ( 'div' == $args['style'] ) {
      $tag = 'div';
      $add_below = 'comment';
    } else {
      $tag = 'li';
      $add_below = 'div-comment';
    }
?>
    <<?php echo esc_attr($tag) ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ( 'div' != $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="media comment">
    <?php endif; ?>
    <a hert="#" class="pull-left comment-avatar">
    <?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
    </a>

    <div class="media-body">
    <p class="comment-meta"><span class="comment-author"><?php printf(__('%s','vcard'), get_comment_author()) ?> <span class="comment-date"> / <?php
       
        printf( __('%1$s','vcard'), get_comment_date()) ?> at <?php
       
        printf( __('%1$s','vcard'), get_comment_time()) ?></span></span></p>
      
    <?php comment_text() ?>
    <div class="space3"></div>
  <?php if ($comment->comment_approved == '0') : ?>
    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.','reversal_theme') ?></em>
    <br />
<?php endif; ?>
    <p class="comment-reply">
    <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </p>
    
    </div>
    

    
    <?php if ( 'div' != $args['style'] ) : ?>
    </div>
    <?php endif; ?>
<?php
        }

// Walker menu

class mi_description_walker extends Walker_Nav_Menu
{
      public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $icon_class = $classes[0];
       $classes = array_slice($classes,1);
 
    
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
 
    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
    $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
 
    $output .= $indent . '<li' . $id . $class_names .'>';
 
    $atts = array();
    $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
    $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
    $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
    $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
 
   
    $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
 
    $attributes = '';
    foreach ( $atts as $attr => $value ) {
        if ( ! empty( $value ) ) {
            $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
            $attributes .= ' ' . $attr . '="' . $value . '"';
        }
    }
 
    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'><i class="'. $icon_class .'"></i>';
    /** This filter is documented in wp-includes/post-template.php */
    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;
 
    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
}
}

// mobile menu walker

class mi_descriptionmenu_walker extends Walker_Nav_Menu
{
      public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $icon_class = $classes[0];
       $classes = array_slice($classes,1);
 
    
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
 
    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
    $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
 
    $output .= $indent . '<li' . $id . $class_names .'>';
 
    $atts = array();
    $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
    $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
    $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
    $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
 
   
    $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
 
    $attributes = '';
    foreach ( $atts as $attr => $value ) {
        if ( ! empty( $value ) ) {
            $value = ( 'value' === $attr ) ? esc_url( $value ) : esc_attr( $value );
            $attributes .= ' ' . $attr . '="' . $value . '"';
        }
    }
 
    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    /** This filter is documented in wp-includes/post-template.php */
    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;
 
    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
}
}

// Woocommerce
// Use WC 2.0 variable price format, now include sale price strikeout
add_filter( 'woocommerce_variable_sale_price_html', 'mi_wc_variation_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'mi_wc_variation_price_format', 10, 2 );
function mi_wc_variation_price_format( $price, $product ) {
    // Main Price
    $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
    $price = $prices[0] !== $prices[1] ? sprintf( __( 'HERE YOUR LANGUAGE: %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
    // Sale Price
    $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
    sort( $prices );
    $saleprice = $prices[0] !== $prices[1] ? sprintf( __( 'HERE YOUR LANGUAGE: %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

    if ( $price !== $saleprice ) {
        $price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
    }
    return $price;
} 


add_filter( 'woocommerce_checkout_fields' , 'mi_theme_custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function mi_theme_custom_override_checkout_fields( $fields ) {
     foreach ($fields as $fieldset) {
         foreach ($fieldset as $field) {
             $field['class'] = array('form-control');
         }
     }
     return $fields;
}
/** Remove product count functionality site-wide */
function woocommerce_result_count() {
        return;
}

function mi_woocommerce_catalog_page_ordering() {
?>

<form action="" method="POST" name="results" class="hotels-filter-form shortby woocommerce-ordering btn-group shop-ordering hidden-xs">
<ul>
  <li class="form-member">
<select name="woocommerce-sort-by-columns" id="woocommerce-sort-by-columns" class="chosen-select sortby btn btn-default dropdown-toggle" onchange="this.form.submit()">
<?php

        //  This is where you can change the amounts per page that the user will use  feel free to change the numbers and text as you want, in my case we had 4 products per row so I chose to have multiples of four for the user to select.
            $shopCatalog_orderby = apply_filters('woocommerce_sortby_page', array(
                ''       => __('Product per page', 'woocommerce'),
                '12'      => __('12', 'woocommerce'),
                '18'     => __('18', 'woocommerce'),
                '27'     => __('27', 'woocommerce'),
                '36'     => __('36', 'woocommerce'),
                '-1'     => __('All', 'woocommerce'),
            ));

            foreach ( $shopCatalog_orderby as $sort_id => $sort_name )
                echo '<option value="' . $sort_id . '" ' . selected( $_SESSION['sortby'], $sort_id, false ) . ' >' . $sort_name . '</option>';
        ?>
</select>
</li>
</ul>
</form>

<?php echo wp_rel_nofollow(' </span>'); ?>
<?php
}

// now we set our cookie if we need to
function mi_sort_by_page($count) {
  if (isset($_COOKIE['shop_pageResults'])) { // if normal page load with cookie
     $count = $_COOKIE['shop_pageResults'];
  }
  if (isset($_POST['woocommerce-sort-by-columns'])) { //if form submitted
    setcookie('shop_pageResults', $_POST['woocommerce-sort-by-columns'], time()+1209600, '/', 'www.your-domain-goes-here.com', false); //this will fail if any part of page has been output- hope this works!
    $count = $_POST['woocommerce-sort-by-columns'];
  }
  // else normal page load and no cookie
  return $count;
}
 
add_filter('loop_shop_per_page','mi_sort_by_page');
add_action( 'woocommerce_before_shop_loop', 'mi_woocommerce_catalog_page_ordering', 20 );



// for message cart button
function mi_add_to_cart_message() {
if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) :
    $message = sprintf( '%s<a href="%s" class="your-style">%s</a>', __( 'Successfully added to cart.', 'woocommerce' ), esc_url( get_permalink( woocommerce_get_page_id( 'shop' ) ) ), __( 'Continue Shopping', 'woocommerce' ) );
else :
    $message = sprintf( '%s<a href="%s" class="button voyo-btn-2 icon br2">%s</a>', __( 'Successfully added to cart.' , 'woocommerce' ), esc_url( get_permalink( woocommerce_get_page_id( 'cart' ) ) ), __( 'View Cart', 'woocommerce' ) );
endif;
return $message;
}
add_filter( 'wc_add_to_cart_message', 'mi_add_to_cart_message' );

// Shop or catalog sidebar Layout

$shop_pagelayout = AfterSetupTheme::mi_return_theme_option('shop_pagelayout');
 if ($shop_pagelayout == 'withsiderbar') {
add_filter('loop_shop_columns', 'mi_loop_columns');
if (!function_exists('mi_loop_columns')) {
  function mi_loop_columns() {
    return 3; // 3 products per row
  }
}
}

// Single Page sidebar layout related post
$shop_singlelayout = AfterSetupTheme::mi_return_theme_option('shop_singlelayout');
 if ($shop_singlelayout == 'swithsiderbar') {

add_filter( 'woocommerce_output_related_products_args', 'mi_related_products_args' );
  function mi_related_products_args( $args ) {

  $args['posts_per_page'] = 3; // 4 related products
  $args['columns'] = 3; // arranged in 2 columns
  return $args;
}
} else {

function mi_related_products_limit() {
  global $product;
  
  $args['posts_per_page'] = 4;
  return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'mi_related_products_args' );
  function mi_related_products_args( $args ) {

  $args['posts_per_page'] = 4; // 4 related products
  $args['columns'] = 4; // arranged in 2 columns
  return $args;
  }
}

// Product Search

add_filter( 'get_product_search_form' , 'mi_custom_product_searchform' );

/**
 * woo_custom_product_searchform
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function mi_custom_product_searchform( $form ) {
  
  $form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
    
    <div class="input-group">
      <input type="text" value="' . get_search_query() . '" name="s" class="form-control" placeholder="' . __( 'Hotel Search', 'woocommerce' ) . '" />
      <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                  </span>
      <input type="hidden" name="post_type" value="product" />
    </div>
     
  </form>';
  
  return $form;
  
}

add_filter( 'woocommerce_product_single_add_to_cart_text', 'mi_custom_cart_button_text' );    // 2.1 +
 
function mi_custom_cart_button_text() {
 
        return __( 'Book Now', 'woocommerce' );
 
}

/**
 * Change text strings
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */
function mi_text_strings( $translated_text, $text, $domain ) {
    switch ( $translated_text ) {
        case 'View Cart' :
            $translated_text = __( 'View Booking', 'woocommerce' );
            break;
    }
    return $translated_text;
}
add_filter( 'gettext', 'mi_text_strings', 20, 3 );