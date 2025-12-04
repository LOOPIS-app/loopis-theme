<?php
// Load WordPress
require_once('/home/intetse/loopis.app/wp-load.php');

// Define theme path
$theme_dir = '/home/intetse/loopis.app/wp-content/themes/loopis-theme';

// Include the necessary files
include_once $theme_dir . '/functions/cron/cron-archive.php';
include_once $theme_dir . '/functions/user/admin-post-comment.php';
include_once $theme_dir . '/functions/user/admin-notification.php';

// Start the custom cron job
cron_job_archive();
?>