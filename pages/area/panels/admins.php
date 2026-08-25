<?php
/**
 * Output of area information.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get all users with role "manager"
$users = get_users(array('role' => 'manager'));
$count = count($users); ?>

<!-- Output -->
<p class="small">↓ <?php echo esc_html($count); ?> admins</p>
<hr>
<?php
foreach ($users as $user) {
    $user_first_name = get_user_meta($user->ID, 'first_name', true);
    $user_last_name = get_user_meta($user->ID, 'last_name', true);
    $author_link = get_author_posts_url($user->ID);
	echo '<p class="small"><a href="' . esc_url($author_link) . '">👤 ' . esc_html($user_first_name . ' ' . $user_last_name) . '</a></p>';
}