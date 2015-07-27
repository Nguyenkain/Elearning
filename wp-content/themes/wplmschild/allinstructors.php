<?php
/**
 * Template Name: All Instructors
 */
get_header();


$no=999;
$args = apply_filters('wplms_allinstructors',array(
                'role' => 'instructor', // instructor
    			'number' => $no, 
                'orderby' => 'post_count', 
                'order' => 'DESC' 
    		));

$user_query = new WP_User_Query( $args );

$ifield = vibe_get_option('instructor_field');
if(!isset($ifield) || $ifield =='')$ifield='Expertise';

$title=get_post_meta(get_the_ID(),'vibe_title',true);
if(vibe_validate($title)){
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
                    if(vibe_validate($breadcrumbs)){
                     vibe_breadcrumbs();
                    }
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

                    if ( !empty( $user_query->results ) ) {
                        foreach ( $user_query->results as $user ) {
                            ?>
                            <div class="row instructor-list">
                                <div class="col-sm-3 wpb_column vc_column_container ">
                                    <div class="wpb_wrapper">

                                        <div class="wpb_single_image wpb_content_element vc_align_left">
                                            <div class="wrapper">
                                                <?php echo bp_core_fetch_avatar( array( 'item_id' => $user->ID,'type'=>'full', 'html' => true ) ); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-9 wpb_column vc_column_container ">
                                    <div class="wrapper">

                                        <div class="wpb_text_column wpb_content_element ">
                                            <div class="wpb_wrapper">
                                                <p><?php echo get_user_meta($user->ID,'user_short_description', true);?></p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
<!--                            <div class="col-md-4 col-sm-4 clear3">-->
<!--                                <div class="instructor">-->
<!--                                    --><?php //echo bp_core_fetch_avatar( array( 'item_id' => $user->ID,'type'=>'full', 'html' => true ) ); ?>
<!--                                    <span>--><?php
//                                        if(bp_is_active('xprofile'))
//                                            echo bp_get_profile_field_data( 'field='.$ifield.'&user_id=' .$instructor );
//                                        ?><!--</span>-->
<!--                                    <h3>--><?php //echo bp_core_get_userlink( $instructor ); ?><!--</h3>-->
<!--                                    <strong><a href="--><?php //echo get_author_posts_url(  $instructor ).'instructing-courses/'; ?><!--">--><?php //_e('Courses by Instructor ','vibe'); echo  '<span>'.count_user_posts_by_type($instructor,'course').'</span></a>'; ?><!--</strong>-->
<!--                                </div>-->
<!--                                --><?php
//
//                                ?>
<!--                            </div>-->

                        <?php
                        }
                    } else {
                        echo '<div id="message"><p>'.__('No Instructors found.','vibe').'</p></div>';
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 left">
                <div class="sidebar">
                    <?php
                    $sidebar = apply_filters('wplms_sidebar','introductionsidebar',get_the_ID());
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();

?>