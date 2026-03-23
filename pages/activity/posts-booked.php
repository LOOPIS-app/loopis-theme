<?php
/**
 * List of fetched posts for the current user.
 * Reached on https://loopis.app/activity/?view=posts-booked
 * 
 * Needs improvements:
 * – Add pagination
 * – Use template for post output? (needs a fix for button output first though)
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include post list output functions
include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-list-output.php';

// Include post action functions
include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-forward.php';

// Get current user ID
$user_ID = wp_get_current_user()->ID;

// Set the category (non-existing slug for forwarded posts)
$url_slug = 'others_booked';
$category_ids = loopis_cats(['booked_custom', 'booked', 'locker']); // Remove 'booked_locker' after migration

// Get all things fetched (using SQL for better performance)
global $wpdb;

$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT p.ID, p.post_title, p.post_date, pm.meta_value AS book_date
         FROM {$wpdb->posts} p
         INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
         INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
         INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
         WHERE pm.meta_key = %s AND pm.meta_value != '' AND pm.meta_value IS NOT NULL
         AND EXISTS (
             SELECT 1
             FROM {$wpdb->postmeta} pm2
             WHERE pm2.post_id = p.ID AND pm2.meta_key = 'fetcher' AND pm2.meta_value = %d
         )
         AND tt.term_id IN (" . implode(',', $category_ids) . ")
         AND p.post_status = 'publish'
         ORDER BY pm.meta_value DESC",
        'book_date', $user_ID
    )
);

// Count the number of posts retrieved
$count = count($results);
?>

<!-- Output title and instructions -->
<h1><?php list_header_output($url_slug) ?></h1>
<hr>
<p><?php list_instruction_output($url_slug, $count) ?></p>

<div class="columns"><div class="column1">↓ <?php echo $count; ?> annons<?php if ($count !== 1) { echo "er"; } ?></div>
<div class="column2 small">💡 Senaste överst</div></div>
<hr>

<!-- Output post list -->
<div class="post-list">
<?php if (!empty($results)) : ?>
    <?php foreach ($results as $post) : ?>
        <?php
        // Get post data
        $post_id = $post->ID;
        $post_title = $post->post_title;
        $book_date = $post->book_date; // Not used yet, but could be used for metadata output
        $permalink = get_permalink($post_id);
        $thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');

        ?>
        <div class="post-list-post" style="position:relative;">
            <div class="post-list-post-thumbnail" onclick="location.href='<?php echo esc_url($permalink); ?>';">
                <?php echo $thumbnail; ?>
            </div>
            <div class="post-list-post-title"><?php echo esc_html($post_title); ?></div>
            <?php if ($forward_post_id) { 
                // Later: Add button to view the forwarded post
                } else { list_button_output($url_slug, $post_id); } ?>
            <div class="notif-meta post-list-post-meta">
                <span><?php the_category(' '); ?></span>
            </div>
        </div><!--post-list-post-->
    <?php endforeach; ?>
<?php else : ?>
    <p>💢 Du har inte paxat några saker ännu.</p>
<?php endif; ?>
</div><!--post-list-->

<?php get_footer(); ?>