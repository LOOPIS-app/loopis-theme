
<?php
/**
 * Enable featured image support for custom post types
 */

if (!defined('ABSPATH')) {
    exit;
}

// Enable featured image for custom post type
function loopis_theme_setup() {
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'loopis_theme_setup');