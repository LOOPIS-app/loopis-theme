<?php
/**
 * Show summary of active locker full warnings
 */

global $wpdb;
if(is_multisite()){
    $table = $wpdb->base_prefix . 'loopis_lockers';
}else{
    $table = $wpdb->prefix . 'loopis_lockers';
}
$total_lockers = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table");
$active_warnings = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE locker_full = 1");

if ($total_lockers === 0) {
	echo '💢 Inga skåp finns';
} elseif ($active_warnings === 0) {
	echo '✅ 0 varningar aktiva';
} else {
	echo '⚠ ' . esc_html($active_warnings) . ' varning' . ($active_warnings === 1 ? '' : 'ar') . ' aktiva';
}