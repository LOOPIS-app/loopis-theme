<?php
/**
 * Build a main-site logout URL with redirect_to back to the current site.
 *
 * Links straight to the main site's wp-login.php so the subsite
 * login redirect (login_init) does not drop the logout action.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function get_loopis_logout_url($redirect_url = '') {
    $redirect_url = '' !== (string) $redirect_url ? (string) $redirect_url : home_url('/');

    $url = add_query_arg(
        array(
            'action'      => 'logout',
            'redirect_to' => rawurlencode($redirect_url),
        ),
        network_site_url('wp-login.php', 'login')
    );

    return wp_nonce_url($url, 'log-out');
}
