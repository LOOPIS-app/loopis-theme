<?php
/**
 * Show user email (with link to send email)
 *
 * Used in author.php & admin area
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Uses $user_id passed from author.php
$user_email = get_the_author_meta('user_email', $user_id);
if (!$user_email) return; // stop if empty

// Inline CSS for copy button
$button_style = "border:none; background:transparent; cursor:pointer; margin-left:0.5rem; 
font-size:1rem; color:#555; transition: color 0.2s ease;";

// Output email with mailto link and copy button
echo '<span class="user-email-container">';
echo '<a href="' . esc_url('mailto:' . $user_email) . '" onclick="return confirm(\'Vill du maila användaren?\')">✉ ' . esc_html($user_email) . '</a>';
echo ' <button class="copy_user_info" title="Copy Email" style="' . $button_style . '" 
onmouseover="this.style.color=\'#000\'" onmouseout="this.style.color=\'#555\'">
<i class="far fa-copy"></i></button>';
echo '</span>';