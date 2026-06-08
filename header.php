<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--Meta tags-->
	<?php
	// Set up variables for meta tags and images based on context
	$blog_name = get_bloginfo('name');
	$og_locale = str_replace('-', '_', get_locale());
	$og_image_width = '';
	$og_image_height = '';
	if (is_home() || is_front_page()) {
		$title = $blog_name . '.app';
		$description = 'Ge & få saker av dina grannar.';
		$meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
		$og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
		$og_type = 'website';
		$og_url = home_url('/');
	} elseif (is_singular()) {
        $post_id = get_queried_object_id();
        $title = get_the_title($post_id) . ' - ' . $blog_name;
        
        // Get excerpt, fallback to truncated content if no excerpt
        $description = get_the_excerpt($post_id);
        if (empty($description)) {
            $content = get_the_content($post_id);
            $description = wp_trim_words(wp_strip_all_tags($content), 30, '...');
        }
        
        $meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
        $og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
        
        if (has_post_thumbnail($post_id)) {
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'large');
            if ($thumbnail) {
                $og_image = $thumbnail[0];
                $meta_image = $thumbnail[0];
				$og_image_width = (string) $thumbnail[1];
				$og_image_height = (string) $thumbnail[2];
            }
        }
        
        $og_type = 'article';
        $og_url = get_permalink($post_id);
	} elseif (is_author()) {
		$author = get_queried_object();
		$title = esc_html($author->display_name) . ' - ' . $blog_name;
		$description = esc_html($author->description);
		$meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
		$og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
		$og_type = 'profile';
		$og_url = get_author_posts_url($author->ID);
	} elseif (is_tag()) {
		$title = '#' . single_tag_title('', false) . ' - ' . $blog_name;
		$description = 'Kategorier på LOOPIS.app';
		$meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
		$og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
		$og_type = 'website';
		$og_url = get_tag_link(get_queried_object_id());
	} elseif (is_archive()) {
		$title = post_type_archive_title('', false) ?: $blog_name;
        $description = 'För en glad och hållbar framtid.';
        $meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
        $og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
        $og_type = 'website';
        $og_url = home_url('/');
    } elseif (is_404()) {
        $title = 'Hoppsan! - ' . $blog_name;
        $description = 'Sidan kunde inte hittas.';
        $meta_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_app.png';
        $og_image = LOOPIS_THEME_URI . '/assets/img/LOOPIS_og.png';
        $og_type = 'website';
        $og_url = home_url('/');
    } else {
        $title = $blog_name;
        $description = 'För en glad och hållbar framtid.';
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
	<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>.app">
	<meta property="og:type" content="<?php echo esc_attr($og_type); ?>">
	<meta property="og:locale" content="<?php echo esc_attr($og_locale); ?>">
	<meta property="og:description" content="<?php echo esc_attr($description); ?>">
	<meta property="og:image" content="<?php echo esc_url($og_image); ?>">
	<?php if ($og_image_width !== '' && $og_image_height !== '') : ?>
	<meta property="og:image:width" content="<?php echo esc_attr($og_image_width); ?>">
	<meta property="og:image:height" content="<?php echo esc_attr($og_image_height); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>

<div id="wrapper">
	<header id="header">
		<div class="group">
			<div class="header-back" onclick="history.back()"><i class="fas fa-chevron-left"></i></div>
			<!-- Multisite: Show site name below logo -->
			<?php if ( is_multisite()) : ?>
			<a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/LOOPIS_logo.png" alt="LOOPIS-logo" id="header-logo"></a>
			<div class="header-area">📍<?php echo get_bloginfo('name'); ?></div>
			<?php else : ?>
			<a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/LOOPIS_logo.png" alt="LOOPIS-logo" id="header-img"></a>
			<?php endif; ?>
			<!-- Back + faq/admin link -->
			<?php if ( is_front_page() && current_user_can('loopis_admin') && !current_user_can('administrator') ) : ?>
				<div class="header-faq" onclick="location.href='<?php echo esc_url( home_url('/admin/') ); ?>'">🐙</div>
			<?php else: ?>
				<div class="header-faq" onclick="location.href='<?php echo esc_url( home_url('/faq/') ); ?>'">💡</div>
			<?php endif; ?>
			</div>
		</header>
		
	<div class="container">