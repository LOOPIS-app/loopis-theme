<?php
/**
 * Show count for pending members in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get pending users
$pending_users = get_users([
    'role__in' => ['member_pending'],
    'blog_id' => get_current_blog_id(),
]);

$count_pending = count($pending_users);

// Get new users today
$today_start = get_gmt_from_date(wp_date('Y-m-d 00:00:00'));
$count_new = count(get_users([
    'role' => 'member',
    'blog_id' => get_current_blog_id(),
    'date_query' => [['after' => $today_start, 'inclusive' => true, 'column' => 'user_registered']],
    'fields' => 'ID',
]));

// Output
if ($count_pending == 0) {
    echo '✅ 0 ej aktiverade';
} else {
    echo '⏳ ' . $count_pending . ' ej aktiverade';
}
if ($count_new == 0) {
    echo '<br>💢 0 nya medlemmar idag';
} else {
    echo '<br>🎉 ' . $count_new . ' nya medlemmar idag';
}
