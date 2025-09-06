<?php
/**
 * Template for displaying the profile fetched tab content, homemade by LOOPIS.
 *
 * @version 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current user ID
$user_ID = wp_get_current_user()->ID;

// Get all things fetched + count
$args = array(
    'meta_key'      => 'fetcher',
    'meta_value'    => $user_ID,
    'cat'   		 => '41',
	'posts_per_page' => -1,
);

$the_query = new WP_Query( $args );
$count = $the_query->found_posts; 
?>

<p class="small">💡 Här visas alla saker du fått.</p>
<h7>⬇ Saker hämtade</h7>
<div class="columns"><div class="column1">↓ <?php echo $count; ?> sak<?php if ($count > 1) { echo "er"; } ?></div>
<div class="column2"></div></div>
<hr>

<div class="post-list">

<?php if( $the_query->have_posts() ): ?>
<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
	<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
		<div class="post-list-post-thumbnail"><?php echo the_post_thumbnail('thumbnail'); ?>´</div>
		<div class="post-list-post-title"><?php the_title(); ?></div>
		<div class="post-list-post-meta">
			<span><?php the_category(' '); ?></span>
			<span class="right bottom"><i class="fas fa-check-square"></i><?php echo human_time_diff(strtotime(get_field('fetch_date')), current_time('timestamp'))?> sen</span>
		</div>
	</div><!--post-list-post-->
<?php endwhile; ?>

<?php else : ?>
		<p>💢 Du har inte hämtat något ännu.</p>
		<p>Ta en titt på <span class="link"><a href="<?php echo home_url(); ?>/things">🎁 Saker att få</a></span></p>
<?php endif; ?>
</div><!--post-list-->

<?php wp_reset_postdata(); ?>

