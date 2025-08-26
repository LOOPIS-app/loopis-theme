<?php
/**
 * Output user gender.
 *
 * Used in author.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Uses $user_id passed from author.php
$gender_key = get_user_meta($user_id, 'wpum_gender', true);

// Define an array mapping the option keys to their labels
$gender_options = array(
    'female' => 'Kvinna',
    'male' => 'Man',
    'nonbinary' => 'Icke-binär',
    'other' => 'Annat',
    'secret' => 'Vill ej uppge',
);

// Get the label for the selected option
$gender_label = isset($gender_options[$gender_key]) ? $gender_options[$gender_key] : 'Okänd';

// Output
echo esc_html($gender_label);