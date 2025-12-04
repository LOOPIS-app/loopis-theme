<?php
// filepath: /Users/mbp/Documents/PROJEKT.../LOOPIS/_APPEN/_GIT/loopis-theme/functions/admin-extra/list_php_files.php
/**
 * Generic function to list PHP files in a directory
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper function to recursively get PHP files
 * 
 * @param string $dir Directory to scan
 * @param string $base_dir Base directory for relative paths
 * @return array Array of file information
 */
function loopis_get_php_files_recursive($dir, $base_dir = '') {
    $files = array();
    
    if (!is_dir($dir)) {
        return $files;
    }
    
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $full_path = $dir . '/' . $item;
        $relative_path = $base_dir ? $base_dir . '/' . $item : $item;
        
        if (is_dir($full_path)) {
            // Recursively get files from subdirectories
            $subfiles = loopis_get_php_files_recursive($full_path, $relative_path);
            $files = array_merge($files, $subfiles);
        } elseif (is_file($full_path) && pathinfo($full_path, PATHINFO_EXTENSION) === 'php') {
            // Keep .php extension for display but remove for view parameter
            $view_name = str_replace('.php', '', $relative_path);
            $display_name = $relative_path; // Keep full filename with .php
            // Count directory depth for indentation
            $depth = substr_count($view_name, '/');
            $files[] = array(
                'view' => $view_name,
                'display_name' => $display_name,
                'name' => basename($item, '.php'),
                'path' => $relative_path,
                'depth' => $depth
            );
        }
    }
    
    return $files;
}

/**
 * Main function to list PHP files in a directory
 * 
 * @param string $content_dir Directory path to scan
 * @param string $url_param URL parameter name for links (default: 'view')
 * @return void
 */
function loopis_list_php_files($content_dir, $url_param = 'view') {
    // Get all PHP files
    $php_files = loopis_get_php_files_recursive($content_dir);

    // Sort files alphabetically
    usort($php_files, function($a, $b) {
        return strcmp($a['view'], $b['view']);
    });
    
    ?>
    <div class="columns">
        <div class="column1">↓ <?php echo count($php_files); ?> filer</div>
        <div class="column2"></div>
    </div>
    <hr>

    <!-- Links output -->
    <div class="file-list">
        <?php if (!empty($php_files)) : ?>
            <?php foreach ($php_files as $file) : ?>
                <p style="margin-left: <?php echo ($file['depth'] * 20); ?>px;">
                    <a href="?<?php echo esc_attr($url_param); ?>=<?php echo esc_attr($file['view']); ?>">
                        <span class="big-label">📄 <?php echo esc_html($file['display_name']); ?></span>
                    </a>
                </p>
            <?php endforeach; ?>
        <?php else : ?>
            <p>💢 Inga PHP-filer hittades.</p>
        <?php endif; ?>
    </div><!--file-list-->
    <?php
}