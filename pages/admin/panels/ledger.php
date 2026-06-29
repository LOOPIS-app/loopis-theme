<?php
/**
 * Show last week statistics for gifts in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$table = $wpdb->base_prefix . 'loopis_ledger';

$now_time = new DateTime(current_time('mysql'));
$start_date = clone $now_time;
$start_date->modify('-8 days');
$start_date = $start_date->format('Y-m-d 00:00:00');

$end_date = clone $now_time;
$end_date->modify('-2 day');
$end_date = $end_date->format('Y-m-d 23:59:59');

$total_events = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE timestamp BETWEEN %s AND %s",$start_date, $end_date));
$total_submitted = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE timestamp BETWEEN %s AND %s AND event = %s",$start_date, $end_date,'submitted'));
$total_booked = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE timestamp BETWEEN %s AND %s AND event = %s",$start_date, $end_date,'booked'));

$events = ($total_events == 0) 
    ? '⚠ 0 händelser <br>'
    : '🔥 ' . $total_events . ' händelser ('.round($total_events/7).' per dag) <br>';
$submitted = ($total_submitted == 0) 
    ? '⚠ 0 annonser <br>'
    : '💚 ' . $total_submitted . ' annonser ('. round($total_submitted/7).' per dag) <br>';
$booked = ($total_booked == 0) 
    ? '⚠ 0 bokade annonser <br>'
    : '❤ ' . $total_booked . ' paxade ('.($total_booked/7).' per dag) =  ♻ ' . round(($total_booked/$total_submitted)*100) . '%';

echo $events;
echo $submitted;
echo $booked;

