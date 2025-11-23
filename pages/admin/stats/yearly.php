<?php
/**
 * Statistics for a selected year.
 * 
 * Will be improved to use custom database table.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📊 År</h1>
<hr>
<p class="small">💡 Statistik för wrapped.</p>

<?php
// Set current year
$current_year = date('Y');

// Render dropdown and get the selected year
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/stats/stats_select_year.php';
$selected_year = stats_select_year();

// Get top posts by participants
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/stats/get_top_posts_by_participants.php';
$top_posts = get_top_posts_by_participants($selected_year);

// Output top posts by participants
?>
<div class="columns"><div class="column1"><h7>🎁 Topp-10 annonser <?php if ($selected_year == 'all' ) { echo "alla år"; } else { echo $selected_year; } ?></h7></div>
<div class="column2 bottom">Deltagare</div></div>
<hr>
<?php if (!empty($top_posts)) : ?>
    <div class="post-list">
        <?php 
        foreach ($top_posts as $post) : 
            $post_link = get_permalink($post['post_id']); // Get the post link
            $post_thumbnail = get_the_post_thumbnail($post['post_id'], 'thumbnail'); // Get the post thumbnail
            $post_tags = get_the_tag_list('', ', ', '', $post['post_id']); // Get the post tags
        ?>
            <div class="post-list-post" onclick="location.href='<?php echo esc_url($post_link); ?>';">
                <div class="post-list-post-thumbnail">
                    <?php echo $post_thumbnail; ?>
                </div>
                <div class="post-list-post-title">
                        <?php echo esc_html($post['post_title']); ?>
                    <span class="right">🧡 <?php echo esc_html($post['participant_count']); ?></span>
                </div>
                <div class="post-list-post-meta">
                    <p><i class="fas fa-hashtag"></i> <?php echo $post_tags; ?></p>
                </div>
            </div>
        <?php 
        endforeach; 
        ?>
    </div>
<?php else : ?>
    <p>No posts found for the selected year.</p>
<?php endif;

// Fetch the top 10 tags used in published posts
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/stats/get_top_tags_published.php';
$tag_post_counts_published = get_top_tags_published($selected_year);

// Fetch the top 10 tags used in fetched posts
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/stats/get_top_tags_fetched.php';
$tag_post_counts_fetched = get_top_tags_fetched($selected_year);
?>

<h7>#⃣ Topp-10 kategorier <?php if ($selected_year == 'all' ) { echo "alla år"; } else { echo $selected_year; } ?></h7>
<div class="columns">
    <div class="column1">↓ Publicerade</div>
    <div class="column2" style="justify-content: flex-start;">↓ Paxade</div>
</div>
<hr>
<div class="columns_cat">
    <!-- Column 1: Published Posts -->
    <div>
        <?php
        // Output the top 10 tags used in published posts
        if (!empty($tag_post_counts_published)) {
            foreach ($tag_post_counts_published as $tag_id => $post_count) {
                $tag = get_tag($tag_id); // Get the tag object
                ?>
                <p>
                    <span class="small">💚 <?php echo esc_html($post_count); ?></span>
					<span class="big-label">
                        <i class="fas fa-hashtag"></i><?php echo esc_html($tag->name); ?> 
                    </span> 
                </p>
                <?php 
            }
        } else {
            echo '<p>No tags found for the selected year.</p>';
        }
        ?>
    </div>

    <!-- Column 2: Fetched Posts -->
    <div>
        <?php
        // Output the top 10 tags used in fetched posts
        if (!empty($tag_post_counts_fetched)) {
            foreach ($tag_post_counts_fetched as $tag_id => $post_count) {
                $tag = get_tag($tag_id); // Get the tag object
                ?>
                <p>
					  <span class="small">❤ <?php echo esc_html($post_count); ?></span>
					<span class="big-label">
                        <i class="fas fa-hashtag"></i><?php echo esc_html($tag->name); ?> 
                    </span>
                </p>
                <?php 
            }
        } else {
            echo '<p>No tags found for the selected year.</p>';
        }
        ?>
    </div>
</div>