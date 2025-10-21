<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--Meta tags-->
	<?php
	// Set up variables for meta tags and images based on context
	if (is_home() || is_front_page()) {
		$title = get_bloginfo('name') . '.app';
		$description = 'Ge & få saker av dina grannar – för en gladare och mer hållbar framtid.';
		$meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
		$og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
		$og_type = 'website';
		$og_url = home_url('/');
	} elseif (is_single() || is_page()) {
		$title = get_the_title() . ' - ' . get_bloginfo('name');
		$description = get_the_excerpt();
		$meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
		$og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
		if (has_post_thumbnail()) {
			$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
			if ($thumbnail) $og_image = $thumbnail[0];
		}
		$og_type = 'article';
		$og_url = get_permalink();
	} elseif (is_author()) {
		$author = get_queried_object();
		$title = esc_html($author->display_name) . ' - ' . get_bloginfo('name');
		$description = esc_html($author->description);
		$meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
		$og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
		$og_type = 'profile';
		$og_url = get_author_posts_url($author->ID);
	} elseif (is_tag()) {
		$title = '#' . single_tag_title('', false) . ' - ' . get_bloginfo('name');
		$description = 'Kategorier på LOOPIS.app';
		$meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
		$og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
		$og_type = 'website';
		$og_url = get_tag_link(get_queried_object_id());
	} elseif (is_archive()) {
		$title = get_bloginfo('name');
		$description = 'För en gladare och mer hållbar framtid.';
		$meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
		$og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
		$og_type = 'website';
		$og_url = home_url('/');
	} else {
		$title = get_bloginfo('name');
		$description = 'För en gladare och mer hållbar framtid.';
		$meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
		$og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
		$og_type = 'website';
		$og_url = home_url('/');
	}
	?>
	<title><?php echo esc_html($title); ?></title>
	<meta name="description" content="<?php echo esc_attr($description); ?>">
	<meta name="keywords" content="LOOPIS, app, skåp, Bagis, Bagarmossen, second hand, bortskänkes, skänka, byta, prylar, gratis">
	<meta name="author" content="LOOPIS">
	<meta name="image" content="<?php echo esc_url($meta_image); ?>">
	<!--Indexing-->
	<?php if (is_author()) { echo '<meta name="robots" content="noindex, nofollow">'; } ?>
	<!--Viewport-->
	<?php if (is_single()) { echo '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1">'; }
	else { echo '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">'; } ?>
	<!--Fonts-->
	<?php include_once LOOPIS_THEME_DIR . '/assets/fonts/google-fonts.php'; ?>
	<?php include_once LOOPIS_THEME_DIR . '/assets/fonts/font-awesome.php'; ?>
	<!--Favicon-->
	<link rel="canonical" href="<?php echo esc_url($og_url); ?>">
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
	<!--Facebook/Open Graph-->
	<meta name="facebook-domain-verification" content="o8yh0nqrbcgnedkvjei7g0imjwzen9">
	<meta property="og:title" content="<?php echo esc_attr($title); ?>">
	<meta property="og:url" content="<?php echo esc_url($og_url); ?>">
	<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>.app">
	<meta property="og:type" content="<?php echo esc_attr($og_type); ?>">
	<meta property="og:description" content="<?php echo esc_attr($description); ?>">
	<meta property="og:image" content="<?php echo esc_url($og_image); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>

<div id="wrapper">
	<header id="header">
		<div class="group">
			<div class="header-back" onclick="history.back()"><i class="fas fa-chevron-left"></i></div>
			<a href="/"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/LOOPIS_logo.png" alt="LOOPIS-logo" id="header-img"></a>
			<?php if ( is_front_page() && current_user_can('loopis_admin') && !current_user_can('administrator') ) : ?>
				<div class="header-faq" onclick="location.href='/admin'">🐙</div>
			<?php else: ?>
				<div class="header-faq" onclick="location.href='/faq'">💡</div>
			<?php endif; ?>
			</div><!--/group-->
		</header><!--/#header-->
	<div class="container">