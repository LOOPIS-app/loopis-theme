<?php
/**
 * Page for Stripe payment confirmation.
 * 
 * Dynamic content of page-shop.php
 * Reached on /shop/?option=coins-recipe
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:35px; width: auto;"> Köp regnbågsmynt</h1>
<hr>
<p class="small">💡 Din betalning är genomförd.</p>

<div class="loopis-message success">
<h5>✅ 5 regnbågsmynt köpta</h5>
<hr>
<p>Gå till <span class="link"><a href="<?php echo esc_url(home_url("/")); ?>">🎁 Saker att få</a></span> för att fortsätta loopa.</p>
</div>

<p><span class="link"><a href="<?php echo esc_url(add_query_arg('option', 'coins', home_url('/shop/'))); ?>">🛒 Köp 5 mynt till</a></span></p>
<p><span class="link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-regnbagsmynt')); ?>">📌 Hur funkar regnbågsmynt?</a></span></p>