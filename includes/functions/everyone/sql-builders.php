<?php
/**
 * List of posts fetched by the current user.
 * 
 * Reached on https://loopis.app/activity/?view=posts-fetched
 * Linked from /profile/user_nicename/posts (WPUM profile page) 
 * 
 * Uses non-existent category slug from URL to filter posts and a function to output labels and content.
 * 
 * Improvements:
 * – Add search form if more than 20 posts (adaptation of templates/search/search-form.php?)
 * – Add pagination (will templates/post-list/pagination.php work with the URL structure?)
 * – Later: Use a template for post list output? (needs a fix for output of specific button and metadata)
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function loopis_get_posts_query($view_type, $fetcher_id, $author_id, $categories, $tags, $search){
    global $wpdb;
    // BASE BUILDING BLOCKS
    $sql_count = "SELECT DISTINCT p.ID";
    $sql_posts = "SELECT p.ID, p.post_title, p.post_date";
    $sql_from = " FROM {$wpdb->posts} p";
    $sql_where = " WHERE p.post_status ='publish' 
            AND p.post_type='post'
            ";
    $params_where = [];

    // JOINS AND ORDER
    if ($view_type === 'posts-fetched'){
        $sql_posts .= ", (
            SELECT pm.meta_value
            FROM {$wpdb->postmeta} pm
            WHERE p.ID = pm.post_id
            AND pm.meta_key = 'fetch_date'
            AND pm.meta_value != '' 
            AND pm.meta_value IS NOT NULL
            ORDER BY CAST(pm.meta_value AS DATETIME) DESC
            LIMIT 1
            ) AS fetch_date";

        $sql_order = " ORDER BY CAST(fetch_date AS DATETIME) DESC ";
    }elseif($view_type === 'posts-booked'){
        // if booked select rows where there exists a nonempty book_date
        $sql_posts .= ", (
            SELECT pm.meta_value
            FROM {$wpdb->postmeta} pm
            WHERE p.ID = pm.post_id
            AND pm.meta_key = 'book_date'
            AND pm.meta_value != '' 
            AND pm.meta_value IS NOT NULL
            ORDER BY CAST(pm.meta_value AS DATETIME) DESC
            LIMIT 1
            ) AS book_date";

        $sql_order = " ORDER BY CAST(book_date AS DATETIME) DESC ";
    }else{
        $sql_order = " ORDER BY p.post_date DESC";
    }

    // FILTERS
    // if fetcher id then look for a meta 'fetcher' where the fetcher id is set
    if ($fetcher_id){

        $sql_where .= " AND EXISTS ( SELECT 1 FROM {$wpdb->postmeta} pm
                WHERE pm.post_id = p.ID 
                AND pm.meta_key = 'fetcher' 
                AND pm.meta_value = %d
            )";
            
            $params_where[] = $fetcher_id;
    }
    
    //if there is an author id set add a where condition which selects said author
    if ($author_id){
        $sql_where .= " AND p.post_author= %d";
        $params_where[] = $author_id;
    }

    if (!empty($tags)){
        $tags = array_map('intval', $tags);
        $placeholders = implode(',', array_fill(0, count($tags), '%d'));

        $sql_where .= " AND EXISTS (
            SELECT 1 FROM {$wpdb->term_relationships} tr_tag
            INNER JOIN {$wpdb->term_taxonomy} tt_tag
            ON tt_tag.term_taxonomy_id = tr_tag.term_taxonomy_id 
            WHERE tr_tag.object_id = p.ID
            AND tt_tag.taxonomy = 'post_tag'
            AND tt_tag.term_id IN ({$placeholders}))";
        
        $params_where = array_merge($params_where,$tags);
    }
    $categories = array_map('intval', !empty($categories) ? (array) $categories :[]);

    if (!empty($categories)){
        $categories = array_map('intval', $categories);
        $placeholders = implode(',', array_fill(0, count($categories), '%d'));

        $sql_where .= " AND EXISTS (
            SELECT 1 FROM {$wpdb->term_relationships} tr
            INNER JOIN {$wpdb->term_taxonomy} tt
            ON tt.term_taxonomy_id = tr.term_taxonomy_id 
            WHERE tr.object_id = p.ID
            AND tt.taxonomy = 'category'
            AND tt.term_id IN ({$placeholders}))";
        
        $params_where = array_merge($params_where,$categories);
    }

    // if search then look for post information similar to searched string
    if ($search){
        $like = '%' . $wpdb->esc_like( $search ) . '%';
        $sql_where .= " AND ( p.post_title LIKE %s
             OR p.post_content LIKE %s
             OR p.post_excerpt LIKE %s
             OR EXISTS ( SELECT 1 
                FROM {$wpdb->term_relationships} tr
                INNER JOIN {$wpdb->term_taxonomy} tt 
                    ON tr.term_taxonomy_id = tt.term_taxonomy_id
                INNER JOIN {$wpdb->terms} t
                    ON tt.term_id = t.term_id
                WHERE tr.object_id = p.ID
                AND tt.taxonomy='post_tag' 
                AND (
                    t.name LIKE %s
                    OR t.slug LIKE %s 
                )
                )
            )";
        array_push($params_where,$like, $like,$like, $like, $like);
    }

    if (!empty($params_where)){
        $sql_where = $wpdb->prepare($sql_where, ...$params_where);
    }

    $pids = $wpdb->get_col($sql_count . $sql_from . $sql_where);
    set_query_var('search_postids', $pids);
    $total = count($pids);
    $posts_per_page = (int) (get_option('posts_per_page') ?? 50);
    $max_pages = ceil($total/$posts_per_page);
    $pagenum = loopis_GET_pagenum($max_pages);
    $offset = ($pagenum - 1)*$posts_per_page;
    $sql_order .= " LIMIT {$posts_per_page} OFFSET {$offset}";

    $posts = $wpdb->get_results( $sql_posts . $sql_from . $sql_where . $sql_order);

    return [$posts, $total];
}
