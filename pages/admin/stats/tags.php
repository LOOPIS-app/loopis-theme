<?php
/**
 * Statistics for tags (called categories in UI))
 * 
 * Will be improved to use generic functions.
 * Will be improved to use custom database table.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📊 Kategorier</h1>
<hr>
<p class="small">💡 Kategorier för hämtade annonser.</p>
<?php
// Get the selected start and end dates from the GET request
$start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';

$categories = array(41);
$tags = get_tags();

// Create an array to store the post counts for each tag
$tag_post_counts = array();

// Initialize the counter for total tags
$total_tags = count($tags); // Total number of tags

foreach ($tags as $tag) {
    $post_count = 0;

    foreach ($categories as $category_id) {
        $args = array(
            'post_type'      => array('post', 'borrow'),
            'tag__in'        => $tag->term_id,
            'category__in'   => $category_id,
            'posts_per_page' => -1,
        );

        // Add date range to the query if both start and end dates are provided
        if (!empty($start_date) && !empty($end_date)) {
            $args['date_query'] = array(
                array(
                    'after'     => $start_date,
                    'before'    => $end_date,
                    'inclusive' => true,
                ),
            );
        }

        $category_query = new WP_Query($args);
        $post_count += $category_query->post_count;
        wp_reset_postdata();
    }

    $tag_post_counts[$tag->term_id] = $post_count;
}

// Sort the tag post counts array in descending order
arsort($tag_post_counts);
?>

<!-- Date Range Selector Form -->
<form method="GET" action="" style="margin-bottom: 20px;">
    <!-- Preserve the view parameter -->
    <input type="hidden" name="view" value="<?php echo esc_attr(isset($_GET['view']) ? $_GET['view'] : ''); ?>">
    <label for="start_date">Startdatum:</label>
    <input type="date" id="start_date" name="start_date" value="<?php echo esc_attr($start_date); ?>">
    <label for="end_date">Slutdatum:</label>
    <input type="date" id="end_date" name="end_date" value="<?php echo esc_attr($end_date); ?>">
    <button type="submit">Filtrera</button>
</form>

<div class="columns">
    <div class="column1">↓ Bokstavsordning</div>
    <div class="column2" style="justify-content: flex-start;">↓ Popularitet</div>
</div>
<hr>
<div class="columns_cat">
    <div>
        <?php foreach ($tags as $tag) : ?>
            <p><span class="big-label">
            <i class="fas fa-hashtag"></i><?php echo $tag->name; ?> <span style="color:#999">(<?php echo $tag_post_counts[$tag->term_id]; ?>)</span></span></p>
        <?php endforeach; ?>
    </div>
    <div>
        <?php
        // Output tags in order of popularity with numbering
        $counter = 1; // Initialize the counter
        foreach ($tag_post_counts as $tag_id => $post_count) {
            $tag = get_tag($tag_id);
        ?>
            <p>
            <?php echo $counter; ?>. <span class="big-label"><i class="fas fa-hashtag"></i><?php echo $tag->name; ?> <span style="color:#999">(<?php echo $post_count; ?>)</span></span></p>
        <?php 
            $counter++; // Increment the counter
        } ?>
    </div>
</div>