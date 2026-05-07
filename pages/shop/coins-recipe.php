<?php
/**
 * Shop: coins-recipe (dynamic loading from page-shop.php)
 * Content overview.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:35px; width: auto;"> Köp regnbågsmynt</h1>
<hr>
<p>Din betalning är genomförd.</p>

<a class="wpum-message success" href="<?php echo esc_url($home_url); ?>">
<p>✅ 5 regnbågsmynt köpta!</p>
</a>

<p><span class="link"><a href="<?php echo esc_url(home_url("/")); ?>">🎁 Saker att få</a></span></p>
<p><span class="link"><a href="<?php echo esc_url(add_query_arg('option', 'coins', home_url('/shop/'))); ?>">🛒 Köp 5 mynt till</a></span></p>
<p><span class="link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-regnbagsmynt')); ?>">📌 Hur funkar regnbågsmynt?</a></span></p>