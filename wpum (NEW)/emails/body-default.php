<?php
/**
 * WPUM template for displaying the body section of the emails.
 *
 * Modified by LOOPIS.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Determine the output of the content.
// If we're loading this file through the editor
// we'll show fake content so the user can edit it.
$output = '{email}';

if ( isset( $data->preview ) && true === $data->preview ) {
	$output = '<div style="padding: 10px;margin-bottom: 20px;text-align: center; font-size: 18px;background: #f5f5f5;border-radius: 10px">' . wpum_get_email_field( $data->email_id, 'content' ) . '</div>';
}

// {email} is replaced by the content entered in the customizer.
?>

<div style="padding: 10px;margin-bottom: 20px;text-align: center; font-size: 18px;background: #f5f5f5;border-radius: 10px">
<?php
echo $output; // phpcs:ignore
?>
</div>