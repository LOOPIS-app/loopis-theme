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
<h7><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:30px; width: auto; padding-top:5px;"> Köp mynt</h7>
<hr>
<?php
// Get current user ID
$user_id = get_current_user_id();
$coins = get_user_meta($user_id, 'loopis_balance', true);
?>

<p>Du har just nu <?php echo $coins; ?> mynt och kan därför paxa och hämta <?php echo $coins; ?> saker.</p>
<p class="small">💡 Mer information finns på <span class="link"><a href="<?php echo esc_url(network_home_url('/user')); ?>">👤 Min profil</a></span></p>

<!--Buy coins-->
<button type="button" class="green" onclick="window.location.href='<?php echo esc_url(add_query_arg('option', 'coins-stripe', network_home_url('/shop/'))); ?>'">Köp mynt</button>
<p class="info">Tryck på knappen för att gå till betalning.</p>

<h7>😎 Pausa annonser</h7>
<hr>
<p>Ska du resa bort? Pausa dina annonser tillfälligt.</p>
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
