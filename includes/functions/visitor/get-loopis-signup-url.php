<?php
/**
 * Build a main-site signup URL for visitors.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function get_loopis_signup_url() {
        if (is_multisite()) {
            return network_site_url('wp-signup.php', 'login');
        }

        return wp_registration_url();
    }
