<?php
/**
 * Block for routing user to FAQ on submit page
 *
 * Included in /pages/submit/start.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$faq_url = home_url( '/faq/' );
?>

<div class="columns"><div class="column1"><h3>Frågor & svar</h3></div>
<div class="column2 bottom"><a href="<?php echo esc_url($faq_url);?>">→ Visa fler</a></div></div>
<hr>
<p><span class="big-link"><a href="<?php echo esc_url($faq_url . 'hur-ger-jag-saker/');?>">📌 Hur ger jag saker?</a></span></p>
<p><span class="big-link"><a href="<?php echo esc_url($faq_url . 'hur-funkar-skapet/');?>">📌 Hur funkar skåpet?</a></span></p>
<p><span class="big-link"><a href="<?php echo esc_url($faq_url . 'saker-som-inte-ryms-i-skapet/');?>">📌 Saker som inte ryms i skåpet?</a></span></p>