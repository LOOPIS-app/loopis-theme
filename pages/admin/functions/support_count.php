<?php
/**
 * Return count for active support posts
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function support_count() {

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

    return $active_count;
}