<?php
/**
 * Shortcode for a membership renewal button.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */


/**
 *  Shortcode: [loopis_member_renew]
 * 
 *  Membership renewal button.
 * 
 * @return string ob_get_clean()
 */
add_shortcode( 'loopis_member_renew', function () {
	ob_start();
	?>

	<?php if ( current_user_can('member_earlier')) : ?>
		<?php 
		if(isset($_POST['renew'])) { 
			$current_user = wp_get_current_user();
			$current_user->set_role('member');
			wp_update_user($current_user->ID);
			?>
			<script>
				window.location.href = '<?php echo home_url(); ?>';
			</script>
			<?php
			exit;
		} ?>
		<form method="post" class="arb" action="">
			<button name="renew" type="submit" class="green">Aktivera</button>
		</form>
	<?php endif; ?>

	<?php
	return ob_get_clean();
} );
