<?php
/**
 * Functions and filters which handle comments in different ways.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */


/**
 * Filters: convert_smilies
 * Description:  
 *              Allow smilies for comments
 *              Allows smiley conversion in obscure places.
 *              This is a sample snippet. Feel free to use it, edit it, or remove it.
 */
add_filter( 'widget_text', 'convert_smilies' );
add_filter( 'the_title', 'convert_smilies' );
add_filter( 'wp_title', 'convert_smilies' );
add_filter( 'get_bloginfo', 'convert_smilies' );


/**
 * Filter: duplicate_comment_id
 * Description: Allow duplicate comments
 */
add_filter('duplicate_comment_id', '__return_false');


/**
 * Filter: comment_flood_filter
 * Description: 
 *           Allow flood of comments
 *           Added 2023-12-18 because sometimes you want to push a lot of buttons :)
 */
add_filter('comment_flood_filter', '__return_false');


/**
 * Function: Preserve_blank_lines_in_comments
 * Description:
 *            Avoids split comments
 * 
 * @return string HTML output
 */

function preserve_blank_lines_in_comments($comment_content, $comment) {
    // Replace consecutive line breaks with a placeholder string
    $comment_content = preg_replace('/\n(\s*\n)+/', '<!-- wp:preserve-blank-line -->', $comment_content);
    
    return $comment_content;
}
add_filter('comment_text', 'preserve_blank_lines_in_comments', 10, 2);