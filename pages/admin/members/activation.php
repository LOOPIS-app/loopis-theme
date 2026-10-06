<?php
/**
 * New members page
 * 
 * Shows new accounts awaiting activation and recently activated accounts
 */

if (!defined('ABSPATH')) {
    exit;
}
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
          
          <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-card.php'; ?>

        <?php endforeach; ?>
    <?php else : ?>
        <p>💢 Inga nya användare just nu.</p>
    <?php endif; ?>
</div>

<?php
// Get recently activated members (last X days)
$days_ago = 7;
$time_ago = strtotime('-' . $days_ago . ' days');
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
            
            <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-card.php'; ?>

        <?php endforeach; ?>
    <?php else : ?>
        <p>💢 Inga nya medlemmar senaste <?php echo $days_ago; ?> dagarna.</p>
    <?php endif; ?>
</div>