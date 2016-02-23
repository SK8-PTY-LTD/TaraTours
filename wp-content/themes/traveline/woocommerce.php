<?php defined('ABSPATH') or die("No script kiddies please!");

get_header();
get_template_part('menu-section'); ?>

         <?php if ( is_singular( 'product' ) ) { ?>
					<section class="page-head-holder">
                <div class="container">
                    <div class="col-xs-12 col-sm-6">
            <h2><?php the_title(); ?></h2>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="breadcrumb-holder">
                            <ol class="breadcrumb">
                                <?php
        /**
         * woocommerce_before_main_content hook
         *
         * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
         * @hooked woocommerce_breadcrumb - 20
         */
        do_action( 'woocommerce_before_main_content' );
    ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
	<?php $shop_singlelayout = AfterSetupTheme::mi_return_theme_option('shop_singlelayout');
 if ($shop_singlelayout == 'swithsiderbar') {?>
	<section class="section wide-fat hotel-detail"> <!-- Start main Section for fullWidth Page-->
    	<div class="container">
						
         				<?php   woocommerce_content(); ?>
        </div>
    </section>
    <?php } }else{ ?>

        <?php  //For ANY product archive.
          //Product taxonomy, product search or /shop landing
           woocommerce_get_template( 'archive-product.php' );
          }?>
<?php get_footer(); ?>
