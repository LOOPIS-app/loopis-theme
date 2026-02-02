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
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/admin_action_activate_account.php';
?>

<style>
.user-card {
    background-color: #f5f5f5;
    padding: 8px;
    margin-bottom: 8px;
    font-size: 0.875rem;
}

.user-card-row {
    display: flex;
    gap: 8px;
}

.user-card-row:first-child {
    font-size: 1.1rem;
    margin-bottom: 2px;
}

.user-card-row > span {
    flex: 1;
}

.user-card-row.header > span {
    flex: 0 0 auto;
}

.user-card-row.header > span:last-child {
    flex: 0 0 auto;
    text-align: right;
}

.user-card-row.details {
    justify-content: flex-start;
    flex-wrap: wrap;
    gap: 8px;
}

.user-card-row.details > span {
    flex: 0 1 auto;
}

.user-card-time {
    font-size: 0.75rem;
    margin-left: auto;
}
</style>

<h1>🎉 Nya medlemmar</h1>
<hr>
<p class="small">💡 Här ser du nya medlemmar - och kan aktivera deras konton manuellt.</p>

<?php
// Get pending users
$args = array(
    'role' => 'member_pending',
    'orderby' => 'registered',
    'order' => 'DESC',
);
$pending_users = get_users($args);
$count = count($pending_users);
?>

<!-- Pending Members -->
<h3>📋 Formulär</h3>
<div class="columns">
    <div class="column1">
        ↓ <?php echo $count; ?> <?php echo ($count == 1) ? 'ny' : 'nya' . ' väntande'; ?>
    </div>
    <div class="column2">💡 Senaste överst</div>
</div>
<hr>

<div class="post-list">
    <?php if (!empty($pending_users)) : ?>
        <?php foreach ($pending_users as $user) : ?>
            <?php
            $user_id = $user->ID;
            if (isset($_POST['activate_account' . $user_id])) {
                admin_action_activate_account($user_id);
            }
            $registered = strtotime($user->user_registered);
            $human_time = human_time_diff($registered, current_time('timestamp'));
            $phone = get_the_author_meta('wpum_phone', $user_id);
            
            // Get area
            ob_start();
            include LOOPIS_THEME_DIR . '/templates/user/profile/user-area.php';
            $area = ob_get_clean();
            ?>
            <div class="user-card">
                <div class="user-card-row header">
                    <span>👤 <strong><?php echo esc_html($user->first_name . ' ' . $user->last_name); ?></strong></span>
                    <span class="user-card-time">⏳ <?php echo esc_html($human_time); ?></span>
                    <span>
                        <span class="big-link"><a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $user_id)); ?>" onclick="return confirm('Vill du redigera i användaren i WP Admin?')">🔧</a></span>
                        <form method="post" id="activate_form_<?php echo $user_id; ?>" style="display: none;">
                            <input type="hidden" name="activate_account<?php echo $user_id; ?>" value="1">
                        </form>
                        <span class="big-link"><a href="#" onclick="if(confirm('Aktivera konto för <?php echo esc_js($user->first_name . ' ' . $user->last_name); ?>?')) { document.getElementById('activate_form_<?php echo $user_id; ?>').submit(); } return false;">✅</a></span>
                    </span>
                </div>
                <div class="user-card-row details">
                    <span>📍 <?php echo $area; ?></span>
                    <span>✉ <a href="mailto:<?php echo esc_attr($user->user_email); ?>"><?php echo esc_html($user->user_email); ?></a></span>
                    <span>📱 <a href="sms:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>💢 Inga väntande konton just nu.</p>
    <?php endif; ?>
</div>

<?php
// Get recently activated users (last 7 days)
$seven_days_ago = strtotime('-7 days');
$args = array(
    'role'       => 'member',
    'orderby'    => 'registered',
    'order'      => 'DESC',
    'date_query' => array(
        array(
            'after'     => date('Y-m-d', $seven_days_ago),
            'before'    => date('Y-m-d'),
            'inclusive' => true,
        ),
    ),
);
$new_users = get_users($args);
$count = count($new_users);
?>

<!-- Recently Activated -->
<h3>✅ Aktiverade</h3>
<div class="columns">
    <div class="column1">
        ↓ <?php echo $count; ?> <?php echo ($count == 1) ? 'ny' : 'nya'; ?> senaste veckan
    </div>
    <div class="column2">💡 Senaste överst</div>
</div>
<hr>

<div class="post-list">
    <?php if (!empty($new_users)) : ?>
        <?php foreach ($new_users as $user) : ?>
            <?php
            $user_id = $user->ID;
            $registered = strtotime($user->user_registered);
            $human_time = human_time_diff($registered, current_time('timestamp'));
            $phone = get_the_author_meta('wpum_phone', $user_id);
            $author_link = get_author_posts_url($user_id);

            $payment_method = '';
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
                        $payment_method = is_array($row['wpum_payment_method'] ?? null)
                            ? ($row['wpum_payment_method'][0]['value'] ?? '')
                            : ($row['wpum_payment_method'] ?? '');
                        if ($payment_method !== '') {
                            break;
                        }
                    }
                }
            }

            // Get area
            ob_start();
            include LOOPIS_THEME_DIR . '/templates/user/profile/user-area.php';
            $area = ob_get_clean();
            ?>
            <div class="user-card">
                <div class="user-card-row header">
                    <span>👤 <strong><a href="<?php echo esc_url($author_link); ?>"><?php echo esc_html($user->first_name . ' ' . $user->last_name); ?></a></strong></span>
                    <span class="user-card-time">⏳ <?php echo esc_html($human_time); ?></span>
                    <span>
                        <span class="big-link"><a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $user_id)); ?>" onclick="return confirm('Vill du redigera i användaren i WP Admin?')">🔧</a></span>
                    </span>
                </div>
                <div class="user-card-row details">
                    <span>📍 <?php echo $area; ?></span>
                    <span>✉ <a href="mailto:<?php echo esc_attr($user->user_email); ?>"><?php echo esc_html($user->user_email); ?></a></span>
                    <span>📱 <a href="sms:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></span>
                    <span>💰 <?php echo esc_html($payment_method ?: '—'); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>💢 Inga nya konton senaste veckan.</p>
    <?php endif; ?>
</div>