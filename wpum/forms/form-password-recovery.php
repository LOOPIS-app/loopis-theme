<?php
/**
 * WPUM template for displaying the password recovery form.
 *
 * Modified by LOOPIS.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wpum-template wpum-form wpum-password-recovery-form">

	<?php do_action( 'wpum_before_password_recovery_form' ); ?>

	<form action="<?php echo esc_url( $data->action ); ?>" method="post" id="wpum-submit-password-recovery-form" enctype="multipart/form-data">

		<?php foreach ( $data->fields as $key => $field ) : ?>
			<fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">

				<?php if ( 'checkbox' === $field['type'] ) : ?>

					<label for="<?php echo esc_attr( $key ); ?>">
						<span class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
							<?php
							// Add the key to field.
							$field['key'] = $key;
							WPUM()->templates
								->set_template_data( $field )
								->get_template_part( 'form-fields/' . $field['type'], 'field' );
							?>
						</span>
						<?php echo esc_html( $field['label'] ); ?>
						<?php if ( isset( $field['required'] ) && $field['required'] ) : ?>
							<span class="wpum-required">*</span>
						<?php endif; ?>
					</label>

				<?php else : ?>

					<label for="<?php echo esc_attr( $key ); ?>">
						<?php echo esc_html( $field['label'] ); ?>
						<?php if ( isset( $field['required'] ) && $field['required'] ) : ?>
							<span class="wpum-required">*</span>
						<?php endif; ?>
					</label>
					<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
						<?php
						// Add the key to field.
						$field['key'] = $key;
						WPUM()->templates
							->set_template_data( $field )
							->get_template_part( 'form-fields/' . $field['type'], 'field' );
						?>
					</div>

				<?php endif; ?>

			</fieldset>
		<?php endforeach; ?>

		<input type="hidden" name="wpum_form" value="<?php echo esc_attr( $data->form ); ?>" />
		<input type="hidden" name="step" value="<?php echo esc_attr( $data->step ); ?>" />
		<?php wp_nonce_field( 'verify_password_recovery_form', 'password_recovery_nonce' ); ?>

		<?php do_action( 'wpum_before_submit_button_password_recovery_form' ); ?>

		<input type="submit" name="submit_password_recovery" class="button" value="<?php esc_html_e( 'Skicka', 'wp-user-manager' ); ?>" />

	</form>
	
</div>
