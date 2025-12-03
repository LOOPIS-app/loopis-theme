<?php
/**
 * Template for displaying my profile comments tab content.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current user iD
$user_ID = wp_get_current_user()->ID;

// Get comments + count
$args = array(
    'user_id' => $user_ID,
    'status' => 'approve', );
$comments = get_comments($args);
$count = count(get_comments($args));
?>

<h7>🗨 Mina kommentarer</h7>
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' kommentar'; } else { echo ' kommentarer'; } ?>
</div>
<div class="column2">Senaste överst
</div></div>
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

<?php get_template_part('assets/output/general/pagination'); ?>

<!-- Ersätt @ i kommentarer -->
<script>
	document.addEventListener('DOMContentLoaded', function() {
	var commentElements = document.querySelectorAll('.post-list-post-comment');
	commentElements.forEach(function(commentElement) {
	var text = commentElement.innerHTML;
	var modifiedText = text.replace(/@/g, '🔔');
	commentElement.innerHTML = modifiedText;
	});
});
</script>

<?php wp_reset_postdata(); ?>
		
</div><!--post-list-->
