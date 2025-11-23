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

// Initialize counts
$ttlitl = 0;
$ttfitl = 0;
$ttgav = 0;
$ttmav = 0;

// Query 1: Time to leave in the locker
$ttlitl = count(get_posts(array(
    'cat' => loopis_cat('booked'),
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 2: Time to fetch in the locker
$ttfitl = count(get_posts(array(
    'meta_key' => 'fetcher',
    'meta_value' => $user_id,
    'cat' => loopis_cat('locker'),
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 3: Time to get a visit
$ttgav = count(get_posts(array(
    'cat' => loopis_cat('booked_custom'),
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 4: Time to make a visit
$ttmav = count(get_posts(array(
    'meta_key' => 'fetcher',
    'meta_value' => $user_id,
    'cat' => loopis_cat('booked_custom'),
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Summarize
$notifications = $ttlitl + $ttfitl + $ttgav + $ttmav;

// Output alerts
if ($notifications > 0) {
    if ($ttlitl > 0) { include_once LOOPIS_THEME_DIR . '/pages/activity/start-tabs/activity-alerts/locker-leave.php'; }
    if ($ttfitl > 0) { include_once LOOPIS_THEME_DIR . '/pages/activity/start-tabs/activity-alerts/locker-fetch.php'; }
    if ($ttgav > 0) { include_once LOOPIS_THEME_DIR . '/pages/activity/start-tabs/activity-alerts/custom-visitor.php'; }
    if ($ttmav > 0) { include_once LOOPIS_THEME_DIR . '/pages/activity/start-tabs/activity-alerts/custom-visit.php'; }
} else {
    include_once LOOPIS_THEME_DIR . '/pages/activity/start-tabs/activity-alerts/no-activity.php';
}