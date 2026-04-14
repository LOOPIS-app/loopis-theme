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

//Get author link
$authorlink = get_author_posts_url($user_id);

// Settings page (use network aware URL if needed)
$settings_link = network_site_url('profile-settings', 'https');

// Logout link (WordPress logout function handles redirection)
$logout_link = wp_logout_url(home_url());


?>

<p class="small">💡 Här ser du information och inställningar för ditt konto.</p>
<div class="columns"><div class="column1"><h7 style="padding-top: 0">📋 Mitt medlemskap</h7></div>
<div class="column2"></div></div>
<hr>

<div class="wrapped">
<p>👤 Användarnamn: <b><?php echo $user->user_login ?></b></p>
<p>✉ E-post: <b><?php echo antispambot($user->user_email); ?></b></p>
<p>📱 Mobilnummer: <b><span class="unclickable"><?php echo antispambot($user->wpum_phone); ?></span></b></p>
<p>📍 Område: <b><?php include_once LOOPIS_THEME_DIR . '/templates/user/profile/user-area.php'; ?></b></p>
</div>


<!--h3>Integritet</h3>
<hr>
<p class="info"><span>👤<?php echo $user->display_name ?></span>visas för alla när du 1⃣ lägger upp en annons och 2⃣ visar intresse för en annons.</p>
<p class="info"><span>📱<?php echo $user->wpum_phone ?></span> visas endast för 1⃣ givare/mottagare vid hämtning på annan plats än skåpet och 2⃣ utlånaren när du vill låna något.</p>
<p class="info"><span>✉<?php echo antispambot($user->user_email); ?></span> visas inte för någon.</p>
<p>Gå till <a href="../../integritetspolicy"><span class="big-link">🗄 Integritetspolicy</span></a></p-->