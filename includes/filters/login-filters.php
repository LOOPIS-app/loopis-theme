<?php
/**
 * Filters controlling login and admin access for non-admin users.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

/**
 * Disable wp-admin for non-admin users
 */
add_action( 'admin_init', function() {
    if ( is_admin() && !current_user_can('manage_options') && !wp_doing_ajax() ) {
        wp_redirect( home_url() );
        exit;
    }
} );


/**
 * Redirect wp-login.php on subsites to the mainsite login.
 */
add_action('login_init', function() {
    if ((int) get_current_blog_id() !== (int) get_main_site_id()) {
        wp_redirect(network_site_url('wp-login.php'));
        exit;
    }
});


/**
 * Skips logout confirmation
 * 
 * @return void
 */
add_action('check_admin_referer', 'logout_without_confirm', 10, 2);
function logout_without_confirm($action, $result)
{
    /**
     * Allow logout without confirmation
     */
    if ($action == "log-out" && !isset($_GET['_wpnonce'])) {
        $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : 'https://loopis.app';
        $location = str_replace('&amp;', '&', wp_logout_url($redirect_to));
        header("Location: $location");
        die;
    }
}

/**
 * Lengthens login time (borrowed from web)
 * 
 * @return int one year in time
 */
function keep_me_logged_in_for_1_year( $expirein ) {
    return 31556926; // 1 year in seconds
}

add_filter( 'auth_cookie_expiration', 'keep_me_logged_in_for_1_year' );