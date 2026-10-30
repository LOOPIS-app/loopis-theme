<?php
/**
 * Gifts statistitcs for admin dashboard (not used)
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// Set timeframe for the last 7 complete days
$now_time = new DateTime(current_time('mysql'));
$start_date = clone $now_time;
$start_date->modify('-8 days');
$start_date = $start_date->format('Y-m-d 00:00:00');

$end_date = clone $now_time;
$end_date->modify('-2 day');
$end_date = $end_date->format('Y-m-d 23:59:59');

// Count new posts last 7 (complete) days
$week_total_args = array(
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'after'     => $start_date,
            'before'    => $end_date,
            'inclusive' => true,
        ),
    ),
);

$week_total_query = new WP_Query($week_total_args);
$week_total_count = $week_total_query->found_posts;
$week_total_average = round($week_total_count / 7, 0);

// Set categories to count
$booked_categories = loopis_cats(['booked', 'booked_custom', 'locker', 'fetched']);

// Count booked posts last 7 (complete) days
$week_booked_args = array(
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

$week_booked_query = new WP_Query($week_booked_args);
$week_booked_count = $week_booked_query->found_posts;
$week_booked_average = round($week_booked_count / 7, 0);

$week_booked_percentage = ($week_total_count == 0) ? 0 : round(($week_booked_count / $week_total_count) * 100);

// Set timeframe for the last 24 hours
$day_end = new DateTime(current_time('mysql'));
$day_start = clone $day_end;
$day_start->modify('-24 hours');

$day_start = $day_start->format('Y-m-d H:i:s');
$day_end = $day_end->format('Y-m-d H:i:s');

// Count new posts last 24 hours
$day_total_args = array(
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'after'     => $day_start,
            'before'    => $day_end,
            'inclusive' => true,
        ),
    ),
);

$day_total_query = new WP_Query($day_total_args);
$day_total_count = $day_total_query->found_posts;

// Count booked posts last 24 hours
$day_booked_args = array(
    'cat'            => $booked_categories,
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'after'     => $day_start,
            'before'    => $day_end,
            'inclusive' => true,
        ),
    ),
);

$day_booked_query = new WP_Query($day_booked_args);
$day_booked_count = $day_booked_query->found_posts;

// Count new posts with at least one participant in the last 24 hours
$day_participant_args = array(
    'post_type'      => 'post',
    'cat'            => loopis_cat('new'),
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'date_query'     => array(
        array(
            'after'     => $day_start,
            'before'    => $day_end,
            'inclusive' => true,
        ),
    ),
    'meta_query'     => array(
        array(
            'key'     => 'participants',
            'compare' => 'EXISTS',
        ),
    ),
);

$day_participant_query = new WP_Query($day_participant_args);
foreach ($day_participant_query->posts as $post_id) {
    $participants = get_post_meta($post_id, 'participants', true);
    if (is_array($participants) && !empty($participants)) {
        $day_booked_count++;
    }
}

$day_booked_percentage = ($day_total_count == 0) ? 0 : round(($day_booked_count / $day_total_count) * 100);

// Set post frequency message (not yet used)
if ($day_total_count < $week_total_average) {
    $post_frequency_message = '😴 Annonserandet går ner';
} elseif ($day_total_count == $week_total_average) {
    $post_frequency_message = '😇 Annonserandet är stabilt';
} elseif ($day_total_count > $week_total_average) {
    $post_frequency_message = '😍 Annonserandet går upp';
}

// Set booking effect message
if ($week_booked_percentage > 60) {
    $effect_message = '👍 Mycket paxningar = minst 60%';
} else {
    $effect_message = '👎 Få paxningar = mindre än 60%';
}

// Output
echo 'Senaste dygnet: 💚 ' . $day_total_count . ' → ' . '❤ ' . $day_booked_count . ' = ♻ ' . $day_booked_percentage . '%<br>';
echo 'Snitt senaste vecka: 💚 ' . $week_total_average . ' → ' . '❤ ' . $week_booked_average . ' = ♻ ' . $week_booked_percentage . '%<br>';
echo '<span class="italic">' . $effect_message . '</span>';