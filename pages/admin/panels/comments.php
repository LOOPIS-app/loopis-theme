<?php
/**
 * Show statistics for comments in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Set timespan
$timespan = gmdate('Y-m-d H:i:s', strtotime('-24 hours'));

// Get all comments from the last 24 hours
$all_count = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT COUNT(*) 
         FROM {$wpdb->comments}
         WHERE comment_date_gmt >= %s",
        $timespan
    )
);

// Get action comments from the last 24 hours
$actions_count = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT COUNT(*)
         FROM {$wpdb->comments}
         WHERE comment_date_gmt >= %s
           AND LEFT(comment_content, 2) = '<p'",
        $timespan
    )
);

// Calculate user-generated comment count (excluding action comments)
$comment_count = $all_count - $actions_count;

// Output
echo 'Senaste dygnet:<br>';
if ($comment_count == 0) {
    echo '💤 0 kommentarer';
} else {
    echo '🗣️ ' . $comment_count . ' kommentar';
    if ($comment_count != 1) {
        echo 'er';
    }
}

echo '<br>';

if ($actions_count == 0) {
    echo '💤 0 händelser';
} else {
    echo '▶️ ' . $actions_count . ' händelser';
}