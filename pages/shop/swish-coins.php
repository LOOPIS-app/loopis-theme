<?php
/**
 * Shop: coins (Swish)
 * 
 * Dynamic loading from page-shop.php
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
<p>⚠ Använd bara Swish om du inte kan eller vill <span class="big-link">💳 <a href="<?php echo esc_url( add_query_arg(array(['option' => 'coins']), home_url('/shop/')) ); ?>">betala med kort</a></span>.</p>
<p class="small">💡 Swish-betalningar registreras manuellt av vår kassör, vanligtvis inom en timme.</p>
</div>

<?php include_once LOOPIS_THEME_DIR . '/templates/general/swish-coins.php'; ?>

<p><span class="link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-regnbagsmynt') ); ?>">📌 Hur funkar regnbågsmynt?</a></span></p>