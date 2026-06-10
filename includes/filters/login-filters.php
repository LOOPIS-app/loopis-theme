<?php
/**
 * Disable wp-admin for non-admin users
 * 
 * This hook hides the WordPress admin-space for users who do not have administrative privileges.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

/**
 * Force admin redirect
 */
add_action( 'admin_init', function() {
    if ( is_admin() && !current_user_can('manage_options') && !wp_doing_ajax() ) {
        wp_redirect( home_url() );
        exit;
    }
} );


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