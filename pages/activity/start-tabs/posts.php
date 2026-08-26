<?php
/**
 * Posts tab.
 * 
 * Showing all posts for the current user.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
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

<h7>💚 Mina annonser</h7>
<div class="columns"><div class="column1">↓ <?php echo $count_posts_submitted; ?> annons<?php if ($count_posts_submitted !== 1) { echo "er"; } ?></div>
<div class="column2"><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'all',
]), $activity_url) ); ?>">→ Visa alla</a></div></div>
<hr>
<?php if ($count_posts_submitted > 0) : ?>
<!--Output list of post types-->
<?php if ($count_posts_new > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'new',
]), $activity_url) ); ?>"><span class="big-link">⏳ <?php echo $count_posts_new; ?> väntar på lottning</span></a></p>
<?php endif; ?>
<?php if ($count_posts_old > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'old',
]), $activity_url) ); ?>"><span class="big-link">🟢 <?php echo $count_posts_old; ?> väntar på paxning</span></a></p>
<?php endif; ?>
<?php if ($count_posts_booked > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'booked',
]), $activity_url) ); ?>"><span class="big-link">💖 <?php echo $count_posts_booked; ?> är paxade</span></a></p>
<?php endif; ?>
<?php if ($count_posts_locker > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'locker',
]), $activity_url) ); ?>"><span class="big-link">⏹ <?php echo $count_posts_locker; ?> är i skåpet</span></a></p>
<?php endif; ?>
<?php if ($count_posts_given > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'fetched',
]), $activity_url) ); ?>"><span class="big-link">✅ <?php echo $count_posts_given; ?> är lämnade</span></a></p>
<?php endif; ?>
<?php if ($count_posts_removed > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'removed',
]), $activity_url) ); ?>"><span class="big-link">❌ <?php echo $count_posts_removed; ?> är borttagna</span></a></p>
<?php endif; ?>
<?php if ($count_posts_archived > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'archived',
]), $activity_url) ); ?>"><span class="big-link">⭕ <?php echo $count_posts_archived; ?> är arkiverade</span></a></p>
<?php endif; ?>
<?php if ($count_posts_paused > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'paused',
]), $activity_url) ); ?>"><span class="big-link">😎 <?php echo $count_posts_paused; ?> är pausade</span></a></p>
<?php endif; ?>
<?php if ($count_posts_disappeared > 0) : ?>
<p><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-submitted',
	'status' => 'disappeared',
]), $activity_url) ); ?>"><span class="big-link">💢 <?php echo $count_posts_disappeared; ?> är försvunna</span></a></p>
<?php endif; ?>
<?php else : ?>
		<p>💢 Du har inte skapat några annonser ännu.</p>
<?php endif; ?>

<h3>❤ Mina paxningar</h3>
<div class="columns"><div class="column1">↓ <?php echo $count_others_claimed; ?> annons<?php if ($count_others_claimed !== 1) { echo "er"; } ?></div>
<div class="column2"><a href="<?php echo esc_url( add_query_arg(array([
	'view' => 'posts-booked',
	'status' => 'all',
]), $activity_url) ); ?>">→ Visa alla</a></div></div>
<hr>
<?php if ($count_others_claimed > 0) : ?>
<?php if ($count_others_booked > 0) : ?>
<p>
	<a href="<?php echo esc_url( add_query_arg(array([
		'view' => 'posts-booked'
	]), $activity_url) ); ?>"><span class="big-link">💝 <?php echo $count_others_booked; ?> är paxade</span>
	</a>
</p>
<?php endif; ?>
<?php if ($count_others_fetched > 0) : ?>
<p>
	<a href="<?php echo esc_url( add_query_arg(array([
			'view' => 'posts-fetched',
		]), $activity_url) ); ?>"><span class="big-link">☑ <?php echo $count_others_fetched; ?> är hämtade</span>
	</a>
</p>
<?php endif; ?>
<?php else : ?>
		<p>💢 Du har inte paxat några annonser ännu.</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>

<?php
// Query: Current raffles
$args = array(
    'date_query' => array(
        array(
            'after'     => '3 days ago',
            'inclusive' => true,
        ),
    ),
    'posts_per_page' => 100, // Limit to prevent massive queries
    'fields'     => 'ids',
    'no_found_rows' => true, // Don't calculate total rows
    'update_post_meta_cache' => false, // Don't load post meta yet
    'update_post_term_cache' => false, // Don't load categories yet
    'meta_query' => array(
        array(
            'key'     => 'participants',
            'compare' => 'EXISTS',
        ),
    ),
);

// Query
$the_query = new WP_Query($args);
$matching_posts = array();

// Filter posts to check user_id and not index (solution by Poe)
if ($the_query->have_posts()) {
    foreach ($the_query->posts as $post_id) {
        $participants = get_post_meta($post_id, 'participants', true);
        if (!empty($participants)) {
            $participants_array = maybe_unserialize($participants);
            if (is_array($participants_array) && in_array($user_id, $participants_array)) {
                $matching_posts[] = $post_id;
            }
        }
    }
}

// Clean up first query
wp_reset_postdata();

// Now get full post data ONLY for matching posts
if (!empty($matching_posts)) {
    $final_query = new WP_Query(array(
        'post__in' => $matching_posts,
        'posts_per_page' => -1,
        'orderby' => 'post__in',
    ));
}

// Output
$count = count($matching_posts);
?>

<!--Output-->
<h3>🎲 Aktuella lottningar</h3>
<div class="columns"><div class="column1">↓ <?php echo $count; ?> lottningar</div>
<div class="column2"></div></div>
<hr>

<div class="post-list">

<?php if (!empty($matching_posts) && $final_query->have_posts()): ?>

<?php while ($final_query->have_posts()) : $final_query->the_post(); ?>

    <div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
        <div class="post-list-post-thumbnail">
            <?php 
            if (has_post_thumbnail()) {
                the_post_thumbnail('thumbnail');
            }
            ?>
        </div>
        <div class="post-list-post-title">
            <?php the_title(); ?>
        </div>
        <div class="post-list-post-meta">
            <span><?php 
            if (in_category('new')) { 
                the_category(' '); 
                echo raffle_time(); 
            } else {
                $fetcher = get_post_meta(get_the_ID(), 'fetcher', true);
                if ($fetcher == $user_id) { 
                    echo '🥳 Du vann!'; 
                } else { 
                    echo '💔 Du vann tyvärr inte'; 
                } 
            } 
            ?></span>
            <span class="right"><i class="fas fa-arrow-alt-circle-up"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> sen</span>
        </div>
    </div>        
<?php endwhile; ?>

<?php else : ?>
    <p>💢 Du har inte deltagit i några lottningar nyligen.</p>
<?php endif; ?>

</div> <!--post-list-->

<?php wp_reset_postdata(); ?>