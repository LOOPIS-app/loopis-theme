<?php
/**
 * Front page alerts for admin.
 *
 * Included in front-page.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Initialize notifications for admin.
$notifications = 0;


// Get count of sms reminders
include_once LOOPIS_THEME_DIR . '/pages/admin/functions/reminder_sms_count.php';
$sms_count = reminder_sms_count();

// Get count of active support posts
include_once LOOPIS_THEME_DIR . '/pages/admin/functions/support_count.php';
$support_count = support_count();


// Summarize notifications for admin.
$notifications = $sms_count + $support_count;


// OUTPUT ALERTS?
if ($notifications > 0) {
    echo '<h5>🦀 Admin</h5>';
    echo '<hr>';
    echo '<div class="span-list">';

    // Set admin base URL
    $admin_url = home_url('/admin/');
}

// Time to send reminders
if ($sms_count > 0) {
    echo '<span class="mega-link notif" onclick="location.href=\'' . esc_url( add_query_arg('view', 'pages/reminders', $admin_url) ) . '\'">📱 Du har ' . $sms_count . ' ' . ($sms_count > 1 ? "påminnelser" : "påminnelse") . ' att skicka →</span><br>';
}

// Active support posts
if ($support_count > 0) {
    echo '<span class="mega-link notif" onclick="location.href=\'' . esc_url( get_post_type_archive_link('support') ) . '\'">🛟 Det finns ' . $support_count . ' ' . ($support_count > 1 ? "aktiva supporttrådar" : "aktiv supporttråd") . ' →</span><br>';
}

// Insert spacer.
if ($notifications > 0) {
    echo '</div>'; // Close span-list
}