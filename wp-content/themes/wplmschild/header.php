<?php
//Header File
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<title>
<?php echo wp_title('|',true,'right'); ?>
</title>
<?php

$layout = vibe_get_option('layout');
if(!isset($layout) || !$layout)
    $layout = '';

wp_head();
?>
</head>
<body <?php body_class($layout); ?>>
<div id="global" class="global">
    <div class="pagesidebar">
        <div class="sidebarcontent">    
            <h2 id="sidelogo">
            <a href="<?php echo vibe_site_url(); ?>"><img src="<?php  echo apply_filters('wplms_logo_url',VIBE_URL.'/images/logo.png'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
            </h2>
            <?php
                $args = apply_filters('wplms-mobile-menu',array(
                    'theme_location'  => 'mobile-menu',
                    'container'       => '',
                    'menu_class'      => 'sidemenu',
                    'fallback_cb'     => 'vibe_set_menu',
                ));

                wp_nav_menu( $args );
            ?>
        </div>
        <a class="sidebarclose"><span></span></a>
    </div>  
    <div class="pusher">
        <?php
            $fix=vibe_get_option('header_fix');
        ?>
        <header class="<?php if(isset($fix) && $fix){echo 'fix';} ?>">
            <div class="container">
                <div id="searchdiv">
                    <form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
                        <div><label class="screen-reader-text" for="s">Search for:</label>
                            <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e('Hit enter to search...','vibe'); ?>" />
                            <?php 
                                $course_search=vibe_get_option('course_search');
                                if(isset($course_search) && $course_search)
                                    echo '<input type="hidden" value="course" name="post_type" />';
                            ?>
                            <input type="submit" id="searchsubmit" value="Search" />
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-9 col-sm-6 col-xs-12">
                        <?php

                            if(is_home()){
                                echo '<h1 id="logo">';
                            }else{
                                echo '<h2 id="logo">';
                            }
                        ?>
                        
                            <a href="<?php echo vibe_site_url(); ?>"><img src="<?php  echo apply_filters('wplms_logo_url',VIBE_URL.'/images/logo.png'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
                        <?php
                            if(is_home()){
                                echo '</h1>';
                            }else{
                                echo '</h2>';
                            }

                            $args = apply_filters('wplms-main-menu',array(
                                 'theme_location'  => 'main-menu',
                                 'container'       => 'nav',
                                 'menu_class'      => 'menu',
                                 'walker'          => new vibe_walker,
                                 'fallback_cb'     => 'vibe_set_menu'
                             ));
                            wp_nav_menu( $args ); 
                        ?>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div id="searchicon"><i class="icon-search-2"></i></div>
                        <?php
                            if ( function_exists('bp_loggedin_user_link') && is_user_logged_in() ) :
                                ?>
                                <ul class="topmenu">
                                    <li><a href="<?php bp_loggedin_user_link(); ?>" class="smallimg vbplogin"><?php $n=vbp_current_user_notification_count(); echo ((isset($n) && $n)?'<em></em>':''); bp_loggedin_user_avatar( 'type=full' ); ?><span><?php bp_loggedin_user_fullname(); ?></span></a></li>
                                    <?php
                                    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists('is_plugin_active_for_network') && is_plugin_active_for_network( 'woocommerce/woocommerce.php'))) { global $woocommerce;
                                    ?>
                                    <li><a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="smallimg vbpcart"><span><?php echo (($woocommerce->cart->cart_contents_count)?'<em>'.$woocommerce->cart->cart_contents_count.'</em>':''); ?></span></a></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            <?php
                            else :
                                ?>
                                <ul class="topmenu">
                                    <li><a href="#login" class="smallimg vbplogin"><span><?php _e('LOGIN','vibe'); ?></span></a></li>
                                    <?php
                                    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists('is_plugin_active_for_network') && is_plugin_active_for_network( 'woocommerce/woocommerce.php'))) { global $woocommerce;
                                    ?>
                                    <li><a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="smallimg vbpcart"><span><?php echo (($woocommerce->cart->cart_contents_count)?'<em>'.$woocommerce->cart->cart_contents_count.'</em>':''); ?></span></a></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            <?php
                            endif;
                        ?>
                        <div id="vibe_bp_login">
                        <?php
                            if ( function_exists('bp_get_signup_allowed')){
                                the_widget('vibe_bp_login',array(),array());   
                            }
                        ?>
                       </div> 
                    </div>
                    <a id="trigger">
                        <span class="lines"></span>
                    </a>
                </div>
            </div>
        </header>
        <div class="box_scroll_right_1">
            <a href="#"><img src="<?php echo VIBE_CHILD_URL?>/images/scroll_lichkhaigiang.png" alt="cau lac bo tieng anh, effortless english, dao tao tieng anh, trung tam tieng anh, hoc tieng anh ha noi,tieng anh giao tiep, phuong phap hoc tieng anh scroll_lichkhaigiang.png"></a>
        </div>
        <div class="box_scroll_right_2">
            <a href="#"><img src="<?php echo VIBE_CHILD_URL?>/images/scroll_hoctienganhquaphim.png" alt="cau lac bo tieng anh, effortless english, dao tao tieng anh, trung tam tieng anh, hoc tieng anh ha noi,tieng anh giao tiep, phuong phap hoc tieng anh scroll_hoctienganhquaphim.png"></a>
        </div>
        <div class="controls_dangky">
            <div class="item-0"><a href="/demo"><span class="label">Học thử online</span></a></div>
            <div class="item-1"><a href="/bai-kiem-tra-trinh-do"><span class="label">Test trình độ</span></a></div>
            <div class="item-2"><a href="/uu-dai-khung-a47"><span class="label">Ưu đãi khủng</span></a></div>
            <div class="item-3"><a href="/tu-tin-giao-tiep-tieng-anh-troi-chay-nhu-nguoi-ban-ngu-a12i521.html"><span class="label">Đăng ký học thử</span></a></div>
            <div class="item-4"><a href="/dang-ky-tham-gia-cau-lac-bo-tieng-anh-langmaster-a32"><span class="label">Đăng ký Club</span></a></div>
        </div>

