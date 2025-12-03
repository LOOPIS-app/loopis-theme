<?php
/**
 * Show statistics for gifts raffle and bookings in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// RAFFLE YESTERDAY (day before yesterday)
$day = new DateTime(current_time('mysql'));
$day->modify('-2 days');
$day_start = $day->format('Y-m-d 00:00:00');
$day_end = $day->format('Y-m-d 23:59:59');

// Query arguments for total post count
$args_total_count = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'date_query'     => array(
        array(
            'after'     => $day_start,
            'before'    => $day_end,
            'inclusive' => true,
        ),
    ),
);

// Query arguments for booked post count (specific categories)
$args_booked_count = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'category__in'   => array(41, 57, 147, 104),
    'date_query'     => array(
        array(
            'after'     => $day_start,
            'before'    => $day_end,
            'inclusive' => true,
        ),
    ),
);

// Perform the queries
$query_total_count = new WP_Query($args_total_count);
$total_count = $query_total_count->found_posts;

$query_booked_count = new WP_Query($args_booked_count);
$booked_count = $query_booked_count->found_posts;

// Calculate the booked percentage
$booked_percentage = ($total_count == 0) ? 0 : round(($booked_count / $total_count) * 100);

echo '✅ ' . $total_count . ' nya annonser iförrgår → ❤ ' . $booked_count . ' paxade nu = ♻ ' . $booked_percentage . '%<br>';

// RAFFLE TODAY (yesterday's posts)
$now_time = new DateTime(current_time('mysql'));
$yesterday = clone $now_time;
$yesterday->modify('-1 day');
$yesterday_start = $yesterday->format('Y-m-d 00:00:00');
$yesterday_end = $yesterday->format('Y-m-d 23:59:59');

$args_yesterday = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'date_query'     => array(
        'after'     => $yesterday_start,
        'before'    => $yesterday_end,
        'inclusive' => true,
    ),
);

$args_yesterday_participants = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'date_query'     => array(
        'after'     => $yesterday_start,
        'before'    => $yesterday_end,
        'inclusive' => true,
    ),
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key'     => 'participants',
            'compare' => 'EXISTS',
        ),
        array(
            'key'     => 'participants',
            'value'   => 'a:0:{}',
            'compare' => '!=',
        ),
    ),
);

$args_yesterday_booked = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'cat'   		 => '41, 57, 104, 147',
    'date_query'     => array(
        'after'     => $yesterday_start,
        'before'    => $yesterday_end,
        'inclusive' => true,
    ),
);

$args_yesterday_new = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'cat'       => '1',
    'date_query'     => array(
        'after'     => $yesterday_start,
        'before'    => $yesterday_end,
        'inclusive' => true,
    ),
);

$query_yesterday = new WP_Query($args_yesterday);
$count_yesterday = $query_yesterday->found_posts;

$query_yesterday_participants = new WP_Query($args_yesterday_participants);
$count_yesterday_participants = $query_yesterday_participants->found_posts;

$query_yesterday_new = new WP_Query($args_yesterday_new);
$count_yesterday_new = $query_yesterday_new->found_posts;

$query_yesterday_booked = new WP_Query($args_yesterday_booked);
$count_yesterday_booked = $query_yesterday_booked->found_posts;

$percentage_yesterday_participants = ($count_yesterday_new == 0) ? 0 : round(($count_yesterday_participants / $count_yesterday_new) * 100);
$percentage_yesterday_booked = ($count_yesterday == 0) ? 0 : round(($count_yesterday_booked / $count_yesterday) * 100);

if ($count_yesterday_new === 0) {
    echo '✅ ' . $count_yesterday . ' nya annonser igår → ❤ ' . $count_yesterday_booked . ' paxade nu = ♻ ' . $percentage_yesterday_booked . '%<br>';
}

if ($count_yesterday_new > 0) {
    echo '⌛ ' . $count_yesterday_new . ' nya annonser igår → 🧡 ' . $count_yesterday_participants . ' paxas idag kl. 12 = ♻ ' . $percentage_yesterday_participants . '%<br>';
}

// RAFFLE TOMORROW (today's posts)
$today = new DateTime(current_time('mysql'));
$today_start = $today->format('Y-m-d 00:00:00');
$today_end = $today->format('Y-m-d 23:59:59');

$args_today = array(
    'post_type' 	 => 'post',
    'posts_per_page' => -1,
    'cat'   		 => '1',
    'date_query' => array(
        'after'     => $today_start,
        'before'    => $today_end,
        'inclusive' => true,
    ),
);

$args_today_participants = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'cat'   		 => '1',
    'date_query'     => array(
        'after'     => $today_start,
        'before'    => $today_end,
        'inclusive' => true,
    ),
    'meta_query' => array(
        array(
            'key'     => 'participants',
            'value'   => '',
            'compare' => '!=',
        ),
    ),
);

$query_today = new WP_Query($args_today);
$count_today = $query_today->found_posts;

$query_today_participants = new WP_Query($args_today_participants);
$count_today_participants = $query_today_participants->found_posts;

$percentage_today_participants = ($count_today == 0) ? 0 : round(($count_today_participants / $count_today) * 100);

echo '⏳ ' . $count_today . ' nya annonser idag → 🧡 ' . $count_today_participants . ' paxas imorrn kl. 12 = ♻ ' . $percentage_today_participants . '%<br>';

wp_reset_postdata();

