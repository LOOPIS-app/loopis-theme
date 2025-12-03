<?php
/**
 * WPUM template for displaying the header section of the emails.
 *
 * Modified by LOOPIS.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$header_img = wpum_get_option( 'email_logo' );
$heading    = $data->heading;

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo esc_html( get_bloginfo( 'name' ) ); ?></title>
	</head>
<body>
<div style="text-align: center;">
<h1 style="font-size: 24px;"><?php echo esc_html( $heading ); ?></h1>
</div>