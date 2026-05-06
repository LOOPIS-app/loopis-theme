<?php
/**
 * Template for single forum post.
 */

get_header(); ?>

<!-- SET VARIABLES -->
<?php
wp_reset_postdata(); // added here when removed from functions.php
$current = get_current_user_id();
$author = get_the_author_meta('ID');
$post_id = get_the_ID();

// Get category of the post
$terms = get_the_terms($post_id, 'forum-category');
$category_name = '';

// Get category name, excluding 'start' if it exists
if ($terms && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        if ($term->slug !== 'start') {
            $category_name = $term->name;
            break; 
        }
    }
}
?>

<!-- POST CONTENT -->
<div class="content">
    <div class="post-wrapper">
        <div class="post-padding">
			<p><span class="rounded"><a href="<?php echo get_post_type_archive_link('forum'); ?>">📡 Nyheter</a></span> <span class="rounded"><?php if ($category_name) { echo esc_html($category_name); } ?></span></p>
            <h1 class="wrap"><?php the_title(); ?></h1>
			<div class="post-meta">
				<!--span class="rounded">🗨 Forum</span-->
				<span><i class="fas fa-pen-alt"></i></i> <?php echo get_the_author_posts_link(); ?></span>
				<span><i class="far fa-clock"></i> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen</span>
			</div><!--post-meta-->

			<div class="post-content">
					<?php the_content(); ?>

<!-- POST OPTIONS -->
<button type="button" id="copy_url">🔗 Kopiera länk</button>

			</div><!--post-content-->				
		</div><!--post-padding-->				
	</div><!--post-wrapper-->							

<div class="page-padding" style="padding-top: 5px;"> <!-- Logg close to post -->

<!-- POST LOG -->
<div class="logg">
<p><i class="fas fa-arrow-alt-circle-up"></i><?php echo get_the_author_posts_link(); ?> postade för <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen <span><?php the_time('Y-m-d H:i')?></span></p>
</div>	

<!-- POST INTERACTION-->
<?php 
// Access control
if ( current_user_can('member') || current_user_can('administrator') ) { 

	// Comments
	if (comments_open()) { comments_template('/comments.php', true); }

	} else {
		// Visitor message
		include_once LOOPIS_THEME_DIR . '/templates/access/no-comments.php'; 
		} ?>

	</div><!--page-padding-->				
</div><!--content-->

<?php get_footer(); ?>