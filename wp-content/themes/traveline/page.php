<?php defined('ABSPATH') or die("No script kiddies please!");

get_header();
get_template_part('menu-section'); ?>
<?php $slider=get_post_meta($post->ID,'rnr_page_slider',true); 
    if($slider == 'yes'){
 get_template_part('slider');
} else {
 get_template_part('banner-section'); 
} ?>
<?php $layout=get_post_meta($post->ID,'rnr_page_layout',true);
    if($layout == 'wt_sidebar') { ?>
<section class="section wide-fat hotel-detail"> <!-- Start main Section for fullWidth Page-->
        <div class="container">
            
<?php while(have_posts() ) : the_post(); ?>
<?php the_content();?>
<?php endwhile; wp_reset_postdata(); ?>
        </div>
</section> 
<?php }elseif ($layout == 'lt_sidebar') { ?>
       <section id="more-pages" class="section wide-fat">
                <div class="container">
                <div class="contents-wrapper">
                    <div class="sidebar col-md-3 col-sm-6">
                     <?php if ( is_active_sidebar( 'blog_sidebar' ) ) { 
                         dynamic_sidebar( 'blog_sidebar' ); 
                        } ?> 
                    </div>
                <div class="contents col-md-9 col-sm-6">

<?php while(have_posts() ) : the_post(); ?>
<?php the_content();?>
<?php endwhile; wp_reset_postdata(); ?>
                </div>
                </div>
                </div>
        </section> 
<?php }elseif ($layout == 'rt_sidebar') { ?>
       <section id="more-pages" class="section wide-fat">
                <div class="container">
                <div class="contents-wrapper">
                <div class="contents col-md-9 col-sm-6">

<?php while(have_posts() ) : the_post(); ?>
<?php the_content();?>
<?php endwhile; wp_reset_postdata(); ?>
                </div>
                <div class="sidebar col-md-3 col-sm-6">
                     <?php if ( is_active_sidebar( 'blog_sidebar' ) ) { 
                         dynamic_sidebar( 'blog_sidebar' ); 
                        } ?> 
                    </div>
                </div>
                </div>
        </section> 
   <?php } else { ?>
   <section class="section wide-fat hotel-detail"> <!-- Start main Section for fullWidth Page-->
        <div class="container contents">
    <?php while(have_posts() ) : the_post(); ?>
            <?php the_content();?>
            <?php endwhile; wp_reset_postdata(); ?>
            <div class="comments">
                        <?php  if ( comments_open()) {
                                        comments_template();
                                    } ?>                         
                        </div>
        </div>
    </section>
    <?php }?>

<?php $hotellist=get_post_meta($post->ID,'rnr_page_hotel',true); 
    if($hotellist == 'yes') { ?>
<section id="hotels" class="section wide-fat"> 
                <div class="container"> 
                    <article id="post-5" class="hotels section-intro">
                        <h1 class="page-title"><?php echo get_post_meta($post->ID,'rnr_hotelti',true);?></h1>
                        <div class="entry-content">
                            <p><?php echo get_post_meta($post->ID,'rnr_hotelconten',true);?></p>
                        </div>              
                    </article><!-- /#post-5.hotels -->          
                    <div class="hotels-filter">
                        <div class="container">
                            <div class="search-heading col-md-3 col-sm-6">
                               <h3><?php $args = array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1 );
                                $products = new WP_Query( $args );
                                echo esc_attr($products)->found_posts;?> <?php _e('Hotels in our List','Traveline')?> </h3>
                            </div>
                        </div>
                    </div><!-- /.hotels-filter -->
                    <div class="contents-wrapper">
                        <div class="row">
                            <div class="sidebar col-md-3 col-sm-6">
                             <?php if ( is_active_sidebar( 'woocommerce_sidebar' ) ) { 
                         dynamic_sidebar( 'woocommerce_sidebar' ); 
                        } ?> 
                            </div><!-- /.sidebar -->
                            <div class="contents grid-contents col-md-9 col-sm-6">
                                <div class="row">
                                <?php global $post;
                                query_posts(array(
                                'post_type' => 'product',
                                'showposts' => 6
                                ));
                                while ( have_posts() ) : the_post();?>
                                    <div class="content col-md-4 col-sm-12">
                                        <div class="inner">
                                        <?php if ( has_post_thumbnail() ) {
                                            $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') );?>
                                            <a class="thumbnailz" href="<?php echo the_permalink();?>">
                                                <img src="<?php echo esc_url($url);?>" alt="Your Hotel Title Here" class="responsive-image" />
                                                <span class="overlay"><?php _e('Details','Traveline')?></span>
                                            </a>
                                            <?php } ?>
                                            <div class="entry">
                                                <article class="entry-content">
                                                    <h2 class="post-title"><a href="#" title="Your Hotel Title Here"><?php the_title();?></a></h2>
                                                    <?php if ( $woocommerce_variable_price_html = $product->get_price_html() ) : ?>
    <span class="price"><span class="higlight emphasize value"><?php echo esc_attr($woocommerce_variable_price_html); ?></span></span>
<?php endif; ?>
                                                    <p><?php the_excerpt();?></p>
                                                </article>
                                                <div class="entry-meta">
                                                <span class="review">
    <?php
global $product;
if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
    return;
}
$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();
if ( $rating_count > 0 ) : ?>
        <?php if ( comments_open() ) : ?><a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s review', '%s reviews', $review_count, 'woocommerce' ), '<span itemprop="reviewCount" class="count">' . $review_count . '</span>' ); ?>)</a><?php endif ?>
<?php endif; ?></span>
                                                   <span class="go-detail"><a href="#">More</a></span>
                                                </div>  
                                            </div><!-- /.entry -->  
                                        </div>
                                    </div><!-- /.content -->
                                <?php endwhile;
                                wp_reset_postdata();?>
                                </div><!-- /.row -->
                            </div><!-- /.contents.grid-contents -->
                        </div><!-- /.row -->
                    </div><!-- /.contents-wrapper -->

                </div>
</section><!-- /#hotels.section -->  
 <?php } ?>
<?php get_footer();?>