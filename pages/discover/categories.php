<?php
/**
 * Output of popular tags (called categories in UI)
 * 
 * Included in search.php
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1><i class="fas fa-hashtag"></i> Kategorier</h1>
<hr>
<p class="small">💡 Listor över alla kategorier.</p>

<?php
$categories = loopis_cats(['new', 'first', 'booked_locker', 'booked_custom']); // Get category IDs for availbale posts by slugs
$tags = get_tags();

// Create an array to store the post counts for each tag
$tag_post_counts = array();

// Initialize the counter for total tags
$total_tags = count($tags); // Total number of tags

foreach ($tags as $tag) {
    $post_count = 0;

    foreach ($categories as $category_id) {
        $args = array(
            'tag__in'        => $tag->term_id,
            'category__in'   => $category_id,
            'posts_per_page' => -1,
        );

        $category_query = new WP_Query($args);
        $post_count += $category_query->post_count;
        wp_reset_postdata();
    }

    $tag_post_counts[$tag->term_id] = $post_count;
}

// Sort the tag post counts array in descending order
arsort($tag_post_counts); ?>

<p>Tryck på en av våra <?php echo $total_tags; ?> kategorier för att se aktuella annonser.<br>
&nbsp;</p>
<div class="columns"><div class="column1">↓ Bokstavsordning</div>
<div class="column2" style="justify-content: flex-start;">↓ Popularitet</div></div>
<hr>
<div class="columns_cat">
    <div>
        <?php foreach ($tags as $tag) : ?>
            <?php $tag_link = get_tag_link($tag->term_id); ?>
            <p><span class="big-link">
            <a href="<?php echo $tag_link; ?>" title="<?php echo $tag->name; ?>" class="<?php echo $tag->slug; ?>">
            <i class="fas fa-hashtag"></i><?php echo $tag->name; ?> <span style="color:#999">(<?php echo $tag_post_counts[$tag->term_id]; ?>)</span></a></span></p>
        <?php endforeach; ?>
    </div>
    <div>
        <?php
        // Output tags in order of popularity
        foreach ($tag_post_counts as $tag_id => $post_count) {
            $tag = get_tag($tag_id);
            $tag_link = get_tag_link($tag_id);
        ?>
            <p><span class="big-link">
            <a href="<?php echo $tag_link; ?>" title="<?php echo $tag->name; ?>" class="<?php echo $tag->slug; ?>">
			<i class="fas fa-hashtag"></i><?php echo $tag->name; ?> <span style="color:#999">(<?php echo $post_count; ?>)</span></a></span></p>
        <?php } ?>
    </div>
</div>