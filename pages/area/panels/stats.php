<?php
/**
 * Output of area information.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Count the number of members on current subsite
$args = array(
	'role' => 'member',
	'blog_id' => get_current_blog_id(),
	'fields' => 'ID',
);
$users = get_users($args);
$count_users = count($users);

// Count the number of posts with category "fetched" on current subsite
$args = array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'category_name' => 'fetched',
	'posts_per_page' => -1,
);
$posts = get_posts($args);
$count_posts = count($posts);

// Output
?>

<div class="wrapped small" style="min-width:250px"><h5>📊 Statistik</h5>
<p>↓ Sedan start</p>
<hr>
<p>👤 Medlemmar: <?php echo $count_users; ?></p>
<p>🎁 Saker cirkulerade: <?php echo $count_posts; ?></p>
</div>