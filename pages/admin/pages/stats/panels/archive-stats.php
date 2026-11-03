<?php
/**
 * Show statistics for archived gifts in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// COUNT STORAGE GIFTS
$storage_args = array(
    'cat'            => loopis_cat('storage'),
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'post_status' => array('draft', 'publish'),
);

$storage_posts = get_posts($storage_args);
$storage_count = count($storage_posts);

echo '📦 ' . $storage_count . ' annonser i lager<br>';

// COUNT ARCHIVED GIFTS
$archived_args = array(
        'cat' => loopis_cat('archived'),
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

$archived_query = new WP_Query($archived_args);
$archived_count = $archived_query->found_posts;


echo '⭕ ' . $archived_count . ' annonser arkiverade<br>';

// COUNT PAUSED GIFTS
    $paused_args = array(
        'cat' => loopis_cat('paused'),
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

$paused_query = new WP_Query($paused_args);
$paused_count = $paused_query->found_posts;

echo '😎 ' . $paused_count . ' annonser pausade';