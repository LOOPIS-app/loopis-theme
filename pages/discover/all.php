<?php
/**
 * Discover: ALL PAGES
 * Links to all php-files in the "discover" directory.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Check access
if (current_user_can('loopis_admin')) : ?>

<h1>♻ Upptäck - alla filer</h1>
<hr>
<p class="small">💡 Lista över alla php-filer i mappen "discover"</p>

<?php
// Include generic function
include_once LOOPIS_THEME_DIR . '/includes/functions/admin-extra/list_php_files.php';

// Specify the directory to scan
$content_dir = LOOPIS_THEME_DIR . '/pages/discover/';

// Use the included generic function
loopis_list_php_files($content_dir, 'view');
?>

<!-- NO ACCESS -->
<?php else : ?>
    <?php include_once LOOPIS_THEME_DIR . '/templates/access/admin-only-page.php'; ?>
<?php endif; ?>