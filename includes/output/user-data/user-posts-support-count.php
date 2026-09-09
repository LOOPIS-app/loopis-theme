<?php
/**
 * Output count of user support posts.
 *
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Uses $user_id passed from author.php
$args = array(
    'post_type'      => 'support',
    'author'         => $user_id,
    'posts_per_page' => -1,
    'fields'         => 'ids',
);

$the_query = new WP_Query($args);
$count = $the_query->found_posts;
wp_reset_postdata();

// Output
echo esc_html($count);