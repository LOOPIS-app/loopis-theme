<?php
/**
 * Show post actions for admin
 * 
 * Work in progress: collecting more admin tools.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="post-actions">

<!-- Fetcher forgot to press fetch? -->
<?php if (in_category( 'locker' )) : ?>
        <?php if(isset($_POST['fetcher_fetched'])) { admin_action_fetched ($post_id); } ?>
        <form method="post" class="arb" action=""><button name="fetcher_fetched" type="submit" class="blue small" onclick="return confirm('Har saken hämtats?')">Hämtat</button></form>
        <p class="info">Har hämtaren glömt tryck hämta? Tryck på knappen.</p>
<?php endif;?>

<!-- Fetcher will not fetch?  -->
<?php if (in_category( array( 'locker', 'booked', 'booked_custom' )) && $queue_total > 0):

		// Include neccessary functions 
		include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-regret-admin.php'; 

        // Output button for admin action
		 if(isset($_POST['fetcher_regret'])) { admin_action_fetcher_regret ($post_id); } ?>
        <form method="post" class="arb" action=""><button name="fetcher_regret" type="submit" class="red small" onclick="return confirm('Boka för nästa i kön?')">Aktivera kö</button></form>
        <p class="info">Kommer hämtaren inte att hämta? Tryck på knappen för att boka för nästa i kön.</p>
<?php endif;?>

<!-- Item disappeared? -->
<?php if (in_category('disappeared')) :
    $fetcher = get_post_meta($post_id, 'fetcher', true); 
    if(!empty($fetcher) && (int) $fetcher > 0) {
        if (isset($_POST['coin-back'])) {
            loopis_ledger_add_post('cancelled', $fetcher, $post_id,['timestamp' => $timestamp, 'type'=>'disappeared']);
            delete_post_meta($post_id, 'fetcher'); 
        } } ?>
	<form method="post"><button type="submit" class="orange small" name="coin-back">Ge mynt tillbaka</button></form>
    <p class="info">Ska hämtaren få ett mynt tillbaka? Tryck på knappen.</p>
	<?php endif; ?>

</div><!--post-actions-->