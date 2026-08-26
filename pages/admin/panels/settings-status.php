<?php
/**
 * Show summary of active locker full warnings
 */

global $wpdb;
$table = $wpdb->prefix . 'loopis_settings';
$total_lockers = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE setting_key LIKE 'locker_full'");
$active_warnings = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE setting_key LIKE 'locker_full' AND setting_value = 1");

if ($total_lockers === 0) {
	echo '💢 Inga skåp finns';
} elseif ($active_warnings === 0) {
	echo '✅ 0 varningar aktiva';
} else {
	echo '⚠ ' . esc_html($active_warnings) . ' varning' . ($active_warnings === 1 ? '' : 'ar') . ' aktiva';
}