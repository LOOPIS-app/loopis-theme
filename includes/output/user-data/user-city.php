<?php
/**
 * Output user city.
 * 
 * TODO: Currently outputs wpum_postcode, which should be used to fetch and store city name in wpum_postarea
 *
 * Used in author.php & admin area
 * $user_id has to be passed from context!
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get user postal code
$city = get_user_meta($user_id, 'wpum_postarea', true);
//
if(empty($city)){
    if(function_exists('loopis_get_city')){
        $city = loopis_get_city(get_user_meta($user_id, 'wpum_postcode', true));
        update_user_meta($user_id, 'wpum_postarea', $city);
    }else{
        $city = 'okänt';
    }
}


// Output
echo esc_html($city);