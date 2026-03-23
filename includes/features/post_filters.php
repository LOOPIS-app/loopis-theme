<?php
/**
 * Functions and filters which handle posts in the frontend.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

 
/**
 * Filter: bnfw_trigger_insert_post
 * Description:  New post: Trigger notifications
 *               Added 2024-02-27 to support WPUM frontend forms.
 *               Remains 2026-02-27 because we still want a mail notification for new support posts.
 */
add_filter( 'bnfw_trigger_insert_post', '__return_true' );


/**
 * Function: mytheme_setup
 * 
 * Description:
 *          Enable featured image on pages
 *          Added 2024-08-30 when creating "LOOPIS på torget" page.
 */
function mytheme_setup() {
    add_theme_support('post-thumbnails', array('post', 'page'));
}
add_action('after_setup_theme', 'mytheme_setup');

/**
 * Function: disable_image_rotation
 * Description:   
 *              Disable image rotation in posts
 *              Added 2024-06-04 when adding "EWWW" as image optimizer (since WP seems to create a "-rotated" copy for no reason)
 *              Updated 2025-06-05 by CoPilot to avoid checking plugin upload
 * 
 * @return string filepath
 */
function disable_image_rotation($file) {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'tiff'])) {
        return $file; // Only process supported formats
    }
    $exif = @exif_read_data($file['tmp_name']);
    if (!empty($exif['Orientation'])) {
        unset($file['image_meta']['orientation']);
    }
    return $file;
}

add_filter('wp_handle_upload_prefilter', 'disable_image_rotation');

/**
 * Function: support_data
 * Description:   
 *              New Support post: Add data
 *              Added 2024-01-04 and adjusted 2024-02-11...
 * 
 * @return string filepath
 */
function support_data( $post_id, $post, $update ) {

    if ( $update ) { return; }
    if ( get_post_type( $post_id ) !== 'support' ) { return; }
    
    // Get current user ID
    $user_ID = wp_get_current_user()->ID;
        
    // Update ACF fields
    update_post_meta($post_id, 'status', loopis_cat("active") );
    $current_title = get_the_title();
    update_post_meta($post_id, 'title', $current_title);
    $current_url = get_permalink();
    update_post_meta($post_id, 'link', $current_url );
        
    // Create post slug
    $count = 0;
    $args = array(
        'post_type' 		=> 'support',
        'post_status' 		=> 'publish',
        'posts_per_page' 	=> -1,);
    $support = new WP_Query($args);
    if ($support->have_posts()) {
        $count = $support->post_count;
        wp_reset_postdata(); }
    $new_slug = $count;
    
    // Create post title
    $new_title = $current_title;
    
    // Update post
    $post_data = array(
            'ID'         => $post_id,
            'post_title' => $new_title,
            'post_name'  => $new_slug,
            'post_author' => $user_ID, );
    remove_action( 'wp_insert_post', 'support_data', 99, 3 );
    wp_update_post( $post_data );
    
}
add_action( 'wp_insert_post', 'support_data', 99, 3 );
    


/**
 * Function: limit_tags
 * Description:   
 *              Limit number of tags
 * 
 * @return int[] Array of tag IDs
 */
function limit_tags($terms) {
    if (count($terms) > 1) {
        $terms = array_slice($terms, 0, 3, true);
    }
    return $terms;
}
add_filter('term_links-post_tag', 'limit_tags');

/**
 * Function: exclude_tips
 * Description:   
 *            Omit the tips category for choosing mail posts
 * 
 * @return int[] Array of category IDs
 */
function exclude_tips($categories) {
  $excluded_category_id = loopis_cat('tips');
  foreach ($categories as $key => $category) {
    if ($category->term_id === $excluded_category_id) {
      unset($categories[$key]);
    }
  }
  
  return $categories;
}

add_filter('get_the_categories', 'exclude_tips');
add_filter('widget_categories_args', 'exclude_tips');

/**
 * Function: exclude_hidden
 * Description:   
 *            Include/exclude hidden categories in main query
 *            Added 2024-04-24 to replace plugin "Ultimate Category Excluder"and updated 2024-06-03 to not affect dashboard search
 * 
 * @return void
 */
function exclude_hidden( $query ) {
    // Check if it's not the admin area
    if ( !is_admin() ) {
        // Exclude categories on the home page, tag archives, and search results
        if ( ( $query->is_home() || $query->is_tag() || $query->is_search() ) && $query->is_main_query() ) {
            $query->set( 'category__not_in', loopis_cats([
                'fetched',
                'booked_locker', 
                'locker', 
                'disappeared', 
                'storage', 
                'paused', 
                'archived',
                 ]) );
        }
    }
}
add_action( 'pre_get_posts', 'exclude_hidden' );


