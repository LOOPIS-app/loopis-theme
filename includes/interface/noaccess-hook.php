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
    if ( is_admin() && !current_user_can('administrator') && !wp_doing_ajax() ) {
        wp_redirect( home_url() );
        exit;
    }
} );
