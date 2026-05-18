<?php
/**
* Page for buying membership with Swish
 * 
 * Dynamic content of page-shop.php
 * Reached on /shop/?option=swish-membership
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>💸 Swisha för medlemskap</h1>
<hr>
<p class="small">💡 Betala för ditt medlemskap</p>

<p>Här kan du betala ditt medlemskap med Swish.</p>

<div class="loopis-message information">
<p>⚠ Swisha bara om du inte kan <span class="big-link">💳 <a href="<?php echo esc_url(home_url('/register-pay'));?>">Betala med kort</a></span></p>
<p class="small">💡 Swish-betalning måste registreras manuellt av vår kassör, vanligtvis inom en timme.</p>
</div>

<?php include_once LOOPIS_THEME_DIR . '/templates/general/swish-membership.php'; ?>