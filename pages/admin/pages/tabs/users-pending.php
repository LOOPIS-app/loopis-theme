<?php
/**
 * Pending users tab
 * 
 * Shows new user accounts awaiting activation
 */

if (!defined('ABSPATH')) {
    exit;
}

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

<!-- Pending registrations -->
<h3>📋 Registreringar</h3>
<div class="columns">
    <div class="column1">
        ↓ <?php echo $count; ?> <?php echo ' ej aktiverad' . (($count != 1) ? 'e' : ''); ?>
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
        <p>💢 Inga nya registreringar just nu.</p>
    <?php endif; ?>
</div>
