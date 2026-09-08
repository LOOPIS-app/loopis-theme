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
$postalcode = get_user_meta($user_id, 'wpum_postcode', true);

// Convert postalcode to city name
// $city = loopis_get_city_by_postalcode($postalcode);
$city = $postalcode; // Temporary, until loopis_get_city_by_postalcode is fixed

// Output
echo esc_html($city);