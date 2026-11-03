<?php
/**
 * Show last week statistics for gifts in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Calculate the start and end dates for the last 7 complete days
$now_time = new DateTime(current_time('mysql'));
$start_date = clone $now_time;
$start_date->modify('-8 days');
$start_date = $start_date->format('Y-m-d 00:00:00');

$end_date = clone $now_time;
$end_date->modify('-2 day');
$end_date = $end_date->format('Y-m-d 23:59:59');

// Count new posts
$total_args = array(
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'after'     => $start_date,
            'before'    => $end_date,
            'inclusive' => true,
        ),
    ),
);

$total_query = new WP_Query($total_args);
$total_count = $total_query->found_posts;
$total_average = round($total_count / 7, 2);

// Count booked posts
$booked_categories = loopis_cats(['booked', 'booked_custom', 'locker', 'fetched']);

$booked_args = array(
    'cat'   		 => $booked_categories,
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'after'     => $start_date,
            'before'    => $end_date,
            'inclusive' => true,
        ),
    ),
);

$booked_query = new WP_Query($booked_args);
$booked_count = $booked_query->found_posts;
$booked_average = round($booked_count / 7, 1);

$booked_percentage = ($total_count == 0) ? 0 : round(($booked_count / $total_count) * 100);

// Set booking effect message
if ($booked_percentage > 60) {
    $effect_message = '<b>👍 Mycket paxningar!</b> = mer än 60%';
} else {
    $effect_message = '<b>☹️ Få paxningar</b> = mindre än 60%';
}


// Output
echo 'Senaste sju dagarna:<br>';
echo '💚 ' . $total_count . ' annonser (' . $total_average . ' per dag)<br>';
echo '❤ ' . $booked_count . ' paxade  (' . $booked_average . ' per dag) = ♻ ' . $booked_percentage . '%<br>';
echo '<em>' . $effect_message . '</em>';