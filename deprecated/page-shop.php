<?php
/**
 * Dynamic content for pages using url /shop/?option=
 */
?>

<?php get_header(); ?>

<div class="page-padding center">
<?php if ( is_user_logged_in() ) : 
    // Dynamic page loader
    $page_dir = get_template_directory() . '/pages/shop/';

    // Get the 'option' parameter from URL
    $page_option = isset($_GET['option']) ? sanitize_file_name($_GET['option']) : 'start';

    $php_file = $page_dir . $page_option . '.php';

    if (file_exists($php_file)) {
        include $php_file;
    } else {
        echo '<h1>🛒 Shoppen</h1><hr>';
        include LOOPIS_THEME_HQ_DIR . '/templates/access/loopis-404.php';
    }
    ?>
    <div class="clear"></div>

<?php else :
// Not logged in message
include LOOPIS_THEME_HQ_DIR . '/templates/access/role-message.php';
endif; ?>

</div><!--page-padding center-->

<?php get_footer();