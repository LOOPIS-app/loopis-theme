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
 * AUTHOR: COMPLAINT POST 
 * Admin clicks complaint on post 
 */
function action_complaint(int $post_id) {

    // Set post meta
	$timestamp = current_time('Y-m-d H:i:s');
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'complaint', 'category' );
	$author_id = get_post_field( 'post_author', $post_id );
	$fetcher_id = (int) get_post_meta($post_id,'fetcher', true);

	if($fetcher_id>0){
		loopis_ledger_add_post('cancelled', $fetcher_id , $post_id, ['timestamp' => $timestamp, 'type' => 'complaint']);
	}
	update_post_meta($post_id,'fetcher', null);
	update_post_meta($post_id,'remove_date', $timestamp);

	// Update ledger
	loopis_ledger_add_post('removed', $author_id , $post_id, ['timestamp' => $timestamp, 'type' => 'complaint']);

    // Refresh page
    refresh_page();
}
