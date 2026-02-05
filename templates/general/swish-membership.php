<?php
/**
 * Button for paying membership via Swish.
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
?>

<p><button type="submit"><a href="https://app.swish.nu/1/p/sw/?sw=<?php echo $number; ?>&amt=<?php echo $fee; ?>&msg=LOOPIS%20-%20medlemskap">Swisha <?php echo $fee; ?> kr</a></button>
<p class="small">💡 Betalningen går till <?php echo $receiver; ?>: <span class="link"><a href="sms:<?php echo $number_dash; ?>">📱<?php echo $number_dash; ?></a></span></p>