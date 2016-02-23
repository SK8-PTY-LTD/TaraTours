<?php defined('ABSPATH') or die("No script kiddies please!");

get_header(); 
get_template_part('menu-section');?>
<?php get_template_part('banner-section');?>

            <section class="section wide-fat hotel-detail">
                <div class="container"> 
                    <div class="col-xs-12 col-sm-8">
                        <div class="single-product-gallery">
                            <div class="single-slider-holder">
                                
                                <div class="hotel-detail-slider single-slider">
                                    <div class="hotel-detail-gallery-item" id="slide1">
                                        <?php if ( has_post_thumbnail() ) { ?>
                                        <?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') ); ?>
                                        <img alt="" src="<?php echo esc_url($url);?>" />
                                        <?php } ?>
                                    </div>
                                                
                                </div>
                                
                            </div>
                        </div>
                        <div class="tab-holder">
                           <!-- Nav tabs -->
                            <ul class="nav nav-tabs" >
                                <li class="active"><a href="#overview" data-toggle="tab"><?php _e('Details','Traveline')?></a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="overview">
                               <?php if(have_posts()): the_post(); ?>
                                    <?php the_content();?>
                                    <?php wp_link_pages(); ?>
                                <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="sidebar-holder">
                            <article class="entry-content">
                                <h2 class="post-title"><?php the_title();?></h2>
                                <span class="price"><span class="higlight emphasize value"><?php echo get_post_meta($post->ID,'rnr_o_price',true);?></span> <?php _e('/','Traveline')?> <?php echo get_post_meta($post->ID,'rnr_o_duration',true);?></span><br />
                                <div class="star-holder "><div class="star big" data-score="<?php echo get_post_meta($post->ID,'rnr_o_review',true);?>"></div></div>
                            </article>
                            <div class="widget-support">
                                <h2><?php _e('you need support?','Traveline')?></h2>
                                <ul>
                                    <li>
                                        <div class="lbl"><?php _e('hotline','Traveline')?></div>
                                        <div class="value"><?php echo get_post_meta($post->ID,'rnr_o_hotline',true);?></div>
                                    </li>
                                    <li>
                                        <div class="lbl"><?php _e('email','Traveline')?></div>
                                        <div class="value"><?php echo get_post_meta($post->ID,'rnr_o_email',true);?></div>
                                    </li>
                                    <li>
                                        <div class="lbl"><?php _e('livechat','Traveline')?></div>
                                        <div class="value"><?php echo get_post_meta($post->ID,'rnr_o_chat',true);?></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="widget recommended-hotels">
                                <h2><?php _e('you maybe also like','Traveline')?></h2>
                                <div class="hotel-lists">
                                    <ul>
                                    <?php
                                $result = $categories[0]->cat_name;
                                $port=array( 'post_type' => 'offer', 'category_name' => $result, 'showposts' => 5   );
                                $loop=new WP_Query($port);
                                while ( $loop->have_posts() ) : $loop->the_post();?>
                                        <li class="hotel-list-item">
                                        <?php if ( has_post_thumbnail() ) { ?>
                                            <?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') ); ?>
                                            <a class="thumbnailz mini" href="#" title="Diamond Hotel">
                                                <img src="<?php echo esc_url($url);?>" alt="Diamond Hotel" />
                                                <span class="overlay"></span>
                                            </a>
                                             <?php } ?>
                                            <article class="entry-content">
                                                <h3><a href="#" title="Diamond Hotel"><?php the_title();?></a></h3>
                                                <span class="price"><span class="higlight emphasize value"><?php echo get_post_meta($post->ID,'rnr_o_price',true);?></span> <?php _e('/','Traveline')?> <?php echo get_post_meta($post->ID,'rnr_o_duration',true);?></span><br />
                                                <a href="#" class="button mini"><?php _e('Details','Traveline')?></a>
                                            </article>  
                                        </li>
                                    <?php endwhile; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
<?php get_footer(); ?>