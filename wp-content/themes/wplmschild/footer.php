<footer>
    <div class="container">
        <div class="row">
            <div class="footertop">
                <?php 
                            if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar('topfootersidebar') ) : ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="footerbottom">
                <?php 
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar('bottomfootersidebar') ) : ?>
                <?php endif; ?>
            </div>
        </div>
    </div> 
    <div id="scrolltop">
        <a><i class="icon-arrow-1-up"></i><span><?php _e('top','vibe'); ?></span></a>
    </div>
</footer>
</div><!-- END PUSHER -->
</div><!-- END MAIN -->
	<!-- SCRIPTS -->
<?php
wp_footer();
?>    
<?php
echo vibe_get_option('google_analytics');
?>
</body>
</html>