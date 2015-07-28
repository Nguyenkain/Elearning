<?php
/**
 * Template Name: Course Page
 */

get_header();
echo do_shortcode('[layerslider id="2"]');
if ( have_posts() ) : while ( have_posts() ) : the_post();

    $title=get_post_meta(get_the_ID(),'vibe_title',true);
    if(vibe_validate($title) || empty($title)){
        ?>
        <section id="title">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 col-sm-8">
                        <div class="pagetitle">
                            <h1><?php the_title(); ?></h1>
                            <?php the_sub_title(); ?>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <?php
                        $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                        if(vibe_validate($breadcrumbs) || empty($breadcrumbs))
                            vibe_breadcrumbs();
                        ?>
                    </div>
                </div>
            </div>
        </section>
    <?php
    }
    ?>
    <section id="content">
    <div class="container">
    <div class="row">
    <div class="col-md-9 col-sm-8 right">
    <div class="content">
        <?php
        the_content();
        echo do_shortcode('[contact-form-7 id="2070" title="Đăng ký học"]');
        ?>
    </div>
<?php

endwhile;
endif;
?>
    </div>
    <div class="col-md-3 col-sm-4 left">
        <div class="sidebar">
            <?php echo do_shortcode('[accordionmenu id="uniquecb8da44" accordionmenu="2153"]')?>
        </div>
    </div>
    </div>
    </div>
    </section>

<?php
get_footer();
?>