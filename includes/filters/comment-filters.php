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
    // Replace consecutive line breaks with a placeholder string.
    $comment_content = preg_replace('/\n(\s*\n)+/', '<!-- wp:preserve-blank-line -->', $comment_content);
    
    return $comment_content;
}
add_filter('comment_text', 'preserve_blank_lines_in_comments', 10, 2);


/**
 * Convert paragraph tags to line breaks for content output.
 *
 * @param string      $content Content HTML.
 * @param WP_Comment|null $comment Unused, kept for signature consistency.
 * @return string HTML output
 */
function preserve_blank_lines_in_content($content, $comment = null) {
    // Remove the first opening <p> tag entirely (no <br> replacement).
    $content = preg_replace('/<p\b[^>]*>/i', '', $content, 1);

    // Replace any remaining opening/closing <p> tags (with optional attributes) with <br>.
    $content = preg_replace('/<\/?p\b[^>]*>/i', '<br>', $content);

    return $content;
}


/**
 * Wrap comment avatars with author links.
 *
 * Make avatars link to author page.
 */
function loopis_wrap_comment_avatars_with_author_link($avatar, $id_or_email, $size, $default, $alt, $args) {
    if (is_admin()) {
        return $avatar;
    }

    $comment = null;

    if ($id_or_email instanceof WP_Comment) {
        $comment = $id_or_email;
    } elseif (is_object($id_or_email) && !empty($id_or_email->comment_ID)) {
        $comment = get_comment((int) $id_or_email->comment_ID);
    }

    if (!$comment || empty($comment->user_id)) {
        return $avatar;
    }

    $author_url = get_author_posts_url((int) $comment->user_id);
    if (empty($author_url)) {
        return $avatar;
    }

    return '<a href="' . esc_url($author_url) . '" class="avatar-link">' . $avatar . '</a>';
}
add_filter('get_avatar', 'loopis_wrap_comment_avatars_with_author_link', 20, 6);




add_filter('comment_text', 'loopis_render_ping', 20, 3);

function loopis_render_ping($text, $comment = null, $args = array()) {
    if (!($comment instanceof WP_Comment)) {
        return $text;
    }

    return preg_replace_callback(
        '/(?<![\p{L}\p{M}\p{N}_.-])@([\p{L}\p{M}\p{N}_.-]+)/u',
        function ($matches) {
            if ($matches[1]==='admin') {
                return '<span class="ping">🔔 Admin </span>';
            }

            $user = get_user_by('login', $matches[1]);

            if (!$user) {
                return $matches[0];
            }

            return sprintf(
                '<span class="ping">🔔 %s</span>',
                esc_html($user->display_name)
            );
        },
        $text
    );
}

add_filter( 'get_comment_author', function ( $author, $comment_id, $comment ) {
    $user = get_userdata($comment->user_id);
    if ( $user ) {
        return $user->display_name;
    }
    return $author;
}, 10, 3 );



function loopis_comment_callback($comment, $args, $depth) {
    $user = $comment->user_id ? get_userdata($comment->user_id) : false;
    $login = $user ? $user->user_login : '';

    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class('', $comment); ?> id="li-comment-<?php comment_ID(); ?>">
        <div id="comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-author">
                <?php echo get_avatar($comment, $args['avatar_size']); ?>
                <cite class="fn" data-login="<?php echo esc_attr($login); ?>">
                    <?php echo get_comment_author_link($comment); ?>
                </cite>
            </div>
            <div class="comment-meta commentmetadata">
                <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>">
                    <time datetime="<?php echo esc_attr(get_comment_time('c', false, true, $comment)); ?>">
                        <?php
                        printf(
                            esc_html__('%1$s kl. %2$s ', 'your-text-domain'),
                            esc_html(get_comment_date('', $comment)),
                            esc_html(get_comment_time('', false, true, $comment))
                        );
                        ?>
                    </time>
                </a>

                <?php edit_comment_link(esc_html__('(Redigera)', 'your-text-domain'), ' ', ''); ?>
            </div>

            <?php comment_text(); ?>
            <?php comment_reply_link(array_merge($args, [
                'depth' => $depth,
                'max_depth' => $args['max_depth'],
            ])); ?>
        </div>
    </li>
    <?php
}