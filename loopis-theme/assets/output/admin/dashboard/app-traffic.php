<?php
/**
 * Show statistics for page traffic in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get statistics from plugin WP Statistics
// Ensure WP Statistics is active before using its functions
if (!function_exists('wp_statistics')) {
    echo '<p>💢 WP Statistics plugin is not active.</p>';
    return; // Exit if not available
}
$users_online = do_shortcode('[wpstatistics stat=usersonline]');
$visitors_today = do_shortcode('[wpstatistics stat=visitors time=today]');
$visits_today = do_shortcode('[wpstatistics stat=visits time=today]');
$pageviews_today = ($visitors_today > 0) ? number_format($visits_today / $visitors_today, 0) : '0';

$visitors_yesterday = do_shortcode('[wpstatistics stat=visitors time=yesterday]');
$visits_yesterday = do_shortcode('[wpstatistics stat=visits time=yesterday]');
$pageviews_yesterday = ($visitors_yesterday > 0) ? number_format($visits_yesterday / $visitors_yesterday, 0) : '0';

// Output statistics
echo '👤 ' . $users_online . ' besökare just nu<br>';
echo '👤 ' . $visitors_today . ' besökare idag → 👁 ' . $pageviews_today . ' sidvisningar i snitt<br>';
echo '👤 ' . $visitors_yesterday . ' besökare igår → 👁 ' . $pageviews_yesterday . ' sidvisningar i snitt';