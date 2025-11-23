<?php
/**
 * WPUM template for displaying the registration forms.
 *
 * Modified by LOOPIS.
 * But needs more modification because this page is currently modified in WP Admin on our live site!
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wpum-template wpum-form wpum-registration-form">

<h1>📋 Bli medlem</h1>
<hr>
<p>Välkommen till LOOPIS - föreningen för oss som vill ha en glad och hållbar framtid! 🙋</p>
<div class="wpum-message information">
<p>⚠ OBS! Du kan bara använda LOOPIS om du bor i eller nära Bagarmossen!</p>
</div>
<p><span class="link"><a href="/varfor-bagis">📌 Varför måste jag bo i Bagis?</a></span></p>

<h3>1⃣ Betala medlemsavgift</h3>
<hr>
<?php include LOOPIS_THEME_DIR . '/templates/general/swish-membership.php'; ?>

<h3>2⃣ Skapa konto</h3>
<hr>

<!-- WPUM default code below at the moment... -->
	<?php do_action( 'wpum_before_registration_form', $data ); ?>

	<form action="<?php echo esc_url( $data->action ); ?>" method="post" id="wpum-submit-registration-form" enctype="multipart/form-data">

		<?php foreach ( $data->fields as $key => $field ) : ?>

			<?php
			/**
			 * Hook to render form field. Always use conditional check to
			 * make sure the field type. Otherwise field would render multiple times.
			 *
			 * @var $field
			 */
			do_action( 'wpum_registration_form_field', $field, $key, $data->fields );
			?>

		<?php endforeach; ?>

		<input type="hidden" name="wpum_form" value="<?php echo esc_attr( $data->form ); ?>" />
		<input type="hidden" name="step" value="<?php echo esc_attr( $data->step ); ?>" />
		<?php wp_nonce_field( 'verify_registration_form', 'registration_nonce' ); ?>

		<?php do_action( 'wpum_before_submit_button_registration_form', $data ); ?>

		<?php
		$label = isset( $data->submit_label ) ? $data->submit_label : esc_html__( 'Register', 'wp-user-manager' );
		?>
		<input type="submit" name="submit_registration" class="button"
			   value="<?php echo esc_html( apply_filters( 'wpum_registration_form_submit_label', $label ) ); ?>"/>

	</form>

	<?php do_action( 'wpum_after_registration_form', $data ); ?>

</div>
