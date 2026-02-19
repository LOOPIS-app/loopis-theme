<?php
/**
 * List of archived support tickets 
 */

if (!defined('ABSPATH')) {
    exit;
}

// Arguments
$args = array(
    'post_type' => 'support',
    'tax_query' => array(
        array(
            'taxonomy' => 'support-status',
            'field' => 'slug',
            'terms' => 'inactive'
        )
    )
);

// Query
$the_query = new WP_Query( $args );
$count = $the_query->found_posts; 
?>

<!--Output-->
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' ärende'; } else { echo ' ärenden'; } ?>
</div><div class="column2">
</div></div>
<hr>
<div class="post-list">

<?php if( $the_query->have_posts() ): ?>

    <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<?php $post_id = get_the_ID(); ?>
			<div class="post-list-post" style="padding-left:10px;" onclick="location.href='<?php the_permalink(); ?>';">
				<div class="post-list-post-title">
					<?php the_title(); ?>
				</div>
				<div class="post-list-post-meta">
					<span><?php echo get_term(get_post_meta($post_id, 'status', true), 'support-status')->name; ?></span>
					<span>👤 <?php echo get_the_author_posts_link(); ?></span>
					<span class="right"><i class="fas fa-arrow-alt-circle-up"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp'));?> sen</span>
				</div>
			</div>
				
    <?php endwhile; ?>

<?php else : ?>
		<p>💢 Inga arkiverade support-ärenden.</p>
	<?php endif; ?>

</div>

<?php wp_reset_postdata(); ?>