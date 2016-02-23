<?php defined('ABSPATH') or die("No script kiddies please!");

get_header(); 
get_template_part('menu-section');?>
<?php get_template_part('banner-section');?>
<section id="more-pages" class="section wide-fat">
                <div class="container">

                <div class="contents-wrapper">
                    <div class="contents col-md-9 col-sm-6">

                        <article class="post-wrap">
                            <div class="post-media">
                              
                                <?php if ( has_post_thumbnail() ) { ?>
                                <?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') ); ?>
                                <div class="thumbnails">
                                    <div class="thumbnail no-border no-padding">
                                        <div class="media">  
                                    <img class="img-responsive" src="<?php echo esc_url($url);?>" alt="Blog_image">
                                            
                                        </div>
                                    </div>
                                </div>
                                 <?php } ?>
                                
                               
                                
                            </div>
                            <div class="post-header">
                                <h2 class="post-title"><?php the_title();?></h2>
                                <?php if(have_posts()): the_post(); ?>
                                <div class="post-meta"><span class="post-date"><i class="fa fa-clock-o"></i> <span class="month"><?php echo esc_attr(get_the_date('F'));?></span> <span class="day"><?php echo esc_attr(get_the_date('j, '));?></span><span class="year"><?php echo esc_attr(get_the_date('Y'));?></span></span> <span class="sep"></span> <i class="fa fa-user"></i> <a href="#"><?php the_author();?></a> <span class="sep"></span> <i class="fa fa-comment"></i> <?php comments_popup_link(__('0 Comments', 'traveline'), __('1 Comment', 'traveline'), '% '.__('Comments', 'traveline')); ?></div>
                            		<?php endif;?>
                            </div>
                            <div class="post-body">
								<?php the_content();?>
								<?php wp_link_pages(); ?>
                            </div>
                            <div class="post-footer">
                                <span class="post-tags"><i class="fa fa-tag"></i> <?php 
                                                $counter=0;
                                                $posttags = get_the_tags();
                                                if ($posttags) {
                                                  foreach($posttags as $tag) {
                                                    if($counter!=0){
                                                     echo '<a href="#" title="10 Topics">,  ';
                                                     } 
                                                     echo esc_attr($tag->name . '');
                                                     $counter++;
                                                    }
                                                }
                                                ?></span>
                                <span class="post-categories"><i class="fa fa-folder-open"></i> <?php 
                                                $counter=0;
                                                $posttags = get_the_category();
                                                if ($posttags) {
                                                  foreach($posttags as $category) {
                                                    if($counter!=0){
                                                     echo '<a href="#" title="10 Topics">,  ';
                                                     } 
                                                     echo esc_attr($category->name . '');
                                                     $counter++;
                                                    }
                                                }
                                                ?></span>
                            </div>
                        </article><!-- /.post-wrap -->
                        <!-- Comments -->
                        <div class="comments">
                        <?php  if ( comments_open()) {
										comments_template();
									} ?>                         
                        </div>
                        <!-- /Comments -->
                    </div>
                    <div class="sidebar col-md-3 col-sm-6">
                    <?php if ( is_active_sidebar( 'blog_sidebar' ) ) { 
                         dynamic_sidebar( 'blog_sidebar' ); 
                        } ?>
                    </div>
                </div>
                </div>
            </section><!-- /#more-pages.section -->
<?php get_footer(); ?>
