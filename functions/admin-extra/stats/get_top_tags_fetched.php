<?php
/**
 * Statistics function: Get top tags fetched for yearly stats
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function get_top_tags_fetched($selected_year) {
    // Set up the query arguments
$args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => -1, // Fetch all posts
    'fields'         => 'ids', // Only retrieve post IDs to optimize performance
    'category__in'   => array(41), // Only fetch posts in category 41
);

    // If a specific year is selected, add a date query
    if ($selected_year !== 'all') {
        $args['date_query'] = array(
            array(
                'year' => $selected_year,
            ),
        );
    }

    // Query posts based on the arguments
    $query = new WP_Query($args);

    $tag_post_counts = array();

    // Loop through posts and count tags
    if ($query->have_posts()) {
        foreach ($query->posts as $post_id) {
            $tags = wp_get_post_tags($post_id); // Get tags for the current post

            foreach ($tags as $tag) {
                $tag_id = $tag->term_id;

                // Increment the count for each tag
                if (!isset($tag_post_counts[$tag_id])) {
                    $tag_post_counts[$tag_id] = 0;
                }
                $tag_post_counts[$tag_id]++;
            }
        }
    }

    // Restore original post data
    wp_reset_postdata();

    // Sort tags by post count in descending order
    arsort($tag_post_counts);

    // Return the top 10 tags
    return array_slice($tag_post_counts, 0, 20, true);
}