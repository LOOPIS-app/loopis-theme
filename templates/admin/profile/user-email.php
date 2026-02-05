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
$user_email = get_the_author_meta('user_email', $user_id);

// Output
echo '<a href="' . esc_url('mailto:' . $user_email) . '" onclick="return confirm(\'Vill du maila användaren?\')">✉ ' . esc_html($user_email) . '</a>';