<header id="header" class="wide-fat">
                <div class="container">
                    <div class="col-xs-12 col-sm-2 no-margin">
                        <div class="branding">
                            <?php $logopic = AfterSetupTheme::mi_return_theme_option('logopic','url');
                            if ($logopic == ''){?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/site-logo.png" alt="Traveline" /> <span><?php _e('Traveline','Traveline')?></span></a>
                            </h1>
                            <?php } else{?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo esc_url($logopic);?>" alt="Traveline" /> <span><?php echo AfterSetupTheme::mi_return_theme_option('logotitle'); ?></span></a>
                            </h1>
                            <?php }?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-10 no-margin">
                        <div id="main-menu">
                            <nav class="navigation">
                            
<?php $defaults = array(
                    'theme_location'  => 'main-menu',
                    'menu'            => '',
                    'container'       => 'ul',
                    'container_class' => 'hidden-xs hidden-sm hidden-md',
                    'menu_class'      => 'hidden-xs hidden-sm hidden-md',
                    'menu_id'         => '',
                    'echo'            => true,
                    'fallback_cb'     => 'wp_page_menu',
                    'before'          => '',
                    'after'           => '',
                    'link_before'     => '',
                    'link_after'      => '',
                    'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
                    'depth'           => 0,
                    'walker'          => new mi_description_walker,
                        );
        if(has_nav_menu('main-menu')) {
                                  wp_nav_menu( $defaults );
          }
          else {
            echo 'No menu assigned!';
          }?>

          <div class="menu-button">
                <button type="button"><i class="fa fa-bars"></i></button>
            </div>
            <div class="show-menu">
          <?php $defaults = array(
                    'theme_location'  => 'main-menu',
                    'menu'            => '',
                    'container'       => 'ul',
                    'container_class' => '',
                    'menu_class'      => '',
                    'menu_id'         => '',
                    'echo'            => true,
                    'fallback_cb'     => 'wp_page_menu',
                    'before'          => '',
                    'after'           => '',
                    'link_before'     => '',
                    'link_after'      => '',
                    'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
                    'depth'           => 0,
                    'walker'          => new mi_descriptionmenu_walker,
                        );
        if(has_nav_menu('main-menu')) {
                                  wp_nav_menu( $defaults );
          }
          else {
            echo 'No menu assigned!';
          }?>
                        </div> 
                             
                            </nav>
                        </div><!-- /#main-menu -->
                    </div>
                </div>
            </header><!-- /#header -->