<?php
/**
 * WPUM template for displaying the login form.
 *
 * Modified by LOOPIS.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$referrer = wp_get_referer();
$redirect_to = filter_input( INPUT_GET, 'redirect_to' );
if ( $redirect_to ) {
	$referrer = wp_validate_redirect( esc_url( $redirect_to ) );
}
?>

<div class="wpum-template wpum-form">

<h1>👤 Logga in</h1>
<hr>

<?php do_action( 'wpum_before_login_form' ); ?>

	<form action="<?php echo esc_url( $data->action ); ?>" method="post" id="wpum-submit-login-form" enctype="multipart/form-data">

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
					</label>

				<?php else : ?>

					<label for="<?php echo esc_attr( $key ); ?>">
						<?php echo esc_html( $field['label'] ); ?>
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
		<input type="hidden" name="submit_referrer" value="<?php echo esc_url( $referrer ); ?>" />

		<?php do_action( 'wpum_before_submit_button_login_form' ); ?>

		<input type="submit" name="submit_login" class="button" value="<?php esc_html_e( 'Logga in', 'wp-user-manager' ); ?>" />

	</form>

<p class="info">Glömt ditt lösenord?&nbsp;<a href="<?php echo esc_url( get_permalink( wpum_get_core_page_id( 'password' ) ) ); ?>">
		<?php echo esc_html( apply_filters( 'wpum_password_link_label', __( 'Tryck här.', 'wp-user-manager' ) ) ); ?>
	</a></p>

<div class="wpum-message information">
<p>Bara medlemmar i föreningen LOOPIS kan logga in.</p>
<p><span class="big-link"><a href="/register">📋 Bli medlem</a></span></p>
<p><span class="link"><a href="/faq/varfor-medlemskap">📌 Varför måste jag vara medlem?</a></span></p>
</div>

	<?php do_action( 'wpum_after_login_form' ); ?>

</div>