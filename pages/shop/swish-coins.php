<?php
/**
 * Shop: start (page-shop.php)
 * Content overview.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>💸 Swisha för regnbågsmynt</h1>
<hr>

<p>Vill du hämta saker utan att ge bort något själv?</p>
<p><strong>Här kan du köpa 5 regnbågsmynt för 50 kr.</strong></p>

<div class="wpum-message information">
<p>⚠ Använd bara Swish om du inte kan eller vill <a href="/shop/?option=coins">betala med kort</a>.</p>
<p class="small">💡 Swish-betalningar registreras manuellt av vår kassör, vanligtvis inom en timme.</p>
</div>

<?php include_once LOOPIS_THEME_DIR . '/templates/general/swish-coins.php'; ?>

<p><span class="link"><a href="/faq/hur-funkar-regnbagsmynt">📌 Hur funkar regnbågsmynt?</a></span></p>