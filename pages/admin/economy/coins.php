<?php
/**
 * Coin purchases page
 * Register and view member coin purchases
 * Shows all coin transactions with payment details
 */

if (!defined('ABSPATH')) {
    exit;
}

// Extra php functions
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/admin_action_add_coins.php';
?>

<h1>🪙 Köp av mynt</h1>
<hr>
<p class="small">💡 Här registrerar du köp av mynt.</p>

<!-- Register New Purchase -->
<h3>💸 Registrera nytt köp</h3>
<hr>

<?php
// Get all members
$member_users = get_users(array('role' => 'member'));
?>

<form method="post" class="arb" action="" style="display: flex; align-items: center;">
    <select id="selected_member" name="selected_member" style="max-width: 175px; margin-right: 10px;">
        <option value="">Välj medlem</option>
        <?php foreach ($member_users as $member_user) : ?>
            <option value="<?php echo esc_attr($member_user->ID); ?>">
                <?php echo esc_html($member_user->display_name); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button name="add_payment" type="submit" class="blue" style="display: none;">☑ Betalat</button>
</form>
<p class="info">Välj en medlem och tryck på knappen.</p>

<?php
// Process payment
if (isset($_POST['add_payment']) && isset($_POST['selected_member'])) {
    $user_id = intval($_POST['selected_member']);
    admin_action_add_coins($user_id);
}
?>

<!-- Show button when member is selected -->
<script>
document.getElementById('selected_member').addEventListener('change', function() {
    var paymentButton = document.querySelector('button[name="add_payment"]');
    if (this.value !== '') {
        paymentButton.style.display = 'inline-block';
    } else {
        paymentButton.style.display = 'none';
    }
});
</script>

<!-- List of Payments -->
<?php
$member_users = get_users(array(
    'role'   => 'member',
    'fields' => array('ID', 'display_name')
));

$count = 0;
$today = date('Y-m-d');
$all_payments = array();

foreach ($member_users as $user) {
    $meta_key = 'wpum_payments';
    $meta_values = get_user_meta($user->ID, $meta_key, true);

    if (!empty($meta_values)) {
        foreach ($meta_values as $row) {
            $payment_type = $row['wpum_payment_type'][0]['value'];
            if ($payment_type === 'Mynt') {
                $payment_date = $row['wpum_payment_date'][0]['value'];
                $payment_amount = $row['wpum_payment_amount'][0]['value'];
                $payment_method = $row['wpum_payment_method'][0]['value'];
                $payment_received_coins = $row['wpum_received_coins'][0]['value'];
                
                $all_payments[] = array(
                    'user_id'                 => $user->ID,
                    'display_name'            => $user->display_name,
                    'payment_date'            => $payment_date,
                    'payment_type'            => $payment_type,
                    'payment_amount'          => $payment_amount,
                    'payment_method'          => $payment_method,
                    'payment_received_coins'  => $payment_received_coins
                );
                $count++;
            }
        }
    }
}

// Sort by date (newest first)
usort($all_payments, function($a, $b) {
    return strtotime($b['payment_date']) - strtotime($a['payment_date']);
});
?>

<h3>🗃 Registrerade köp</h3>
<div class="columns">
    <div class="column1">↓ <?php echo $count; ?> köp</div>
    <div class="column2 small">💡 Senaste överst</div>
</div>
<hr>

<?php if ($count > 0) : ?>
    <?php foreach ($all_payments as $payment) : ?>
        <?php
        $author_url = get_author_posts_url($payment['user_id']);
        $payment_date = date('Y-m-d', strtotime($payment['payment_date']));
        $blue = ($payment_date === $today) ? 'blue_light' : '';
        ?>
        <p>
            <span class="link grey <?php echo esc_attr($blue); ?>">
                <a href="<?php echo esc_url($author_url); ?>" class="link grey">
                    <i class="fas fa-receipt"></i><?php echo esc_html($payment['payment_date']); ?>: 
                    <?php echo esc_html($payment['display_name']); ?> - 
                    <?php echo esc_html($payment['payment_received_coins']); ?> mynt - 
                    <?php echo esc_html($payment['payment_amount']); ?>kr 
                    (<?php echo esc_html($payment['payment_method']); ?>)
                </a>
            </span>
        </p>
    <?php endforeach; ?>
<?php else : ?>
    <p>💢 Hittade inga registrerade betalningar för mynt.</p>
<?php endif; ?>