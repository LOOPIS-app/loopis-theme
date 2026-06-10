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
* FETCHER: FETCHED
* Fetcher clicks button when item is fetched (from locker or custom location)
*/	
function action_fetched(int $post_id) {
	
	// Set post meta
	$timestamp = current_time('Y-m-d H:i:s');
	wp_set_object_terms($post_id, null, 'category');
	wp_set_object_terms($post_id, 'fetched', 'category'); 
	update_post_meta($post_id, 'fetch_date', $timestamp);

	$fetcher = get_post_meta($post_id, 'fetcher', true);
	$author_id = get_post_field( 'post_author', $post_id );
	loopis_ledger_add_post('fetched', $fetcher, $post_id,['timestamp'=> $timestamp]);
	loopis_ledger_add_post('given', $author_id, $post_id,['timestamp'=> $timestamp]);
	
	// Leave comment by fetcher
	add_comment ('<p class="fetched">☑ Nu har jag hämtat! <span>🔔LOOPIS</span></p>', $post_id );
		
	// Refresh page
    refresh_page();
}

/** 
* AUTHOR: FETCHED CUSTOM
* Author clicks button when item is fetched (from custom location)
*/	
function action_fetched_custom(int $post_id) {
	// Set post meta
	$timestamp = current_time('Y-m-d H:i:s');
	wp_set_object_terms($post_id, null, 'category');
	wp_set_object_terms($post_id, 'fetched', 'category'); 
	update_post_meta($post_id,'fetch_date', $timestamp);
		
	// Get fetcher name
	$fetcher = get_post_meta($post_id, 'fetcher', true); if ($fetcher) { $fetchername = get_userdata($fetcher)->display_name; } 
	$author_id = get_post_field( 'post_author', $post_id );
	loopis_ledger_add_post('fetched', $fetcher, $post_id,['timestamp'=> $timestamp]);
	loopis_ledger_add_post('given', $author_id, $post_id,['timestamp'=> $timestamp]);
		
	// Leave comment by author
	add_comment ('<p class="fetched">☑ Nu har ' . $fetchername . ' hämtat! <span>🔔LOOPIS</span></p>', $post_id );
		
	// Refresh page
    refresh_page();
}

/** ADMIN: FETCHED */
// Admin clicks fetched on post
function admin_action_fetched(int $post_id) {

	// Set post meta
	$timestamp = current_time('Y-m-d H:i:s');
	wp_set_object_terms($post_id, null, 'category');
	wp_set_object_terms($post_id, 'fetched', 'category'); 
	update_post_meta($post_id,'fetch_date', $timestamp);

	$fetcher = get_post_meta($post_id, 'fetcher', true);
	$author_id = get_post_field( 'post_author', $post_id );
	loopis_ledger_add_post('fetched', $fetcher, $post_id,['timestamp'=> $timestamp]);
	loopis_ledger_add_post('given', $author_id, $post_id,['timestamp'=> $timestamp]);
		
	// Leave comment by admin
	add_admin_comment ('<p class="fetched">☑ Markerar som hämtad, eftersom mottagaren inte gjort det.</p>', $post_id, 1 );
		
	// Refresh page
	refresh_page();
}
