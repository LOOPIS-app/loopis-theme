<?php
/**
 * Helper function for updating the loopis_lockers table.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function update_locker($locker_id, $field, $value) {
    global $wpdb;
    $table = $wpdb->prefix . 'loopis_lockers';

    if ($locker_id === '' || $field === '') {
        return false;
    }

    return (bool) $wpdb->update(
        $table,
        array($field => $value),
        array('locker_id' => $locker_id)
    );
}
