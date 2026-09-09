<?php
/**
 * Dynamic content loader for pages using url /area/?view=
 */
?>

<?php get_header(); ?>

    <div class="page-padding">
        <?php 

        // Access check (local members + administrator only)
        if (current_user_can('member') || current_user_can('manage_options')) :

        // Dynamic content loader
        $content_dir = get_template_directory() . '/pages/area/';

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
            echo '<h1>📍 ' . esc_html(get_bloginfo('name')) . '</h1>';
            echo '<p>💢 Filen hittades inte: <b>' . esc_html($php_file) . '</b></p>';
        }
        ?>

        <div class="clear"></div>

    <!-- NO ACCESS -->
<?php else : ?>
    <h1>📍 <?php echo esc_html(get_bloginfo('name')); ?></h1>
    <hr>
    <?php include LOOPIS_THEME_DIR . '/includes/output/access/only-member.php'; ?>

<?php endif; ?>

</div><!--page-padding-->

<?php get_footer(); ?>