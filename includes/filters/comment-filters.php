<?php
/**
 * Filters and actions affecting comments.
 * 
 * Always included in functions.php
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

if (!defined('ABSPATH')) {
    exit;
}
 
/**
 * Convert :) to emojis in comments, post titles and post content.
 */
add_filter( 'comment_text', 'convert_smilies' );
add_filter( 'the_title',    'convert_smilies' );
add_filter( 'the_content',  'convert_smilies' );


/**
 * Allow similar comments in quick succession.
 */
add_filter('duplicate_comment_id', '__return_false');


/**
 * Allow multiple comments in quick succession.
 */
add_filter('comment_flood_filter', '__return_false');


/**
 * Prevent comments with blank lines from splitting into separate comments.
 * 
 * @return string HTML output
 */

function preserve_blank_lines_in_comments($comment_content, $comment) {
    // Replace consecutive line breaks with a placeholder string
    $comment_content = preg_replace('/\n(\s*\n)+/', '<!-- wp:preserve-blank-line -->', $comment_content);
    
    return $comment_content;
}
add_filter('comment_text', 'preserve_blank_lines_in_comments', 10, 2);