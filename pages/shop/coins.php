<?php
/**
 * Shop: coins (dynamic loading from page-shop.php)
 * Content overview.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:35px; width: auto;"> Köp regnbågsmynt</h1>
<hr>

<p>Vill du hämta saker utan att ge bort något själv?</p>
<p>Här kan du köpa 5 regnbågsmynt för 50 kr.</p>

<div class="wpum-message warning">
<p>⚠ OBS! Du måste ange rätt e-postadress vid betalning: <?php
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    echo '<strong>' . esc_html($current_user->user_email) . '</strong>';
}
?></p>
</div>

<?php
// Stripe Sandbox?
$test_mode = defined('WP_TEST') && WP_TEST;
if ($test_mode) {
    $payment_link = 'https://buy.stripe.com/test_dRm7sL5l05Bk7IKfNZcV200';
    echo '<div class="wpum-message info"><p>⚠ Testläge! Använd testkort 4242 4242 4242 4242.</p></div>';
} else {
    $payment_link = 'https://buy.stripe.com/8x2fZh4gZaGj8L16MC1wY01';
}
?>

<p><button type="submit"><a href="<?php echo esc_url($payment_link); ?>">💳 Betala 50 kr</a></button></p>
<p class="small">💡 Du får dina mynt direkt när betalningen är genomförd.</p>

<p><span class="link"><a href="/faq/hur-funkar-regnbagsmynt">📌 Hur funkar regnbågsmynt?</a></span></p>
<p><span class="link"><a href="/shop/?option=swish-coins">💸 Betala med Swish istället</a></span></p>
