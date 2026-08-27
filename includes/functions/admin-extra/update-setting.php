<?php
/**
 * Helper function for updating the loopis_settings table.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Update settings.
 *
 * @param string $key The setting key.
 * @param mixed $value The setting value.
 * @return bool True on success, false on failure.
 */
function loopis_update_setting($key, $value){
    global $wpdb;
    $table = $wpdb->prefix . 'loopis_settings';

    if ($key === '') {
        return false;
    }

    $exists = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE setting_key = %s", $key));
    if ($exists > 0) {
        $result = $wpdb->update(
            $table,
            array('setting_value' => $value),
            array('setting_key' => $key)
        );
    } else {
        $result = $wpdb->insert(
            $table,
            array(
                'setting_key' => $key,
                'setting_value' => $value,
            )
        );
    }

    return $result !== false;
}

/**
 * Store line breaks as <br> and output as newlines.
 */

function loopis_setting_textarea_to_br($value) {
    return str_replace(["\r\n", "\r", "\n"], '<br>', (string) ($value ?? ''));
}

function loopis_setting_textarea_from_br($value) {
    return str_replace(['<br />', '<br/>', '<br>'], "\n", (string) ($value ?? ''));
}
