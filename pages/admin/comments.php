<?php
/**
 * Comments overview page
 * Shows all comments made in the last 24 hours
 * Displays comment content, author, and associated post
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🗨 Kommentarer</h1>
<hr>
<p class="small">💡 Här ser du alla kommentarer som gjorts senaste 24 timmarna.</p>

<?php
// Set timespan
$timespan = date('Y-m-d H:i:s', strtotime('-24 hours'));

// Query comments from last 24 hours
$comments_query = new WP_Comment_Query(array(
    'date_query' => array(
        array(
            'after'     => $timespan,
            'inclusive' => true,
        ),
    ),
));

$comments = $comments_query->get_comments();
$count = count($comments);
?>

<h3>🗨 Senaste dygnet</h3>
<div class="columns">
    <div class="column1">↓ <?php echo $count; ?> kommentarer</div>
    <div class="column2 small">💡 Senaste överst</div>
</div>
<hr>

<div class="post-list">
    <?php if (!empty($comments)) : ?>
        <?php foreach ($comments as $comment) : ?>
            <div class="post-list-post">
                <a href="<?php echo esc_url(get_post_permalink($comment->comment_post_ID)); ?>">
                    <div class="post-list-post-thumbnail">
                        <?php echo get_the_post_thumbnail($comment->comment_post_ID, 'thumbnail'); ?>
                    </div>
                    <div class="post-list-post-comment">
                        <p>
                            <?php
                            $comment_content = $comment->comment_content;
                            // Remove links in comments
                            $comment_content = preg_replace('/<a\b[^>]+>/', '<span>', $comment_content);
                            $comment_content = preg_replace('/<\/a>/', '</span>', $comment_content);
                            echo wp_kses_post($comment_content);
                            ?>
                        </p>
                    </div>
                    <div class="post-list-post-meta">
                        <?php echo get_comment_author_link($comment); ?> → <?php echo esc_html(get_the_title($comment->comment_post_ID)); ?>
                        <span class="right">
                            <i class="far fa-comment"></i><?php echo human_time_diff(strtotime($comment->comment_date), current_time('timestamp')); ?> sen
                        </span>
                    </div>
                </a>
            </div><!--post-list-post-->
        <?php endforeach; ?>
    <?php else : ?>
        <p>💢 Inga kommentarer senaste 24 timmarna.</p>
    <?php endif; ?>
</div><!--post-list-->

<!-- Mention script -->
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/mentions-show-list.js.js"></script>

<?php wp_reset_postdata(); ?>