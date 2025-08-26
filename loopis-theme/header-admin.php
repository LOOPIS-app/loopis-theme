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
	<link rel="manifest" href="/favicon/site.webmanifest">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
	<link rel="icon" type="image/png" sizes="192x192" href="/favicon/android-chrome-192x192.png">
	<link rel="icon" type="image/png" sizes="512x512" href="/favicon/android-chrome-512x512.png">
	<link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#ffffff">
	<meta name="theme-color" content="#ffffff">
	<!--Microsoft fixes-->
	<meta name="msapplication-config" content="/favicon/browserconfig.xml">
	<meta name="msapplication-TileImage" content="/favicon/mstile-150x150.png">
	<meta name="msapplication-TileColor" content="#00a300">
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
			<a href="/admin"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/LOOPIS_logo_admin.png" alt="LOOPIS-logo" id="header-img"></a>
			<div class="header-faq" onclick="location.href='/'">🌈</div>
			</div><!--/group-->
		</header><!--/#header-->
	<div class="container" >