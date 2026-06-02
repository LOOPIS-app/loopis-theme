<?php
/**
 * Page for buying coins with Stripe
 * 
 * Dynamic content of page-shop.php
 * Reached on /shop/?option=coins-stripe
 */

if (!defined('ABSPATH')) {
    exit;
}

$current_user  = wp_get_current_user();
$user_id       = $current_user->ID;
$user_roles    = (array) $current_user->roles;
$checkout_status = isset($_GET['checkout']) ? sanitize_key($_GET['checkout']) : '';
$is_member         = in_array('member', $user_roles, true);
$is_member_pending = in_array('member_pending', $user_roles, true);
?>

<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:35px; width: auto;"> Köp regnbågsmynt</h1>
<hr>
<p class="small">💡 Hämta saker utan att ge bort något själv.</p>

<?php if ($is_member) : ?>
    
<p>Här kan du köpa 5 regnbågsmynt för 50 kr.</p>

<?php if ('success' === $checkout_status) : ?>

<div class="loopis-message success"><p>✅ Betalning mottagen!</p>
<p>Du har fått 5 regnbågsmynt att paxa saker för.</p>
</div>
<p><span class="big-link"><a href="<?php echo esc_url(add_query_arg('option', 'coins-stripe', home_url('/shop/'))); ?>">🛒 Köp 5 mynt till</a></span></p>
<p><span class="big-link"><a href="<?php echo esc_url(home_url('/profile/')); ?>">👛 Mina mynt</a></span></p>

<?php elseif ('cancelled' === $checkout_status) : ?>

<div class="loopis-message warning">
<p>⚠ Betalningen avbröts. Försök igen?</p>
<p><a href="<?php echo esc_url(add_query_arg('option', 'coins-stripe', home_url('/shop/'))); ?>" class="button-primary">↻ Försök igen</a></p>
</div>

<?php else : ?>

<p>
    <button
        type="button"
        class="green"
        id="loopis-coins-payment-button"
        onclick="loopis_start_coins_checkout_payment(event)"
    >
        💳 Betala 50 kr
    </button>
</p>
<p class="small">💡 Du får dina mynt direkt när betalningen är genomförd.</p>

<script>
function loopis_start_coins_checkout_payment(event) {
    event.preventDefault();
    var nonce = <?php echo wp_json_encode(wp_create_nonce('wp_rest')); ?>;
    var button = document.getElementById('loopis-coins-payment-button');
    button.disabled = true;
    button.textContent = '⏳ Laddar...';

    fetch('/wp-json/loopis/v1/create-coins-checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': nonce
        }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.url) {
            window.location.href = data.url;
        } else {
            alert('Fel: ' + (data.error || 'Kunde inte starta betalning'));
            button.disabled = false;
            button.textContent = '💳 Betala 50 kr';
        }
    })
    .catch(function() {
        alert('Ett tekniskt fel uppstod. Försök igen senare.');
        button.disabled = false;
        button.textContent = '💳 Betala 50 kr';
    });
}
</script>

<?php endif; ?>

<p><span class="link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-regnbagsmynt')); ?>">📌 Hur funkar regnbågsmynt?</a></span></p>
<p><span class="link"><a href="<?php echo esc_url(add_query_arg('option', 'coins-swish', home_url('/shop/'))); ?>">💸 Betala med Swish istället</a></span></p>

<?php
// Develooper info
if (defined('LOOPIS_TEST') && LOOPIS_TEST) {
    include_once LOOPIS_THEME_DIR . '/templates/develooper/test-coins.php';  
    }
?>

<?php elseif ($is_member_pending) : ?>

<?php include LOOPIS_THEME_DIR . '/templates/access/member-only.php'; ?>

<?php else : ?>

<?php include LOOPIS_THEME_DIR . '/templates/access/logged-in-only.php'; ?>

<?php endif; ?>