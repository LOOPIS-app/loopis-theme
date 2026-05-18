<?php
/**
 * Page for buying coins with Swish
 * 
 * Dynamic content of page-shop.php
 * Reached on /shop/?option=swish-coins
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>💸 Swisha för regnbågsmynt</h1>
<hr>
<p class="small">💡 Hämta saker utan att ge bort något själv.</p>

<p>Här kan du köpa 5 regnbågsmynt för 50 kr.</p>

<div class="loopis-message information">
<p>⚠ Swisha bara om du inte kan <span class="big-link">💳 <a href="<?php echo esc_url( add_query_arg(array(['option' => 'coins']), home_url('/shop/')) ); ?>">Betala med kort</a></span></p>
<p class="small">💡 Swish-betalning måste registreras manuellt av vår kassör, vanligtvis inom en timme.</p>
</div>

<?php include_once LOOPIS_THEME_DIR . '/templates/general/swish-coins.php'; ?>

<p><span class="link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-regnbagsmynt') ); ?>">📌 Hur funkar regnbågsmynt?</a></span></p>