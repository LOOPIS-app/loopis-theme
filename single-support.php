<?php
/**
 * Template for single support post.
 */

get_header(); ?>

<!-- VARIABLER -->
<?php
wp_reset_postdata(); // added here when removed from functions.php
$post_id = get_the_ID();
$post_slug = get_post_field('post_name', $post_id);
$source_title = get_post_meta($post_id, 'title', true);
$source_link = get_post_meta($post_id, 'link', true);

// Get category of the post
$category_id = get_the_terms($post_id, 'support-category');
$category_slug = $category_id ? $category_id[0]->slug : '';
$category_name = $category_id ? $category_id[0]->name : 'Okänd';
?>

<div class="content">
	<div class="post-wrapper">
		<div class="post-padding">
		<p><span class="rounded"><a href="<?php echo get_post_type_archive_link('support'); ?>">🛟 Support</a></span> <span class="rounded">🗂 Nr. <?php echo $post_slug; ?></span></p>
			<h1><?php the_title(); ?></h1>
			<div class="post-meta">
				<span><?php echo $category_name; ?></span>
				<span><i class="far fa-clock"></i> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen</span>
			</div><!--post-meta-->

			<div class="post-content">

				<!-- Copy link <button type="button" id="copy_url">🔗 Kopiera länk</button> -->
                
			</div><!--post-content-->				
		</div><!--post-padding-->				
	</div><!--post-wrapper-->							

<div class="page-padding">

<!-- Access check -->
<?php 
$author = get_the_author_meta('ID');
$current = get_current_user_id();
if ($current == $author || current_user_can('loopis_support')) { ?>

<!-- Support post content -->
<div id="commentlist-container" class="comment-tab">			
		<ol class="commentlist" style="margin-bottom:0">
			<li class="comment byuser">
				<div id="div-comment-post" class="comment-body">
					<div class="comment-author vcard">
						<?php echo get_avatar(get_the_author_meta('ID'), 96); ?>
						<cite class="fn"><?php echo get_the_author_posts_link(); ?></cite>
					</div>

					<div class="comment-meta commentmetadata">
						<a href="<?php the_permalink(); ?>"><?php echo get_the_date('Y-m-d'); ?> <?php echo get_the_time('H:i'); ?></a>
						<?php if ((int) get_current_user_id() === (int) get_post_field('post_author', $post_id) && get_edit_post_link($post_id)) : ?>
							&nbsp;&nbsp;<?php edit_post_link('(Edit)', '', '', $post_id, 'comment-edit-link'); ?>
						<?php endif; ?>
					</div>

					<?php
					$support_content = apply_filters('the_content', get_the_content());
					echo str_replace('<p>', '<p class="red_light">', $support_content);
					?>

				</div>
			</li>
		</ol>	
    </div>	

	<p class="label">Skickad från: <span class="link"><a href="<?php echo $source_link; ?>"><?php echo $source_title; ?></a></span></p>

<!-- INTERACTION-->
<?php if (comments_open()) { comments_template('/comments.php', true); } ?>

<!-- Archive -->
<?php if ($category_slug == 'active' && current_user_can('loopis_support')) : ?>
<div class="admin-block">
<?php include LOOPIS_THEME_DIR . '/templates/admin/links/admin-link.php'; ?>
<?php if(isset($_POST['inactive'])) { 
	// Change category to inactive
	wp_set_post_terms($post_id, loopis_support_cat('inactive'), 'support-category', false);
	// Add resolved comment
	add_comment ('<p class="participate">✅ Markerar frågan som besvarad.</p>', $post_id );
	echo "<meta http-equiv='refresh' content='0'>"; } ?>
		<form method="post" class="arb" action=""><button name="inactive" type="submit" class="green small" onclick="return confirm('Är frågan besvarad?')">Frågan är besvarad</button></form>
		<p class="info">Tryck på knappen för att arkivera ärendet.</p>
</div>
		<?php endif;?>

<!-- No access-->
<?php } else { 
include LOOPIS_THEME_DIR . '/templates/access/no-access.php';
 } ?>

</div> <!--page-padding-->
</div> <!--content-->

<?php get_footer(); ?>
