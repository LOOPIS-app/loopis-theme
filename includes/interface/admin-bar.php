<?php
/**
 * Disable admin bar for non-admin users
 * 
 * This filter hides the WordPress admin bar for users who do not have administrative privileges.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Disable admin bar for non-admin users
 */
if ( ! current_user_can( 'manage_options' ) ) {
    add_filter( 'show_admin_bar', '__return_false' );
}