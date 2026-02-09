<?php
/**
 * Output user area.
 *
 * Used in author.php & admin area
 * $user_id has to be passed from context!
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Uses $user_id passed from author.php
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);

// Output
echo esc_html('👤 ' . $first_name . ' ' . $last_name);