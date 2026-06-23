<?php
/**
 * Dynamic content for pages using url /user/?option=
 * 
 * Content is shared to "LOOPIS Theme HQ"
 */
?>

<?php get_header(); ?>

<div class="page-padding">
    <h1>👤 Min profil</h1>

<?php if ( is_user_logged_in() ) : 
    // Dynamic page loader - with pages inserted from LOOPIS Theme HQ
    $page_dir = LOOPIS_THEME_HQ_DIR . '/pages/user/';

    // Get the 'option' parameter from URL
    $page_option = isset($_GET['option']) ? sanitize_file_name($_GET['option']) : 'tabs';

    $php_file = $page_dir . $page_option . '.php';

    if (file_exists($php_file)) {
        include $php_file;
    } else {
        echo '<hr>';
        include LOOPIS_THEME_DIR . '/templates/access/loopis-404.php';
    }
    ?>
    <div class="clear"></div>

<?php else :
    // Not logged in message
    echo '<hr>';
    include LOOPIS_THEME_DIR . '/includes/output/access/only-user.php';
    include LOOPIS_THEME_DIR . '/templates/faq/questions-visitor.php';
endif; ?>

</div><!--page-padding-->

<?php get_footer();