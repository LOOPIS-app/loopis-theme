<?php
/**
 * Show user age on profile.
 *
 * Used in author.php & admin area
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Uses $user_id passed from author.php
$birthyear = get_user_meta($user_id, 'wpum_birthyear', true);
$birthyear_int = intval($birthyear);
// Calculate current year and user's age
if ($birthyear_int > 0) {
    $current_year = intval(date('Y'));
    $age = $current_year - $birthyear_int;
    $output = "🚼 $birthyear_int ≈ $age år";
} else {
    $output = "🚼 Okänd";
}


// Output
echo esc_html($output);