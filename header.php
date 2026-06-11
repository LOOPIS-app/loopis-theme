<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php
	// Set up default meta tags and images
	$site_name = "LOOPIS";
	$blog_name = get_bloginfo('name');
	$title = $site_name . ' - ' . $blog_name;
	$description = 'Ge & få saker av dina grannar.';
	$og_type = 'website';
	$og_locale = str_replace('-', '_', get_locale());
	$og_image_width = '';
	$og_image_height = '';
	$og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
	$meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
	if (is_home() || is_front_page()) {
		// Frontpage
		$og_url = home_url('/');
	} elseif (is_singular()) {
		// Posts and pages
		$post_id = get_queried_object_id();
		$title = get_the_title($post_id) . ' - ' . $site_name;
		// Featured image?
		if (has_post_thumbnail($post_id)) {
			$thumbnail_id = get_post_thumbnail_id($post_id);
			$thumbnail_url = wp_get_attachment_url($thumbnail_id);
			if ($thumbnail_url) {
				$og_image = $thumbnail_url;

				$image_meta = wp_get_attachment_metadata($thumbnail_id);
				if (is_array($image_meta)) {
					if (!empty($image_meta['width'])) {
						$og_image_width = (string) $image_meta['width'];
					}
					if (!empty($image_meta['height'])) {
						$og_image_height = (string) $image_meta['height'];
					}
				}
			}
		}

		$og_type = 'article';
		$og_url = get_permalink($post_id);
	} elseif (is_author()) {
		// User profiles
		$author = get_queried_object();
		$title = esc_html($author->display_name) . ' - ' . $site_name;
		$og_type = 'profile';
		$og_url = get_author_posts_url($author->ID);
	} elseif (is_tag()) {
		// Tags
		$title = '#' . single_tag_title('', false) . ' - ' . $site_name;
		$og_url = get_tag_link(get_queried_object_id());
	} elseif (is_archive()) {
		// Archives
		$title = post_type_archive_title('', false) ?: $site_name;
		$og_url = home_url('/');
	} elseif (is_404()) {
		// 404 page
		$title = 'Hoppsan! - ' . $site_name;
		$og_url = home_url('/');
	} else {
		// Default
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
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
	<link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg">
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="manifest" href="/favicon/site.webmanifest">
	<meta name="theme-color" content="#ffffff">
	<!--Facebook/Open Graph-->
	<meta name="facebook-domain-verification" content="o8yh0nqrbcgnedkvjei7g0imjwzen9">
	<meta property="og:title" content="<?php echo esc_attr($title); ?>">
	<meta property="og:url" content="<?php echo esc_url($og_url); ?>">
	<meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
	<meta property="og:type" content="<?php echo esc_attr($og_type); ?>">
	<meta property="og:locale" content="<?php echo esc_attr($og_locale); ?>">
	<meta property="og:description" content="<?php echo esc_attr($description); ?>">
	<meta property="og:image" content="<?php echo esc_url($og_image); ?>">
	<meta property="og:image:secure_url" content="<?php echo esc_url($og_image); ?>">
	<?php if ($og_image_width !== '' && $og_image_height !== '') : ?>
	<meta property="og:image:width" content="<?php echo esc_attr($og_image_width); ?>">
	<meta property="og:image:height" content="<?php echo esc_attr($og_image_height); ?>">
	<?php endif; ?>
	<!--Twitter/X Cards-->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
	<meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">
	<meta name="twitter:url" content="<?php echo esc_url($og_url); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>

<div id="wrapper">
	<header id="header">
		<div class="group">
			<div class="header-back" onclick="history.back()"><i class="fas fa-chevron-left"></i></div>
			<!-- Multisite: Show site name below logo -->
			<a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/LOOPIS_logo.png" alt="LOOPIS-logo" id="header-logo"></a>
			<div class="header-area">📍<?php echo get_bloginfo('name'); ?></div>

			<!-- Profile/main site link -->
			<?php if (is_user_logged_in()) : ?>
				<div class="header-faq" onclick="location.href='<?php echo esc_url( network_home_url('/user/') ); ?>'">👤</div>
			<?php else: ?>
				<div class="header-faq" onclick="location.href='<?php echo esc_url( network_home_url() ); ?>'">🗺</div>
			<?php endif; ?>
			</div>
		</header>
		
	<div class="container">