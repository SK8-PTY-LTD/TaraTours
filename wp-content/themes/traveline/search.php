<?php defined('ABSPATH') or die("No script kiddies please!");
get_header();
get_template_part('menu-section');?>
<section class="page-head-holder">
                <div class="container">

                    <div class="col-xs-12 col-sm-6">
                        <h2><?php _e('Search','traveline')?></h2>
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
                    <h1 class="post-title pt2"><?php printf( __( 'Search Results for: %s', 'vcard' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                    <?php  
                        if (have_posts()) : 
                        while (have_posts()) : the_post(); ?>
                        <article class="post-wrap ">
                         
                            <div class="post-header">
                                <h2 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
                                <div class="post-meta"><span class="post-date"><i class="fa fa-clock-o"></i> <span class="month"><?php echo esc_attr(get_the_date('F'));?></span> <span class="day"><?php echo esc_attr(get_the_date('j, '));?></span><span class="year"><?php echo esc_attr(get_the_date('Y'));?></span></span> <span class="sep"></span> <i class="fa fa-user"></i> <a href="#"><?php the_author();?></a> <span class="sep"></span> <i class="fa fa-comment"></i> <?php comments_popup_link(__('0 Comments', 'traveline'), __('1 Comment', 'traveline'), '% '.__('Comments', 'traveline')); ?></div>
                            </div>
                            <div class="post-body">
                                <div class="post-excerpt"><?php the_excerpt(); ?></div>
                            </div>
                            <div class="post-footer">
                                <span class="post-readmore"><a href="<?php the_permalink();?>" class="readmore-link btn button"><?php _e('Continue reading','traveline')?></a></span>
                            </div>
                            
                        </article><!-- /.post-wrap -->
                    
                         <?php endwhile;
                          else: ?>
                          <article class="post-wrap ">
                         
                            <div class="post-header">
                                <h2 class="post-title"><?php printf( __( 'Nothing Found: %s', 'traveline' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
                                
                            </div>
                            <div class="post-body">
                                <div class="post-excerpt"><?php _e( 'Sorry, Nothing matched your search criteria. Please try again with some different keywords.', 'traveline' ); ?></div>
                            </div>
                            
                            
                        </article>
                          <?php endif; ?>
                    
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
                
                </div>

                </div>
</section><!-- /#more-pages.section -->
           
<?php get_footer();?>