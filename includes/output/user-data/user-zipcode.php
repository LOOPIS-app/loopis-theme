<?php
/**
 * Output user postal code (zipcode).
 *
 * $user_id has to be passed from context!
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get user postal code
$zipcode = get_user_meta($user_id, 'wpum_postcode', true);

if (empty($zipcode)) {
    $zipcode = 'Okänt';
    echo esc_html($zipcode);
    return;
}

$maps_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($zipcode . ', Sweden');
echo '<a href="' . esc_url($maps_url) . '" target="_blank" rel="noopener noreferrer">' . esc_html($zipcode) . '</a>';