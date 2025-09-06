<?php
/**
 * The Template for displaying the header section of the emails.
 *
 * This template can be overridden by copying it to yourtheme/wpum/emails/header-default.php
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