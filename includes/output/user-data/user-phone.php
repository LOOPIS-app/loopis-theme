<?php
/**
 * Show user phone (with link to send SMS)
 *
 * Used in author.php & admin area
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Uses $user_id passed from author.php
$user_phone = get_the_author_meta('wpum_phone', $user_id);
if (!$user_phone) {
	echo '–';
	return;
}

// Output phone with SMS link and copy button
echo '<a href="sms:' . esc_attr($user_phone) . '" onclick="return confirm(\'Vill du skicka sms till användaren?\')">' . esc_html($user_phone) . '</a>';
echo ' <span class="copy_user_info" role="button" tabindex="0" aria-label="Copy Phone" title="Copy Phone">
<i class="far fa-copy"></i></span>';