<?php
/**
 * The Template for displaying the action links within forms.
 *
 * Not yet modified by LOOPIS.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset( $data->scalar ) ) {
	return;
}

?>

<ul class="wpum-action-links">
	<?php if ( isset( $data->login_link ) && ( true === $data->login_link || 'yes' === $data->login_link ) ) : ?>
	<li>
		<?php
		// translators: %s login page url
		echo wp_kses_post( apply_filters( 'wpum_login_link_label', sprintf( __( 'Already have an account? <a href="%s">Sign In &raquo;</a>', 'wp-user-manager' ), esc_url( get_permalink( wpum_get_core_page_id( 'login' ) ) ) ) ) );
		?>
	</li>
	<?php endif; ?>
	<?php if ( isset( $data->register_link ) && ( true === $data->register_link || 'yes' === $data->register_link ) ) : ?>
	<li>
		<?php
		// translators: %s registration page url
		echo wp_kses_post( apply_filters( 'wpum_registration_link_label', sprintf( __( 'Don\'t have an account? <a href="%s">Signup Now &raquo;</a>', 'wp-user-manager' ), esc_url( get_permalink( wpum_get_core_page_id( 'register' ) ) ) ) ) );
		?>
	</li>
	<?php endif; ?>
	<?php if ( isset( $data->psw_link ) && ( 'yes' === $data->psw_link || true === $data->psw_link ) ) : ?>
	<li>
		<a href="<?php echo esc_url( get_permalink( wpum_get_core_page_id( 'password' ) ) ); ?>">
			<?php echo esc_html( apply_filters( 'wpum_password_link_label', __( 'Lost your password?', 'wp-user-manager' ) ) ); ?>
		</a>
	</li>
	<?php endif; ?>
</ul>
