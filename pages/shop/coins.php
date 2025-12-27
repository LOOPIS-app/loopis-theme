<?php
/**
 * Shop: start (page-shop.php)
 * Content overview.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:35px; width: auto;"> Köp regnbågsmynt</h1>
<hr>
<p class="small">💡 Här köper du regnbågsmynt</p>

<p>Med 5 regnbågsmynt kan du hämta fem saker utan att själv ge bort något.</p>
<p><span class="link"><a href="hur-funkar-regnbagsmynt">📌 Hur funkar regnbågsmynt?</a></span></p>
<?php echo do_shortcode('[wpum_register form_id="4"]'); ?>

<?php insert(20); ?>
<p class="info"> We will use the payment link from Stripe below instead?</p>
<?php insert(20); ?>

<h3>Payment link from Stripe</h3>
<hr>
<p>Köp 5 regnbågsmynt för 50 kr.</p>
<p><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:15px; width: auto;">
<img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:15px; width: auto;">
<img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:15px; width: auto;">
<img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:15px; width: auto;">
<img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:15px; width: auto;"></p>
<p><button type="submit"><a href="https://buy.stripe.com/test_dRm7sL5l05Bk7IKfNZcV200">Gå till betalning</a></button></p>
