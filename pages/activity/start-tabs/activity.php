<?php
/**
 * Activity tab.
 * 
 * Showing alerts for fetching/leaving stuff for the current user.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Extra php functions
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-fetch.php'; 
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-deliver.php';

// Query 1: Time to leave in the locker - OPTIMIZED
$query_ttlitl = new WP_Query(array(
    'cat' => loopis_cat('booked_locker'),
    'author' => $user_id,
    'posts_per_page' => -1,
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
));
$ttlitl = $query_ttlitl->post_count;

// Query 2: Time to fetch in the locker - OPTIMIZED
$query_ttfitl = new WP_Query(array(
    'meta_key' => 'fetcher',
    'meta_value' => $user_id,
    'cat' => loopis_cat('locker'),
    'posts_per_page' => -1,
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
));
$ttfitl = $query_ttfitl->post_count;

// Query 3: Time to get a visit - OPTIMIZED
$query_ttgav = new WP_Query(array(
    'cat' => loopis_cat('booked_custom'),
    'author' => $user_id,
    'posts_per_page' => -1,
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
));
$ttgav = $query_ttgav->post_count;

// Query 4: Time to make a visit - OPTIMIZED
$query_ttmav = new WP_Query(array(
    'meta_key' => 'fetcher',
    'meta_value' => $user_id,
    'cat' => loopis_cat('booked_custom'),
    'posts_per_page' => -1,
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
));
$ttmav = $query_ttmav->post_count;

// Summarize
$notifications = $ttlitl + $ttfitl + $ttgav + $ttmav;

// Output alerts - PASS QUERIES to avoid re-querying
if ($notifications > 0) {
    if ($ttlitl > 0) { 
        $the_query = $query_ttlitl; // Reuse query
        include LOOPIS_THEME_DIR . '/pages/activity/start-tabs/activity-alerts/locker-leave.php'; 
    }
    if ($ttfitl > 0) { 
        $the_query = $query_ttfitl; // Reuse query
        include LOOPIS_THEME_DIR . '/pages/activity/start-tabs/activity-alerts/locker-fetch.php'; 
    }
    if ($ttgav > 0) { 
        $the_query = $query_ttgav; // Reuse query
        include LOOPIS_THEME_DIR . '/pages/activity/start-tabs/activity-alerts/custom-visitor.php'; 
    }
    if ($ttmav > 0) { 
        $the_query = $query_ttmav; // Reuse query
        include LOOPIS_THEME_DIR . '/pages/activity/start-tabs/activity-alerts/custom-visit.php'; 
    }
} else {
    include LOOPIS_THEME_DIR . '/pages/activity/start-tabs/activity-alerts/no-activity.php';
}

// Clean up
wp_reset_postdata();