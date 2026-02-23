<?php
/**
 * Show user email (with link to send email)
 *
 * Used in author.php & admin area
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Uses $user_id passed from author.php
$user_phone = get_the_author_meta('wpum_phone', $user_id);

// Output
echo '<a href="sms:' . esc_attr($user_phone) . '" onclick="return confirm(\'Vill du skicka sms till användaren?\')">📱 ' . esc_html($user_phone) . '</a>';