<?php
/** 
* This file is used to add and modify features of a WordPress theme
*/	

// Define theme folder path constants
define('LOOPIS_THEME_DIR', get_template_directory()); // Theme directory absolute path, for server-side operations
define('LOOPIS_THEME_URI', get_template_directory_uri()); // Theme directory URI, for client-side operations

/** 
* Enqueue theme CSS and JavaScript
*/

function loopis_theme_assets() {
    // Enqueue CSS theme styles
    wp_enqueue_style('loopis-theme-style', get_stylesheet_uri(), array(), filemtime(LOOPIS_THEME_DIR . '/style.css'));
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
    $absolute_path = LOOPIS_THEME_DIR . '/assets/functions/' . $folder_name;
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
    loopis_theme_include_folder('general');

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
 * Force Twemoji Override
 */


// Add our own Twemoji implementation
function loopis_add_twemoji() {
    ?>
    <script type="text/javascript">
    !function(window, document) {
        // Immediate emoji replacement based on wp-emoji-loader.min.js
        function loadTwemojiSync() {
            var script = document.createElement('script');
            script.src = 'https://unpkg.com/twemoji@latest/dist/twemoji.min.js';
            script.onload = function() {
                // Parse immediately when loaded
                twemoji.parse(document.body || document.documentElement, {
                    folder: 'svg',
                    ext: '.svg'
                });
                
                // Set up observer for dynamic content
                if (window.MutationObserver) {
                    var observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            mutation.addedNodes.forEach(function(node) {
                                if (node.nodeType === 1) {
                                    twemoji.parse(node, {
                                        folder: 'svg',
                                        ext: '.svg'
                                    });
                                }
                            });
                        });
                    });
                    
                    observer.observe(document.body || document.documentElement, {
                        childList: true,
                        subtree: true
                    });
                }
            };
            document.head.appendChild(script);
        }
        
        // Load immediately - don't wait for DOMContentLoaded
        if (document.head) {
            loadTwemojiSync();
        } else {
            // Fallback if head doesn't exist yet
            document.addEventListener('DOMContentLoaded', loadTwemojiSync);
        }
        
    }(window, document);
    </script>
    <?php
}
// Add Twemoji to both frontend and admin
add_action('wp_head', 'loopis_add_twemoji');
add_action('admin_head', 'loopis_add_twemoji');