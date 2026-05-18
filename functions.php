<?php
/**
 * Theme bootstrap for LOOPIS sub sites (aka. local apps)
 *
 * Loads all frontend core files.
 */

// Prevent direct access
if (!defined('ABSPATH')) { exit; }

// Only run in frontend (Guard to be enabled when theme functionality is strictly frontend only)
// if (is_admin()) { return; }

/** 
 * Define constants
 */

// Define theme version
define('LOOPIS_THEME_VERSION', '0.85'); // Update version number here + in style.css

// Define theme folder path constants
define('LOOPIS_THEME_DIR', get_template_directory());       // Server-side path to /wp-content/themes/loopis-theme/
define('LOOPIS_THEME_URI', get_template_directory_uri());   // Client-side path to https://loopis.app/wp-content/themes/loopis-theme/

// Define locker ID for this installation (temporary solution)
define('LOCKER_ID', '12845-1');

/** 
 * Enqueue theme CSS and JavaScript
 */

function loopis_theme_assets() {
    // Enqueue CSS theme styles
    wp_enqueue_style('loopis-theme-style', get_stylesheet_uri(), array(), LOOPIS_THEME_VERSION);
    wp_enqueue_style('loopis-theme-responsive', LOOPIS_THEME_URI . '/assets/css/responsive.css', array(), filemtime(LOOPIS_THEME_DIR . '/assets/css/responsive.css'));
    
    // Enqueue jQuery (default Wordpress version) + theme scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('loopis-theme-scripts', LOOPIS_THEME_URI . '/assets/js/general.js', array('jquery'), filemtime(LOOPIS_THEME_DIR . '/assets/js/general.js'), true);

    // Enqueue CSS styles and JS for admin
    if (current_user_can('manage_options') || current_user_can('loopis_admin')) {
        wp_enqueue_style('loopis-theme-admin', LOOPIS_THEME_URI . '/assets/css/admin.css', array(), filemtime(LOOPIS_THEME_DIR . '/assets/css/admin.css')); 
        wp_enqueue_script('loopis-admin-script', LOOPIS_THEME_URI . '/assets/js/admin.js', array('jquery'), filemtime(LOOPIS_THEME_DIR . '/assets/js/admin.js'), true);
    }
}
add_action('wp_enqueue_scripts', 'loopis_theme_assets');

/**
 * Include PHP files
 */

 // Utility function to include all PHP files in a folder
function loopis_theme_include_folder($folder_name) {
    $absolute_path = LOOPIS_THEME_DIR . '/includes/' . $folder_name;
    if (is_dir($absolute_path)) {
        foreach (glob($absolute_path . '/*.php') as $file) {
            include_once $file;
        }
    } else {
        loopis_log_level1("LOOPIS Theme failed to include folder: {$folder_name}");
    }
}
// Define folders to load
function loopis_theme_load_files() {
    // For everyone
    loopis_theme_include_folder('interface');
    loopis_theme_include_folder('features');
    loopis_theme_include_folder('shortcodes');
    loopis_theme_include_folder('filters');
    loopis_theme_include_folder('functions/everyone');

    // For user
    if (is_user_logged_in()) { 
        loopis_theme_include_folder('functions/user');
    }
}
add_action('after_setup_theme', 'loopis_theme_load_files');