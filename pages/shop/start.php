<?php
/**
 * Shop overview page
 * 
 * Dynamic content of page-shop.php
 * Reached on /shop (but currently coins.php is set as the default page)
 * 
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🛒 Shoppen</h1>
<hr>
<p class="small">💡 Vår avdelning för hantering av vanliga pengar</p>

<h3>Köp mynt eller medlemskap</h3>
<hr>
<p><span class="link"><a href="<?php echo esc_url(add_query_arg('option', 'coins', home_url('/shop/'))); ?>">💳 Köp regnbågsmynt</a></span></p>
<p><span class="link"><a href="<?php echo esc_url(add_query_arg('option', 'membership', home_url('/shop/'))); ?>">💳 Köp medlemskap</a></span></p>