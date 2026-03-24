<?php
/**
 * Post handling functions for user.
 *
 * Included where needed.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * USER: BOOK LOCKER
 * User clicks book to fetch in locker
 */
function action_book_locker(int $post_id) {
    
    // Get user variables
    $author = get_post_field('post_author');
    $author_name = get_userdata($author)->display_name;
    $fetcher = get_current_user_id();
    $fetcher_name = get_userdata($fetcher)->display_name;

    // Check fetcher economy
    $profile_economy = get_economy($fetcher);
    $coins = $profile_economy['coins'];
    if ($coins < 1) {
        include LOOPIS_THEME_DIR . '/templates/access/no-coins.php';
        echo '<script src="' . LOOPIS_THEME_DIR . '/assets/js/scroll-to-warning.js"></script>';
        return;
    }
    
    // Get locker code
    $locker_code = get_locker_code(LOCKER_ID);

    // Set post meta
    wp_set_object_terms($post_id, null, 'category');
    wp_set_object_terms($post_id, 'booked', 'category');
    update_post_meta($post_id,'fetcher', $fetcher);
    update_post_meta($post_id,'book_date', current_time('Y-m-d H:i:s'));

    // Send notification from LOOPIS to author
    send_admin_notification('
    ❤ ' . $fetcher_name . ' har paxat! <br>
    ⌛ Lämna gärna i skåpet inom 24 timmar. <br>
    🔓 Kod till skåpet: <b>' . $locker_code . '</b> <br>
    🙏 Tack för att du loopar! @' . $author_name, $post_id, 1);

    // Leave comment by fetcher
    add_comment('<p class="book">
    ❤ Paxad! Du kan lämna i skåpet nu <span>🔔' . $author_name . '</span></p>', $post_id);

    // Refresh page
    refresh_page();
}

/** 
 * USER: BOOK CUSTOM
 * User clicks book to fetch at custom location
 */
function action_book_custom(int $post_id) {
    // Check economy
    $fetcher = get_current_user_id();
    $profile_economy = get_economy($fetcher);
    $coins = $profile_economy['coins'];
    if ($coins < 1) {
        include LOOPIS_THEME_DIR . '/templates/access/no-coins.php';
        echo '<script src="' . LOOPIS_THEME_DIR . '/assets/js/scroll-to-warning.js"></script>';
        return;
    }

    // Get variables
    $location = get_post_meta($post_id, 'location', true);
    $author = get_post_field('post_author');
    $author_name = get_userdata($author)->display_name;
    $fetcher_name = get_userdata($fetcher)->display_name;
    $author_phone = get_the_author_meta('wpum_phone');

    // Set post meta
    wp_set_object_terms($post_id, null, 'category');
    wp_set_object_terms($post_id, 'booked_custom', 'category');
    update_post_meta($post_id,'fetcher', $fetcher);
    update_post_meta($post_id,'book_date', current_time('Y-m-d H:i:s'));

    // Send notification from LOOPIS to author
    send_admin_notification('
    ❤ ' . $fetcher_name . ' har paxat!<br>
    📱 Du kommer få ett sms för att komma överens om hämtning på ' . $location . '. <br>
    🙏 Tack för att du loopar! @' . $author_name, $post_id, 1);

    // Send notification from LOOPIS to fetcher
    send_admin_notification('
    📍 Du har paxat för hämtning på ' . $location . '. <br>
    📱 Skicka ett sms till ' . $author_name . ' på <a href="sms:' . $author_phone .'">' . $author_phone .'</a> <br>
    🙏 Tack för att du loopar! @' . $fetcher_name, $post_id, 1);

    // Leave comment by fetcher
    add_comment('<p class="book">
    ❤ Paxad för hämtning på <span>📍 ' . $location . '</span> <br>
    📱 Jag skickar ett sms inom kort <span>🔔' . $author_name . '</span></p>', $post_id);

    // Refresh page
    refresh_page();
}
