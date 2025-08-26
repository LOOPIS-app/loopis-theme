<?php
/**
 * Show specific interaction for post category 'storage'.
 *
 * Used in comments.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="admin-block">
	<?php include_once LOOPIS_THEME_DIR . '/assets/output/admin/admin-label.php'; ?>
	<?php $event_name = function_exists('loopis_get_setting') ? loopis_get_setting('event_name', '📍 Inget event angivet') : '📍 Inget event angivet'; ?>
	<p class="small">💡 Du har behörighet att markera hämtning på: <?php echo $event_name; ?></p>
	<?php $member_users = get_users(array('role' => 'member')); ?>	
	<form method="post" class="arb" action="" style="display: flex; align-items: center;">
    	<select id="selected_member" name="selected_member" style="max-width:185px; margin-right: 10px;">
        <option value="">❤ Välj medlem</option>
        <?php foreach ($member_users as $member_user) { ?>
		<option value="<?php echo $member_user->ID; ?>"><?php echo $member_user->display_name; ?></option>
        <?php } ?>
    	</select>
    <button name="book_storage" type="submit" class="blue" style="display:none;">☑ Hämtad</button>
	</form>
	<p class="info">Välj den medlem som hämtar.</p>
		
	<?php if(isset($_POST['book_storage']) && isset($_POST['selected_member'])) { $user_id = $_POST['selected_member']; admin_action_book_storage($user_id, $post_id); } ?>

	<!-- Hide button until a member is chosen -->
	<script>
	document.getElementById('selected_member').addEventListener('change', function() {
    var bookButton = document.querySelector('button[name="book_storage"]');
    if (this.value !== '') { bookButton.style.display = 'inline-block'; } 
    else { bookButton.style.display = 'none'; } });
	</script>
</div>