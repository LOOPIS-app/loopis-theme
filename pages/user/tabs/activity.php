<?php
/**
 * Template for displaying LOOPIS user tab content.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current user iD
$user_id = get_current_user_id();

/*
// Get profile economy
$profile_economy = get_economy($user_id);
$payments_membership = $profile_economy['payments_membership'];
$payments_coins = $profile_economy['payments_coins'];
$membership_coins = $profile_economy['membership_coins'];
$bought_coins = $profile_economy['bought_coins'];
$count_given = $profile_economy['count_given'];
$count_booked = $profile_economy['count_booked'];
$count_submitted = $profile_economy['count_submitted'];
$count_deleted = $profile_economy['count_deleted'];
$stars = $profile_economy['stars'];
$star_coins = $profile_economy['star_coins'];
$clovers = $profile_economy['clovers'];
$clover_coins = $profile_economy['clover_coins'];
$coins = $profile_economy['coins'];
*/

// Mock data for now
$payments_membership = 0;
$payments_coins = 0;
$membership_coins = 0;
$bought_coins = 0;
$count_given = 0;
$count_booked = 0;
$count_submitted = 0;
$count_deleted = 0;
$stars = 0;
$star_coins = 0;
$clovers = 0;
$clover_coins = 0;
$coins = 0;
?>

<h3>🧮 Min aktivitet</h3>
<hr>
<p class="small">💡 Information om din aktivitet.</p>

<!--COINS-->
<div class="economy wrapped">
<p>Regnbågsmynt<span class="right"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:15px; width: auto;"></span></p>
<hr>
<p><b><?php echo $payments_membership; ?></b> köp av medlemskap <span class="plus right">+<?php echo $membership_coins; ?></span></p>
<?php if ( $payments_coins > 0 ) { ?>
<p><b><?php echo $payments_coins; ?></b> köp av extra mynt <span class="plus right">+<?php echo $bought_coins; ?></span></p>
<?php } ?>
<p><b><?php echo $count_given; ?></b> saker lämnade <span class="plus right">+<?php echo $count_given; ?></span></p>
<p><b><?php echo $count_booked; ?></b> saker hämtade/paxade <span class="minus right">–<?php echo $count_booked; ?></span></p>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $coins - $clover_coins - $star_coins; ?></b></span></p>
</div>

<!--CLOVERS-->
<div class="economy wrapped">
<p>Fyrklöver<span class="right">🍀</span></p>
<hr>
<p><b><?php echo $count_submitted; ?></b> annonser skapade <span class="plus right">+<?php echo $count_submitted; ?></span></p>
<p><b><?php echo $count_booked; ?></b> saker hämtade <span class="plus right">+<?php echo $count_booked; ?></span></p>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $clovers; ?></b></span></p>
<!-- Reward -->
<p class="small">
<?php if ($clover_coins > 0) { ?>
→ <b><?php echo $clover_coins; ?> mynt</b> i belöning! 🎉
<?php } else { ?>→ Inga mynt i belöning.
<?php } ?>
</p>
</div>

<!--STARS-->
<div class="economy wrapped">
<p>Guldstjärnor<span class="right">🌟</span></p>
<hr>
<?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-rewards.php'; ?>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $stars; ?></b></span></p>
<!--Reward-->
<p class="small">
<?php if ($star_coins > 0) { ?>
→ <b><?php echo $star_coins; ?> mynt</b> i belöning! 🎉 
<?php } else { ?>→ Inga mynt i belöning.
<?php } ?>
</p>
</div>

<!--Info-->
<p class="small">
<?php if ($clovers >= 10) {  $remainder = $clovers % 10; $remaining = 10 - $remainder; ?>
💡 Samla <?php echo $remaining; ?> fyrklöver för att få nästa mynt.<br>
<?php } ?>
<?php if ($clovers < 10) { $remaining = 10 - $clovers; ?>
💡 Samla <?php echo $remaining; ?> fyrklöver så får du ett mynt!<br>
<?php } ?>
</p>

<!--FAQ-->	
<p><span class="link"><a href="<?php echo esc_url(network_home_url( '/faq/hur-funkar-beloningar' )); ?>">📌 Hur funkar belöningar?</a></span></p>

<!--Payments-->	
<h3>📒 Mina kvitton</h3>
<hr>
<p>Dina registrerade betalningar till föreningen.</p>
<?php include_once LOOPIS_THEME_DIR . '/includes/output/user-data/user-payments.php'; ?>
