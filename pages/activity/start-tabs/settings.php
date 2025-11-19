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
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-pause.php'; 

// Get current user ID
$user_ID = get_current_user_id();

?>
<hr>
<p>Ska du resa bort?<br>
Eller vill du ta en paus från loopandet?<br>
Här kan du tillfälligt pausa dina aktiva annonser.</p>

<p class="small">💡 Pausade annonser listas inte på LOOPIS och kan inte paxas.<br>
💡 Nya och paxade annonser går inte att pausa.</p>

<?php
$user_ID = get_current_user_id();

// Count posts in category "Först till kvarn"
$args = array(
    'author'         => $user_ID,
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'cat'            => '37',
);

$count_cat37 = count(get_posts($args));
wp_reset_postdata();

// Count posts in category "Pausad"
$args = array(
    'author'         => $user_ID,
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'cat'            => '159',
);

$count_cat159 = count(get_posts($args));
wp_reset_postdata();
?>
<div class="wrapped">
<h5>Dina annonser:</h5>
	<hr>
<p>🟢 Först till kvarn: <?php echo $count_cat37; ?> annonser</p>
<p>😎 Pausade: <?php echo $count_cat159; ?> annonser</p>
</div>

<?php if ($count_cat37 > 0) { ?>
<?php if(isset($_POST['pause_ads'])) { action_pause_all($user_ID); } ?>
	<form method="post" class="arb" action=""><button name="pause_ads" type="submit" class="yellow small" onclick="return confirm('Vill du pausa <?php echo $count_cat37; ?> annonser?')">Pausa <?php echo $count_cat37; ?> annonser</button></form>
	<p class="info">Tryck på knappen för att tillfälligt dölja dina aktiva annonser.</p>
<?php } ?>

<?php if ($count_cat159 > 0) { ?>
<?php if(isset($_POST['unpause_ads'])) { action_unpause_all($user_ID); } ?>
	<form method="post" class="arb" action=""><button name="unpause_ads" type="submit" class="small" onclick="return confirm('Vill du aktivera <?php echo $count_cat159; ?> annonser?')">Aktivera <?php echo $count_cat159; ?> annonser</button></form>
	<p class="info">Tryck på knappen för att aktivera dina pausade annonser.</p>
<?php } ?>
