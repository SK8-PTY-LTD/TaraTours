<?php defined('ABSPATH') or die("No script kiddies please!");
/**
 * Template Name: 404 Page
 */
get_header();
get_template_part('menu-section'); ?>
<?php global $slider, $value;
if (is_array($value) || is_object($value))
{
foreach ($value['layout'] as $slider)
{
    if ($slider)
    {
        $slider=get_post_meta($post->ID,'rnr_page_slider',true); 
    }
}
}
    if($slider == 'yes'){
 get_template_part('slider');
} else {
 get_template_part('banner-section'); 
} ?>
		<section id="more-pages" class="section wide-fat">
                <div class="area-404-2">

                    <div class='content-holder'>

                        <img class='error-image' alt='error 404' src='<?php echo get_template_directory_uri();?>/images/404.png'>

                        <h1><?php _e('Error 404: Page not found','traveline')?></h1>

                        <h3><?php _e('You are lost somewhere','traveline')?></h3>

                        <p class="margin-top-30"><a class="button btn-lg" href="<?php echo home_url();?>"><?php _e('Go back to home','traveline')?></a></p>
                        

                    </div>

                </div>
            </section><!-- /#more-pages.section -->

<?php get_footer();?>