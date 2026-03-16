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
	
	// Create a new post with the same content
	$new_post_args = array(
	    'post_title'   => $current_post->post_title,
	    'post_content' => $current_post->post_content,
	    'post_status'  => 'publish',
	    'post_type'    => $current_post->post_type,
	    'post_author'  => get_current_user_id(),
	);
	$new_post_id = wp_insert_post($new_post_args);
	
	if (!is_wp_error($new_post_id)) {
	
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
	update_field('forward_date', current_time('Y-m-d H:i:s'), $post_id);
	update_field('forward_post', $new_post_id, $post_id);
	
	// Set new post ACF meta
	update_field('previous_post', $post_id, $new_post_id);
	update_field('location', 'Skåpet', $new_post_id);
	
	// Clear edit lock meta fields
	delete_post_meta($new_post_id, '_edit_lock');
	delete_post_meta($new_post_id, '_edit_last');

	// Leave comment by author
	add_comment('<p class="forward">💝 Jag skickar vidare. <span>→ <a href="/' . get_post_field('post_name', $new_post_id) . '">Ny annons</a></span></p>', $post_id);
	
	// Check queue + notify
	$queue = maybe_unserialize(get_post_meta($post_id, 'queue', true)); // Ensure the value is unserialized
	if (!empty($queue) && is_array($queue)) { 
    // Generate the notification message with usernames
    $user_mentions = array_map(function ($user_ID) {
        $user = get_userdata($user_ID); // Fetch user data by ID
        return $user ? '<span>@' . $user->user_login . '</span>' : ''; // Use the username (user_login) wrapped in <span>
    }, $queue);
    // Remove any empty mentions (in case a user ID is invalid)
    $user_mentions = array_filter($user_mentions);
    // Convert the mentions into a single string
    $user_mentions_string = implode(' + ', $user_mentions);
    // Send notification from LOOPIS to users in queue
    $notification_message = '♻ Denna sak har skickats vidare!<br>⏳ Ni kan delta i lottning igen imorgon klockan 12.<br>💡 Tips till ' . $user_mentions_string;
    add_admin_comment($notification_message, $new_post_id, 1);
	}

	// Redirect to new post with "/edit" appended to the URL
	$redirect_script = '<script type="text/javascript">';
	$redirect_script .= 'window.location.href = "' . esc_url(get_permalink($new_post_id)) . '/edit";';
	$redirect_script .= '</script>';
	echo $redirect_script;
	exit;
		
	} else {
	// Error occurred while duplicating post
	echo 'Ett fel uppstod: ' . $new_post_id->get_error_message();
	}
	
}
