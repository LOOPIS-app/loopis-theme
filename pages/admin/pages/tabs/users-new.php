<?php
/**
 * New users tab
 * 
 * Shows new user accounts recently activated
 */

if (!defined('ABSPATH')) {
    exit;
}

// Set timeframe
$today_start = get_gmt_from_date(wp_date('Y-m-d 00:00:00'));
$days_ago = 7;
$time_ago = gmdate('Y-m-d H:i:s', strtotime('-' . $days_ago . ' days', strtotime($today_start . ' UTC')));
if ($days_ago == 0) { $time_frame = 'idag'; } else { $time_frame =  'senaste ' . $days_ago . ' ' . (($days_ago == 1) ? 'dagen' : 'dagarna'); }

// Get new members
$args = array(
    'role'       => 'member',
    'blog_id' => get_current_blog_id(),
    'orderby'    => 'registered',
    'order'      => 'DESC',
    'date_query' => array(
        array(
            'after'     => $time_ago,
            'inclusive' => true,
        ),
    ),
);
$new_users = get_users($args);
$count = count($new_users);
?>

<!-- Recently Activated -->
<h3>✅ Nya användare</h3>
<div class="columns">
    <div class="column1">
        ↓ <?php echo $count; ?> <?php echo (($count == 1) ? 'ny' : 'nya'); ?>
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