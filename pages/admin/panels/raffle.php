<?php
/**
 * Show statistics for gifts raffle and bookings in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Set categories
$new_cat = loopis_cat('new');
$booked_cats = loopis_cats(['fetched', 'booked', 'booked_custom', 'locker']);


/* RAFFLE YESTERDAY */
/* posts created the day before yesterday */

$day = new DateTime(current_time('mysql'));
$day->modify('-2 days');
$day_start = $day->format('Y-m-d 00:00:00');
$day_end = $day->format('Y-m-d 23:59:59');

// Arguments: total post count
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

// Arguments: booked post count
$args_booked_count = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'category__in'   => $booked_cats,
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

// Calculate percentages
$booked_percentage = ($total_count == 0) ? 0 : round(($booked_count / $total_count) * 100);



/* RAFFLE TODAY */
/* posts created yesterday */

$now_time = new DateTime(current_time('mysql'));
$yesterday = clone $now_time;
$yesterday->modify('-1 day');
$yesterday_start = $yesterday->format('Y-m-d 00:00:00');
$yesterday_end = $yesterday->format('Y-m-d 23:59:59');

// Arguments: total post count
$args_yesterday = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'after'     => $yesterday_start,
            'before'    => $yesterday_end,
            'inclusive' => true,
        ),
    ),
);

// Arguments: posts with participants meta key
$args_yesterday_participants = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'after'     => $yesterday_start,
            'before'    => $yesterday_end,
            'inclusive' => true,
        ),
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

// Arguments: booked posts
$args_yesterday_booked = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'cat'   		 => $booked_cats,
    'date_query'     => array(
        array(
            'after'     => $yesterday_start,
            'before'    => $yesterday_end,
            'inclusive' => true,
        ),
    ),
);

// Arguments: new posts
$args_yesterday_new = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'cat'       => $new_cat,
    'date_query'     => array(
        array(
            'after'     => $yesterday_start,
            'before'    => $yesterday_end,
            'inclusive' => true,
        ),
    ),
);

// Perform the queries
$query_yesterday = new WP_Query($args_yesterday);
$count_yesterday = $query_yesterday->found_posts;

$query_yesterday_participants = new WP_Query($args_yesterday_participants);
$count_yesterday_participants = $query_yesterday_participants->found_posts;

$query_yesterday_new = new WP_Query($args_yesterday_new);
$count_yesterday_new = $query_yesterday_new->found_posts;

$query_yesterday_booked = new WP_Query($args_yesterday_booked);
$count_yesterday_booked = $query_yesterday_booked->found_posts;

// Calculate percentages
$percentage_yesterday_participants = ($count_yesterday_new == 0) ? 0 : round(($count_yesterday_participants / $count_yesterday_new) * 100);
$percentage_yesterday_booked = ($count_yesterday == 0) ? 0 : round(($count_yesterday_booked / $count_yesterday) * 100);


/* RAFFLE TOMORROW */
/* posts created today */
$today = new DateTime(current_time('mysql'));
$today_start = $today->format('Y-m-d 00:00:00');
$today_end = $today->format('Y-m-d 23:59:59');

// Arguments: today's posts
$args_today = array(
    'post_type' 	 => 'post',
    'posts_per_page' => -1,
    'cat'   		 => $new_cat,
    'date_query' => array(
        'after'     => $today_start,
        'before'    => $today_end,
        'inclusive' => true,
    ),
);

// Arguments: today's posts with participants meta key
$args_today_participants = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'cat'   		 => $new_cat,
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

// Perform the queries
$query_today = new WP_Query($args_today);
$count_today = $query_today->found_posts;

$query_today_participants = new WP_Query($args_today_participants);
$count_today_participants = $query_today_participants->found_posts;

// Calculate percentages
$percentage_today_participants = ($count_today == 0) ? 0 : round(($count_today_participants / $count_today) * 100);



// Output
echo 'Annonser senaste tre dagarna:<br>';

echo '✅ ' . $total_count . ' nya iförrgår → ❤ ' . $booked_count . ' paxade nu = ♻ ' . $booked_percentage . '%<br>';

if ($count_yesterday_new === 0) {
    echo '✅ ' . $count_yesterday . ' nya igår → ❤ ' . $count_yesterday_booked . ' paxade nu = ♻ ' . $percentage_yesterday_booked . '%<br>';
}

if ($count_yesterday_new > 0) {
    echo '⌛ ' . $count_yesterday_new . ' nya igår → 🧡 ' . $count_yesterday_participants . ' paxas idag = ♻ ' . $percentage_yesterday_participants . '%<br>';
}

echo '⏳ ' . $count_today . ' nya idag → 🧡 ' . $count_today_participants . ' paxas imorrn = ♻ ' . $percentage_today_participants . '%<br>';

wp_reset_postdata();

