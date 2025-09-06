<?php
/**
 * Show user age on profile.
 *
 * Used in author.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Uses $user_id passed from author.php
$birthyear = get_user_meta($user_id, 'wpum_birthyear', true);

// Calculate current year and user's age
$current_year = date('Y');
$age = $current_year - $birthyear;

// Output
echo esc_html($birthyear . ' ≈ ' . $age . ' år');