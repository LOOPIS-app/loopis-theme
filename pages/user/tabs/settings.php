<?php
/**
 * Template for displaying LOOPIS user tab content.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current user ID
$user_id = get_current_user_id();
$user = wp_get_current_user();

?>

<h3>⚙ Mina inställningar</h3>
<hr>
<p class="small">💡 Inställningar för ditt konto.</p>

<div class="wrapped">
<p>👤 Användarnamn: <b><?php echo $user->user_login ?></b></p>
<p>✉ E-post: <b><?php echo antispambot($user->user_email); ?></b></p>
<p>📱 Mobilnummer: <b><span class="unclickable"><?php echo antispambot($user->wpum_phone); ?></span></b></p>
<p>📍 Område: <b><?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-area.php'; ?></b></p>
</div>

<p><span class="big-link"><a href="<?php echo esc_url(home_url('/user/?option=member-form')); ?>">📋 Medlemsregister</a></span> för dina aktuella uppgifter</p>
<p><span class="big-link"><a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>">👥 Din profil</a></span> som den visas för andra</p>
<p><span class="big-link"><a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">🚪 Logga ut</a></span> från LOOPIS.app</p>