<?php
/**
 * Block for routing visitor to FAQ on post
 *
 * Included in single.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}   

$faq_url = home_url( '/faq/' );
?>

<div class="columns"><div class="column1"><h3>💡 Vanliga frågor</h3></div>
<div class="column2"><a href="<?php echo esc_url(network_home_url( '/faq/')); ?>">Alla frågor & svar →</a></div></div>
<hr>
<p><span class="big-link"><a href="<?php echo esc_url(network_home_url( '/faq/hur-funkar-loopis/')); ?>">📌 Hur funkar LOOPIS?</a></span></p>
<p><span class="big-link"><a href="<?php echo esc_url(network_home_url( '/faq/varfor-medlemskap/')); ?>">📌 Varför måste jag vara medlem?</a></span></p>
<p><span class="big-link"><a href="<?php echo esc_url(network_home_url( '/faq/varfor-bagis/')); ?>">📌 Varför måste jag bo i Bagis?</a></span></p>
