<?php
/**
 * Show a selected news post on front page. 
 *
 * Included in front-page.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Query to get forum post in category start
$args = array(
    'post_type' => 'forum',
    'tax_query' => array(
        array(
            'taxonomy' => 'forum-category',
            'field' => 'slug',
            'terms' => array('start'),
        ),
    ),
);
$the_query = new WP_Query($args);
if ( $the_query->have_posts() ) : ?>
<h5>📡 Nyhet</h5>
<hr>
<style>
</style>
<div class="post-list">
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
    <div class="post-list-cpt" style="height:60px;" onclick="location.href='<?php the_permalink(); ?>';">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-list-cpt-thumbnail" style="width: 60px; height: 60px; overflow: hidden;">
                        <?php get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?>
                    </div>
                <?php endif; ?>
                <div class="post-list-cpt-title"><?php echo esc_html(get_the_title()); ?></div>
                <div class="post-list-cpt-excerpt"><?php echo get_the_excerpt(); ?> <span class="read-more"> → Läs mer</span></div>
			</div>
<?php endwhile; ?>
</div><!--post-list-->
<?php endif; ?>
<?php wp_reset_postdata(); ?>