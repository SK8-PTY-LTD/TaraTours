<?php defined('ABSPATH') or die("No script kiddies please!"); ?>
<footer id="footer-black" class="widefat ">
                <div class="container">
                    <div class="col-xs-12 col-md-4">
                         <?php if ( is_active_sidebar( 'footer_area_1_sidebar' ) ) { 
                         dynamic_sidebar( 'footer_area_1_sidebar' ); 
                        } ?>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <?php if ( is_active_sidebar( 'footer_area_2_sidebar' ) ) { 
                         dynamic_sidebar( 'footer_area_2_sidebar' ); 
                        } ?>
                    </div>
                    <div class="col-xs-12 col-md-4">
                         <?php if ( is_active_sidebar( 'footer_area_3_sidebar' ) ) { 
                         dynamic_sidebar( 'footer_area_3_sidebar' ); 
                        } ?>
                    </div>
                </div>
            </footer><!-- /#footer -->
<div id="footer" class="widefat">
                <div class="container">
                    <div class="footer-inner">
                        <p class="credit"><?php echo esc_attr(AfterSetupTheme::mi_return_theme_option('footercopyright'));?></p>
                    </div><!-- /.footer-inner -->
                </div>
            </div><!-- /#footer -->
</div> <!-- END site -->
<?php wp_footer(); ?>
</body>
</html>
