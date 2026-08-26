<?php
/**
 * Show user email (with link to send email)
 *
 * Used in author.php & admin area
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Get user email
$user_email = get_the_author_meta('user_email', $user_id);
if (!$user_email) {
	echo '–';
	return;
}

// Output email with mailto link and copy button
echo '<a href="' . esc_url('mailto:' . $user_email) . '" onclick="return confirm(\'Vill du maila användaren?\')">' . esc_html($user_email) . '</a>';
echo ' <span class="copy_user_info" role="button" tabindex="0" aria-label="Copy Email" title="Copy Email">
<i class="far fa-copy"></i></span>';