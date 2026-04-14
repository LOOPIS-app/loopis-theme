<?php
/**
 * Button for buying coins via Swish.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get variables
$payment_info = swish_payment();
$fee = $payment_info['fee'];
$coins = $payment_info['coins'];
$number = $payment_info['number'];
$number_dash = $payment_info['number_dash'];
$receiver = $payment_info['receiver'];

$parameters = [
    'sw' => $number,
    'amt' => $fee,
    'msg' => "LOOPIS - $coins mynt"
];

$swish_url = 'https://app.swish.nu/1/p/sw/?'. http_build_query($parameters);
?>

<p>
    <a href="<?php echo esc_url($swish_url); ?>" class="button">
        Swisha <?php echo esc_html($fee); ?> kr
    </a>
</p>
<p class="small">💡 Betalningen går till <?php echo $receiver; ?>: <span class="link"><a href="sms:<?php echo $number_dash; ?>">📱<?php echo $number_dash; ?></a></span></p>