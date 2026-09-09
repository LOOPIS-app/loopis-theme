<?php
/**
 * Output user primary blog.
 *
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get primary blog
$primary_blog_id = get_user_meta($user_id, 'primary_blog', true);
$primary_blog = $primary_blog_id ? get_blog_details($primary_blog_id) : null;

// Output
if ($primary_blog) {
    echo esc_html($primary_blog->blogname);
} else {
    echo esc_html('Ingen primär blogg');
}