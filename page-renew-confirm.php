<?php
/**
 * Content for page using url /renew
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

<h1>🙏 Tack!</h1>
<hr>
<p class="small">💡 Dina uppgifter är uppdaterade.</p>

<p>Tryck på knappen för att aktivera ditt medlemskap - sen kan du börja loopa prylar igen!</p>

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

    </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>