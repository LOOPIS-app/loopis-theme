<?php
/**
 * Output posts submitted by user.
 *
 * Used in author.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get the current author ID
$user_ID = get_queried_object_id();

// Get all things sumbitted + count
$args = array(
    'author'         => $user_ID, // Filter by author ID
    'post_type'      => 'post',   // Specify the post type
    'posts_per_page' => -1,       // Retrieve all posts
    'post_status'    => 'publish' // Only include published posts
);

// Create a new WP_Query instance
$the_query = new WP_Query($args);

// Get the count of submitted posts
$count_submitted = $the_query->found_posts;

// Don't forget to reset post data after custom query
wp_reset_postdata();
?>

<!-- OUTPUT -->
<h7>⬆ Annonser</h7>
<div class="columns"><div class="column1">
↓ <?php echo $count_submitted; if ( $count_submitted == 1 ) { echo ' annons'; } else { echo ' annonser'; } ?>
</div>
<div class="column2">
</div></div>
<hr>

<div class="post-list">

	<?php if ( $the_query->have_posts() ) : ?>

		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

			<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
				<div class="post-list-post-thumbnail">
					<?php echo the_post_thumbnail('thumbnail'); ?>
				</div>
				<div class="post-list-post-title">
					<?php the_title(); ?>
				</div>
				<div class="post-list-post-meta">
					<span><?php the_category(' '); if (in_category( 'new' )) { echo raffle_time(); } ?></span>
					<span class="right"><i class="fas fa-arrow-alt-circle-up"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp'));?> sen</span>
				</div>
			</div>

		<?php endwhile; ?>

<!-- Check if pagination is needed -->
<!-- Removed because not working with tabs -->

	<?php else : ?>
		<p>💢 Användaren har inte skapat några annonser ännu.</p>
	<?php endif; ?>
		
</div><!--post-list-->


<?php wp_reset_postdata(); ?>