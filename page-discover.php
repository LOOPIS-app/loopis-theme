<?php
/**
 * Dynamic content for pages using url /discover/?view=
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

        <?php
        // Dynamic content loader
        $content_dir = get_template_directory() . '/pages/discover/';

        // Get 'view' parameter from URL (default to 'start')
        $content_name = isset($_GET['view']) ? sanitize_file_name($_GET['view']) : 'start';
        
        // Additional sanitization - only allow alphanumeric, dash, underscore
        $content_name = preg_replace('/[^a-zA-Z0-9_-]/', '', $content_name);
        
        // Prevent empty string after sanitization
        if (empty($content_name)) {
            $content_name = 'start';
        }
        
        // Define the full path to the PHP file
        $php_file = $content_dir . $content_name . '.php';

        // Check if file exists and is actually a file (not a directory)
        if (file_exists($php_file) && is_file($php_file)) {
            include $php_file;
        } else {
            echo '<h1>♻ Upptäck</h1><hr>';
            echo '<p>💢 Filen hittades inte: <b>' . esc_html($php_file) . '</b></p>';
        }
        ?>

        <div class="clear"></div>

    </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>