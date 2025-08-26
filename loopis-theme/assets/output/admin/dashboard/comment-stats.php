<?php
/**
 * Show statistics for comments in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Set timespan for last 24 hours
$timespan = date('Y-m-d H:i:s', strtotime('-24 hours'));

// Query comments from last 24 hours
$comments_query = new WP_Comment_Query(array(
    'date_query' => array(
        array(
            'after' => $timespan,
            'inclusive' => true,
        ),
    ),
));

$comments = $comments_query->get_comments();
$count = count($comments);

// Output
if ($count == 0) {
    echo '💢 0 kommentarer senaste dygnet';
} else {
    echo '🗨 ' . $count . ' kommentar';
    if ($count != 1) {
        echo 'er';
    }
    echo ' senaste dygnet';
}