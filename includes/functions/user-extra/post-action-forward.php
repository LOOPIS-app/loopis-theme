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
 * FETCHER: FORWARD POST
 * Fetcher clicks forward on fetched post
 */
function action_forward(int $post_id) {
	
	// Retrieve the current post data
	$current_post = get_post($post_id);
	$author =  get_current_user_id();
	$location = get_post_meta($post_id,'location',true);
	
	// Create a new post with the same content
	$new_post_args = array(
	    'post_title'   => $current_post->post_title,
	    'post_content' => $current_post->post_content,
	    'post_status'  => 'publish',
	    'post_type'    => $current_post->post_type,
	    'post_author'  => $author,
	);
	$new_post_id = wp_insert_post($new_post_args);
	
	if (!is_wp_error($new_post_id)) {

	loopis_ledger_add_post('submitted', $author, $new_post_id ,['timestamp' => current_time('Y-m-d H:i:s'), 'location' => $location, 'type' => 'forwarded']);
	
	// Set the same featured image for new post
	$featured_image = get_post_thumbnail_id($post_id);
	if ($featured_image) { set_post_thumbnail($new_post_id, $featured_image); }
	
	// Define meta keys to copy
	$copied_meta_keys = array('created_via', 'wpum_post', 'image_2', 'location');

	// Copy specified metadata from the original post to the new post
	$meta_data = get_post_meta($post_id);
	foreach ($meta_data as $key => $values) {
    	if (in_array($key, $copied_meta_keys)) { // Check if the meta key is in the allowed list
        	foreach ($values as $value) {
            	update_post_meta($new_post_id, $key, maybe_unserialize($value));
        	}
	    }
	}
	
	// Set the same tags for new post
	$current_tags = wp_get_post_tags($post_id);
	if ($current_tags) {
	$tag_ids = array();
	foreach ($current_tags as $tag) { $tag_ids[] = $tag->term_id; }
	wp_set_post_tags($new_post_id, $tag_ids);
	}
		
	// Set old post ACF meta
	update_post_meta($post_id,'forward_date', current_time('Y-m-d H:i:s'));
	update_post_meta($post_id, 'forward_post', $new_post_id);
	
	// Set new post ACF meta
	update_post_meta($new_post_id,'previous_post', $post_id);
	update_post_meta($new_post_id, 'location', 'Skåpet');
	
	// Clear edit lock meta fields
	delete_post_meta($new_post_id, '_edit_lock');
	delete_post_meta($new_post_id, '_edit_last');

	// Leave comment by author
	add_comment(
		'<p class="forward">💝 Jag skickar vidare. <span><a href="' . esc_url( get_permalink($new_post_id) ) . '">Ny annons</a></span></p>',
		$post_id
	);
	
	/* NOTIFY USERS IN QUEUE */

	// Check queue
	$queue = maybe_unserialize(get_post_meta($post_id, 'queue', true)); // Ensure the value is unserialized
	$queue_count = 0;
	if (!empty($queue) && is_array($queue)) {

		// Send notification email from LOOPIS to every user in queue
		$notification_message = '♻ Denna sak har skickats vidare!<br>⏳ Du kan delta i lottning igen imorgon klockan 12.<br>💡 Tips skickat till dig eftersom du stod i kö senast.';
		foreach ($queue as $user_id) {
			if (get_userdata($user_id)) {
				send_admin_notification_email($notification_message, $new_post_id, 2, (int) $user_id);
				$queue_count++;
			}
		}
	}

	// Add one comment from LOOPIS on post if there were users in the queue
	if ($queue_count > 0) {
		$admin_comment = '<p class="forward">♻ Denna sak har skickats vidare!<br>⏳ Alla kan delta i lottning igen imorgon klockan 12.<br>💡 Tips skickat till <span>🔔 ' . $queue_count . ' användare</span> som stod i kö senast.</p>';
		add_admin_comment($admin_comment, $new_post_id, 2);
	}
	
	// Redirect user to the edit page of the new post
	$redirect_url = add_query_arg(array('option'=> 'single', 'edit_post_id' => $new_post_id), home_url('/submit/'));
	wp_safe_redirect($redirect_url);
	exit;
		
	} else {
	// Error occurred while duplicating post
	echo 'Ett fel uppstod: ' . $new_post_id->get_error_message();
	}
	
}