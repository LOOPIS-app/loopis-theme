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
 * AUTHOR: EXTEND POST 
 * Author clicks activate on archived post
 */
function action_extend(int $post_id) {

	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'first', 'category' );
	update_field('extend_date', current_time('Y-m-d H:i:s'));	
	
    // Refresh page
    refresh_page();
}

/** 
 * AUTHOR: EXTEND ALL POSTS
 * Author clicks button to activate all archived posts
 */
function action_extend_all(int $user_ID) {
		
	    // Arguments
	    $args = array(
	        'author'         => $user_ID,
	        'post_type'      => 'post',
	        'posts_per_page' => -1,
	        'fields'         => 'ids',
	        'category_name'  => 'archived',
	    );
	
	    // Get the post IDs
	    $user_posts = get_posts($args);
	
	    // Loop through the user's posts and update the category
	    foreach ($user_posts as $post_id) {
			wp_set_object_terms( $post_id, null, 'category' ); 
			wp_set_object_terms( $post_id, 'first', 'category' );
			update_field('extend_date', current_time('Y-m-d H:i:s'), $post_id);
	    }
	
    // Refresh page
    refresh_page();
}