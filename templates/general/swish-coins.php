<?php
/**
 * Button for buying coins via Swish.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Set variables
$fee = 50;
$coins = 5;
$number = '0739993265';
$number_dash = '073-9993265';
$receiver = 'föreningens kassör Tone Alin';

$parameters = [
    'sw' => $number,
    'amt' => $fee,
    'msg' => "LOOPIS - $coins mynt"
];

$swish_url = 'https://app.swish.nu/1/p/sw/?'. http_build_query($parameters);
?>

<p><button class="green" onclick="window.location.href='<?php echo esc_url($swish_url); ?>'">💸 Swisha <?php echo esc_html($fee); ?> kr</button></p>

<p class="small">💡 Betalningen går till <?php echo $receiver; ?>: <span class="link"><a href="sms:<?php echo $number_dash; ?>">📱<?php echo $number_dash; ?></a></span></p>