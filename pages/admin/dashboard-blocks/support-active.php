<?php
/**
 * Show count for active support posts in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Query active support posts
$args = array(
    'post_type'      => 'support',
    'posts_per_page' => -1,
    'tax_query'      => array(
        array(
            'taxonomy' => 'support-status',
            'field'    => 'slug',
            'terms'    => 'active'
        )
    )
);

$the_query = new WP_Query($args);
$count = $the_query->found_posts;

// Output
if ($count == 0) {
    echo '✅ 0 pågående ärenden';
} else {
    echo '⚠ ' . $count . ' pågående ärende';
    if ($count != 1) {
        echo 'n';
    }
}