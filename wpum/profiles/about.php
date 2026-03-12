<?php
/**
 * The Template for displaying the profile about tab content.
 *
 * This template can be overridden by copying it to yourtheme/wpum/profiles/about.php
 *
 * HOWEVER, on occasion WPUM will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current user ID
$user_id = get_current_user_id();
$user = wp_get_current_user();

//Get author link
$authorlink = get_author_posts_url($user_id);
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

<p><span class="big-link"><a href="<?php echo $authorlink ?>">👥 Din profil</a></span> som den visas för andra</p>
<p><span class="big-link"><a href="../../profile-settings">⚙ Inställningar</a></span> för medlemskap</p>
<p><span class="big-link"><a href="../../wp-login.php?action=logout">🚪 Logga ut</a></span> från LOOPIS.app</p>

<!--h3>Integritet</h3>
<hr>
<p class="info"><span>👤<?php echo $user->display_name ?></span>visas för alla när du 1⃣ lägger upp en annons och 2⃣ visar intresse för en annons.</p>
<p class="info"><span>📱<?php echo $user->wpum_phone ?></span> visas endast för 1⃣ givare/mottagare vid hämtning på annan plats än skåpet och 2⃣ utlånaren när du vill låna något.</p>
<p class="info"><span>✉<?php echo antispambot($user->user_email); ?></span> visas inte för någon.</p>
<p>Gå till <a href="../../integritetspolicy"><span class="big-link">🗄 Integritetspolicy</span></a></p-->