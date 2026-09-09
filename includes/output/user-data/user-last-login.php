<?php
/**
 * Show user latest login.
 * 
 * Should be replaced with latest visit... But that value has to be stored somewhere first?
 *
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get latest login
$last_login_timestamp = get_user_meta($user_id, 'last_login', true);
$last_login = $last_login_timestamp ? human_time_diff((int) $last_login_timestamp, current_time('timestamp')) : 'aldrig';

// Output
echo esc_html($last_login . ' sedan');