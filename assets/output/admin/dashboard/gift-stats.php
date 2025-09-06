<?php
/**
 * Show last week statistics for gifts in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Exclude the board
$board_ids = array(2, 3, 66);

// Calculate the start and end dates for the last 7 complete days
$current_time = new DateTime(current_time('mysql'));
$start_date = clone $current_time;
$start_date->modify('-8 days');
$start_date = $start_date->format('Y-m-d 00:00:00');

$end_date = clone $current_time;
$end_date->modify('-2 day');
$end_date = $end_date->format('Y-m-d 23:59:59');

// Count new posts
$total_args = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'author__not_in' => $board_ids,
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
$booked_args = array(
    'post_type'      => 'post',
    'cat'   		 => '41, 57, 104, 147',
    'posts_per_page' => -1,
    'author__not_in' => $board_ids,
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

// Output
echo 'Senaste sju dagarna:<br>';
echo '💚 ' . $total_count . ' annonser (' . $total_average . ' per dag)<br>';
echo '❤ ' . $booked_count . ' paxade  (' . $booked_average . ' per dag) = ♻ ' . $booked_percentage . '%';