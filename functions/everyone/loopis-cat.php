<?php
/**
 * Get category IDs from slugs dynamically.
 * 
 * @package LOOPIS_Theme
 * @since 0.6
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get category ID by slug
 * 
 * @param string $slug Category slug (e.g., 'new', 'booked', 'fetched')
 * @return int|false Category ID or false if not found
 */
function loopis_cat($slug) {
    // Get category by slug
    $category = get_category_by_slug($slug);
    
    // Return ID if found, otherwise return false
    return $category ? $category->term_id : false;
}

/**
 * Get multiple category IDs by slugs
 * 
 * @param array $slugs Array of category slugs (e.g., ['new', 'booked', 'fetched'])
 * @return array Array of category IDs (excludes non-existent categories)
 */
function loopis_cats($slugs) {
    $ids = array();
    
    foreach ($slugs as $slug) {
        $id = loopis_cat($slug);
        if ($id) {
            $ids[] = $id;
        }
    }
    
    return $ids;
}