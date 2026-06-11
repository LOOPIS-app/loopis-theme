<?php
/**
 * Build a main-site login URL with redirect_to back to current subsite URL.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function loopis_theme_get_network_login_url($redirect_url = '') {
        $redirect_url = (string) $redirect_url;

        if ('' === $redirect_url) {
            $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '/';
            $request_path = (string) wp_parse_url($request_uri, PHP_URL_PATH);
            $request_query = (string) wp_parse_url($request_uri, PHP_URL_QUERY);

            if ('' === $request_path) {
                $request_path = '/';
            }

            $home_path = (string) wp_parse_url(home_url('/'), PHP_URL_PATH);
            if (!empty($home_path) && '/' !== $home_path) {
                $normalized_home_path = untrailingslashit($home_path);
                if (0 === strpos($request_path, $normalized_home_path)) {
                    $request_path = substr($request_path, strlen($normalized_home_path));
                }
            }

            $request_path = '/' . ltrim((string) $request_path, '/');
            $request_target = $request_path;
            if ('' !== $request_query) {
                $request_target .= '?' . $request_query;
            }

            $redirect_url = wp_validate_redirect(home_url($request_target), home_url('/'));
        }

        return add_query_arg('redirect_to', $redirect_url, network_site_url('wp-login.php', 'login'));
    }