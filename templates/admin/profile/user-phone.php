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
if (!$user_phone) return; // stop if empty

// Inline CSS for copy button
$button_style = "border:none; background:transparent; cursor:pointer; margin-left:0.5rem; 
font-size:1rem; color:#555; transition: color 0.2s ease;";

// Output phone with SMS link and copy button
echo '<span class="user-phone-container">';
echo '<a href="sms:' . esc_attr($user_phone) . '" onclick="return confirm(\'Vill du skicka sms till användaren?\')">📱 ' . esc_html($user_phone) . '</a>';
echo ' <button class="copy_user_info" title="Copy Phone" style="' . $button_style . '" 
onmouseover="this.style.color=\'#000\'" onmouseout="this.style.color=\'#555\'">
<i class="far fa-copy"></i></button>';
echo '</span>';