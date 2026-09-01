<?php
/**
 * Template for single support post. (To be renamed to single-forum.php when CPT is renamed)
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
$category_terms = get_the_terms($post_id, 'support-category');
$category_slugs = wp_list_pluck((array) $category_terms, 'slug');
$category_names = wp_list_pluck((array) $category_terms, 'name');
?>

<!-- Post output -->
 <div class="page-padding">
		<p><span class="rounded"><a href="<?php echo get_post_type_archive_link('support'); ?>">🛟 Support</a></span>
		<span><a href="#" id="copy_url" class="option">🔗 Kopiera länk</a></span></p>
			<h1><?php echo esc_html(strip_emoji(get_the_title())); ?></h1>
			<hr>
			<div class="post-meta">
				<?php foreach ($category_names as $term_name) : ?>
					<span><?php echo esc_html($term_name); ?></span>
				<?php endforeach; ?>
				<span><i class="far fa-clock"></i> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen</span>
			</div><!--post-meta-->
			<p>&nbsp;</p>

<!-- Access check -->
<?php 
$author = get_the_author_meta('ID');
$current = get_current_user_id();
$current_user = wp_get_current_user();
$current_roles = (array) $current_user->roles;
$is_member = in_array('member', $current_roles, true);
$is_private_support = has_term('private', 'support-category', $post_id);

$can_view_support = $is_member || current_user_can('manage_options');
if ($can_view_support && $is_private_support) {
	$can_view_support = ((int) $current === (int) $author) || current_user_can('loopis_support') || current_user_can('manage_options');
}

if ($can_view_support) : ?>

<!-- Mimic comment layout -->
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
					$support_content = preserve_blank_lines_in_content($support_content);
					echo '<p class="blue_light">';
					echo $support_content;
					echo '</p>';
					?>

				</div>
			</li>
		</ol>	
    </div>	

<!-- SOURCE?-->	
<?php if ($source_link) : ?>
<br>
	<p class="info">Bifogad länk:</p>
<div>
	<div class="post-list-post" onclick="location.href='<?php echo $source_link; ?>';">
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>
	<?php endif; ?>
	<div class="post-list-post-title"><?php echo $source_title; ?></div>
	<div class="post-list-post-meta"><span>Tryck här för att visa.</span></div>
</div>
<?php endif; ?>

<!-- INTERACTION-->
<?php if (comments_open()) { comments_template('/comments.php', true); } ?>

<!-- Archive -->
<?php if (in_array('active', $category_slugs, true) && current_user_can('loopis_support')) : ?>
	<div class="admin-block">
	<?php include LOOPIS_THEME_DIR . '/templates/links/admin-link.php'; ?>
	<?php if(isset($_POST['inactive'])) { 
		// Replace only "active" with "inactive" and keep other terms (for example "private").
		$current_terms = get_the_terms($post_id, 'support-category');
		$updated_term_ids = array();
		
		if (!empty($current_terms) && is_array($current_terms)) {
			foreach ($current_terms as $term) {
				if ($term->slug !== 'active') {
					$updated_term_ids[] = (int) $term->term_id;
				}
			}
		}

		$inactive_term_id = (int) loopis_support_cat('inactive');
		if (!in_array($inactive_term_id, $updated_term_ids, true)) {
			$updated_term_ids[] = $inactive_term_id;
		}

		wp_set_post_terms($post_id, $updated_term_ids, 'support-category', false);
		// Add resolved comment
		add_comment ('<p class="participate">✅ Markerar frågan som besvarad.</p>', $post_id );
		echo "<meta http-equiv='refresh' content='0'>"; } ?>
			<form method="post" class="arb" action=""><button name="inactive" type="submit" class="green small" onclick="return confirm('Är frågan besvarad?')">Frågan är besvarad</button></form>
			<p class="info">Tryck på knappen för att arkivera ärendet.</p>
	</div>
<?php endif;?>

<!-- No access-->
<?php else : include LOOPIS_THEME_DIR . '/includes/output/access/only-user.php'; endif; ?>

</div> <!--page-padding-->

<?php get_footer(); ?>
