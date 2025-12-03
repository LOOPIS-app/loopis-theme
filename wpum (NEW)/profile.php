<?php
/**
 * WPUM template for displaying the profile page.
 *
 * Modified by LOOPIS.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wpum-template wpum-profile-page" id="wpum-profile">

	<?php do_action( 'wpum_before_profile' ); ?>

	<div id="profile-header-container">
		<?php
			WPUM()->templates
				->set_template_data( array(
					'user'            => $data->user,
					'current_user_id' => $data->current_user_id,
				) )
				->get_template_part( 'profiles/cover' );

			WPUM()->templates
				->set_template_data( array(
					'user'            => $data->user,
					'current_user_id' => $data->current_user_id,
				) )
				->get_template_part( 'profiles/header' );

			?>
	</div>

	<div id="profile-tab-content" style="margin-top:15px;">
		<?php
		$active_tab = wpum_get_active_profile_tab();
		WPUM()->templates->set_template_data( array(
			'user'            => $data->user,
			'current_user_id' => $data->current_user_id,
		) )->get_template_part( "profiles/{$active_tab}" );

		do_action( 'wpum_profile_page_content_' . $active_tab, $data, $active_tab );
		?>
	</div>

	<div class="wpum_clearfix"></div>

	<?php do_action( 'wpum_after_profile' ); ?>

</div>
