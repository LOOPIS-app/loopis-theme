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
$leave = 0;
$fetch = 0;
$get_visit = 0;
$make_visit = 0;
$paused = 0;
$archived = 0;

// Query 1: Time to leave in the locker
$leave = count(get_posts(array(
    'cat' => loopis_cat('booked_locker'),
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 2: Time to fetch in the locker
$fetch = count(get_posts(array(
    'meta_key' => 'fetcher',
    'meta_value' => $user_id,
    'cat' => loopis_cat('locker'),
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 3: Time to get a visit
$get_visit = count(get_posts(array(
    'cat' => loopis_cat('booked_custom'),
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 4: Time to make a visit
$make_visit = count(get_posts(array(
    'meta_key' => 'fetcher',
    'meta_value' => $user_id,
    'cat' => loopis_cat('booked_custom'),
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 5: Paused posts
$paused = count(get_posts(array(
    'cat' => loopis_cat('paused'),
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Query 6: Archived posts
$archived = count(get_posts(array(
    'cat' => loopis_cat('archived'),
    'author' => $user_id,
    'fields' => 'ids',
    'posts_per_page' => -1,
)));

// Summarize
$notifications = $leave + $fetch + $get_visit + $make_visit + $paused + $archived;

// Show warning if locker is full and user has things to leave or fetch
if ($leave > 0 || $fetch > 0) {
    $warning_enabled = get_locker_data(LOCKER_ID, 'locker_full', 0);
    if ($warning_enabled) {
        $full_warning = loopis_get_setting('locker_full_warning', '');
        if (!empty($full_warning)) {
            echo '<h5>⚠ Mycket saker i skåpen!</h5><hr>';
            echo '<div class="wpum-message warning"><p>' . wp_kses_post(nl2br($full_warning)) . '</p></div>';
        }
    }
}

// OUTPUT ALERTS?
if ($notifications > 0) {
    echo '<h5>🔔 Du har saker att göra...</h5>';
    echo '<hr>';
}

// Time to leave in the locker?
if ($leave > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/activity/\'"><i class="fas fa-walking"></i>Du ska lämna ' . $leave . ' sak' . ($leave > 1 ? 'er' : '') . ' i skåpet →</span></p>';
}

// Time to fetch in the locker?
if ($fetch > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/activity/\'"><i class="fas fa-walking"></i>Du ska hämta ' . $fetch . ' sak' . ($fetch > 1 ? 'er' : '') . ' i skåpet →</span></p>';
}

// Time to get a visit?
if ($get_visit > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/activity/\'">🚪 Någon ska hämta ' . $get_visit . ' sak' . ($get_visit > 1 ? 'er' : '') . ' hos dig →</span></p>';
}

// Time to make a visit?
if ($make_visit > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/activity/\'">📱 Du ska hämta ' . $make_visit . ' sak' . ($make_visit > 1 ? 'er' : '') . ' hos någon →</span></p>';
}

// Archived posts?
if ($archived > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/activity/?view=posts&status=archived\'">⭕ Du har ' . $archived . ($archived === 1 ? ' arkiverad annons' : '') . ($archived > 1 ? ' arkiverade annonser' : '') . ' →</span></p>';
}

// Paused posts?
if ($paused > 0) {
    echo '<p><span class="mega-link notif" onclick="window.location.href=\'/activity/?view=posts&status=paused\'">😎 Du har ' . $paused . ($paused === 1 ? ' pausad annons' : '') . ($paused > 1 ? ' pausade annonser' : '') . ' →</span></p>';
}

// Insert spacer.
if ($notifications > 0) {
    insert_spacer(20);
}