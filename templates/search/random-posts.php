<?php
/**
 * Output of three random available posts
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get random posts from categories 1 or 37
$args = array(
    'posts_per_page' => 3,
    'orderby'        => 'rand',
    'category__in'   => loopis_cats(['new', 'old']),
);
$the_query = new WP_Query($args);
?>

<div class="columns"><div class="column1"><h3>🤹 Tre tips</h3></div>
<div class="column2 bottom"><a href="/discover/?view=random-posts">Fler tips →</a></div></div>
<hr>
 <!-- Posts output -->
<div class="post-list">
        <?php if ($the_query->have_posts()) : ?>
            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                <?php get_template_part('templates/post-list/small-posts-tags'); ?>
            <?php endwhile; ?>
</div><!--post-list-->
<?php else : ?>
    <p>💢 Inga aktuella annonser</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>