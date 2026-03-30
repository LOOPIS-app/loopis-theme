<?php
/**
 * Filters and actions affecting posts.
 * 
 * Migrated from earlier use in Code Snippets plugin.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Exclude posts with certain "hidden" categories in main query
 * Added to replace plugin "Ultimate Category Excluder"
 * Does not affect dashboard search
 * 
 * @return void
 */
function exclude_hidden( $query ) {
    // Check if it's not the admin area
    if ( !is_admin() ) {
        // Exclude categories on the home page, tag archives, and search results
        if ( ( $query->is_home() || $query->is_tag() || $query->is_search() ) && $query->is_main_query() ) {
            $query->set( 'category__not_in',  loopis_cats( 'fetched', 'removed', 'locker', 'disappeared', 'storage', 'paused', 'archived' ) );
        }
    }
}
add_action( 'pre_get_posts', 'exclude_hidden' );


/**
 * Omit the "tips" category when outputting post categories
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
 * Limit the number of tags displayed for a post in lists to 3.
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
 * Add and update necessary data to support posts
 * 
 * @return string filepath
 */
function support_data( $post_id, $post, $update ) {

    if ( $update ) { return; }
    if ( get_post_type( $post_id ) !== 'support' ) { return; }
    
    // Get current user ID
    $user_ID = wp_get_current_user()->ID;
        
    // Update ACF fields
    update_post_meta($post_id, 'status', loopis_support_cat("active") );
    wp_set_post_terms( $post_id, loopis_support_cat("active"), 'support-status', false );
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
    // get content
    $post_content = get_post_field('post_content', $post_id);
    
    // Update post
    $post_data = array(
            'ID'         => $post_id,
            'post_title' => $new_title,
            'post_name'  => $new_slug,
            'post_author' => $user_ID, );
    remove_action( 'wp_insert_post', 'support_data', 99, 3 );
    wp_update_post( $post_data );
    // get managers
    $managers = get_users( ['role'=>'manager'] );
    foreach ($managers as $user) {
        // send email to all managers!
        send_admin_notification_email('📣 Hjälp!<br>'.$post_content, $post_id, $user_ID, $user->ID);
    }
}
add_action( 'wp_insert_post', 'support_data', 99, 3 );
