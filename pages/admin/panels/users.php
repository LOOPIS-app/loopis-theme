<?php
/**
 * Show count for pending users in admin dashboard
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

// Set timeframe
$today_start = get_gmt_from_date(wp_date('Y-m-d 00:00:00'));
$days_ago = 7;
$time_ago = gmdate('Y-m-d H:i:s', strtotime('-' . $days_ago . ' days', strtotime($today_start . ' UTC')));
if ($days_ago == 0) { 
    $time_frame = 'idag'; 
} elseif ($days_ago == 7) {
    $time_frame = 'senaste veckan';
} else { 
    $time_frame =  'senaste ' . $days_ago . ' ' . (($days_ago == 1) ? 'dagen' : 'dagarna'); 
}

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
$count_new = count($new_users);

// Output
if ($count_new == 0) {
    echo '📉 0 nya';
} else {
    echo '📈 ' . $count_new . ' nya';
}
echo ' ' . $time_frame;

echo '<br>';

if ($count_pending == 0) {
    echo '✅ 0 ej aktiverade';
} else {
    echo '⏳ ' . $count_pending . ' ej aktiverade';
}
