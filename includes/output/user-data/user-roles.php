<?php
/**
 * Output user roles.
 *
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get user roles
$user = get_userdata($user_id);
$roles = $user ? $user->roles : array();
$output = !empty($roles) ? implode(', ', $roles) : '–';


// Output
echo esc_html($output);