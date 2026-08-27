<?php
/**
 * Redirects from privated sites
 */
add_action( 'template_redirect', function() {
    $area_privacy = filter_var(loopis_get_setting( 'area_privacy', false ),FILTER_VALIDATE_BOOLEAN);

    if (!$area_privacy){
        return;
    }

    if (
        is_admin()
        || wp_doing_ajax()
        || ( defined( 'REST_REQUEST' ) && REST_REQUEST )
        || ( defined( 'DOING_CRON' ) && DOING_CRON )
        || current_user_can( 'manage_options' )
    ) {
        return;
    }

    if ( ! is_user_logged_in() ) {
        wp_safe_redirect(
            wp_login_url()
        );
        exit;
    }

    $user = wp_get_current_user();

    if ( ! is_user_member_of_blog( $user->ID, get_current_blog_id()) ) {
        wp_safe_redirect( get_home_url( 1, '/' ) );
        exit;
    }
} );