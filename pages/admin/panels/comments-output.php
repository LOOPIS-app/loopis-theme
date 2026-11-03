<?php
/**
 * Output comments in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Set timespan
$timespan = gmdate('Y-m-d H:i:s', strtotime('-3 days'));

$comments = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT *
         FROM {$wpdb->comments}
         WHERE comment_approved = '1'
           AND comment_date_gmt >= %s
           AND LEFT(comment_content, 2) != '<p'
         ORDER BY comment_date_gmt DESC",
        $timespan
    )
);
$comments_count = count($comments);

// Output
?>
<div class="columns">
    <div class="column1">↓ <?php echo (int) $comments_count; ?> kommentarer</div>
    <div class="column2"><a href='<?php echo esc_url( add_query_arg('view', 'pages/comments', $admin_url) ); ?>'>→ Se fler</a></div>
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
        <p>💢 Inga kommentarer senaste tre dagarna.</p>
    <?php endif; ?>
</div><!--post-list-->

<!-- Mention script -->
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/mentions-show-list.js"></script>