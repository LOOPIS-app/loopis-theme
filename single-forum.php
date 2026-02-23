<?php
/**
 * Template for single forum post.
 */

get_header(); ?>

<!-- VARIABLER -->
<?php
wp_reset_postdata(); // added here when removed from functions.php
$current = get_current_user_id();
$author = get_the_author_meta('ID');
$post_id = get_the_ID();

// Get category of the post
$terms = get_the_terms($post_id, 'forum-category');
$category_name = '';

if ($terms && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        if ($term->slug !== 'start') {
            $category_name = $term->name;
            break; 
        }
    }
}
?>

<!-- THE POST  -->
<div class="content">
    <div class="post-wrapper">
        <div class="post-padding">
            <h1 class="wrap"><?php the_title(); ?></h1>
			<div class="post-meta">
				<!--span class="rounded">🗨 Forum</span-->
				<span><?php if ($category_name) { echo esc_html($category_name); } ?></span>
				<span><i class="fas fa-pen-alt"></i></i> <?php echo get_the_author_posts_link(); ?></span>
				<span><i class="far fa-clock"></i> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen</span>
			</div><!--post-meta-->

			<div class="post-content">

<?php if ( current_user_can('member') || current_user_can('administrator') ) { ?>

					<?php the_content(); ?>

<!-- POST OPTIONS -->
<button type="button" id="copy_url">🔗 Kopiera länk</button>

			</div><!--post-content-->				
		</div><!--post-padding-->				
	</div><!--post-wrapper-->							

<div class="page-padding" style="padding-top: 5px;"> <!-- Logg close to post -->

<!-- POST LOGG -->
<div class="logg">
<p><i class="fas fa-arrow-alt-circle-up"></i><?php echo get_the_author_posts_link(); ?> postade för <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen <span><?php the_time('Y-m-d H:i')?></span></p>
</div><!--LOGG-->	

<!-- POST INTERACTION-->
<?php if (comments_open()) { comments_template('/comments.php', true); } ?>

</div><!--page-padding-->

<!-- NO ACCESS MESSAGE -->
<?php } else { ?>
			<?php include_once LOOPIS_THEME_DIR . '/templates/access/member-only.php'; ?>
			</div><!--post-content-->				
		</div><!--post-padding-->		
<?php } ?>

</div><!--post-wrapper-->				
</div><!--content-->

<!-- Copy URL -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
var $temp = $("<input>");
var $url = $(location).attr('href');
$('#copy_url').click(function() {
$("body").append($temp);
$temp.val($url).select();
document.execCommand("copy");
$temp.remove();
});
});
</script>


<?php get_footer(); ?>