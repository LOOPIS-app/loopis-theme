<?php
/**
 * Show count for active support posts in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// Count total support posts
$inactive_args = array(
    'post_type'      => 'support',
    'posts_per_page' => -1,
    'count'          => true,
    'tax_query'      => array(
        array(
            'taxonomy' => 'support-category',
            'field'    => 'slug',
            'terms'    => 'inactive'
        )
    )
);

$inactive_query = new WP_Query($inactive_args);
$inactive_count = $inactive_query->found_posts;


// Count active support posts
$active_args = array(
    'post_type'      => 'support',
    'posts_per_page' => -1,
    'count'          => true,
    'tax_query'      => array(
        array(
            'taxonomy' => 'support-category',
            'field'    => 'slug',
            'terms'    => 'active'
        )
    )
);

$active_query = new WP_Query($active_args);
$active_count = $active_query->found_posts;

// Count support posts created past 7 days
$new_args = array(
    'post_type'      => 'support',
    'posts_per_page' => -1,
    'count'          => true,
    'date_query'     => array(
        array(
            'after' => '7 days ago'
        )
    )
);

$new_new = new WP_Query($new_args);
$new_count = $new_new->found_posts;

// Output
if ($new_count == 0) {
    echo '💤 0 nya trådar';
} else {
    echo '⏳ ' . $new_count;
    echo $new_count == 1 ? ' ny tråd' : ' nya trådar senaste veckan';
}

echo '<br>';

if ($active_count == 0) {
    echo '✅ 0 aktiva trådar';
} else {
    echo '🔴 ' . $active_count . ' ';
    echo $active_count == 1 ? 'aktiv tråd' : 'aktiva trådar';
    echo ' just nu';
}

echo '<br>';

if ($inactive_count == 0) {
    echo '✅ 0 lösta trådar';
} else {
    echo '🟢 ' . $inactive_count . ' ';
    echo $inactive_count == 1 ? 'löst tråd' : 'lösta trådar';
}