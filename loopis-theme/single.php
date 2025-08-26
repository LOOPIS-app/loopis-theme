<?php get_header(); ?>

<!-- Extra php functions -->
<?php if (current_user_can('member') || current_user_can('administrator')) {
	include_once LOOPIS_THEME_DIR . '/assets/functions/user-extra/post-action-single.php';
} 
if (current_user_can('administrator')) {
	include_once LOOPIS_THEME_DIR . '/assets/functions/admin-extra/gift-action-special.php';
} 
?>

<!-- VARIABLER -->
<?php
wp_reset_postdata(); // added here when removed from functions.php
$current = get_current_user_id();
$author = get_the_author_meta('ID'); 
$post_id = get_the_ID();
$post_date = get_the_time('Y-m-d H:i');
$extend_date = get_field('extend_date');
$previous_post_id = get_field('previous_post');
$forward_post_id = get_field('forward_post');
$fetcher = get_post_meta($post->ID, 'fetcher', true);
	if ($fetcher) { $fetchername = get_userdata($fetcher)->display_name; $fetcherlink = get_author_posts_url($fetcher); } 
?>

<!-- Custom location?  -->
<?php $location = get_field('location', $post_id);
if ( $location == 'Annan adress' ) { $location = get_field('custom_location', $post_id); update_field('location', $location, $post_id); } ?>

<!-- Extra image?  -->
<?php $thumbnail_id = get_post_thumbnail_id($post_id); ?>
<?php $image_2_id = get_field('image_2', $post_id);
if (empty($image_2_id)) {
    $extra_image_url = get_post_meta($post_id, 'extra_image', true);
    if ($extra_image_url) {
	    $file_name = basename($extra_image_url);
        $attachment_id = media_sideload_image($extra_image_url, $post_id, $file_name, 'id');
        update_field('image_2', $attachment_id, $post_id);
        delete_post_meta($post_id, 'extra_image');
        $cache_buster = time();
        echo '<meta http-equiv="refresh" content="0;url=' . esc_url(add_query_arg('cache_buster', $cache_buster)) . '">';
        exit; } } ?>

<div class="content">

<!-- THE GIFT -->
<div class="post-wrapper">    
		<div class="post-images">
			<div class="post-image"><?php echo wp_get_attachment_image($thumbnail_id, 'large'); ?></div>
		    <?php if ($image_2_id) {
				echo '<div class="extra-image">';
				echo wp_get_attachment_image($image_2_id, 'large');
				echo '</div>'; } ?>
		</div> <!--post-images-->

        <div class="post-padding">
            <h1 class="wrap"><?php the_title(); ?></h1>
			<div class="post-meta">
				<span><?php the_category(' '); if (in_category( 'new' )) { echo raffle_time(); } ?></span>
				<span><i class="fas fa-walking"></i><?php if ($location == 'Skåpet') { ?><a href="https://maps.app.goo.gl/bp1v8fSAf7MJqxu88"><?php echo get_field('location'); ?></a><?php } else { ?><a href="https://maps.google.com/maps?q=<?php echo urlencode(get_field('location')); ?>"><?php echo get_field('location') ?></a><?php } ?></span>
				<?php $tags = get_the_tags(); if ($tags) { foreach ($tags as $tag) { echo '<span><a href="' . get_tag_link($tag->term_id) . '"><i class="fas fa-hashtag"></i>' . $tag->name . '</a></span>'; }} ?>
			</div><!--post-meta-->

			<div class="post-content">
					<?php the_content(); ?>
					<?php if ($author == 66 || $author == 237) : ?>
        			<a style="float:left; font-size:14px; padding-top:2px; margin-right:12px" href="/faq/max-murpos">💫 Räddad från soprum</a>
    				<?php endif; ?>

<!-- Options -->
<?php if (($current == $author || current_user_can('administrator')) && !in_category(array('removed', 'fetched', 'locker'))) : ?>
<?php if (isset($_POST['remove'])) { action_remove(get_the_ID()); } ?>
    <button type="submit" form="remove-form" class="option" onclick="return confirm('Ta bort annonsen?')">
        <i class="fas fa-times"></i>Ta bort
    </button>
<form id="remove-form" method="post" action="" style="display:inline; float:left;">
    <input type="hidden" name="remove" value="1">
</form>
<?php endif;?>
<button type="button" id="copy_url">🔗 Kopiera länk</button>

			</div><!--post-content-->				
		</div><!--post-padding-->				
	</div><!--post-wrapper-->							

<!-- User log -->
<?php include LOOPIS_THEME_DIR . '/assets/output/post/gift/gift-log.php'; ?>

<div class="page-padding">

<!-- INTERACTION -->
<div class="auto-respond">
<div class="columns"><div class="column1"><h3>Dina alternativ</h3></div>
<div class="column2 bottom"><a href="../faq/hur-far-jag-saker/">📌 Hur får jag saker?</a></div></div>
<hr>
<?php if (current_user_can('member') || current_user_can('administrator')) {
	if (comments_open()) {
		comments_template('/comments.php', true);
	}
} else {
	include LOOPIS_THEME_DIR . '/assets/output/access/message.php';
	include LOOPIS_THEME_DIR . '/assets/output/visitor/faq-single.php';
} ?>
</div><!--auto-respond-->

<!-- Admin log -->
<?php if ( current_user_can('administrator') || current_user_can('manager')) { include LOOPIS_THEME_DIR . '/assets/output/post/gift/gift-log-admin.php'; } ?>

</div> <!--page-padding-->
</div> <!--content-->

<!-- Extra scripts -->
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/extra-image-switch.js"></script>

<?php get_footer(); ?>