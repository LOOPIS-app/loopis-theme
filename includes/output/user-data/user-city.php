<?php
/**
 * Output user postal area (place of living).
 * 
 * If not yet stored in user_meta, it will be fetched and stored based on postal code.
 *
 * $user_id has to be passed from context!
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get user postal code
$postarea = get_user_meta($user_id, 'wpum_postarea', true);

// Get user postal area
if(empty($postarea)){
    // Include function for mapping postal code to postal area.
    include_once LOOPIS_USERS_DIR . '/includes/functions/loopis-get-city.php';
    $lookup_city = loopis_get_city(get_user_meta($user_id, 'wpum_postcode', true));

    if ($lookup_city) {
        $postarea = $lookup_city;
        update_user_meta($user_id, 'wpum_postarea', $postarea);
    } else {
        $postarea = 'Okänt';
    }
}

// Output
echo esc_html($postarea);