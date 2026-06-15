<?php
/**
 * User page member form.
 * 
 * Dynamic content of page-user.php
 * Reached on /user/?option=member-form
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get current user iD
$user_id = get_current_user_id();
$user = wp_get_current_user();

// Include member form
include LOOPIS_THEME_DIR . '/templates/forms/member-form.php'; ?>

<p>Läs hur föreningen hanterar dina uppgifter: <span class="big-link"><a href="<?php echo esc_url(home_url('/privacy/')); ?>">🗄 Integritet</a></span></p>