<?php
/**
 * Show count for active support posts in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}



// Count active support posts
$active_args = array(
    'post_type'      => 'support',
    'posts_per_page' => -1,
    'count'          => true,
    'tax_query'      => array(
        array(
            'taxonomy' => 'support-category',
            'field'    => 'slug',
            'terms'    => 'active'
        )
    )
);

$active_query = new WP_Query($active_args);
$active_count = $active_query->found_posts;


// Output
?>
<div class="columns">
    <div class="column1">↓ <?php echo $active_count; if ( $active_count == 1 ) { echo ' aktiv tråd'; } else { echo ' aktiva trådar'; } ?></div>
    <div class="column2"><a href='<?php echo get_post_type_archive_link('support'); ?>'>→ Visa alla</a></div>
</div>
<hr>

<div class="post-list">
<!--Post loop-->
<?php if( $active_query->have_posts() ): ?>
    <?php while( $active_query->have_posts() ) : $active_query->the_post(); ?>
     
        <div class="post-list-cpt compact" onclick="location.href='<?php the_permalink(); ?>';">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="post-list-cpt-thumbnail">
                <?php the_post_thumbnail('thumbnail'); ?>
            </div>
        <?php endif; ?>
        <div class="post-list-cpt-title">🔴 <?php echo esc_html(strip_emoji(get_the_title())); ?> &nbsp;<span class="small">&nbsp;<i class="far fa-comment"></i><?php echo get_comments_number(); ?></span></div>
        <div class="post-list-cpt-excerpt"><?php echo get_the_excerpt(); ?></div>
    </div>

    <?php endwhile; ?>

<?php else : ?>
		<p>💢 Inga aktiva trådar.</p>
	<?php endif; ?>

</div>

<?php wp_reset_postdata(); ?>