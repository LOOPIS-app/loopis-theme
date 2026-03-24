<?php
/**
 * Filters and actions affecting post thumbnails.
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
 * Enable featured image for pages
 */
function page_thumbnail() {
    add_theme_support('post-thumbnails', array('post', 'page'));
}
add_action('after_setup_theme', 'page_thumbnail');


/**
 * Enable featured image for custom post types
 */

function cpt_thumbnail() {
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'cpt_thumbnail');


/**
 * Image missing thumbnail fix
 * 
 * @return string HTML output
 */
function no_thumbnail($html, $post_id, $post_thumbnail_id, $size, $attr) {
    $thumbnail = '/wp-content/themes/loopis-theme/assets/img/support.png';

    if (empty($html)) {
        $html = '<img src="' . esc_url($thumbnail) . '" alt="Thumbnail">';
    }
    return $html;
}
add_filter('post_thumbnail_html', 'no_thumbnail', 10, 5);


/**
 * Disable image rotation in posts
 * Added because WordPress (or EWWW?) seems to create a "-rotated" copy for no reason.
 * 
 * @return array Modified file array
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