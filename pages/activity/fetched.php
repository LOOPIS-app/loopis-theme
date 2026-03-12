<?php
/**
 * List of fetched posts for the current user.
 * 
 * Needs improvements:
 * – Add search box
 * – Add pagination
 * – For a forwarded post: add button (with correct category symbol) to view the forwarded post
 * – Maybe add metadata output for forward_date
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Output functions
include_once __DIR__ . '/functions/profile-list-components.php';

// Include post action functions
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-forward.php';

// Get current user ID
$user_ID = wp_get_current_user()->ID;

// Set the category (non-existing slug for forwarded posts)
$category_slug = 'forward';

// Get all things fetched (using SQL for better performance)
global $wpdb;

$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT p.ID, p.post_title, p.post_date, pm.meta_value AS fetch_date
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
         AND tt.term_id = %d
         AND p.post_status = 'publish'
         ORDER BY pm.meta_value DESC",
        'fetch_date', $user_ID, 41 // Replace 41 with your category ID
    )
);

// Count the number of posts retrieved
$count = count($results);
?>

<h1><?php list_header_output($category_slug) ?></h1>
<hr>
<p><?php list_instruction_output($category_slug, $count) ?></p>

<div class="columns"><div class="column1">↓ <?php echo $count; ?> annons<?php if ($count !== 1) { echo "er"; } ?></div>
<div class="column2 small">💡 Senaste överst</div></div>
<hr>

<div class="post-list">
<?php if (!empty($results)) : ?>
    <?php foreach ($results as $post) : ?>
        <?php
        // Get post data
        $post_id = $post->ID;
        $post_title = $post->post_title;
        $fetch_date = $post->fetch_date; // Already fetched in the query
        $permalink = get_permalink($post_id);
        $thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');

        // Check if post already has been forwarded
        $forward_post_id = get_post_meta($post_id, 'forward_post', true);
        if ($forward_post_id) {
            $forward_post_category = get_the_category($forward_post_id);
            // Later: Fetch category symbol
        }
        ?>
        <div class="post-list-post" style="position:relative;">
            <div class="post-list-post-thumbnail" onclick="location.href='<?php echo esc_url($permalink); ?>';">
                <?php echo $thumbnail; ?>
            </div>
            <div class="post-list-post-title"><?php echo esc_html($post_title); ?></div>
            <?php if ($forward_post_id) { 
                // Later: Add button to view the forwarded post
                } else { list_button_output($category_slug, $post_id); } ?>
            <div class="notif-meta post-list-post-meta">
                <span>
                    <?php list_category_output($category_slug); ?> för 
                    <?php echo human_time_diff(strtotime($fetch_date), current_time('timestamp')); ?> sen
                </span>
            </div>
        </div><!--post-list-post-->
    <?php endforeach; ?>
<?php else : ?>
    <p>💢 Du har inte hämtat några saker ännu.</p>
<?php endif; ?>
</div><!--post-list-->

<?php get_footer(); ?>