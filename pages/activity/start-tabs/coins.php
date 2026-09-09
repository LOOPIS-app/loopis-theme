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

<h7>👛 Mina mynt</h7>
<hr>
<div class="wrapped">
<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="Mynt:" class="symbol"><?php echo $coins; ?></h1>
<p class="small">Du kan just nu paxa och hämta <?php echo $coins; ?> saker.</p>
<hr>
<p class="small">💚 <?php echo $count_given; ?> saker lämnade</p>
<p class="small">❤ <?php echo $count_booked; ?> saker hämtade (inkl. paxade)</p>
<p class="small">🍀 <?php echo $clovers; ?> fyrklöver</p>
<p class="small">🌟 <?php echo $stars; ?> guldstjärnor</p>
</div><!-- wrapped -->

<!--Info-->
<p class="small">💡 Detaljerad lista över din aktivitet finns på <span class="link"><a href="<?php echo esc_url(network_home_url('/user')); ?>">📋 Mitt medlemskap</a></span></p>


<!--Buy coins-->
<p><button type="button" class="green" onclick="window.location.href='<?php echo esc_url(add_query_arg('option', 'coins-stripe', network_home_url('/shop/'))); ?>'">Köp mynt</button></p>

<!--FAQ-->
<p><span class="link"><a href="<?php echo esc_url(network_home_url('/faq/hur-funkar-regnbagsmynt')); ?>">📌 Hur funkar regnbågsmynt?</a></span></p>