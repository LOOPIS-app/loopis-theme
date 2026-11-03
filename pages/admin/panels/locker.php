<?php
/**
 * Show statistics for current gift circulation in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// COUNT ITEMS IN LOCKER
$args_locker = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'tax_query'      => array(
        array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => loopis_cat('locker'),
        ),
    ),
);

$query_locker = new WP_Query($args_locker);
$count_locker = $query_locker->found_posts;


// COUNT ITEMS BOOKED FOR LOCKER
$args_booked = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'tax_query'      => array(
        array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => loopis_cat('booked'),
        ),
    ),
);

$query_booked = new WP_Query($args_booked);
$count_booked = $query_booked->found_posts;

// COUNT ITEMS BOOKED FOR CUSTOM PICKUP
$args_booked_custom = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'tax_query'      => array(
        array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => loopis_cat('booked_custom'),
        ),
    ),
);

$query_booked_custom = new WP_Query($args_booked_custom);
$count_booked_custom = $query_booked_custom->found_posts;


// CHECK LOCKER WARNING STATUS
$locker_warning_value = loopis_get_setting('locker_warning', '0');

if ($locker_warning_value === '0') {
	$locker_warning_text = '💡 Varning för skåp ej aktiv<br>';
} else {
	$locker_warning_text = '<b>⚠ Varning för skåp är aktiv!</b><br>';
}

// Output
echo $locker_warning_text;
echo '⏹ ' . $count_locker . ' saker finns i skåpet<br>';
echo '❤ ' . $count_booked . ' saker ska till skåpet<br>';
echo '📍 ' . $count_booked_custom . ' saker ska hämtas på annan plats<br>';

wp_reset_postdata();