<?php
/**
 * Account activation page
 * Allows administrators to activate pending member accounts
 * Shows new accounts awaiting activation and recently activated accounts
 */

if (!defined('ABSPATH')) {
    exit;
}

// Extra php functions
include_once LOOPIS_THEME_HQ_DIR . '/includes/functions/admin-extra/admin_action_add_membership.php';
?>

<h1>🎉 Nya medlemmar</h1>
<hr>
<p class="small">💡 Här ser du blivande och nya medlemmar.</p>

<?php
// Get pending users
$args = array(
    'role' => 'member_pending',
    'blog_id' => get_current_blog_id(),
    'orderby' => 'registered',
    'order' => 'DESC',
);
$pending_users = get_users($args);
$count = count($pending_users);
?>

<!-- Pending Members -->
<h3>📋 Nya användare</h3>
<div class="columns">
    <div class="column1">
        ↓ <?php echo $count; ?> <?php echo ' ej komplett' . (($count == 1) ? '' : 'a'); ?>
    </div>
    <div class="column2 small">💡 Senaste överst</div>
</div>
<hr>

<div class="post-list">
    <?php if (!empty($pending_users)) : ?>
        <?php foreach ($pending_users as $user) : ?>
            <?php
            $user_id = $user->ID;
            if (isset($_POST['add_membership' . $user_id])) {
                admin_action_add_membership($user_id);
            }
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
            <div class="user-card">
                <div class="user-card-row header">
                    <span>👤 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-names.php'; ?></span>
                    <span class="user-card-actions">
                        <span class="big-link"><a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $user_id)); ?>" onclick="return confirm('Vill du redigera i användaren i WP Admin?')">🔧</a></span>
                        <form method="post" id="activate_form_<?php echo $user_id; ?>" style="display: none;">
                            <input type="hidden" name="add_membership<?php echo $user_id; ?>" value="1">
                        </form>
                        <span class="big-link"><a href="#" onclick="if(confirm('Aktivera konto för <?php echo esc_js($user->first_name . ' ' . $user->last_name); ?>?')) { document.getElementById('activate_form_<?php echo $user_id; ?>').submit(); } return false;">💸</a></span>
                    </span>
                </div>
                <div class="user-card-row details">
                    <span>🗺 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-city.php'; ?></span>
                    <span>📍 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-primary-blog.php'; ?></span>
                    <span>⚧ <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-gender.php'; ?></span>
                    <span>🚼 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-age.php'; ?></span>
                    <span>💰 <?php echo esc_html($payment_method ?: '—'); ?></span>
                </div>
                <div class="user-card-row details">
                    <span>📧 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-email.php'; ?></span>
                    <span>📱 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-phone.php'; ?></span>
                    <span class="user-card-time">⏳ <?php echo esc_html($registered); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
        <p class="small">💡 Tryck på 💸 för att registrera Swish-betalning.</p>
    <?php else : ?>
        <p>💢 Inga nya användare just nu.</p>
    <?php endif; ?>
</div>

<?php
// Get recently activated users (last X days)
$time_ago = strtotime('-7 days');
$args = array(
    'role'       => 'member',
    'blog_id' => get_current_blog_id(),
    'orderby'    => 'registered',
    'order'      => 'DESC',
    'date_query' => array(
        array(
            'after'     => date('Y-m-d', $time_ago),
            'before'    => date('Y-m-d'),
            'inclusive' => true,
        ),
    ),
);
$new_users = get_users($args);
$count = count($new_users);
?>

<!-- Recently Activated -->
<h3>✅ Nya medlemmar</h3>
<div class="columns">
    <div class="column1">
        ↓ <?php echo $count; ?> <?php echo (($count == 1) ? 'ny' : 'nya'); ?> senaste veckan
    </div>
    <div class="column2 small">💡 Senaste överst</div>
</div>
<hr>

<div class="post-list">
    <?php if (!empty($new_users)) : ?>
        <?php foreach ($new_users as $user) : ?>
            <?php
            $user_id = $user->ID;
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
            <div class="user-card">
                <div class="user-card-row header">
                    <span>👤 <a href="<?php echo esc_url($author_link); ?>"><?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-names.php'; ?></a></span>
                    <span class="user-card-actions">
                        <span class="big-link"><a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $user_id)); ?>" onclick="return confirm('Vill du redigera i användaren i WP Admin?')">🔧</a></span>
                    </span>
                </div>
                <div class="user-card-row details">
                    <span>🗺 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-city.php'; ?></span>
                    <span>📍 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-primary-blog.php'; ?></span>
                    <span>⚧ <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-gender.php'; ?></span>
                    <span>🚼 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-age.php'; ?></span>
                </div>
                <div class="user-card-row details">
                    <span>📧 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-email.php'; ?></span>
                    <span>📱 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-phone.php'; ?></span>
                    <span>💰 <?php echo esc_html($payment_method ?: '—'); ?></span>
                    <span class="user-card-time">⏳ <?php echo esc_html($registered); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>💢 Inga nya medlemmar senaste 7 dagarna.</p>
    <?php endif; ?>
</div>