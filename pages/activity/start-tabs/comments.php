<?php
/**
 * Comments tab.
 * 
 * Showing 50 latest comments by the current user.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get current user ID
$user_ID = get_current_user_id();

// Arguments
$args = array(
    'user_id' => $user_ID,
    'status' => 'approve',
    'number' => 50, );

// Query
$comments = get_comments($args); ?>

<!--Output-->

<div class="columns"><div class="column1">↓ 50 senaste</div>
<div class="column2"></div></div>
<hr>

<div class="post-list">
    <?php if (!empty($comments)) :
    foreach ($comments as $comment) : ?>
    <div class="post-list-post">
        <a href="<?php echo get_post_permalink($comment->comment_post_ID); ?>">
            <div class="post-list-post-thumbnail">
                <?php echo get_the_post_thumbnail($comment->comment_post_ID, 'thumbnail'); ?>
            </div>
            <div class="post-list-post-comment">
                <?php
                    $comment_content = $comment->comment_content;
                    // Remove links in comments
                    $comment_content = preg_replace('/<a\b[^>]+>/', '<span>', $comment_content);
                    $comment_content = preg_replace('/<\/a>/', '</span>', $comment_content);
                    echo $comment_content;
                ?>
            </div>
            <div class="post-list-post-meta">
                <span>→ <?php echo get_the_title($comment->comment_post_ID); ?></span>
				<span class="right"><i class="far fa-comment"></i><?php echo human_time_diff(strtotime($comment->comment_date), current_time('timestamp')); ?> sen</span>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
    <?php else : ?>
        <p>💢 Du har inte skapat några kommentarer ännu.</p>
    <?php endif; ?>
</div>

<?php wp_reset_postdata(); ?>

<!-- Mention script -->
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/mentions-show-list.js.js"></script>