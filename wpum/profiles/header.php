<?php
/**
 * The Template for displaying the profile intro details.
 *
 * Modified by LOOPIS.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="header-profile-details">
		<h1>👤 Min profil</h1>
	<div id="profile-navigation">
		<?php
			WPUM()->templates
				->set_template_data( array(
					'user'            => $data->user,
					'current_user_id' => $data->current_user_id,
					'tabs'            => wpum_get_registered_profile_tabs(),
				) )
				->get_template_part( 'profiles/navigation' );
			?>
	</div>
</div>
