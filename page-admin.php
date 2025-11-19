<?php
/**
 * Template for dynamic output of admin content.
 */

// Check access
if (current_user_can('loopis_admin')) : ?>

    <?php include LOOPIS_THEME_DIR . '/templates/admin/header-admin.php'; ?>

    <div class="admin-content">
        <div class="page-padding">

            <?php
            // Dynamic admin page loader
            $content_dir = get_template_directory() . '/pages/admin/';

            // Get 'view' parameter from URL (default to 'start')
            $content_name = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'start';
            
            // Additional sanitization - only allow alphanumeric, dash, underscore, and forward slash
            $content_name = preg_replace('/[^a-zA-Z0-9_\/-]/', '', $content_name);
            
            // Prevent directory traversal
            $content_name = str_replace(['../', '..\\', './'], '', $content_name);
            
            // Remove leading/trailing slashes
            $content_name = trim($content_name, '/');
            
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
                echo '<h1>🐙 Admin</h1><hr>';
                echo '<p>💢 Filen hittades inte: <b>' . esc_html($php_file) . '</b></p>';
            }
            ?>

            <div class="clear"></div>

        </div><!--page-padding-->
    </div><!--admin-content-->

    <?php include LOOPIS_THEME_DIR . '/templates/admin/footer-admin.php'; ?>

<!-- NO ACCESS -->
<?php else : ?>
    <?php include_once LOOPIS_THEME_DIR . '/templates/access/admin-only.php'; ?>
<?php endif; ?>