<?php
/**
 * Shop: membership (Swish)
 * 
 * Dynamic loading from page-shop.php
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>💸 Swisha för medlemskap</h1>
<hr>

<p>Här kan du betala ditt medlemskap med Swish.</p>

<div class="wpum-message information">
<p>⚠ Swisha bara om du inte kan <span class="big-link">💳 <a href="<?php echo esc_url(home_url('/register-pay'));?>">Betala med kort</a></span></p>
<p class="small">💡 Swish-betalning måste registreras manuellt av vår kassör, vanligtvis inom en timme.</p>
</div>

<?php include_once LOOPIS_THEME_DIR . '/templates/general/swish-membership.php'; ?>