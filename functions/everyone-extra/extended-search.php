<?php
/**
 * Extended search functionality
 * - Search in tags
 * - Search in partial words (wildcard search)
 * - Limit to post type 'post' and categories 1,37,57,147
 * 
 * 100% created by CoPilot – to replace plugins.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Limit search to 'post' type only and published status
 */
function loopis_search_post_type($query) {
    if (!is_admin() && $query->is_search() && $query->is_main_query()) {
        $query->set('post_type', 'post');
        $query->set('post_status', 'publish');
    }
}
add_action('pre_get_posts', 'loopis_search_post_type');

/**
 * Extend search to include tags
 */
function loopis_search_by_tags($search, $wp_query) {
    global $wpdb;

    if (is_admin() || !$wp_query->is_search() || empty($wp_query->get('s'))) {
        return $search;
    }

    $search_term = $wp_query->get('s');
    $like = '%' . $wpdb->esc_like($search_term) . '%';
    
    // Find posts with matching tags in allowed categories
    $tag_query = $wpdb->prepare(
        "SELECT DISTINCT tr.object_id 
        FROM {$wpdb->term_relationships} tr
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        INNER JOIN {$wpdb->posts} p ON tr.object_id = p.ID
        WHERE tt.taxonomy = 'post_tag'
        AND t.name LIKE %s
        AND p.post_type = 'post'
        AND p.post_status = 'publish'
        AND tr.object_id IN (
            SELECT object_id 
            FROM {$wpdb->term_relationships} tr2
            INNER JOIN {$wpdb->term_taxonomy} tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id
            WHERE tt2.taxonomy = 'category'
            AND tt2.term_id IN (1,37,57,147)
        )",
        $like
    );
    
    $post_ids = $wpdb->get_col($tag_query);
    
    if (empty($search)) {
        return $search;
    }
    
    // Remove leading "AND" and wrap search logic
    $search = preg_replace('/^\s*AND\s+/i', '', $search);
    
    if (!empty($post_ids)) {
        $post_ids_string = implode(',', array_map('intval', $post_ids));
        $search = " AND (($search) OR {$wpdb->posts}.ID IN ($post_ids_string))";
    } else {
        $search = " AND ($search)";
    }

    return $search;
}
add_filter('posts_search', 'loopis_search_by_tags', 500, 2);

/**
 * Remove quotes from search terms (allows wildcard matching)
 */
function loopis_search_query_vars($query_vars) {
    if (isset($query_vars['s']) && !empty($query_vars['s'])) {
        $query_vars['s'] = str_replace('"', '', $query_vars['s']);
    }
    return $query_vars;
}
add_filter('request', 'loopis_search_query_vars');