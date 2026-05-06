<?php
/**
 * Show statistics for comments in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Query comments from last 24 hours

$timespan = gmdate('Y-m-d H:i:s', strtotime('-24 hours'));

$count = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT COUNT(*) 
         FROM {$wpdb->comments}
         WHERE comment_date_gmt >= %s",
        $timespan
    )
);

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