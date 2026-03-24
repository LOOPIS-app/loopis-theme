<?php
/**
 * Output of popular tags 
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="columns">
    <div class="column1"><h3><i class="fas fa-hashtag"></i> Populära kategorier</h3></div>
    <div class="column2 bottom"><a href="/discover/?view=categories">Alla kategorier →</a></div>
</div>
<hr>

<p style="line-height:2.2em">
<?php
// Get category IDs for available posts by slugs
$categories = loopis_cats(['new', 'old', 'booked', 'booked_custom', 'borrow']);

// Create an array to store the post counts for each tag
$tag_post_counts = array();

// Loop through the categories and count the posts for each tag
foreach ($categories as $category_id) {
    $args = array(
        'category__in'   => $category_id,
        'posts_per_page' => -1,
    );

    $category_query = new WP_Query($args);

    while ($category_query->have_posts()) {
        $category_query->the_post();
        $post_tags = get_the_tags();

        if ($post_tags) {
            foreach ($post_tags as $tag) {
                if (isset($tag_post_counts[$tag->term_id])) {
                    $tag_post_counts[$tag->term_id]++;
                } else {
                    $tag_post_counts[$tag->term_id] = 1;
                }
            }
        }
    }
}

// Reset post data AFTER the loop
wp_reset_postdata();

// Sort the tag post counts array in descending order
arsort($tag_post_counts);

// Get the top 10 tags with the most posts
$top_tags = array_slice($tag_post_counts, 0, 10, true);

// Output tags
foreach ($top_tags as $tag_id => $post_count) {
    $tag = get_tag($tag_id);
    $tag_link = get_tag_link($tag_id);
    ?>
    <span class="big-link">
        <a href="<?php echo esc_url($tag_link); ?>" title="<?php echo esc_attr($tag->name); ?>" class="<?php echo esc_attr($tag->slug); ?>">
            <i class="fas fa-hashtag"></i><?php echo esc_html($tag->name); ?>
        </a>
    </span>&nbsp;
<?php 
}
?>
</p>