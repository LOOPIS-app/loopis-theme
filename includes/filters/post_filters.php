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
            $query->set( 'category__not_in', loopis_cats( ['fetched', 'removed', 'locker', 'disappeared', 'storage', 'paused', 'archived' ]) );
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

