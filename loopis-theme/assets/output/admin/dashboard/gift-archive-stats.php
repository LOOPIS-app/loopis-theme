<?php
/**
 * Show statistics for archived gifts in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// COUNT STORAGE GIFTS
$storage_args = array(
    'post_type'      => 'post',
    'cat'            => '157',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'post_status' => array('draft', 'publish'),
);

$storage_posts = get_posts($storage_args);
$storage_count = count($storage_posts);

echo '📦 ' . $storage_count . ' annonser i lager<br>';

// COUNT ARCHIVED GIFTS
$archived_category = get_category_by_slug('archived');
$archived_count = 0;

if ($archived_category) {
    $archived_args = array(
        'category__in' => array($archived_category->term_id),
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    $archived_query = new WP_Query($archived_args);
    $archived_count = $archived_query->found_posts;
}

echo '⭕ ' . $archived_count . ' annonser arkiverade<br>';

// COUNT PAUSED GIFTS
$paused_category = get_category_by_slug('paused');
$paused_count = 0;

if ($paused_category) {
    $paused_args = array(
        'category__in' => array($paused_category->term_id),
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    $paused_query = new WP_Query($paused_args);
    $paused_count = $paused_query->found_posts;
}

echo '😎 ' . $paused_count . ' annonser pausade';