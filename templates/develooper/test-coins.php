<?php
/**
 * Block for routing user to FAQ on faq post
 *
 * Included in single-faq.php and archive-faq.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="admin-block">
    <?php include_once LOOPIS_THEME_DIR . '/templates/links/developer-link.php' ?>
    <h5>⚠ Testläge</h5>
    <hr>
    <p class="small">💡 Använd kortnummer "4242 4242 4242 4242" för att simulera en betalning med Stripe sandbox.</p>

    <?php if(isset($_POST['activate'])) { add_coins(); refresh_page(); } ?>
		<form method="post" class="arb" action=""><button name="activate" type="submit" class="purple small">Ge mig 5 mynt</button></form>
		<p class="info">Registera mynt för test.</p>
</div>