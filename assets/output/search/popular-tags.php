<?php
/**
 * Popular tags for search page
 * 
 * Included in searchform.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>
<div class="columns"><div class="column1">↓ Utforska en populär kategori</div>
<div class="column2"><a href="/kategorier">Alla kategorier →</a></div></div>
<hr>
<p style="line-height:2.2em">
<?php
$categories = array(1, 37, 42, 57, 147); // Specify the category IDs

// Create an array to store the post counts for each tag
$tag_post_counts = array();

// Loop through the categories and count the posts for each tag
foreach ($categories as $category_id) {
    $args = array(
        'post_type'      => array('post', 'borrow'), // Specify the post types
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

    wp_reset_postdata(); } 

// Sort the tag post counts array in descending order
arsort($tag_post_counts);

// Get the top 10 tags with the most posts
$top_tags = array_slice($tag_post_counts, 0, 12, true);

// Output tags
foreach ($top_tags as $tag_id => $post_count) {
    $tag = get_tag($tag_id);
    $tag_link = get_tag_link($tag_id);
    ?>
    <span class="big-link">
            <a href="<?php echo $tag_link; ?>" title="<?php echo $tag->name; ?>" class="<?php echo $tag->slug; ?>">
                <i class="fas fa-hashtag"></i><?php echo $tag->name; ?>
            </a>
        </span>&nbsp;
    <?php } ?>
</p>
<p>&nbsp;</p>