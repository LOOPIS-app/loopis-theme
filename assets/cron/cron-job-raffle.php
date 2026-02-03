<?php
// Build paths relative to this script location.
$wp_root = dirname(__DIR__, 5);
$wp_content = $wp_root . '/wp-content';
$theme_dir = $wp_content . '/themes/loopis-theme';

// Load WordPress
require_once $wp_root . '/wp-load.php';

// Set current user to admin (ID 1) to avoid permission issues
wp_set_current_user( 1 );

// Include the necessary files
include_once $theme_dir . '/functions/cron/cron-raffle.php';
include_once $theme_dir . '/functions/cron/cron-raffle-functions.php';
include_once $theme_dir . '/functions/user/admin-post-comment.php';
include_once $theme_dir . '/functions/user/admin-notification.php';
include_once $theme_dir . '/functions/user/get-locker.php';

// Start the custom cron job
cron_job_raffle();
?>