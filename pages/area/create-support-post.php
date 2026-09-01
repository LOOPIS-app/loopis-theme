<?php
/**
 * Page for creating a new support post.
 * 
 * Dynamic content of page-area.php
 * Reached on /area/?view=create-support-post
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🛟 Skapa supporttråd</h1>
<hr>
<p class="small">💡 Få hjälp från admin & andra medlemmar.</p>
 
<?php include LOOPIS_THEME_DIR . '/templates/forms/support-form.php'; ?>