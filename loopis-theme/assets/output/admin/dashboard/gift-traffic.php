<?php
/**
 * Show statistics for current gift circulation in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// GIFTS BOOKED FOR LOCKER
$args_booked_locker = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'tax_query'      => array(
        array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => 57,
        ),
    ),
);

$query_booked_locker = new WP_Query($args_booked_locker);
$count_booked_locker = $query_booked_locker->found_posts;

echo '❤ ' . $count_booked_locker . ' saker ska till skåpet<br>';

// GIFTS BOOKED FOR CUSTOM LOCATION
$args_booked_custom = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'tax_query'      => array(
        array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => 147,
        ),
    ),
);

$query_booked_custom = new WP_Query($args_booked_custom);
$count_booked_custom = $query_booked_custom->found_posts;

echo '📍 ' . $count_booked_custom . ' saker ska hämtas på annan adress<br>';

// GIFTS IN LOCKER
$args_locker = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'tax_query'      => array(
        array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => 104,
        ),
    ),
);

$query_locker = new WP_Query($args_locker);
$count_locker = $query_locker->found_posts;

echo '⏹ ' . $count_locker . ' saker finns i skåpet';

wp_reset_postdata();