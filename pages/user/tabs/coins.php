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
$payments_membership = array ();
$payments_coins = array ();
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

<h3>👛 Mina mynt</h3>
<hr>
<p class="small">💡 Information om dina regnbågsmynt.</p>
<div class="wrapped">
<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="Mynt:" class="symbol"><?php echo $coins; ?></h1>
<p class="small">Du kan just nu hämta <?php echo $coins; ?> saker.</p>
<hr>
<p><span class="label">💚 <?php echo $count_given; ?> saker lämnade</span></p>
<p><span class="label">❤ <?php echo $count_booked; ?> saker hämtade</span></p>
<p><span class="label">🍀 <?php echo $clovers; ?> fyrklöver</span></p>
<p><span class="label">🌟 <?php echo $stars; ?> guldstjärnor</span></p>
</div><!-- wrapped -->

<!--Info-->
<p class="small">💡 Detaljerad lista över din aktivitet finns på nästa flik.</p>

<!--Buy coins-->
<p><button type="button" class="green" onclick="window.location.href='<?php echo esc_url(add_query_arg('option', 'coins-stripe', home_url('/shop/'))); ?>'">Köp mynt</button></p>

<!--FAQ-->
<p><span class="link"><a href="<?php echo esc_url(network_home_url('/faq/hur-funkar-regnbagsmynt')); ?>">📌 Hur funkar regnbågsmynt?</a></span></p>