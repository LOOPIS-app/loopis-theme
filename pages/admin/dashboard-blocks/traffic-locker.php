<?php
/**
 * Show statistics for todays locker traffic in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get today's date
$today_date = date('Y-m-d');

// Initialize arrays to store unique user IDs
$fetcher_ids = array();
$author_ids = array();

// Initialize counters for the number of posts
$count_fetched = 0;
$count_locker = 0;

// Query posts with 'locker_date' matching today's date
$args_locker = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'locker_date',
            'value'   => $today_date,
            'compare' => 'LIKE',
        ),
    ),
);

$query_locker = new WP_Query($args_locker);

if ($query_locker->have_posts()) {
    while ($query_locker->have_posts()) {
        $query_locker->the_post();

        // Increment the count of posts with 'locker_date'
        $count_locker++;

        // Get the post author ID
        $author_id = get_the_author_meta('ID');

        // Add the author ID to the author IDs array
        if ($author_id) {
            $author_ids[] = $author_id;
        }
    }
}

// Query posts with 'fetch_date' matching today's date
$args_fetch = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'fetch_date',
            'value'   => $today_date,
            'compare' => 'LIKE',
        ),
    ),
);

$query_fetch = new WP_Query($args_fetch);

if ($query_fetch->have_posts()) {
    while ($query_fetch->have_posts()) {
        $query_fetch->the_post();

        // Increment the count of posts with 'fetch_date'
        $count_fetched++;

        // Get the 'fetcher' custom field (user ID)
        $fetcher_id = get_post_meta(get_the_ID(), 'fetcher', true);

        // Add the fetcher ID to the fetcher IDs array
        if ($fetcher_id) {
            $fetcher_ids[] = $fetcher_id;
        }
    }
}

// Reset post data
wp_reset_postdata();

// Combine fetcher IDs and author IDs, and count unique IDs
$unique_visitors = count(array_unique(array_merge($fetcher_ids, $author_ids)));

// Output the results
echo '🔓 ' . esc_html($unique_visitors) . ' besökare idag<br>';
echo '✅ ' . esc_html($count_locker) . ' saker lämnade<br>';
echo '☑ ' . esc_html($count_fetched) . ' saker hämtade';