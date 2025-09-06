<?php
/**
 * Support handling functions for admin.
 *
 * Included for admin in functions.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Count users' support post.
 * 
 * Triggered in author.php
 */
function count_user_support($user_ID) {
    $args = array(
        'post_type'      => 'support',
        'author'         => $user_ID,
        'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}