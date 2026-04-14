<?php
/**
 * Template for displaying WPUM profile tab content.
 * 
 * Created by LOOPIS.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get author user iD
$user_id = get_queried_object_id();

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
?>

<p class="small">💡 Här ser du detaljerad information om din aktivitet.</p>
<div class="columns"><div class="column1"><h7>🧮 Min aktivitet</h7></div>
<div class="column2 small bottom"></div></div>
<hr>

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

<!-- CLOVERS -->
<div class="economy wrapped">
<p>Fyrklöver<span class="right">🍀</span></p>
<hr>
<p><b><?php echo $count_submitted; ?></b> annonser skapade <span class="plus right">+<?php echo $count_submitted; ?></span></p>
<p><b><?php echo $count_booked; ?></b> saker hämtade <span class="plus right">+<?php echo $count_booked; ?></span></p>
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
<p>Guldstjärnor<span class="right">🌟</span></p>
<hr>
<?php include_once LOOPIS_THEME_DIR . '/templates/user/profile/user-rewards.php'; ?>
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
<a href="<?php echo get_permalink( get_page_by_path('hur-funkar-beloningar/') ); ?>">📌 Hur funkar belöningar?</a>
</p>


<!--PAYMENTS-->	
<h3>📒 Mina kvitton</h3>
<hr>
<p>Här är dina registrerade betalningar till föreningen:</p>
<?php include_once LOOPIS_THEME_DIR . '/templates/user/profile/user-payments.php'; ?>
