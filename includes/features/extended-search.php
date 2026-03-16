<?php
/**
 * Extended search functionality on frontend search pages.
 * 
 * Features:
 * - Limit search to specified categories
 * - Extend search to include tag names
 * - Enable search in partial words (wildcard search)
 */

if (!defined('ABSPATH')) {
    exit;
}

define('LOOPIS_SEARCH_CATEGORY_SLUGS', array('new', 'old', 'booked', 'booked_custom'));

// Determine if a query is the frontend main search query.
function loopis_is_frontend_main_search_query($query) {
    return !is_admin() && $query->is_main_query() && $query->is_search();
}

// Resolve allowed search category IDs from configured slugs.
function loopis_get_search_category_ids() {
    if (!function_exists('loopis_cats')) {
        return array();
    }

    $category_ids = loopis_cats(LOOPIS_SEARCH_CATEGORY_SLUGS);
    $category_ids = array_map('intval', (array) $category_ids);

    return array_values(array_filter($category_ids));
}

/**
 * Limit search to specified categories
 */
function loopis_search_post_type($query) {
    if (!loopis_is_frontend_main_search_query($query)) {
        return;
    }

    $query->set('post_type', 'post');
    $query->set('post_status', 'publish');

    $category_ids = loopis_get_search_category_ids();
    if (!empty($category_ids)) {
        $query->set('category__in', $category_ids);
    }
}
add_action('pre_get_posts', 'loopis_search_post_type');

/**
 * Extend search to include tag names.
 */
function loopis_search_by_tags($search, $wp_query) {
    global $wpdb;

    if (!loopis_is_frontend_main_search_query($wp_query) || empty($wp_query->get('s'))) {
        return $search;
    }

    $search_term = $wp_query->get('s');
    $like = '%' . $wpdb->esc_like($search_term) . '%';
    
    if (empty($search)) {
        return $search;
    }

    // Remove leading "AND" and wrap search logic
    $search = preg_replace('/^\s*AND\s+/i', '', $search);

    $tag_exists_sql = $wpdb->prepare(
        "EXISTS (
            SELECT 1
            FROM {$wpdb->term_relationships} tr
            INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
            WHERE tr.object_id = {$wpdb->posts}.ID
            AND tt.taxonomy = %s
            AND t.name LIKE %s
        )",
        'post_tag',
        $like
    );

    $search = " AND (($search) OR $tag_exists_sql)";

    return $search;
}
add_filter('posts_search', 'loopis_search_by_tags', 500, 2);

/**
 * Remove quotes from search terms (allows wildcard matching).
 */
function loopis_search_query_vars($query_vars) {
    if (!is_admin() && isset($query_vars['s']) && !empty($query_vars['s'])) {
        $query_vars['s'] = str_replace('"', '', $query_vars['s']);
        $query_vars['sentence'] = 0;
    }

    return $query_vars;
}
add_filter('request', 'loopis_search_query_vars');