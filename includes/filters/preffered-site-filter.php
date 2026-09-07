<?php
/**
 * Filter which redirects users once to their preffered.
 * 
 * Always included in functions.php
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */
add_action('template_redirect', function () {
    if (!empty($_COOKIE['stop_redirect'])) return;

    if (
        is_admin()
        || wp_doing_ajax()
        || ( defined( 'REST_REQUEST' ) && REST_REQUEST )
        || ( defined( 'DOING_CRON' ) && DOING_CRON )
        || current_user_can( 'manage_options' )
    ) {
        return;
    }


    $user_id = get_current_user_id();


    $blog_id = (int) get_user_meta($user_id,'primary_blog',true);

    if($blog_id===0){
        return;
        $blog_id = 1;
        update_user_meta($user_id,'primary_blog',1);
    }
    
    setcookie(
        'stop_redirect',
        base64_encode($blog_id),
        [
            'expires'  => time() + 60 * 60 * 24,
            'path'     => '/',
            'secure'   => is_ssl(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]
    );
    $target_url = get_home_url($blog_id, '/');

    if ( ! is_user_member_of_blog( $user->ID, $blog_id) ) {
        wp_safe_redirect( get_home_url( 1, '/' ) );
        exit;
    }

    wp_safe_redirect($target_url);
    exit;
    
});
