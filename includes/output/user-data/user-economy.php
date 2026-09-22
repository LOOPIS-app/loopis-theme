<?php
/**
 * Output summary of user activity
 * 
 * $user_id is set from author.php
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Set author user ID
$user_id = get_queried_object_id(); // redundant?
$first_name = get_user_meta($user_id, 'first_name', true);

// Get profile economy
$profile_economy = loopis_ledger_economy($user_id);
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
?>

<p class="small">💡 Aktivitet och ekonomi.</p>
<h7>🧮 <?php echo $first_name;?>s aktivitet</h7>
<hr>

<div class="economy wrapped">
<p><span class="left text-left">Regnbågsmynt</span> <span class="right"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:15px; width: auto;" /></span></p>
<hr>
<p class="group"><span class="left"><b><?php echo $payments_membership; ?></b> köp av medlemskap</span> <span class="plus right">+<?php echo $membership_coins; ?></span></p>
<p class="group"><span class="left"><b><?php echo $payments_coins; ?></b> köp av mynt</span> <span class="plus right">+<?php echo $bought_coins; ?></span></p>
<p class="group"><span class="left"><b><?php echo $count_given; ?></b> saker lämnade</span> <span class="plus right">+<?php echo $count_given; ?></span></p>
<p class="group"><span class="left"><b><?php echo $count_booked; ?></b> saker hämtade/paxade</span>&nbsp; &nbsp;<span class="minus right">–<?php echo $count_booked; ?></span></p>
<p class="group"><span class="left"><b><?php echo $clovers; ?></b> fyrklöver</span> <span class="plus right">+<?php echo $clover_coins; ?></span></p>
<p class="group"><span class="left"><b><?php echo $stars; ?></b> guldstjärnor</span> <span class="plus right">+<?php echo $star_coins; ?></span></p>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $coins; ?></b></span></p>
</div>

<!-- CLOVERS -->
<div class="economy wrapped">
<p><span class="left">Fyrklöver</span> <span class="right">🍀</span></p>
<hr>
<p><span class="left"><b><?php echo $count_submitted; ?></b> annonser skapade</span>&nbsp; &nbsp;<span class="plus right">+<?php echo $count_submitted; ?></span></p>
<p><span class="left"><b><?php echo $count_booked; ?></b> saker hämtade</span> <span class="plus right">+<?php echo $count_booked; ?></span></p>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $clovers; ?></b></span></p>

<p class="small">
<?php if ($clover_coins > 0) { ?>
→ <b><?php echo $clover_coins; ?> mynt</b> i belöning! 🎉
<?php } else { ?>→ Inga mynt i belöning.
<?php } ?>
</p>
</div>

<!-- STARS -->
<div class="economy wrapped">
<p><span class="left">Guldstjärnor</span><span class="right">🌟</span></p>
<hr>
<?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-rewards.php'; ?>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $stars; ?></b></span></p>

<p class="small">
<?php if ($star_coins > 0) { ?>
→ <b><?php echo $star_coins; ?> mynt</b> i belöning! 🎉 
<?php } else { ?>→ Inga mynt i belöning.
<?php } ?>
</p>
</div>

<p class="small">
<?php if ($clovers >= 10) {  $remainder = $clovers % 10; $remaining = 10 - $remainder; ?>
💡 Samla <?php echo $remaining; ?> fyrklöver för att få nästa mynt.<br>
<?php } ?>
<?php if ($clovers < 10) { $remaining = 10 - $clovers; ?>
💡 Samla <?php echo $remaining; ?> fyrklöver så får du ett mynt!<br>
<?php } ?>
<a href="<?php echo esc_url(network_home_url( '/faq/hur-funkar-beloningar/')); ?>">📌 Hur funkar belöningar?</a>
</p>


<!--PAYMENTS-->	
<h3>📒 Kvitton</h3>
<hr>
<p><?php echo $first_name; ?>s betalningar till föreningen:</p>
<?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-payments.php'; ?>

