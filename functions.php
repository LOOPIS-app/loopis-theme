<?php
/** 
* Theme functions 
* Loads all frontend assets and functions
*/	

// Define theme version
define('LOOPIS_THEME_VERSION', '0.77');

// Define theme folder path constants
define('LOOPIS_THEME_DIR', get_template_directory());       // Server-side path to /wp-content/themes/loopis-theme/
define('LOOPIS_THEME_URI', get_template_directory_uri());   // Client-side path to https://loopis.app/wp-content/themes/loopis-theme/

// Load environment variables from theme .env if present
$loopis_env_path = __DIR__ . '/.env';
if (is_readable($loopis_env_path)) {
    $loopis_env_lines = file($loopis_env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($loopis_env_lines as $loopis_env_line) {
        $loopis_env_line = trim($loopis_env_line);
        if ($loopis_env_line === '' || str_starts_with($loopis_env_line, '#')) {
            continue;
        }
        if (!str_contains($loopis_env_line, '=')) {
            continue;
        }
        [$loopis_env_key, $loopis_env_value] = explode('=', $loopis_env_line, 2);
        $loopis_env_key = trim($loopis_env_key);
        $loopis_env_value = trim($loopis_env_value);
        if ($loopis_env_key !== '' && getenv($loopis_env_key) === false) {
            putenv("{$loopis_env_key}={$loopis_env_value}");
            $_ENV[$loopis_env_key] = $loopis_env_value;
        }
    }
}

// Define locker ID for this installation (temporary solution)
define('LOCKER_ID', '12845-1');

// Stripe API Configuration
define('LOOPIS_STRIPE_SECRET_KEY', getenv('LOOPIS_STRIPE_SECRET_KEY') ?: '');

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
    if (current_user_can('administrator') || current_user_can('manager') || current_user_can('board_member')) {
        wp_enqueue_style('loopis-theme-admin', LOOPIS_THEME_URI . '/assets/css/admin.css', array(), filemtime(LOOPIS_THEME_DIR . '/assets/css/admin.css')); 
        wp_enqueue_script('loopis-admin-script', LOOPIS_THEME_URI . '/assets/js/admin.js', array('jquery'), filemtime(LOOPIS_THEME_DIR . '/assets/js/admin.js'), true);
    }
}
add_action('wp_enqueue_scripts', 'loopis_theme_assets');

/**
 * Include custom functions from PHP files
 */

 // Utility function to include all PHP files in a folder
function loopis_theme_include_folder($folder_name) {
    $absolute_path = LOOPIS_THEME_DIR . '/functions/' . $folder_name;
    if (is_dir($absolute_path)) {
        foreach (glob($absolute_path . '/*.php') as $file) {
            include_once $file;
        }
    } else {
        error_log("Failed to include folder: {$folder_name}");
    }
}
// Define folders to load
function loopis_theme_load_files() {
    // Load general functions
    loopis_theme_include_folder('everyone');

    // Load user functions
    if (is_user_logged_in()) { 
        loopis_theme_include_folder('user');
    }

    // Load admin functions
    if (current_user_can('administrator') || current_user_can('manager')) { 
        loopis_theme_include_folder('admin');
        loopis_theme_include_folder('cron');
    }
}
add_action('after_setup_theme', 'loopis_theme_load_files');

/**
*  Extra WP settings
*/

// Enable featured image for custom post type
function loopis_theme_setup() {
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'loopis_theme_setup');

/**
 * Load search functions on search pages (frontend only)
 */
function loopis_load_search_functions() {
    // Only load on frontend (not in admin area)
    if (is_admin()) {
        return;
    }
    
    if (is_search() || (isset($_GET['s']) && !empty($_GET['s']))) {
        require_once LOOPIS_THEME_DIR . '/functions/everyone-extra/extended-search.php';
    }
}
add_action('init', 'loopis_load_search_functions');