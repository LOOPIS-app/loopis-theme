<?php
/**
 * Show post details & settings for admin
 *
 * Used in single.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Set more variables
$authorname = get_the_author_meta('display_name', $author);
$authorlink = get_author_posts_url(get_the_author_meta('ID')); 
$participants = get_post_meta($post_id, 'participants', true); 
	if (is_array($participants) && !empty($participants)) {
		$participants = array_filter($participants);   /* remove gaps  */
		$participants = array_values($participants) ;}  /* re-index */
	if (is_array($participants)) { $count = count($participants); } else { $count = 0; }
$raffle_date = get_post_meta($post_id, 'raffle_date', true);
$edit_wpadmin = get_admin_url(null, 'post.php?post=' . $post_id . '&action=edit');
$edit_wpum = get_permalink() . 'edit';
?>

<div class="admin-block">
<?php include LOOPIS_THEME_DIR . '/assets/output/admin/admin-link.php'; ?>

<!-- KÖLISTA -->	
<?php if (!in_category( 'new' )) :
	$queue = get_post_meta($post_id, 'queue', true); 
	if (is_array($queue)) { $queue_count = count($queue); } else { $queue_count = 0; } ?>
	
<div class="columns">↓ <?php echo $queue_count; ?> deltagare i kö</div>	
<hr style="margin-bottom: 2px;">
<div class="logg">
	<?php if (!empty($queue)) {
    foreach ($queue as $user_id) {
        $user_info = get_userdata($user_id);
        if ($user_info !== false) {
            $display_name = $user_info->display_name;
			$author_posts_url = get_author_posts_url($user_info->ID);
			
            echo '<i class="fas fa-user"></i><a href="' . $author_posts_url . '">' . $display_name . '</a><br>'; } }
			} else { echo '<p>💢 Ingen står i kö.</p>'; } ?>
</div><!--logg-->
<?php endif;?>
	
<!-- DELTAGARE -->
<div class="columns">↓ <?php echo $count; ?> deltagare i lottning</div>	
<hr style="margin-bottom: 2px;">
<div class="logg">
	<?php if (!empty($participants)) {
    foreach ($participants as $user_id) {
        $user_info = get_userdata($user_id);
        if ($user_info !== false) {
            $display_name = $user_info->display_name;
			$author_posts_url = get_author_posts_url($user_info->ID);
			
            echo '<i class="fas fa-user"></i><a href="' . $author_posts_url . '">' . $display_name . '</a><br>'; } }
			} else { echo '<p>💢 Ingen har anmält intresse.</p>'; } ?>
</div><!--logg-->
	
<!-- LOGG -->
<div class="columns">↓ Logg</div>	
<hr style="margin-bottom: 2px;">
<div class="logg">

<?php if ($previous_post_id) { ?>
<p><a href="<?php echo get_permalink($previous_post_id); ?>"><i class="fas fa-arrow-alt-circle-left"></i></a><i class="fas fa-arrow-alt-circle-right"></i><?php echo get_the_author_posts_link(); ?> – <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen <span><?php the_time('Y-m-d H:i')?></span></p>
<?php } else { ?>
<p><i class="fas fa-arrow-alt-circle-up"></i><?php echo get_the_author_posts_link(); ?> – <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen <span><?php the_time('Y-m-d H:i')?></span></p>
<?php } ?>

<?php if ($raffle_date) { ?>
<p><i class="fas fa-dice-six"></i><?php echo $count ?> deltagare – <?php echo human_time_diff(strtotime(get_field('raffle_date')), current_time('timestamp'))?> sen <span><?php echo get_field('raffle_date')?></span></p> <?php } ?>
	
<?php if (in_category( array( 'booked_locker', 'booked_custom', 'locker', 'fetched') )) { ?>
<p><i class="fas fa-heart"></i><a href="<?php echo esc_url($fetcherlink); ?>"><?php echo $fetchername; ?></a> – <?php echo human_time_diff(strtotime(get_field('book_date')), current_time('timestamp'))?> sen <span><?php echo get_field('book_date')?></span></p>
<?php } ?>

<?php if (in_category( 'booked_locker' )) { ?>
	<p><i class="fas fa-check-square"></i><?php echo get_the_author_posts_link(); ?> – <i class="fas fa-walking"></i><?php echo get_field('location'); ?>...<span><?php include LOOPIS_THEME_DIR . '/assets/output/post/gift/timer-locker.php';?></span></p>
<?php } ?>
	
<?php if (in_category( 'booked_custom' )) { ?>
<p><i class="fas fa-mobile-alt"></i><a href="<?php echo esc_url($fetcherlink); ?>"><?php echo $fetchername; ?></a> – <i class="fas fa-walking"></i><?php echo get_field('location'); ?>...<span><?php include LOOPIS_THEME_DIR . '/assets/output/post/gift/timer-locker.php';?></span></p>
<?php } ?>

