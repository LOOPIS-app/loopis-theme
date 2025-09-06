<?php
/**
 * Show count for active booking posts in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Query active bookings
$args = array(
    'post_type' => 'booking',
    'tax_query' => array(
        array(
            'taxonomy' => 'booking-status',
            'field' => 'slug',
            'terms' => array('sent', 'confirmed', 'borrowed'),
        ),
    ),
);

$the_query = new WP_Query($args);
$count = $the_query->found_posts;

// Output
if ($count == 0) {
    echo '✅ 0 aktiva bokningar';
} else {
    echo '🔄 ' . $count . ' aktiv';
    if ($count != 1) {
        echo 'a';
    }
    echo ' bokning';
    if ($count != 1) {
        echo 'ar';
    }
}