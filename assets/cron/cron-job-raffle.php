<?php
// Build paths relative to this file location
$wp_root = dirname(__DIR__, 5);
$theme_dir = $wp_root . '/wp-content/themes/loopis-theme';

// Load WordPress
require_once $wp_root . '/wp-load.php';

// Include the necessary files
include_once $theme_dir . '/functions/cron/cron-raffle.php';
include_once $theme_dir . '/functions/cron/cron-raffle-functions.php';
include_once $theme_dir . '/functions/user/admin-post-comment.php';
include_once $theme_dir . '/functions/user/admin-notification.php';
include_once $theme_dir . '/functions/user/get-locker.php';

// Start the custom cron job
cron_job_raffle();
?>