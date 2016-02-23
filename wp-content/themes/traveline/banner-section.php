<section class="page-head-holder">
                <div class="container">
                    <div class="col-xs-12 col-sm-6">
                        <h2><?php the_title();?></h2>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="breadcrumb-holder">
                            <ol class="breadcrumbs breadcrumb pull-right" xmlns:v="http://rdf.data-vocabulary.org/#">
                                <?php if(function_exists('bcn_display'))
                                {
                                    bcn_display();
                                }?>
                            </ol>
                       </div>
                    </div>
                </div>
</section>