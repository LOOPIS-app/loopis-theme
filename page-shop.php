<?php
/**
 * Dynamic content for pages using url /shop/?option=
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

<?php
    // Dynamic page loader
    $page_dir = get_template_directory() . '/pages/shop/';

    // Get the 'option' parameter from URL
    $page_option = isset($_GET['option']) ? sanitize_file_name($_GET['option']) : 'coins';

    $php_file = $page_dir . $page_option . '.php';

    if (file_exists($php_file)) {
        include $php_file;
    } else {
      echo '<div class="content"><div class="page-padding">';
        echo '<h1>🛒 Shoppen</h1><hr>';
        echo '<p>💢 Filen hittades inte: <b>' . esc_html($php_file) . '</b></p>';
        echo '</div></div>';
    }  ?>
        <div class="clear"></div>

    </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>