            <section id="featured" class="wide-fat">
                <div class="featured-inner">
                    <div class="slider">
                        <div id="top-slider" class="flexslider">
                        <?php $section1 = AfterSetupTheme::mi_return_theme_option('section-media-checkbox1');
                        $sliderimage1 = AfterSetupTheme::mi_return_theme_option('sliderimage1','url');
                        if ($section1 == '1'){?>
                            <ul class="slides">
                                <li>
                                    <img src="<?php echo esc_url($sliderimage1); ?>" alt="Featured Image" />
                                </li>
                            <?php $section2 = AfterSetupTheme::mi_return_theme_option('section-media-checkbox2');
                            $sliderimage2 = AfterSetupTheme::mi_return_theme_option('sliderimage2','url');
                            if ($section2 == '1'){?>
                                <li>
                                    <img src="<?php echo esc_url($sliderimage2); ?>" alt="Featured Image" />
                                </li>
                            <?php } ?>
                            <?php $section3 = AfterSetupTheme::mi_return_theme_option('section-media-checkbox3');
                            $sliderimage3 = AfterSetupTheme::mi_return_theme_option('sliderimage3','url');
                            if ($section3 == '1'){?>
                                <li>
                                    <img src="<?php echo esc_url($sliderimage3); ?>" alt="Featured Image" />
                                </li>
                            <?php } ?>
                            <?php $section4 = AfterSetupTheme::mi_return_theme_option('section-media-checkbox4');
                            $sliderimage4 = AfterSetupTheme::mi_return_theme_option('sliderimage34','url');
                            if ($section4 == '1'){?>
                                <li>
                                    <img src="<?php echo esc_url($sliderimage4); ?>" alt="Featured Image" />
                                </li>
                            <?php } ?>
                             <?php $section5 = AfterSetupTheme::mi_return_theme_option('section-media-checkbox5');
                            $sliderimage5 = AfterSetupTheme::mi_return_theme_option('sliderimage5','url');
                            if ($section5 == '1'){?>
                                <li>
                                    <img src="<?php echo esc_url($sliderimage5); ?>" alt="Featured Image" />
                                </li>
                            <?php } ?>
                            </ul><!-- /.slides -->
                            <?php } ?>
                            <?php $searchform = AfterSetupTheme::mi_return_theme_option('section-media-checkbox');
                            $sliderimage5 = AfterSetupTheme::mi_return_theme_option('sliderimage5','url');
                            if ($searchform == '1'){?>
                            <div class="opener-area">
                                <a  href="#" class="open-btn open-close-btn"><i class="fa fa-chevron-circle-right"></i></a>
                                <ul class="social-icons vertical">
                                    <?php if (AfterSetupTheme::mi_return_theme_option('fb')=='yes'){?>
                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('fblink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('fblink'));?>"><i class="fa fa-facebook"></i></a></li>
                        <?php } ?>
                        <?php if (AfterSetupTheme::mi_return_theme_option('tw')=='yes'){?>
                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('twlink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('twlink'));?>"><i class="fa fa-twitter"></i></a></li>
                        <?php } ?>
                        <?php if (AfterSetupTheme::mi_return_theme_option('dr')=='yes'){?>
                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('drlink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('drlink'));?>"><i class="fa fa-dribbble"></i></a></li>
                        <?php } ?>
                        <?php if (AfterSetupTheme::mi_return_theme_option('pin')=='yes'){?>
                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('pinlink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('pinlink'));?>"><i class="fa fa-pinterest"></i></a></li>
                        <?php } ?>
                        <?php if (AfterSetupTheme::mi_return_theme_option('in')=='yes'){?>
                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('inlink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('inlink'));?>"><i class="fa fa-linkedin"></i></a></li>
                        <?php } ?>
                        <?php if (AfterSetupTheme::mi_return_theme_option('g')=='yes'){?>
                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('glink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('glink'));?>"><i class="fa fa-google-plus"></i></a></li>
                        <?php } ?>
                        <?php if (AfterSetupTheme::mi_return_theme_option('yt')=='yes'){?>
                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('ytlink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('ytlink'));?>"><i class="fa fa-youtube"></i></a></li>
                        <?php } ?>
                        <?php if (AfterSetupTheme::mi_return_theme_option('db')=='yes'){?>
                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('dblink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('dblink'));?>"><i class="fa fa-dropbox"></i></a></li>
                        <?php } ?>
                        <?php if (AfterSetupTheme::mi_return_theme_option('sk')=='yes'){?>
                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('sklink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('sklink'));?>"><i class="fa fa-skype"></i></a></li>
                        <?php } ?>
                        <?php if (AfterSetupTheme::mi_return_theme_option('flc')=='yes'){?>
                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('flclink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('flclink'));?>"><i class="fa fa-flickr"></i></a></li>
                        <?php } ?>
                                </ul>
                            </div>
                            <div class="featured-overlay">
                                <a id="close-form" href="#" class="button close open-close-btn"><i class="icon_close_alt2"></i></a>
                                <div class="featured-overlay-inner">
                                    <form class="location-search"  method="get" id="searchform" action="<?php esc_url( home_url( '/'  ) )?>">
                                        <div class="search-field">
                                            <div class="destination-field">
                                                <label for="destination"><?php _e('Choose Your Destination','traveline')?></label><br />
                                                 <div class="input-group">
                                          <input type="text" value="<?php echo get_search_query() ?>" name="s" class="form-control" placeholder="<?php _e( 'Hotel Search', 'woocommerce' ) ?>" />
                                          <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                                      </span>
                                                <input type="hidden" name="post_type" value="product" />
                                                </div>
                                            </div>
                                        </div>                                            
                                         <div class="search-field">
                                          <input type="submit" class="button wide-fat" value="Search" />
                                        </div>
                                    </form><!-- /form.location-search -->
                                    <div class="featured-teaser-text">
                                      <?php $searchtitle1 = AfterSetupTheme::mi_return_theme_option('searchtitle1');
                                        if ($searchtitle1 != ' '){?>
                                        <h1><?php echo AfterSetupTheme::mi_return_theme_option('searchtitle1'); ?></h1>
                                        <?php } ?>
                                        <?php $searchtitle2 = AfterSetupTheme::mi_return_theme_option('searchtitle2');
                                        if ($searchtitle2 != ' '){?>
                                        <h2><?php echo AfterSetupTheme::mi_return_theme_option('searchtitle2'); ?></h2>
                                        <?php } ?>
                                        <?php $searchcontent = AfterSetupTheme::mi_return_theme_option('searchcontent');
                                        if ($searchcontent != ' '){?>
                                        <p><?php echo AfterSetupTheme::mi_return_theme_option('searchcontent'); ?></p>
                                        <?php } ?>
                                    </div><!-- /.featured-teaser-text -->
                                    <div class="social-networks">
                                        <ul>
                                           <?php if (AfterSetupTheme::mi_return_theme_option('fb')=='yes'){?>
                                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('fblink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('fblink'));?>"><i class="social_facebook"></i></a></li>
                                        <?php } ?>
                                        <?php if (AfterSetupTheme::mi_return_theme_option('tw')=='yes'){?>
                                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('twlink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('twlink'));?>"><i class="social_twitter"></i></a></li>
                                        <?php } ?>
                                        <?php if (AfterSetupTheme::mi_return_theme_option('dr')=='yes'){?>
                                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('drlink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('drlink'));?>"><i class="social_dribbble"></i></a></li>
                                        <?php } ?>
                                        <?php if (AfterSetupTheme::mi_return_theme_option('pin')=='yes'){?>
                                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('pinlink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('pinlink'));?>"><i class="social_pinterest"></i></a></li>
                                        <?php } ?>
                                        <?php if (AfterSetupTheme::mi_return_theme_option('in')=='yes'){?>
                                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('inlink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('inlink'));?>"><i class="social_linkedin"></i></a></li>
                                        <?php } ?>
                                        <?php if (AfterSetupTheme::mi_return_theme_option('g')=='yes'){?>
                                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('glink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('glink'));?>"><i class="social_googleplus"></i></a></li>
                                        <?php } ?>
                                        <?php if (AfterSetupTheme::mi_return_theme_option('yt')=='yes'){?>
                                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('ytlink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('ytlink'));?>"><i class="social_youtube"></i></a></li>
                                        <?php } ?>
                                        <?php if (AfterSetupTheme::mi_return_theme_option('db')=='yes'){?>
                                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('dblink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('dblink'));?>"><i class="social_dropbox"></i></a></li>
                                        <?php } ?>
                                        <?php if (AfterSetupTheme::mi_return_theme_option('sk')=='yes'){?>
                                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('sklink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('sklink'));?>"><i class="social_skype"></i></a></li>
                                        <?php } ?>
                                        <?php if (AfterSetupTheme::mi_return_theme_option('flc')=='yes'){?>
                                        <li><a href="<?php echo AfterSetupTheme::mi_return_theme_option('flclink')==null?'#': esc_url(AfterSetupTheme::mi_return_theme_option('flclink'));?>"><i class="social_flickr"></i></a></li>
                                        <?php } ?>
                                        </ul>
                                    </div><!-- /.social-networks -->
                                </div><!-- /.featured-overlay-inner -->
                            </div><!-- /.featured-overlay -->
                            <?php } ?>
                        </div>
                    </div><!-- /.slider -->					
                </div><!-- /.featured-inner -->
            </section><!-- /#featured -->