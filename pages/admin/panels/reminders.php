<?php
/**
 * Time to send reminders?
 * 
 * Output the count for posts where it's time to send an sms.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Extra functions
include_once LOOPIS_THEME_DIR . '/pages/admin/functions/reminder_sms_count.php';

// Get the count of posts where it's time to send an sms.
$sms_count = reminder_sms_count();

// Output
if ($sms_count == 0) {
    echo '✅ 0 sms att skicka';
} else {
    echo '<b>📱 ' . $sms_count . ' sms att skicka</b>';
}
