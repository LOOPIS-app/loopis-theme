<?php
/**
 * Output user information
 *
 * $user has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get user ID 
$user_id = $user->ID;

// Get user data 
$registered = human_time_diff(strtotime($user->user_registered), current_time('timestamp'));
$author_link = get_author_posts_url($user_id);
$payment_method = '';
$payment_type_display = '';
$payments = get_user_meta($user_id, 'wpum_payments', true);
if (!empty($payments) && is_array($payments)) {
    foreach ($payments as $row) {
        $payment_type = '';
        if (isset($row['wpum_payment_type'])) {
            $payment_type = is_array($row['wpum_payment_type'])
                ? ($row['wpum_payment_type'][0]['value'] ?? '')
                : $row['wpum_payment_type'];
        }
        $normalized_type = strtolower($payment_type);
        if (in_array($normalized_type, array('membership', 'medlemskap'), true)) {
            $payment_type_display = $payment_type;
            $payment_method = is_array($row['wpum_payment_method'] ?? null)
                ? ($row['wpum_payment_method'][0]['value'] ?? '')
                : ($row['wpum_payment_method'] ?? '');
            if ($payment_method !== '') {
                break;
            }
        }
    }
}
?>

<div class="user-card" onclick="location.href='<?php echo esc_url(get_author_posts_url($user_id)); ?>'">
    <h5>👤 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-names.php'; ?><span class="detail">⏳ <?php echo esc_html($registered); ?></span></h5>
<p>
    <?php if (is_main_site()) : ?>
    <span>📍 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-primary-blog.php'; ?></span>
    <?php endif; ?>
    <span>🗺 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-city.php'; ?> (<?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-zipcode.php'; ?>)</span>
    <span>🚼 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-age.php'; ?></span>
    <span>⚧ <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-gender.php'; ?></span>
    </p>
<p>
    <span>📧 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-email.php'; ?></span>
    <span>📱 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-phone.php'; ?></span>
</p>
<p>
    <span>💰 <?php echo esc_html($payment_method ?: '—'); ?></span>
    <span>🧩 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-roles.php'; ?></span>
        <span class="detail"><a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $user_id)); ?>" onclick="return confirm('Vill du redigera i användaren i WP Admin?')">🔧 <?php echo $user_id; ?></a></span>
</p>
</div>