<?php
/**
 * Functions and filters which handle frontend links in different ways.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

/**
 * Function: wrap_links
 * Description: Wrap, shorten & make links clickable
 * 
 * @return string HTML output
 */
function wrap_links($content) {
    // Check if we're on the single post or page view
    if (is_singular('post')) {
        $content = make_clickable($content);
        $content = preg_replace_callback(
            '/<a href="(https?:\/\/[^"]+)"[^>]*>([^<]+)<\/a>/',
            function($matches) {
                $link_url = $matches[1];
                $link_text = $matches[2];

                // Check if the link is an edit link
                if (strpos($link_url, 'edit') !== false) {
                    return $matches[0];
                }

                $link_text_limited = substr($link_text, 0, 40);
                if ($link_text_limited != $link_text) {
                    $link_text_limited .= '...';
                }
                return '<span><i class="fas fa-share" style="color: #999;"></i> <a href="' . esc_url($link_url) . '" target="_blank" rel="nofollow ugc">' . esc_html($link_text_limited) . '</a></span>';
            },
            $content
        );
        return $content;
    }
    
    // If not on a single post or page, return the original content
    return $content;
}

// Add the filter to the post content and comments, but only on single posts and pages
add_filter('the_content', 'wrap_links', 10);
add_filter('comment_text', 'wrap_links', 10);
add_filter( 'the_category', 'no_links' );

/**
 * Function: no_links
 * Desctiption: Disable links for categories
 * 
 * @return string 
 */
function no_links($thelist) {
    return preg_replace('#<a.*?>([^<]*)</a>#i', '$1', $thelist);
}

/**
 * Function: customize_comment_author_link
 * 
 * Description: Builds link to comment author
 * 
 * @return string HTML output link
 */
function customize_comment_author_link($author_link, $author, $comment_id) {
    $user_id = get_comment($comment_id)->user_id;
    $author_url = get_author_posts_url($user_id);
    if (!empty($author_url)) {
        $author_link = '<a href="' . esc_url($author_url) . '">' . $author . '</a>';
    }
    return $author_link;
}

add_filter('get_comment_author_link', 'customize_comment_author_link', 10, 3);


/**
 * Function: logout_without_confirm
 * 
 * Description: Skips logout confirmation
 * 
 * @return void
 */
add_action('check_admin_referer', 'logout_without_confirm', 10, 2);
function logout_without_confirm($action, $result)
{
    /**
     * Allow logout without confirmation
     */
    if ($action == "log-out" && !isset($_GET['_wpnonce'])) {
        $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : 'https://loopis.app';
        $location = str_replace('&amp;', '&', wp_logout_url($redirect_to));
        header("Location: $location");
        die;
    }
}

/**
 * Function: no_thumbnail
 * 
 * Description: Image missing thumbnail fix
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
 * Function: add_opengraph_doctype
 * Description: Facebook open-graph protocol. Varianter finns i header.php och header-single.php
 * @return string HTML output
 */
function add_opengraph_doctype( $output ) {
    return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
}
add_filter('language_attributes', 'add_opengraph_doctype');
