<?php get_header(); ?>

<!-- VARIABLER -->
<?php
wp_reset_postdata(); // added here when removed from functions.php
$post_id = get_the_ID();
$author = get_the_author_meta('ID');
$current = get_current_user_id();
$invited = get_post_meta($post_id, 'invited', true);
	if (!is_array($invited)) { $invited = array(); }
$page_title = get_field('title');
$page_link = get_field('link');

// Get status of the post
$status_id = get_field('status');
$status_term = get_term($status_id, 'support-status');
$status_name = $status_term->name;
$status_slug = $status_term->slug;
?>

<div class="content">

<!-- BEHÖRIG? -->
<?php if ($current == $author || current_user_can('loopis_support') || $current == 2 || in_array($current, $invited)) { ?>

	<div class="post-wrapper">
		<div class="post-padding">
		<p><span class="rounded">🛠 Support</span></p>
			<h1><?php the_title(); ?></h1>
			<div class="post-meta">
				<span><?php echo $status_name; ?></span>
				<span>👤 <?php echo get_the_author_posts_link(); ?></span>
				<span><i class="far fa-clock"></i> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen</span>
			</div><!--post-meta-->

			<div class="post-content">

<p class="label">Skickad från:</p>
<p><span class="link"><a href="<?php echo $page_link; ?>"><?php echo $page_title; ?></a></span></p>

<?php if ($invited) : ?>
    <p class="label">Berörda användare:</p>
    <p>
        <?php
        foreach ($invited as $user_id) {
            $user_data = get_userdata($user_id);
            if ($user_data) {
                $display_name = $user_data->display_name;
                $author_link = get_author_posts_url($user_id);
                echo '<span class="label">👤<a href="' . $author_link . '">' . $display_name . '</a></span> ';
            }
        }
        ?>
    </p>
<?php endif; ?>

<p class="label">Meddelande:</p>
<?php the_content(); ?>

			</div><!--post-content-->				
		</div><!--post-padding-->				
	</div><!--post-wrapper-->							

<!-- LOGG -->
<div class="logg">
<p>✉ Skickad av <?php echo get_the_author_posts_link(); ?> för <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen <span><?php the_time('Y-m-d H:i')?></span></p>
</div><!--logg-->	

<div class="page-padding">


<!-- INTERACTION-->
<?php if (comments_open()) { comments_template('/comments-support.php', true); } ?>

</div> <!--page-padding-->

<!-- EJ BEHÖRIG-->
<?php } else { ?>
<div class="wpum-message information">
<p>Support-ärendet visas endast för skaparen, admin och eventuella andra berörda användare.</p>
</div>

<?php } ?>


</div> <!--content-->

<?php get_footer(); ?>