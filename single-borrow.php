<?php get_header(); ?>

<!-- VARIABLER -->
<?php
wp_reset_postdata(); // added here when removed from functions.php
$location = get_field('location');
?>

<div class="content">
	<div class="post-wrapper">
		<div class="post-image">
			<?php if ( has_post_thumbnail() ) {	the_post_thumbnail('large'); } ?>
		</div><!--post-image-->
		<div class="post-padding">
			<h1><?php the_title(); ?></h1>
			<div class="post-meta">
				<span><?php the_category(' '); include LOOPIS_THEME_DIR . '/assets/output/post/borrow/borrow-days.php'; ?></span>
				<span><i class="fas fa-walking"></i><?php if ($location == 'Skåpet') { ?><a href="https://maps.app.goo.gl/bp1v8fSAf7MJqxu88"><?php echo get_field('location'); ?></a><?php } else { ?><a href="https://maps.google.com/maps?q=<?php echo urlencode(get_field('location')); ?>"><?php echo get_field('location') ?></a><?php } ?></span>
				<?php $tags = get_the_tags(); if ($tags) { foreach ($tags as $tag) { echo '<i class="fas fa-hashtag"></i><a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a>'; }} ?>
			</div><!--post-meta-->

			<div class="post-content">
					<?php the_content(); ?>

<button type="button" id="copy_url">🔗 Kopiera länk</button>

			</div><!--post-content-->				
		</div><!--post-padding-->				
	</div><!--post-wrapper-->							


<!-- Räkna antal lån -->
<?php $current_post_id = get_the_ID();
$args = array(
    'post_type'      => 'booking',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'   => 'object',
            'value' => $current_post_id, ), ), );
$bookings = new WP_Query( $args );
$count_bookings = $bookings->found_posts;
wp_reset_postdata(); ?>

<!-- LOGG -->
<div class="logg">
<p><i class="fas fa-arrow-alt-circle-up"></i> <?php echo get_the_author_posts_link(); ?> skapade för <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen <span><?php the_time('Y-m-d H:i')?></span></p>
<!--p><i class="fas fa-sync-alt"></i> Lånad <?php echo $count_bookings ?> gånger</p-->
</div><!--logg-->	

<div class="page-padding">

<!-- INTERACTION-->
<div class="auto-respond">
<h3>Vill du låna?</h3>
<hr>
<?php if ( current_user_can('member') || current_user_can('administrator') ) { echo do_shortcode('[code_snippet id=16 php=true]');
} else {
	include LOOPIS_THEME_DIR . '/assets/output/access/message.php';
	include LOOPIS_THEME_DIR . '/assets/output/visitor/faq-single.php';
} ?>
</div><!--auto-respond-->

</div> <!--page-padding-->
</div> <!--content-->

<?php get_footer(); ?>