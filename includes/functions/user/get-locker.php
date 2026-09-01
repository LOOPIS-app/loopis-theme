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
 * Get locker data from loopis_settings table
 *
 * @param string $locker_id The locker ID to look up
 * @return string|null The locker code or null if not found
 */

function get_locker_data($field, $default = '') {
    global $wpdb;

    $table = $wpdb->prefix . 'loopis_settings';

    $key = 'locker_'.$field;
    
    $value = $wpdb->get_var($wpdb->prepare("SELECT setting_value FROM $table WHERE setting_key = %s", $key));
    return ($value !== null) ? $value : $default;
}


/**
 * Get locker code from loopis_settings table
 *
 * @param string $index The index of the locker to look up
 * @return string|null The locker code or null if not found
 */
function get_locker_code() {
    global $wpdb;

    $table = $wpdb->prefix . 'loopis_settings';
    $setting_key = 'locker_code';

    $locker_code = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT setting_value FROM $table WHERE setting_key = %s",
            $setting_key
        )
    );

    return $locker_code;
}

/**
 * Get area privacy from loopis_settings table
 *
 * @param string $index The index of the locker to look up
 * @return string|null The locker code or null if not found
 */
function get_privacy() {
    global $wpdb;

    $table = $wpdb->prefix . 'loopis_settings';
    $setting_key = 'area_privacy';

    $area_privacy = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT setting_value FROM $table WHERE setting_key = %s",
            $setting_key
        )
    );

    return $area_privacy ?? 0;
}


/**
 * Get locker array from loopis_settings table
 * 
 * @return array|null The locker information as an array containing keys: id, name, code, postal_code, full, privacy,  warning_info,  warning_header
 */
function get_locker() {
    global $wpdb;

    $table = $wpdb->prefix . 'loopis_settings';

    $prefix = 'locker_';
    $setting_key_like = $wpdb->esc_like($prefix) . '%';

    $locker = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT setting_key, setting_value FROM {$table} WHERE setting_key LIKE %s",
            $setting_key_like
        ), OBJECT_K
    );

    $locker_arr = [
            "id"      => $locker['locker_id']->setting_value,
            "name"    => $locker['locker_name']->setting_value,
            "code"    => $locker['locker_code']->setting_value,
            "postal_code"    => $locker['locker_postal_code']->setting_value,
            "full"    => $locker['locker_full']->setting_value,
            "privacy" =>  get_privacy(),
            "warning_info" => $locker['locker_warning_info']->setting_value,
            "warning_header" => $locker['locker_warning_header']->setting_value,
        ];
    return $locker_arr;
}

