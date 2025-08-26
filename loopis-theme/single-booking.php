<?php get_header(); ?>

<!-- VARIABLER -->
<?php
wp_reset_postdata(); // added here when removed from functions.php
$post_id = get_the_ID();
$current = get_current_user_id();
$author = get_the_author_meta('ID');
$owner = get_field('owner');

// Access?
if ($current == $author || $current == $owner || current_user_can('loopis_bookings')) { 

// Get more variables
$ownername = get_the_author_meta('display_name', $owner);
$ownerlink = get_author_posts_url($owner);
$location = get_post_meta($post_id, 'location', true);

// Get status of the post
$taxonomies = get_the_terms( get_the_ID(), 'booking-status' );
if ( $taxonomies && ! is_wp_error( $taxonomies ) ) {
foreach ( $taxonomies as $taxonomy ) {
$status = get_term( $taxonomy->term_id, 'booking-status' );
$status_name = $status->name;
$status_slug = $status->slug; } }

// Get booking data
$terms = get_field('terms');
$date_start = get_field('date_start');
$day_start = date('l Y-m-d', strtotime($date_start));
$date_end = get_field('date_end');
$day_end = date('l Y-m-d', strtotime($date_end));
?>

<!-- THE BOOKING -->
<div class="content">
	<div class="post-wrapper">
		<div class="post-padding">
		<p><span class="rounded">🗓 Bokning</span></p>
			<h1><?php the_title(); ?></h1>
			<div class="post-meta">
				<span><?php echo $status_name; ?></span>
				<span>👤 <?php echo get_the_author_posts_link(); ?></span>
				<span><i class="far fa-clock"></i> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen</span>
			</div><!--post-meta-->

			<div class="post-content">
			<h4>Skickad information:</h4>
			<hr>
			<p class="label">Hämtning:</p>
			<p><span><i class="far fa-calendar"></i> <?php echo $day_start ?></span> - <span><i class="fas fa-walking"></i> <?php echo get_field('location', get_field('object')) ?></span></p>
			<p class="label">Återlämning:</p>
			<p><span><i class="far fa-calendar"></i> <?php echo $day_end ?></span> - <span><i class="fas fa-walking"></i> <?php echo get_field('return_location', get_field('object')) ?></span></p>
			<p class="label">Meddelande:</p>
			<p style="font-style:italic"><?php echo get_the_content(); ?></p>
			<p class="label">Villkor:</p>
			<p style="font-style:italic; font-size: 14px;">⚠ <?php echo $terms ?></p>
			<!-- OBJEKTET-->
			 <p class="label">Annons:</p>
			<div class="post-list">
				<div class="post-list-post" style="background: #fff" onclick="location.href='<?php echo get_permalink(get_field('object')); ?>';">
					<div class="post-list-post-thumbnail"><?php echo get_the_post_thumbnail(get_field('object'), 'thumbnail'); ?></div>
					<div class="post-list-post-title"><?php echo get_the_title(get_field('object')); ?></div>
					<div class="post-list-post-meta">
					<p><?php $categories = get_the_category(get_field('object')); foreach ($categories as $category) { echo $category->name . ' '; } ?><!--?php include LOOPIS_THEME_DIR . '/assets/output/post/borrow/borrow-days.php'; ?--></p>
					</div>
				</div>
			</div><!--post-list-->
			</div><!--post-content-->
		</div><!--post-padding-->
	</div><!--post-wrapper-->

<!-- User log -->
<?php include LOOPIS_THEME_DIR . '/assets/output/post/booking/booking-log.php'; ?>

<div class="page-padding">

<!-- INTERACTION-->
<?php if (comments_open()) { comments_template('/comments-booking.php', true); } ?>

<!-- Admin log -->
<?php if ( current_user_can('loopis_bookings')) { include LOOPIS_THEME_DIR . '/assets/output/post/booking/booking-log-admin.php'; } ?>

<!-- NO ACCESS MESSAGE -->
<?php } else { ?>
			<div class="wpum-message information">
			<p>Information om bokningar visas endast för inblandade medlemmar.</p>
			</div>			
<?php } ?>


<?php get_footer(); ?>