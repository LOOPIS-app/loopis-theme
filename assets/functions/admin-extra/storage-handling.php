<?php
/**
 * Post handling functions for LOOPIS admin and storage-booker.
 *
 * Included in comments.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** ADMIN: BOOK FROM STORAGE */
// Admin clicks book on post in storage
function admin_action_book_storage(int $user_id, int $post_id) {

	// Set comment content
	$event_name = function_exists('loopis_get_setting') ? loopis_get_setting('event_name', '📍 Inget event angivet') : '📍 Inget event angivet';
	$fetcher_name = get_userdata($user_id)->display_name;
	
	// Set post meta
	wp_set_object_terms($post_id, null, 'category');
	wp_set_object_terms($post_id, 'fetched', 'category'); 
	update_field('fetcher', $user_id);
	update_field('book_date', current_time('Y-m-d H:i:s'));
	update_field('fetch_date', current_time('Y-m-d H:i:s'));
	update_field('location', "LOOPIS-bord");
	
	// Update post
	$post_data = array(
		'ID' => $post_id,
		'post_status' => 'publish',
	);
	wp_update_post($post_data);
		
	// Leave comment by user
	add_comment ('<p class="fetched">☑ Hämtad av <span>👤 ' . $fetcher_name . '</span> på <span>' . $event_name . '</span></p>', $post_id );
		
	// Refresh page
	refresh_page();
}


/** ADMIN: PUBLISH FROM STORAGE */
// Admin clicks publish on post in storage
function admin_action_publish_storage(int $post_id) {
	// Set post meta
	wp_set_object_terms($post_id, null, 'category');
	wp_set_object_terms($post_id, 'new', 'category');
	
	// Update post
	$current_time = current_time('mysql');
	$post_data = array(
		'ID' => $post_id,
		'post_status' => 'publish',
		'post_date' => $current_time,
		'post_date_gmt' => get_gmt_from_date($current_time),
	);
	wp_update_post($post_data);
		
	// Refresh page
	refresh_page();
}