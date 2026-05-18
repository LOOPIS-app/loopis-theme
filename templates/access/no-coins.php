<?php
/**
 * Message for members with no coins.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="loopis-message warning">
	<h5>⚠ Slut på regnbågsmynt</h5>
	<p>Du behöver ge bort något eller köpa fler mynt.</p>
	<p><a href="<?php echo esc_url(home_url('/submit'))?>"><span class="link">💚 Ge bort</span></a> &nbsp;<span class="link"><a href="<?php echo esc_url(add_query_arg('option', 'coins', home_url('/shop/')))?>">💰 Köp mynt</a></span></p>
	<p><span class="link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-regnbagsmynt/'))?>">📌 Hur funkar regnbågsmynt?</a></span></p>
</div>