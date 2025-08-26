<?php
/**
 * Activity alerts for member.
 *
 * Included in activity.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Initialize counts
$ttlitl = 0;
$ttfitl = 0;
$ttgav = 0;
$ttmav = 0;

// Query 1: Time to leave in the locker
$ttlitl = count(get_posts(array(
    'cat' => 57,
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 2: Time to fetch in the locker
$ttfitl = count(get_posts(array(
    'meta_key' => 'fetcher',
    'meta_value' => $user_id,
    'cat' => 104,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 3: Time to get a visit
$ttgav = count(get_posts(array(
    'cat' => 147,
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 4: Time to make a visit
$ttmav = count(get_posts(array(
    'meta_key' => 'fetcher',
    'meta_value' => $user_id,
    'cat' => 147,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Summarize
$notifications = $ttlitl + $ttfitl + $ttgav + $ttmav;

// Output alerts
if ($notifications > 0) {
if ($ttlitl > 0) { include_once LOOPIS_THEME_DIR . '/assets/output/user/activity/locker-leave.php'; }
if ($ttfitl > 0) { include_once LOOPIS_THEME_DIR . '/assets/output/user/activity/locker-fetch.php'; }
if ($ttgav > 0) { include_once LOOPIS_THEME_DIR . '/assets/output/user/activity/custom-visitor.php'; }
if ($ttmav > 0) { include_once LOOPIS_THEME_DIR . '/assets/output/user/activity/custom-visit.php';}
} else {
    include_once LOOPIS_THEME_DIR . '/assets/output/user/activity/no-activity.php';
}