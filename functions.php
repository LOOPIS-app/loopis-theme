<?php
/** 
* This file is used to add and modify features of a WordPress theme
*/	

// Define theme version
define('LOOPIS_THEME_VERSION', '0.7.0');

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
        error_log("loopis-theme: Failed to include folder from functions.php: {$folder_name}");
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

// Load theme languages
function loopis_theme_load() {
    load_theme_textdomain('loopis-theme', LOOPIS_THEME_DIR . '/assets/lang');
}
add_action('after_setup_theme', 'loopis_theme_load');

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

/**
 * DEBUG: Log the 248 user query
 */
add_filter('query', function($query) {
    if (strpos($query, 'FROM wpxn_usermeta WHERE user_id IN') !== false) {
        error_log('==================== LOOPIS DEBUG ====================');
        error_log('Usermeta query triggered!');
        error_log('Current page: ' . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'unknown'));
        error_log('Backtrace:');
        foreach(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $i => $trace) {
            $file = isset($trace['file']) ? $trace['file'] : 'unknown';
            $line = isset($trace['line']) ? $trace['line'] : 'unknown';
            $function = isset($trace['function']) ? $trace['function'] : 'unknown';
            error_log("  #{$i} {$function}() - {$file}:{$line}");
        }
        error_log('======================================================');
    }
    return $query;
});

/**
 * Prevent WPUM Custom Fields from loading all users on EVERY page
 * This is a performance fix - WPUM loads 248 users on init for dropdown fields
 */
add_action('plugins_loaded', function() {
    // Only allow WPUM Custom Fields to register on admin pages
    if (!is_admin()) {
        remove_action('carbon_fields_register_fields', 'wpumcf_register_fields_in_admin');
        
        // Return empty array for user field options on frontend
        add_filter('wpum_user_field_get_options', function($options) {
            return array();
        }, 1);
    }
}, 1);


/**
 * ULTIMATE FIX: Completely disable WPUM Custom Fields on frontend
 */
add_filter('option_active_plugins', function($plugins) {
    if (!is_admin()) {
        // Temporarily remove WPUM Custom Fields from active plugins list on frontend
        $plugins = array_diff($plugins, array(
            'wpum-custom-fields/wpum-custom-fields.php'
        ));
    }
    return $plugins;
}, 1);
