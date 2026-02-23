<?php
/**
 * Dynamic content for pages using url /submit/?option=
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

<?php
    // Dynamic page loader
    $page_dir = get_template_directory() . '/pages/submit/';

    // Get the 'option' parameter from URL
    $page_option = isset($_GET['option']) ? sanitize_file_name($_GET['option']) : 'start';

    $php_file = $page_dir . $page_option . '.php';

    if (file_exists($php_file)) {
        include $php_file;
    } else {
      echo '<div class="content"><div class="page-padding">';
        echo '<h1>💚 Ge bort</h1><hr>';
        echo '<p>💢 Filen hittades inte: <b>' . esc_html($php_file) . '</b></p>';
        echo '</div></div>';
    }

    // Closing divs for content & footer are added in dynamic files.