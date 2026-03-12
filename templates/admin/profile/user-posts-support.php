<?php
/**
 * Output support posts created by user.
 *
 * Used in author.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get all things posted + count
$user_ID = get_queried_object_id();
$the_query = new WP_Query(array(
    'post_type'      => 'support',
    'author'         => $user_ID,
    'posts_per_page' => -1,
));
$count = $the_query->found_posts;
?>

<!-- OUTPUT -->
<h7>🛟 Support</h7>
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' ärende'; } else { echo ' ärenden'; } ?>
</div>
<div class="column2">
</div></div>
<hr>

<div class="post-list">

	<?php if ( $the_query->have_posts() ) : ?>

		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<?php $post_id = get_the_ID(); ?>
			<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
				<div class="post-list-post-thumbnail">
					<?php echo the_post_thumbnail('thumbnail'); ?>
				</div>
				<div class="post-list-post-title">
					<?php the_title(); ?>
				</div>
				<div class="post-list-post-meta">
					<span><?php echo get_term(get_post_meta($post_id, 'status', true), 'support-status')->name; ?></span>
					<span class="right"><i class="far fa-clock"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp'));?> sen</span>
				</div>
			</div>

		<?php endwhile; ?>

<!-- Check if pagination is needed -->
<!-- Removed because not working with tabs -->

	<?php else : ?>
		<p>💢 Användaren har inte skapat några support-ärenden ännu.</p>
	<?php endif; ?>
		
</div><!--post-list-->


<?php wp_reset_postdata(); ?>