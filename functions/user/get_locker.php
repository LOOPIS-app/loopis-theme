<?php
/**
 * Helper functions for reading the loopis_lockers table.
 * 
 * Work in progress! We need to handle multiple areas and lockers.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get locker data from loopis_lockers table
 *
 * @param string $locker_id The locker ID to look up
 * @return string|null The locker code or null if not found
 */

function get_locker_data($locker_id, $field, $default = '') {
    global $wpdb;
    $table = $wpdb->prefix . 'loopis_lockers';
    $value = $wpdb->get_var($wpdb->prepare("SELECT `$field` FROM $table WHERE locker_id = %s", $locker_id));
    return ($value !== null) ? $value : $default;
}


/**
 * Get locker code from loopis_lockers table
 *
 * @param string $locker_id The locker ID to look up
 * @return string|null The locker code or null if not found
 */
function get_locker_code($locker_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'loopis_lockers';

    $locker_code = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT locker_code FROM $table WHERE locker_id = %s",
            $locker_id
        )
    );

    return $locker_code;
}