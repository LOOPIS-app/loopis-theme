<?php
/**
 * The Template for displaying the profile intro details.
 *
 * This template can be overridden by copying it to yourtheme/wpum/profiles/intro.php
 *
 * HOWEVER, on occasion WPUM will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 1.0.0
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
