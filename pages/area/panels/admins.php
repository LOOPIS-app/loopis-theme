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
<div class="wrapped link small" style="min-width:250px" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'admin', $area ) ); ?>'">
<h5><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/user_manager.png" alt="Admin" style="height:30px; width: auto; vertical-align: middle; margin-bottom: 4px;"> Admin</h5>

<p class="small">↓ <?php echo esc_html($count); ?> admins<span class="right blue">Läs mer →</span></p>
<hr>
<?php
foreach ($users as $user) {
    $user_first_name = get_user_meta($user->ID, 'first_name', true);
    $user_last_name = get_user_meta($user->ID, 'last_name', true);
	echo '<p class="small">👤 ' . esc_html($user_first_name . ' ' . $user_last_name) . '</p>';
}
?>
</div> 