<?php if (in_category( array( 'locker', 'fetched' )) && $location == 'Skåpet') { ?>
<p><i class="fas fa-check-square"></i><?php echo get_the_author_posts_link(); ?> – <?php echo human_time_diff(strtotime(get_field('locker_date')), current_time('timestamp'))?> sen<span><?php echo get_field('locker_date')?></span></p>
<?php } ?>
		
<?php if (in_category( 'locker' )) { ?>
<p><i class="far fa-square"></i><a href="<?php echo esc_url($fetcherlink); ?>"><?php echo $fetchername; ?></a> <i class="fas fa-walking"></i><?php echo get_field('location'); ?>...<span><?php include LOOPIS_THEME_DIR . '/assets/output/post/gift/timer-fetch.php';?></span></p>
<?php } ?>

<?php if (in_category( 'fetched' ) && $location == 'Skåpet') { ?>
<p><i class="far fa-check-square"></i><a href="<?php echo esc_url($fetcherlink); ?>"><?php echo $fetchername; ?></a> – <?php echo human_time_diff(strtotime(get_field('fetch_date')), current_time('timestamp'))?> sen<span><?php echo get_field('fetch_date')?></span></p>
<?php } ?>
	
<?php if (in_category( 'fetched' ) && $location != 'Skåpet') { ?>
<p><i class="far fa-check-square"></i><a href="<?php echo esc_url($fetcherlink); ?>"><?php echo $fetchername; ?></a> hämtade för <?php echo human_time_diff(strtotime(get_field('fetch_date')), current_time('timestamp'))?> sen<span><?php echo get_field('fetch_date')?></span></p>
<?php } ?>

<?php if ($forward_post_id) { ?>
	<p><a href="<?php echo get_permalink($forward_post_id); ?>"><i class="fas fa-arrow-alt-circle-right"></i></a><a href="<?php echo $fetcherlink; ?>"><?php echo $fetchername; ?></a> – <?php echo human_time_diff(strtotime(get_field('forward_date')), current_time('timestamp'))?> sen <span><?php echo get_field('forward_date')?></span></p>
<?php } ?>
	
<?php if (in_category( 'removed' )) { ?>
<p><i class="fas fa-times-circle"></i>Borttagen – <?php echo human_time_diff(strtotime(get_field('remove_date')), current_time('timestamp'))?> sen<span><?php echo get_field('remove_date')?></span></p>
<?php } ?>

</div><!--logg-->

<div class="columns">Metadata</div>	
<hr style="margin-bottom: 2px;">
<div class="logg">
	<p><i class="fas fa-info-circle"></i> Post ID: <?php echo $post_id; ?></p>
	<p><i class="fas fa-user-circle"></i> Author ID: <?php echo $author; ?></p>
</div><!--logg-->	

<?php
// Edit images
$image_2_id = get_field('image_2', $post_id);
if (has_post_thumbnail()) {
    $thumbnail_id = get_post_thumbnail_id();
    $edit_image_url = admin_url('post.php?post=' . $thumbnail_id . '&action=edit');
    echo '<div class="columns">Bilder</div>	
<hr style="margin-bottom: 2px;"><div class="logg"><p><a href="' . esc_url($edit_image_url) . '"><i class="fas fa-image"></i> Redigera bild</a></p>';
}
if ($image_2_id) {
    $edit_image_2_url = admin_url('post.php?post=' . $image_2_id . '&action=edit');
    echo '<p><a href="' . esc_url($edit_image_2_url) . '"><i class="fas fa-image"></i> Redigera bild-2</a></p>';
}
echo '</div><!--logg-->';
?>

<div class="columns">Hantera</div>	
<hr style="margin-bottom: 2px;">
<!-- Fetched button -->
<?php if (in_category( array( 'locker', 'booked_custom' ))) : ?>
		<?php if(isset($_POST['fetched'])) { admin_action_fetched ($post_id); } ?>
		<form method="post" class="arb" action=""><button name="fetched" type="submit" class="admin-style blue small" onclick="return confirm('Har saken hämtats?')">Hämtat</button></form>
		<p class="info">Har hämtaren glömt tryck hämta? Tryck på knappen.</p>
<?php endif;?>

<!-- Notification button -->
		<?php if(isset($_POST['notif_manual'])) { admin_action_notif_manual ($post_id); } ?>
		<form method="post" class="arb" action=""><button name="notif_manual" type="submit" class="admin-style orange small" onclick="return confirm('Skicka manuellt?')">Skicka besked</button></form>
		<p class="info">Har inga mail skickats? Tryck på knappen.</p>

<!-- Edit & remove -->
<div class="logg">
<p><?php
echo '<a href="' . $edit_wpum . '">Redigera annons</a> '; 
echo ' <a href="' . $edit_wpadmin . '">👽 Redigera i WP-admin</a>';
	?></p>
</div><!--logg-->
</div><!--admin-->