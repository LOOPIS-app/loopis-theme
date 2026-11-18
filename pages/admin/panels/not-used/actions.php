<?php
/**
 * Output of comments in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Fetch latest three comments starting with <p> 
$comments = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT *
         FROM {$wpdb->comments}
         WHERE comment_approved = '1'
           AND LEFT(comment_content, 2) = '<p'
         ORDER BY comment_date_gmt DESC
         LIMIT 3"
    )
);
?>
<!--Output-->
<p class="small">↓ 3 senaste</p>
<?php if ( !empty($comments) ) : ?>
    <?php foreach ( $comments as $comment ) : ?>
        <p class="small shorten">🎁 <span class="bold"><?php echo esc_html(get_the_title($comment->comment_post_ID)); ?></span>&nbsp;<?php echo esc_html(wp_strip_all_tags($comment->comment_content)); ?></p>
    <?php endforeach; ?>

<?php else : ?>
    <p class="info">💢 Inga händelser senaste tre dagarna.</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>

<!-- Mention script -->
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/mentions-show-list.js"></script>