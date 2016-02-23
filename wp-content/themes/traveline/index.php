<?php defined('ABSPATH') or die("No script kiddies please!");
get_header();
get_template_part('menu-section');?>
<section class="page-head-holder">
                <div class="container">
                    <div class="col-xs-12 col-sm-6">
                        <h2><?php _e('Blog','traveline')?></h2>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="breadcrumb-holder">
                            <ol class="breadcrumb">
                                 <?php if(function_exists('bcn_display'))
                                {
                                bcn_display();
                                }?>
                            </ol>
                        </div>
                    </div>

                </div>
</section>
<section id="more-pages" class="section wide-fat">
                <div class="container">

                <div class="contents-wrapper">
                    <div class="contents col-md-9 col-sm-6">
                    <?php query_posts('post_type=post&post_status=publish&paged='. get_query_var('paged')); 
                        if (have_posts()) : 
                        while (have_posts()) : the_post(); ?>
                        <article class="post-wrap ">
                         <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <div class="post-media">
                            
                                <?php if ( has_post_thumbnail() ) { ?>
                                <?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') ); ?>
                                <div class="thumbnails">
                                    <div class="thumbnaila no-border no-padding">
                                        <div class="media">  
                                    <img class="img-responsive" src="<?php echo esc_url($url);?>" alt="Blog_image">
                                           
                                        </div>
                                    </div>
                                </div>
                                 <?php }  ?>
                                
                                
                            </div>
                            <div class="post-header">
                                <h2 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
                                <div class="post-meta"><span class="post-date"><i class="fa fa-clock-o"></i> <span class="month"><?php echo esc_attr(get_the_date('F'));?></span> <span class="day"><?php echo esc_attr(get_the_date('j, '));?></span><span class="year"><?php echo esc_attr(get_the_date('Y'));?></span></span> <span class="sep"></span> <i class="fa fa-user"></i> <a href="#"><?php the_author();?></a> <span class="sep"></span> <i class="fa fa-comment"></i> <?php comments_popup_link(__('0 Comments', 'traveline'), __('1 Comment', 'traveline'), '% '.__('Comments', 'traveline')); ?></div>
                            </div>
                            <div class="post-body">
                                <div class="post-excerpt"><?php the_excerpt(); ?></div>
                            </div>
                            <?php wp_link_pages(); ?>
                            <div class="post-footer">
                                <span class="post-readmore"><a href="<?php the_permalink();?>" class="readmore-link btn button"><?php _e('Continue reading','traveline')?></a></span>
                            </div>
                            </div><!-- END Blog Item -->
                        </article><!-- /.post-wrap -->
                    
                         <?php endwhile;
                          endif;
                          wp_reset_postdata(); ?>
                    
                        <hr class="transparent"/>

                        <div class="pagination-holder right">
                            <?php Navigation::mi_paging_nav(); ?>
                        </div>

                    </div>

                    <div class="sidebar col-md-3 col-sm-6">
                    <?php if ( is_active_sidebar( 'blog_sidebar' ) ) { 
                         dynamic_sidebar( 'blog_sidebar' ); 
                        } ?>                        
                    </div>  
                    <div style="display:none;"><?php the_tags(); the_post_thumbnail();  next_posts_link(); previous_posts_link();comment_form(); wp_enqueue_script('comment-reply');?></div>              
                </div>
                </div>
</section><!-- /#more-pages.section -->
           
<?php get_footer();?>