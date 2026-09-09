<?php
/**
 * Helper functions for loading mail templates
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function loopis_get_mail(){
    $absolute_path = LOOPIS_THEME_HQ_DIR . '/includes/functions/mail';
    if (is_dir($absolute_path)) {
        foreach (glob($absolute_path . '/*.php') as $file) {
            include_once $file;
        }
    } else {
        loopis_log_level1("LOOPIS Theme HQ failed to include folder: {$folder_name} ({$base_theme_dir})");
    }
}

function loopis_get_mail_templates(){
    include_once  LOOPIS_THEME_HQ_DIR . '/includes/functions/mail/loopis-mail-footer.php';
    include_once  LOOPIS_THEME_HQ_DIR . '/includes/functions/mail/loopis-mail-template.php';
    include_once  LOOPIS_THEME_HQ_DIR . '/includes/functions/mail/loopis-mail-headers.php';
}