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
$area_key = get_user_meta($user_id, 'wpum_area', true);

// Define an array mapping the option keys to their labels
    $area_options = array(
        '1' => 'Bagarmossen',
        '2' => 'Skarpnäck',
        '3' => 'Kärrtorp',
        '4' => 'Björkhagen',
        '5' => 'Enskede',
        'other' => 'Annat område',
    );

// Get the label for the selected option
$area_label = isset($area_options[$area_key]) ? $area_options[$area_key] : '';

// Output
echo esc_html('📍 ' . $area_label);