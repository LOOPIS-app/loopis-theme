<?php
/**
 * Post handling functions for LOOPIS user.
 *
 * Included in profil/posts.php
 * Included in profil/fetched.php
 * Included in single.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** 
 * AUTHOR: REMOVE POST 
 * Author (or admin) clicks remove on post 
 */
function action_remove(int $post_id) {

    // Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'removed', 'category' );
	update_field('fetcher', null);
	update_field('remove_date', current_time('Y-m-d H:i:s'));

	// Leave comment by author
	add_comment ('<p class="remove">❌ Annons borttagen.</p>', $post_id );

    // Refresh page
    refresh_page();
}

/** 
 * AUTHOR: UNREMOVE POST 
 * Author clicks unremove on post
 */
function action_unremove(int $post_id) {
	
	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'first', 'category' );
	update_field('remove_date', null);
	update_field('extend_date', current_time('Y-m-d H:i:s'));
	
	// Leave comment by author
	add_comment ('<p class="unremove">🌀 Annons publicerad igen.</p>', $post_id );
	
	// Refresh page
	refresh_page();
}