<?php
/**
 * Template for displaying WPUM profile tab content.
 * 
 * Modified by LOOPIS.
 * 
 * Improvements:
 * – Fade out post types not relevant to the user
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Count function
include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-list-counts.php';

// Get current user iD
$user_id = get_current_user_id();

// Count all posts published by user
$user_post_count = user_post_count($user_id);
$count_posts_submitted = $user_post_count['count_posts_submitted'];
$count_posts_new = $user_post_count['count_posts_new'];
$count_posts_old = $user_post_count['count_posts_old'];
$count_posts_active = $user_post_count['count_posts_active'];
$count_posts_given = $user_post_count['count_posts_given'];
$count_posts_booked = $user_post_count['count_posts_booked'];
$count_posts_locker = $user_post_count['count_posts_locker'];
$count_posts_removed = $user_post_count['count_posts_removed'];
$count_posts_archived = $user_post_count['count_posts_archived'];
$count_posts_paused = $user_post_count['count_posts_paused'];
$count_posts_disappeared = $user_post_count['count_posts_disappeared'];
$count_others_claimed = $user_post_count['count_others_claimed'];
$count_others_booked = $user_post_count['count_others_booked'];
$count_others_fetched = $user_post_count['count_others_fetched'];

//
$activity_url = home_url('/activity/');

?>

<p class="small">💡 Här hittar du samtliga annonser du skapat och paxat.</p>
<h7>💚 Mina annonser</h7>
<div class="columns"><div class="column1">↓ <?php echo $count_posts_submitted; ?> annons<?php if ($count_posts_submitted !== 1) { echo "er"; } ?></div>
<div class="column2"></div></div>
<hr>
<?php if ($count_posts_submitted > 0) : ?>
<!--Output list of post types-->
<?php if ($count_posts_new > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'new',
]), $admin_url) ); ?>"><span class="big-link">⏳ <?php echo $count_posts_new; ?> väntar på lottning</span></a></p>
<?php endif; ?>
<?php if ($count_posts_old > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'old',
]), $admin_url) ); ?>"><span class="big-link">🟢 <?php echo $count_posts_old; ?> väntar på paxning</span></a></p>
<?php endif; ?>
<?php if ($count_posts_booked > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'booked',
]), $admin_url) ); ?>"><span class="big-link">💖 <?php echo $count_posts_booked; ?> är paxade</span></a></p>
<?php endif; ?>
<?php if ($count_posts_locker > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'locker',
]), $admin_url) ); ?>"><span class="big-link">⏹ <?php echo $count_posts_locker; ?> är i skåpet</span></a></p>
<?php endif; ?>
<?php if ($count_posts_given > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'fetched',
]), $admin_url) ); ?>"><span class="big-link">☑ <?php echo $count_posts_given; ?> är hämtade</span></a></p>
<?php endif; ?>
<?php if ($count_posts_removed > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'removed',
]), $admin_url) ); ?>"><span class="big-link">❌ <?php echo $count_posts_removed; ?> är borttagna</span></a></p>
<?php endif; ?>
<?php if ($count_posts_archived > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'archived',
]), $admin_url) ); ?>"><span class="big-link">⭕ <?php echo $count_posts_archived; ?> är arkiverade</span></a></p>
<?php endif; ?>
<?php if ($count_posts_paused > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'paused',
]), $admin_url) ); ?>"><span class="big-link">😎 <?php echo $count_posts_paused; ?> är pausade</span></a></p>
<?php endif; ?>
<?php if ($count_posts_disappeared > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'disappeared',
]), $admin_url) ); ?>"><span class="big-link">💢 <?php echo $count_posts_disappeared; ?> är försvunna</span></a></p>
<?php endif; ?>
<?php else : ?>
		<p>💢 Du har inte skapat några annonser ännu.</p>
		<p><span class="link"><a href="<?php esc_url(home_url( '/submit/')); ?>">💚 Ge bort</a></span> något nu</p>
<?php endif; ?>

<h3>❤ Mina paxningar</h3>
<div class="columns"><div class="column1">↓ <?php echo $count_others_claimed; ?> annons<?php if ($count_others_claimed !== 1) { echo "er"; } ?></div>
<div class="column2"></div></div>
<hr>
<?php if ($count_others_claimed > 0) : ?>
<?php if ($count_others_booked > 0) : ?>
<p>
	<a href="<?php echo esc_url( add_query_arg(array([
		'view' => 'posts-booked'
	]), $admin_url) ); ?>/activity/?view=posts-booked"><span class="big-link">💝 <?php echo $count_others_booked; ?> är paxade</span>
	</a>
</p>
<?php endif; ?>
<?php if ($count_others_fetched > 0) : ?>
<p>
	<a href="<?php echo esc_url( add_query_arg(array([
			'view' => 'posts-fetched',
		]), $admin_url) ); ?>"><span class="big-link">✅ <?php echo $count_others_fetched; ?> är hämtade</span>
	</a>
</p>
<?php endif; ?>
<?php else : ?>
		<p>💢 Du har inte paxat några annonser ännu.</p>
		<p>Ta en titt på <span class="link"><a href="<?php echo home_url(); ?>/things">🎁 Saker att få</a></span></p>
<?php endif; ?>
<?php wp_reset_postdata(); ?>