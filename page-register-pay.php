<?php
/**
 * Content for page using url /register-pay
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

<h1>🎉 Bli medlem</h1>
<hr>
<p class="small">💡 Verifiera din e-post och betala medlemskap</p>

<h3 style="opacity: 0.5;">1⃣ Fyll i formulär</h3>
<hr style="opacity: 0.5;">
<p style="opacity: 0.5;">✅ Bra jobbat.</p>

<h3>2⃣ Betala medlemskap</h3>
<hr>
<p>→ Tryck på knappen för att betala 50 kronor.</p>

<?php
// Stripe Sandbox?
$test_mode = defined('WP_TEST') && WP_TEST;
if ($test_mode) {
    $payment_link = 'https://buy.stripe.com/test_14A14n9Bg7JsaUWatFcV201';
} else {
    $payment_link = 'https://buy.stripe.com/5kQcN54gZ01Fd1h2wm1wY00';
}
?>

<p><button class="green" onclick="window.location.href='<?php echo esc_url($payment_link); ?>'">💳 Betala 50 kr</button></p>
<p class="info">💡 Problem eller frågor? Maila <a href="mailto:info@loopis.org">info@loopis.org</a></p>

<div class="wpum-message warning">
<p>⚠ OBS! Du måste ange samma e-postadress i betalningen som i formuläret.</p>
</div>

<p><span class="big-link"><a href="<?php echo esc_url(add_query_arg('option', 'swish-membership', home_url('/shop/'))); ?>">💸 Betala med Swish istället</a></span></p>

<h3>3⃣ Verifiera e-postadress</h3>
<hr>
<p>→ Kolla din inkorg och tryck på länken i det mail som vi skickat.</p>

<?php
// Developer info in test mode
if ($test_mode) {
    echo '<div class="admin-block">';
    include_once LOOPIS_THEME_DIR . '/templates/admin/links/developer-link.php';
    echo '<h5>⚠ Testläge!</h5>';
    echo '<hr>';
    echo '<p>Genom att använda kortnummer "4242 4242 4242 4242" simulerar du en betalning med Stripe sandbox.</p>';
    echo '</div>';
}
?>