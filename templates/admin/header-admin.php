<!DOCTYPE html> 
<html <?php language_attributes(); ?>>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--Meta tags-->
	<title><?php echo get_the_title(); ?> - <?php echo bloginfo('name'); ?></title>
	<!--Indexing-->
	<meta name="robots" content="noindex, nofollow">
	<!--Viewport-->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<!--Fonts-->
	<?php include_once LOOPIS_THEME_DIR . '/assets/fonts/google-fonts.php'; ?>
	<?php include_once LOOPIS_THEME_DIR . '/assets/fonts/font-awesome.php'; ?>
	<!--Favicon-->
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
	<link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg">
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="manifest" href="/favicon/site.webmanifest">
	<meta name="theme-color" content="#ffffff">
	<!--Facebook-->
	<meta name="facebook-domain-verification" content="o8yh0nqrbcgnedkvjei7g0imjwzen9">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>

<div id="wrapper">
	<header id="header">
		<div class="group">
			<div class="header-back" onclick="history.back()"><i class="fas fa-chevron-left"></i></div>
			<a href="<?php echo esc_url( home_url('/admin/') ); ?>"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/LOOPIS_logo_admin.png" alt="LOOPIS-logo" id="header-img"></a>
			<div class="header-faq" onclick="location.href='<?php echo esc_url( home_url('/') ); ?>'">🌈</div>
			</div><!--/group-->
		</header><!--/#header-->
	<div class="container" >