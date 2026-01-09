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

<h1>⚡ Aktivera konton</h1>
<hr>
<p class="small">💡 Här aktiverar du konton för nya medlemmar.</p>

<?php
// Get pending users
$args = array(
    'role' => 'member_pending',
);
$pending_users = get_users($args);
$count = count($pending_users);
?>

<!-- Pending Members -->
<h3>🎉 Nya medlemmar</h3>
<div class="columns">
    <div class="column1">
        ↓ <?php echo $count; ?> <?php echo ($count == 1) ? 'ny' : 'nya'; ?>
    </div>
    <div class="column2"></div>
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
            ?>
            
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 15px;">
                <span class="big-label">📋 <?php echo esc_html($user->first_name . ' ' . $user->last_name); ?></span>
                <span class="big-label">📧 <?php echo esc_html($user->user_email); ?></span>
                <span class="big-link"><a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $user_id)); ?>">⚙ Redigera</a></span>
                <form method="post" style="display: inline-block; margin: 0;">
                    <button name="activate_account<?php echo $user_id; ?>" 
                            type="submit" 
                            class="small" 
                            onclick="return confirm('Aktivera konto för <?php echo esc_js($user->first_name . ' ' . $user->last_name); ?>?')">
                        Aktivera
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>✅ Inga nya konton att aktivera.</p>
    <?php endif; ?>
</div>

<?php
// Get recently activated users (last 7 days)
$seven_days_ago = strtotime('-7 days');
$args = array(
    'role'       => 'member',
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
<h3>🙂 Aktiverade</h3>
<div class="columns">
    <div class="column1">
        ↓ <?php echo $count; ?> <?php echo ($count == 1) ? 'ny' : 'nya'; ?> senaste veckan
    </div>
    <div class="column2"></div>
</div>
<hr>

<div class="post-list">
    <?php if (!empty($new_users)) : ?>
        <?php foreach ($new_users as $user) : ?>
            <?php $author_link = get_author_posts_url($user->ID); ?>
            <p>
                <span class="big-link"><a href="<?php echo esc_url($author_link); ?>">👤 <?php echo esc_html($user->user_login); ?></a></span>
                <span class="big-label">📧 <?php echo esc_html($user->user_email); ?></span>
                <span class="big-link"><a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $user->ID)); ?>">⚙ Redigera</a></span>
            </p>
        <?php endforeach; ?>
    <?php else : ?>
        <p>💢 Inga nya konton senaste veckan.</p>
    <?php endif; ?>
</div>