<?php
/**
 * Overview for area pages
 *
 * Dynamic content of page-area.php
 * Reached on /area (this view is set as default)
 */

if (!defined('ABSPATH')) {
    exit;
}
// Set the base URL for area views
$area = home_url('/area/');
?>

<h1>🛟 <?php echo esc_html(get_bloginfo('name')); ?></h1>
<hr>
<p class="small">💡 Information och support för ditt område.</p>

<p>Här hittar du information och support för <?php echo esc_html(get_bloginfo('name')); ?>. Tillsammans ser vi till att LOOPIS funkar så bra som möjligt!</p>

<!-- Instructions -->
<?php include LOOPIS_THEME_DIR . '/pages/area/panels/instructions.php'; ?>

<!-- FAQ -->
<?php include LOOPIS_THEME_DIR . '/pages/area/panels/faq-examples.php'; ?>

<!-- Support -->
<?php include LOOPIS_THEME_DIR . '/pages/area/panels/support-latest.php'; ?>

<!-- News -->
<?php include LOOPIS_THEME_DIR . '/pages/area/panels/news-latest.php'; ?>

<!-- Admin -->
<?php include LOOPIS_THEME_DIR . '/pages/area/panels/admins.php'; ?>

<!-- Statistics -->
<!--?php include LOOPIS_THEME_DIR . '/pages/area/panels/stats.php'; ?-->
