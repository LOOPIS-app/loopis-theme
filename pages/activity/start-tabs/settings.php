<?php
/**
 * Settings tab.
 * 
 * Showing activity settings for the current user.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Extra php functions
include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-pause.php'; 

?>

<h7>😎 Pausa annonser</h7>
<hr>
<p>Ska du resa bort? Här kan du pausa dina annonser tillfälligt.</p>
<p class="small">💡 Pausade annonser visas inte för andra och kan inte paxas.<br>
💡 Annonser som redan är paxade eller väntar på lottning kan inte pausas.</p>

<?php if ($count_posts_old > 0) { ?>
<?php if(isset($_POST['pause_ads'])) { action_pause_all($user_ID); } ?>
	<form method="post" class="arb" action=""><button name="pause_ads" type="submit" class="yellow small" onclick="return confirm('Vill du pausa <?php echo $count_posts_old; ?> annonser?')">Pausa <?php echo $count_posts_old; ?> annonser</button></form>
	<p class="info">Tryck på knappen för att pausa dina aktiva annonser.</p>
<?php } ?>

<?php if ($count_posts_paused > 0) { ?>
<?php if(isset($_POST['unpause_ads'])) { action_unpause_all($user_ID); } ?>
	<form method="post" class="arb" action=""><button name="unpause_ads" type="submit" class="yellow small" onclick="return confirm('Vill du aktivera <?php echo $count_posts_paused; ?> annonser?')">Aktivera <?php echo $count_posts_paused; ?> annonser</button></form>
	<p class="info">Tryck på knappen för att aktivera dina pausade annonser.</p>
<?php } ?>

<?php if ($count_posts_paused === 0 && $count_posts_old === 0) { ?>
	<p>💢 Du har inga annonser att pausa eller aktivera.</p>
<?php } ?>

<p><span class="big-link"><a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">🚪 Logga ut</a></span> från LOOPIS.app</p>