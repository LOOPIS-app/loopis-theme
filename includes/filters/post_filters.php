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



function loopis_image_post_processing(){
    $postid = isset($_POST['postid']) ? intval($_POST['postid']) : 0;
    $images = get_post_meta($postid, '_pending_images', true);
    $thumb = $images['thumb'];
    $imagenum = 2;
    
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    if(!empty($images['name'][0])){
        foreach ($images['name'] as $index => $name){
            if ($name==='old'){
                if ($index === 0){
                    $attachment_id = get_post_thumbnail_id($postid);
                } else{
                    $attachment_id =  get_post_meta($postid, 'image_'.($index+1), true);;
                }

                $path = get_attached_file($attachment_id);
                $editor = wp_get_image_editor($path);

                if (!is_wp_error($editor)) {

                    $editor->rotate($images['rotation'][$index]);

                    $saved = $editor->save($path);
                }
            }
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $new_name = 'img-' .$postid. '-' . $index .'.'. $ext;
            $file = [
                'name'     => $new_name,
                'type'     => mime_content_type($images['tmp_name'][$index]),
                'tmp_name' => $images['tmp_name'][$index],
                'error'    => 0,
                'size'     => filesize($images['tmp_name'][$index]),
            ];
            if ($images['rotation'][$index] != 0){
                $image = imagecreatefromstring(file_get_contents($images['tmp_name'][$index]));
                $rotated = imagerotate($image, -$images['rotation'][$index], 0);
                imagejpeg($rotated, $images['tmp_name'][$index], 100);
                imagedestroy($image);
                imagedestroy($rotated);
            }

            $image_id = media_handle_sideload($file, $postid);

            if (!is_wp_error($image_id)){
                if ($index===$thumb){
                    set_post_thumbnail($postid, $image_id);
                    update_post_meta($postid, 'image_' . (0), $image_id);
                    if (file_exists($images['tmp_name'][$index])) {
                        unlink($images['tmp_name'][$index]);
                    }
                }else{
                    update_post_meta($postid, 'image_' . ($imagenum), $image_id);
                    if (file_exists($images['tmp_name'][$index])) {
                        unlink($images['tmp_name'][$index]);
                    }
                    $imagenum++;
                }
            }
        }
    }

    delete_post_meta($postid, '_pending_images');
    $images_html = [];

    $thumbnail_id = get_post_thumbnail_id($postid);
    $image_2_id = get_post_meta($postid, 'image_2', true);
    $image_3_id = get_post_meta($postid, 'image_3', true);

    ob_start();
    echo wp_get_attachment_image($thumbnail_id, 'large');
    $images_html[] = ob_get_clean();
    if ($image_2_id){
        ob_start();
        echo wp_get_attachment_image($image_2_id, 'large');
        $images_html[] = ob_get_clean();
    }
    if ($image_3_id){ 
        ob_start();
        echo wp_get_attachment_image($image_3_id, 'large');
        $images_html[] = ob_get_clean();
    }

    wp_send_json_success(['images' => $images_html]);
}

function loopis_image_rotation(){
    $postid = isset($_POST['postid']) ? intval($_POST['postid']) : 0;
    $images = get_post_meta($postid, '_image_rotation', true);
    foreach($images as $id => $rotation){
        
        if ($index === 0){
            $attachment_id = get_post_thumbnail_id($postid);
        } else{
            $attachment_id =  get_post_meta($postid, 'image_'.($index+1), true);;
        }
        
        $aid = get_postmeta('image_'.$id);
        $path = get_attached_file($aid);
        $editor = wp_get_image_editor($path);

        if (!is_wp_error($editor)) {

            $editor->rotate($rotation);
            $saved = $editor->save($path);

        }
    }
    
    delete_post_meta($postid, '_image_rotation');
}


add_action('wp_ajax_loopis_image_post_processing', 'loopis_image_post_processing');
add_action('wp_ajax_nopriv_loopis_image_post_processing', 'loopis_image_post_processing');