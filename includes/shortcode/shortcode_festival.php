<?php
/**
 * Function for fetching and displaying posts relevant to festival event.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */


/**
 *  Shortcode: [loopis_get_festival]
 * 
 *  Displays an array of storage posts for the festival!
 * 
 * @return string HTML output
 */

add_shortcode( 'loopis_get_festival', function () {
	ob_start();
	?>

	<?php
	// Arguments
	$args = array(
	    'post_type' => 'post',
	    'cat'   	=> '155', // Categopry 'tips'
	    'post_status' => 'publish',
	    'posts_per_page' => -1, // Output all posts
	);
	
	// Query
	$the_query = new WP_Query($args);
	$count = $the_query->found_posts;
	?>
	
	<!--Output-->
	<h7>🎁 Saker att hämta</h7>
	<div class="columns"><div class="column1">↓ <?php echo $count; ?> sak<?php if ($count !== 1) { echo "er"; } ?> kvar</div>
	<div class="column2"></div></div>
	<hr>
	
	<div class="post-list">
	
	<?php if ( $the_query->have_posts() ) : ?>
	<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
		<div class="post-list-post-big" onclick="location.href='<?php the_permalink(); ?>';">
			<div class="post-list-post-thumbnail-big"><?php the_post_thumbnail('thumbnail'); ?></div>
			<div class="post-list-post-title-big"><?php the_title(); ?></div>
			<div class="post-list-post-meta">
				<p>🟢 Fortfarande kvar</p>
				<p><i class="fas fa-walking"></i>Folkets Hus</p>
				<p><i class="fas fa-hashtag"></i><?php the_tags(''); ?></p>		
			</div>
		</div>
	<?php
	    endwhile;
	    wp_reset_postdata();
		else : ?>
	    <p>💢 Det finns inga saker kvar att hämta.</p>
	<?php endif; ?>
	</div><!--post-list-->
	
	<?php wp_reset_postdata(); ?>

	<?php
	return ob_get_clean();
} );


