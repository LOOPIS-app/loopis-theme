<?php
/**
 * Admin: ALL CONTENT
 * Links to all php-files in the "admin" directory.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🐙 Admin - alla filer</h1>
<hr>
<p class="small">💡 Lista över alla php-filer i mappen "admin"</p>

<?php
// Include generic function
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/list_php_files.php';

// Specify the directory to scan
$content_dir = LOOPIS_THEME_DIR . '/pages/admin/';

// Use the included generic function
loopis_list_php_files($content_dir, 'view');
?>