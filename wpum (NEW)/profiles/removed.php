<?php
/**
 * Template for displaying the profile removed posts tab content.
 * 
 * Added by LOOPIS (to show among the WPUM profile tabs).
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current user ID
$user_ID = wp_get_current_user()->ID;

// Get all things given + count
$args = array( 
    'author' 	=> $user_ID,
    'cat'   	=> '58',
    'posts_per_page' => -1,
);

$the_query = new WP_Query( $args );
$count = $the_query->found_posts;

?>

<p class="small">💡 Här visas alla annonser du tagit bort.</p>
<h7>🗑 Borttagna annonser</h7>
<div class="columns"><div class="column1">↓ <?php echo $count; ?> annons<?php if ($count > 1) { echo "er"; } ?></div>
<div class="column2"></div></div>
<hr>

<div class="post-list">

<?php if ( $the_query->have_posts() ) : ?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
		<div class="post-list-post-thumbnail"><?php echo the_post_thumbnail('thumbnail'); ?></div>
		<div class="post-list-post-title"><?php the_title(); ?></div>
		<div class="post-list-post-meta">
			<span><?php the_category(' '); ?></span>
			<span class="right"><i class="fas fa-arrow-alt-circle-up"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp'));?> sen</span>
		</div>
	</div><!--post-list-post-->
<?php endwhile; ?>
		
<?php else : ?>
		<p>💢 Du har inga borttagna annonser.</p>
<?php endif; ?>
</div><!--post-list-->

<?php wp_reset_postdata(); ?>