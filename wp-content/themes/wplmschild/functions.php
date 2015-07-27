<?php

// Essentials
include_once 'includes/config.php';
include_once 'includes/register.php';
include_once 'setup/setup.php';

//add_action('wp_enqueue_scripts', 'vibe_wplms_child_js');
//function vibe_wplms_child_js(){
//	wp_enqueue_script( 'child-custom-js', get_stylesheet_directory_uri().'/custom.js',array('jquery'));
//
//	wp_enqueue_style( 'animation-css', VIBE_URL . '/css/animate.css' );
//}

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { ?>

    <h3>Extra profile information</h3>

    <table class="form-table">

        <tr>
            <th><label for="user_short_description">Description</label></th>

            <td>
                <textarea name="user_short_description" id="user_short_description" class="regular-text" ><?php echo esc_attr( get_the_author_meta( 'user_short_description', $user->ID ) ); ?></textarea><br />
                <span class="description">Please enter teacher description.</span>
            </td>
        </tr>

    </table>
<?php }

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {

    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;

    /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
    update_user_meta( $user_id, 'user_short_description', $_POST['user_short_description'] );
}

?>
