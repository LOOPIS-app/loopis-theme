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
 * AUTHOR: REMOVE POST 
 * Author (or admin) clicks remove on post 
 */
function action_remove(int $post_id) {

    // Set post meta
	$timestamp = current_time('Y-m-d H:i:s');
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'removed', 'category' );
	$author_id = get_post_field( 'post_author', $post_id );
	$fetcher = (int) get_post_meta($post_id,'fetcher', true);
	if($fetcher>0){
		loopis_ledger_add_post('cancelled', $author_id , $post_id, ['timestamp' => $timestamp, 'type' => 'removed']);
		send_admin_notification_email('Tyvärr har '.get_the_author_meta('display_name', $author_id).' tagit bort denna annons!', $post_id, 2, $fetcher);
	}
	update_post_meta($post_id,'fetcher', null);
	update_post_meta($post_id,'remove_date', $timestamp);
	// Update ledger
	loopis_ledger_add_post('removed', $author_id , $post_id, ['timestamp' => $timestamp]);
	

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
	wp_set_object_terms( $post_id, 'old', 'category' );
	update_post_meta($post_id,'remove_date', null);
	update_post_meta($post_id,'extend_date', current_time('Y-m-d H:i:s'));
	
	// Leave comment by author
	add_comment ('<p class="unremove">🌀 Annons publicerad igen.</p>', $post_id );
	
	// Refresh page
	refresh_page();
}