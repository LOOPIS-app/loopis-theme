<?php
/**
 * Page for buying membership with Stripe
 *
 * Dynamic content of page-shop.php
 * Reached on /shop/?option=membership-stripe
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

<h1>💳 Betala medlemskap</h1>
<hr>
<p class="small">💡 Du måste vara medlem för att kunna loopa.</p>

<?php if (!is_user_logged_in()) : ?>

    <p>Du måste vara inloggad för att betala ditt medlemskap.</p>
    <?php include LOOPIS_THEME_DIR . '/templates/links/log-in-button.php'; ?>

<?php elseif ('success' === $checkout_status) : ?>

    <p><strong>✅ Betalning mottagen!</strong></p>
    <p>Ditt medlemskap är aktiverat och du har 5 regnbågsmynt.</p>
    <p>Ta en titt på <span class="link"><a href="<?php echo home_url(); ?>">🎁 Saker att få</a></span></p>
    <script>
    (function() {
        var nonce = <?php echo wp_json_encode(wp_create_nonce('wp_rest')); ?>;
        var pollCount = 0;
        var interval = setInterval(function() {
            fetch('/wp-json/loopis/v1/membership-status', {
                headers: { 'X-WP-Nonce': nonce }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.is_member) {
                    clearInterval(interval);
                    window.location.href = '<?php echo esc_js(home_url('/shop/?option=membership-stripe')); ?>';
                }
            })
            .catch(function() {});
            if (++pollCount >= 40) { clearInterval(interval); } // 2 min max
        }, 3000);
    }());
    </script>

<?php elseif ($is_member) : ?>

    <p>✅ Du är redan medlem.</p>

<?php elseif ('cancelled' === $checkout_status) : ?>

    <p><strong>⚠ Betalningen avbröts.</strong> Du kan försöka igen när du är redo.</p>
    <p><a href="<?php echo esc_url(add_query_arg('option', 'membership-stripe', home_url('/shop/'))); ?>" class="button-primary">↻ Försök igen</a></p>

<?php elseif ($is_member_pending) : ?>

    <p>Betala medlemsavgift (50 kronor) för att aktivera ditt medlemskap.</p>
    <p>Du får 5 regnbågsmynt att paxa saker för.</p>
    <p>
        <button
            type="button"
            class="green"
            id="loopis-payment-button"
            onclick="loopis_start_checkout_payment(event)"
        >
            💳 Betala 50 kr
        </button>
    </p>
    <?php include LOOPIS_THEME_DIR . '/templates/links/mail-support.php'; ?>
    <p><span class="link"><a href="<?php echo esc_url(home_url('/faq/varför-medlemskap') ); ?>">📌 Varför måste jag vara medlem?</a></span></p>
    <p><span class="link"><a href="<?php echo esc_url(home_url('/faq/hur-funkar-regnbagsmynt') ); ?>">📌 Hur funkar regnbågsmynt?</a></span></p>
    <p><span class="link"><a href="<?php echo esc_url(add_query_arg('option', 'membership-swish', home_url('/shop/'))); ?>">💸 Betala med Swish istället</a></span></p>

    <script>
    function loopis_start_checkout_payment(event) {
        event.preventDefault();
        var nonce = <?php echo wp_json_encode(wp_create_nonce('wp_rest')); ?>;
        var button = document.getElementById('loopis-payment-button');
        button.disabled = true;
        button.textContent = '⏳ Laddar...';

        fetch('/wp-json/loopis/v1/create-membership-checkout', {
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

<?php
// Develooper info
if (defined('LOOPIS_TEST') && LOOPIS_TEST) { 
    include LOOPIS_THEME_DIR . '/templates/develooper/test-membership.php'; }
?>

<?php else : ?>

    <p>Ditt konto verkar inte ha rätt behörighet för att betala här. Kontakta <a href="mailto:info@loopis.app">info@loopis.app</a> om du behöver hjälp.</p>

<?php endif; ?>