<?php
/**
 * Show post details for user/visitor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

echo '<div class="logg">';
if (is_user_logged_in()) {
    if ($previous_post_id) {
        // Recreated
        echo '<p><i class="fas fa-arrow-alt-circle-right"></i>' . get_the_author_posts_link() . ' <a href="' . get_permalink($previous_post_id) . '">återskapade</a> ';
    } else {
        // Created
        echo '<p><i class="fas fa-arrow-alt-circle-up"></i>' . get_the_author_posts_link() . ' skapade ';
    }
    echo 'för ' . human_time_diff(get_the_time('U'), current_time('timestamp')) . ' sen <span>' . $post_date . '</span></p>';

    // Extended
    if ($extend_date) {
        echo '<p><i class="fas fa-check-circle"></i>Förnyad för ' . human_time_diff(strtotime($extend_date), current_time('timestamp')) . ' sen <span>' . $extend_date . '</span></p>';
    }
    // Paused
    if (in_category('paused')) {
        $pause_date = get_field('pause_date');
        echo '<p><i class="fas fa-pause-circle"></i>Pausad för ' . human_time_diff(strtotime($pause_date), current_time('timestamp')) . ' sen<span>' . $pause_date . '</span></p>';
    }
    // Archived
    if (in_category('archived')) {
        $archive_date = get_field('archive_date');
        echo '<p><i class="fas fa-minus-circle"></i>Arkiverad för ' . human_time_diff(strtotime($archive_date), current_time('timestamp')) . ' sen<span>' . $archive_date . '</span></p>';
    }
    // Booked
    if (in_category(array('booked_locker', 'booked_custom', 'locker'))) {
        $book_date = get_field('book_date');
        echo '<p><i class="fas fa-heart"></i><a href="' . $fetcherlink . '">' . $fetchername . '</a> paxade för ' . human_time_diff(strtotime($book_date), current_time('timestamp')) . ' sen <span>' . $book_date . '</span></p>';
    }
    // Fetched
    if (in_category('fetched')) {
        $fetch_date = get_field('fetch_date');
        echo '<p><i class="fas fa-check-square"></i><a href="' . $fetcherlink . '">' . $fetchername . '</a> hämtade för ' . human_time_diff(strtotime($fetch_date), current_time('timestamp')) . ' sen <span>' . $fetch_date . '</span></p>';
    }
    // Forwarded
    if ($forward_post_id) {
        $forward_date = get_field('forward_date');
        echo '<p><i class="fas fa-arrow-alt-circle-right"></i><a href="' . $fetcherlink . '">' . $fetchername . '</a> <a href="' . get_permalink($forward_post_id) . '">skickade vidare</a> för ' . human_time_diff(strtotime($forward_date), current_time('timestamp')) . ' sen <span>' . $forward_date . '</span></p>';
    }
    // Removed
    if (in_category('removed')) {
        $remove_date = get_field('remove_date');
        echo '<p><i class="fas fa-times-circle"></i>Borttagen för ' . human_time_diff(strtotime($remove_date), current_time('timestamp')) . ' sen<span>' . $remove_date . '</span></p>';
    }
} else {
    // Visitor
    echo '<p><i class="fas fa-arrow-alt-circle-up"></i>Skapad för ' . human_time_diff(get_the_time('U'), current_time('timestamp')) . ' sen <span>' . $post_date . '</span></p>';
}
echo '</div><!--logg-->';
?>