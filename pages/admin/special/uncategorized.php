<?php
/**
 * List posts with no category (output as "Uncategorized").
 * 
 * Used for debugging and support.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>❤️‍🩹 Uncategorized</h1>
<hr>
<p class="small">💡 Annonser som helt saknar kategori.</p>

<?php
$paged = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;

$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 50,
    'paged'          => $paged,
    'tax_query'      => array(
        array(
            'taxonomy' => 'category',
            'operator' => 'NOT EXISTS',
        ),
    ),
);

$the_query = new WP_Query($args);
$post_count = $the_query->found_posts;
?>

<div class="columns"><div class="column1">↓ <?php echo $post_count; ?> annonser</div>
<div class="column2"></div></div>
<hr>

<!-- Post output -->
<div class="post-list">
    <?php if ($the_query->have_posts()) : ?>
        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <?php get_template_part('templates/post-list/big-posts'); ?>
        <?php endwhile; ?>
</div><!--post-list-->

<?php include_once get_template_directory() . '/templates/post-list/pagination.php'; ?>

<?php else : ?>
    <p>💢 Inga annonser</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>