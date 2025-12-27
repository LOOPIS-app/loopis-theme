<?php
/**
 * Helper functions for reading the loopis_settings table.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function loopis_get_setting($key, $default = '') {
    global $wpdb;
    $table = $wpdb->prefix . 'loopis_settings';
    $value = $wpdb->get_var($wpdb->prepare("SELECT setting_value FROM $table WHERE setting_key = %s", $key));
    return ($value !== null) ? $value : $default;
}