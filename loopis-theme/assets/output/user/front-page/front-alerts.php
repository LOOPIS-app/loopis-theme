<?php
/**
 * Front page alerts for member.
 *
 * Included in front-page.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get current user ID
$user_id = get_current_user_id();

// Initialize counts
$ttlitl = 0;
$ttfitl = 0;
$ttgav = 0;
$ttmav = 0;
$paused = 0;
$archived = 0;

// Query 1: Time to leave in the locker
$ttlitl = count(get_posts(array(
    'cat' => 57,
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 2: Time to fetch in the locker
$ttfitl = count(get_posts(array(
    'meta_key' => 'fetcher',
    'meta_value' => $user_id,
    'cat' => 104,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 3: Time to get a visit
$ttgav = count(get_posts(array(
    'cat' => 147,
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 4: Time to make a visit
$ttmav = count(get_posts(array(
    'meta_key' => 'fetcher',
    'meta_value' => $user_id,
    'cat' => 147,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 5: Paused posts
$paused = count(get_posts(array(
    'cat' => 159,
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 6: Archived posts
$archived = count(get_posts(array(
    'cat' => 167,
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Summarize
$notifications = $ttlitl + $ttfitl + $ttgav + $ttmav + $paused + $archived;

// Output alerts?
if ($notifications > 0) {
    echo '<h5>🔔 Du har saker att göra...</h5>';
    echo '<hr>';
}

// Time to leave in the locker?
if ($ttlitl > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/activity/\'"><i class="fas fa-walking"></i>Du ska lämna ' . $ttlitl . ' sak' . ($ttlitl > 1 ? 'er' : '') . ' i skåpet →</span></p>';
    
    // Show leave warning if enabled
    $locker_id = "12845-1"; // Hardcoded locker ID
    if (function_exists('loopis_get_locker') && function_exists('loopis_get_setting')) {
        $leave_warning_enabled = loopis_get_locker($locker_id, 'leave_warning', 0);
        if ($leave_warning_enabled) {
            $leave_warning = loopis_get_setting('locker_leave_warning', '');
            if (!empty($leave_warning)) {
                echo '<div class="wpum-message information"><p>' . wp_kses_post($leave_warning) . '</p></div>';
            }
        }
    }
}

// Time to fetch in the locker?
if ($ttfitl > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/activity/\'"><i class="fas fa-walking"></i>Du ska hämta ' . $ttfitl . ' sak' . ($ttfitl > 1 ? 'er' : '') . ' i skåpet →</span></p>';
    
    // Show fetch warning if enabled
    $locker_id = "12845-1"; // Hardcoded locker ID
    if (function_exists('loopis_get_locker') && function_exists('loopis_get_setting')) {
        $fetch_warning_enabled = loopis_get_locker($locker_id, 'fetch_warning', 0);
        if ($fetch_warning_enabled) {
            $fetch_warning = loopis_get_setting('locker_fetch_warning', '');
            if (!empty($fetch_warning)) {
                echo '<div class="wpum-message warning"><p>' . wp_kses_post($fetch_warning) . '</p></div>';
            }
        }
    }
}

// Time to get a visit?
if ($ttgav > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/activity/\'">🚪 Någon ska hämta ' . $ttgav . ' sak' . ($ttgav > 1 ? 'er' : '') . ' hos dig →</span></p>';
}

// Time to make a visit?
if ($ttmav > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/activity/\'">📱 Du ska hämta ' . $ttmav . ' sak' . ($ttmav > 1 ? 'er' : '') . ' hos någon →</span></p>';
}

// Archived posts?
if ($archived > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/profil/posts/?status=archived\'">⭕ Du har ' . $archived . ($archived === 1 ? ' arkiverad annons' : '') . ($archived > 1 ? ' arkiverade annonser' : '') . ' →</span></p>';
}

// Paused posts?
if ($paused > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/profil/posts/?status=paused\'">😎 Du har ' . $paused . ($paused === 1 ? ' pausad annons' : '') . ($paused > 1 ? ' pausade annonser' : '') . ' →</span></p>';
}

// Add a spacer if there are notifications
if ($notifications > 0) {
    echo '<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>';
}