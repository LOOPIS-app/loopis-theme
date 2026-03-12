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

// Get all things booked + count
$args = array(
    'meta_key'      => 'fetcher',
    'meta_value'    => $user_ID,
    'cat'   		 => '41, 57, 104, 106',
);

$the_query = new WP_Query( $args );
$count = $the_query->found_posts; 
?>

<h7>⬇ Saker hämtade</h7>
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' annons'; } else { echo ' annonser'; } ?>
</div>
<div class="column2">
</div></div>
<hr>

<div class="post-list">

<?php if( $the_query->have_posts() ): ?>

    <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<?php $post_id = get_the_ID(); ?>
			<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
				<div class="post-list-post-thumbnail">
					<?php echo the_post_thumbnail('thumbnail'); ?>
				</div>
				<div class="post-list-post-title">
					<?php the_title(); ?>
				</div>
				<div class="post-list-post-meta">
				<span><?php the_category(' '); ?> för 
				<?php if (in_category( array( 'booked_locker', 'booked_custom', 'locker' ) )) : ?>
					<?php echo human_time_diff(strtotime(get_post_meta($post_id, 'book_date', true)), current_time('timestamp'))?>
				<?php endif;?>
				<?php if (in_category( 'fetched' )) : ?>
					<?php echo human_time_diff(strtotime(get_post_meta($post_id, 'fetch_date', true)), current_time('timestamp'))?>
				<?php endif;?>
				 sen</span>
				</div>
			</div>
    <?php endwhile; ?>

<!-- Check if pagination is needed -->
<!-- Removed because not working with tabs -->
	
	<?php else : ?>
		
		<p>💢 Användaren har inte paxat eller hämtat något ännu.</p>

	<?php endif; ?>

<?php wp_reset_postdata(); ?>

</div><!--post-list-->