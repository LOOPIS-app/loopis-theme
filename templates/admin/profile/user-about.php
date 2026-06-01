<?php
/**
 * Template for displaying WPUM profile tab content.
 *
 * Modified by LOOPIS.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current user ID
$user_id = get_queried_object_id();
$user = get_user($user_id);
?>

<!-- OUTPUT -->
<p class="small">💡 <?php echo $first_name; ?>s... medlemskap.</p>
<div class="columns"><div class="column1"><h7 style="padding-top: 0">📋 Medlemskap</h7></div>
<div class="column2"></div></div>
<hr>

<div class="wrapped">
<p>👤 Användarnamn: <b><?php echo $user->user_login ?></b></p>
<p>✉ E-post: <b><?php echo antispambot($user->user_email); ?></b></p>
<p>📱 Mobilnummer: <b><span class="unclickable"><?php echo antispambot($user->wpum_phone); ?></span></b></p>
<p>📍 Område: <b><?php include_once LOOPIS_THEME_DIR . '/templates/user/profile/user-area.php'; ?></b></p>
</div>

