<?php
/**
 * Helper functions for reading the loopis_lockers table.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function loopis_get_locker($locker_id, $field, $default = '') {
    global $wpdb;
    $table = $wpdb->prefix . 'loopis_lockers';
    $value = $wpdb->get_var($wpdb->prepare("SELECT `$field` FROM $table WHERE locker_id = %s", $locker_id));
    return ($value !== null) ? $value : $default;
}