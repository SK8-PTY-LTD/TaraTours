<?php defined('ABSPATH') or die("No script kiddies please!");

get_header(); 
get_template_part('menu-section');?>
<?php get_template_part('banner-section');?>
            <section id="our-travel-detail" class="section wide-fat">
                <div class="container"> 
                    <div class="contents grid-contents col-xs-12 travel-detail">
                        <div class="head">
                            <div class="col-xs-12 col-sm-8 no-margin">
                                <h3 class="category-heading"><?php _e('Awesome Destinations','Traveline')?></h3> 
                            </div>
                            <div class="col-xs-12 col-sm-4 no-margin">
                                <h3 class="list-heading"><?php _e('Destination List','Traveline')?></h3>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="content  wide">
                                <div class="inner">
                                    <div class="col-xs-12 col-md-4 no-margin">
                                        <div class="hotel-detail-slider-holder single-slider-holder ">
                                            
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
                                    <div class="col-xs-12 col-md-4 no-margin">
                                        <div class="entry">
                                            <article class="entry-content">
                                                <h2 class="post-title"><a href="<?php echo the_permalink(); ?>" title="Your Hotel Title Here"><?php the_title();?></a></h2>
                                                <p><?php echo get_post_meta($post->ID,'rnr_d_description',true);?></p>
                                            </article>
                                            <div class="entry-meta">
                                                <div class="star-holder "><div class="star big" data-score="<?php echo get_post_meta($post->ID,'rnr_d_review',true);?>"></div></div>
                                            </div>  
                                        </div><!-- /.entry -->  
                                    </div>
                                    <div class="col-xs-12 col-md-4 no-margin">
                                        <div class="right-area">
                                            <div id="scrollBar" class="scrollbar">
                                                <div class="handle"></div>
                                            </div>
                                            <div id="frame" class="hotel-lists ">
                                                <ul class="slidee">
                                                <?php query_posts(array(
                                                'post_type' => 'product',
                                                'showposts' => -1
                                                ));
                                                while ( have_posts() ) : the_post();?>
                                                    <li class="hotel-list-item">
                                                        <div class="col-xs-4">
                                                        <a class="thumbnailz mini" href="#" title="Diamond Hotel">
                                                        <?php if ( has_post_thumbnail() ) { ?>
                                                        <?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') ); ?>
                                                            <img src="<?php echo esc_url($url);?>" alt="Diamond Hotel" />
                                                        <?php } ?>
                                                        </a>
                                                        </div>
                                                        <div class="col-xs-8">
                                                        <article class="entry-content">
                                                            <h3><a href="<?php echo the_permalink();?>" title="Diamond Hotel"><?php the_title();?></a></h3>
                                                            <span class="price"><span class="higlight emphasize value">$150</span> /Night</span><br />
                                                            <a href="<?php echo the_permalink();?>" class="button mini">Details</a>
                                                        </article>
                                                        </div>
                                                    </li>
                                                <?php endwhile; wp_reset_postdata();?>
                                                </ul>
                                            </div><!-- /.hotel-lists -->
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.content -->
                        </div><!-- /.row -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="rating-area">
                                <h1><?php _e('Destination Details','Traveline')?></h1>
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
                            <div class="widget-support widget no-margin">
                                <h2><?php _e('you need support?','Traveline')?></h2>
                                <ul>
                                    <li>
                                        <div class="lbl"><?php _e('hotline','Traveline')?></div>
                                        <div class="value"><?php echo get_post_meta($post->ID,'rnr_d_hotline',true);?></div>
                                    </li>
                                    <li>
                                        <div class="lbl"><?php _e('Email','Traveline')?></div>
                                        <div class="value"><a href="#"><?php echo get_post_meta($post->ID,'rnr_d_email',true);?></a></div>
                                    </li>
                                    <li>
                                        <div class="lbl"><?php _e('livechat','Traveline')?></div>
                                        <div class="value"><a href="#"><?php echo get_post_meta($post->ID,'rnr_d_chat',true);?></a></div>
                                    </li>                        
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="hotels-list-row" class="row">
                        <div class="contents grid-contents">
                            <div class="row">
                            <?php
                                $result = $categories[0]->cat_name;
                                $port=array( 'post_type' =>'destination', 'category_name' => $result, 'showposts' => 4   );
                                $loop=new WP_Query($port);
                                while ( $loop->have_posts() ) : $loop->the_post();?>
                                <div class="content col-md-6 col-lg-3 col-sm-12">
                                    <div class="inner">
                                    <?php if ( has_post_thumbnail() ) { ?>
                                    <?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') ); ?>
                                        <a class="thumbnailz" href="<?php echo the_permalink();?>">
                                            <img src="<?php echo esc_url($url);?>" alt="Your Hotel Title Here" class="responsive-image" />
                                            <span class="overlay"><?php _e('Details','Traveline')?></span>
                                        </a>
                                        <?php } ?>
                                        <div class="entry">
                                            <article class="entry-content">
                                                <h2 class="post-title"><a href="<?php echo the_permalink();?>" title="Your Hotel Title Here"><?php the_title();?></a></h2>
                                                <p><?php echo get_post_meta($post->ID,'rnr_d_description',true);?></p>
                                            </article>
                                            <div class="entry-meta">
                                                <div class="star-holder"><div class="star" data-score="3"></div></div>
                                                
                                                <span class="go-detail"><a href="<?php echo the_permalink();?>"><?php _e('More','Traveline')?></a></span>
                                            </div>  
                                        </div><!-- /.entry -->  
                                    </div>
                                </div><!-- /.content -->
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
</div>
            </section><!-- /#our-travel.section -->
<?php get_footer(); ?>