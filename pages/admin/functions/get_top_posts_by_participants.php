<?php
/**
 * Statistics function: Höh?
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function get_top_posts_by_participants($selected_year) {
    // Set up the query arguments
    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1, // Fetch all posts
        'meta_key'       => 'participants', // Ensure posts have the 'participants' field
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

    $posts_with_participants = array();

    // Loop through posts and count participants
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $post_id = get_the_ID();
            $participants = get_post_meta($post_id, 'participants', true);

            // Ensure participants is an array
            if (!empty($participants) && is_array($participants)) {
                $posts_with_participants[] = array(
                    'post_id'          => $post_id,
                    'post_title'       => get_the_title(),
                    'participant_count' => count($participants),
                );
            }
        }
    }

    // Restore original post data
    wp_reset_postdata();

    // Sort posts by participant count in descending order
    usort($posts_with_participants, function ($a, $b) {
        return $b['participant_count'] - $a['participant_count'];
    });

    // Return the top 10 posts
    return array_slice($posts_with_participants, 0, 10);
}