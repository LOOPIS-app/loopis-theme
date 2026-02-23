<?php
/**
 * The Template for displaying the post forms.
 *
 * Modified by LOOPIS.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

global $post;
?>
<h1>🖊 Redigera annons</h1>
<hr>
<div class="post-wrapper-form">
<div class="wpum-template wpum-form wpum-post-form">

	<?php do_action( 'wpumfr_before_post_form', $data ); ?>

	<form action="<?php echo esc_url( $data->action ); ?>" method="post" id="wpum-submit-post-form" enctype="multipart/form-data">
		<?php foreach ( $data->fields as $key => $field ) :
			if ( isset( $post ) ):
				$field['value'] = apply_filters( 'wpumfr_post_form_field_value', '', $key, $post, $field );
			endif;

			/**
			 * Hook to render form field. Always use conditional check to
			 * make sure the field type. Otherwise field would render multiple times.
			 *
			 * @var $field
			 */
			do_action( 'wpumfr_post_form_field', $field, $key, true );
			?>

		<?php endforeach; ?>

		<?php do_action( 'wpumfr_after_post_form_fields', $data ); ?>

		<input type="hidden" name="wpum_form" value="<?php echo $data->form; ?>" />
		<input type="hidden" name="wpum_post" value="<?php echo $data->form_id; ?>" />
		<input type="hidden" name="step" value="<?php echo esc_attr( $data->step ); ?>" />
		<input type="hidden" name="update_post" value="<?php echo isset( $post ) ? $post->ID : ''; ?>" />
		<?php wp_nonce_field( 'verify_post_form', 'post_nonce' ); ?>
		<?php do_action( 'wpumfr_before_submit_button_post_form', $data ); ?>
		<input type="submit" name="submit_registration" class="button" value="<?php echo esc_html( apply_filters( 'wpumfr_edit_post_form_save_label', __( 'Save', 'wpum-frontend-posting' ) ) ); ?>" />

	</form>

	<?php do_action( 'wpumfr_after_post_form', $data ); ?>

</div>
</div>