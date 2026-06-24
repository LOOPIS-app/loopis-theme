<?php
/**
 * Theme bootstrap for LOOPIS sub sites (aka. local apps)
 *
 * Loads all frontend core files.
 */

// Prevent direct access
if (!defined('ABSPATH')) { exit; }

// Define theme version
define('LOOPIS_THEME_VERSION', '1.01'); // Update version number here + in style.css

// Theme folder constants are provided by MU plugin "LOOPIS Constants".

// Define locker ID for this installation (temporary solution)
if (!defined('LOCKER_ID')) { define('LOCKER_ID', '12845-1'); }

/** 
 * Enqueue theme CSS and JavaScript
 */

function loopis_theme_assets() {
    // Enqueue CSS theme styles
    wp_enqueue_style('loopis-theme-style', LOOPIS_THEME_URI . '/assets/css/base.css', array(), filemtime(LOOPIS_THEME_DIR . '/assets/css/base.css'));
    wp_enqueue_style('loopis-theme-forms', LOOPIS_THEME_URI . '/assets/css/forms.css', array('loopis-theme-style'), filemtime(LOOPIS_THEME_DIR . '/assets/css/forms.css'));
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
    loopis_theme_include_folder('filters');
    loopis_theme_include_folder('functions/everyone');
    loopis_theme_include_folder('shortcodes');

    // For user
    if (is_user_logged_in()) { 
        loopis_theme_include_folder('functions/user');
    } else {
    // For visitor
        loopis_theme_include_folder('functions/visitor');
    }
}
add_action('after_setup_theme', 'loopis_theme_load_files');



add_action('wp_ajax_loopis_ledger_page', 'loopis_ledger_page');

function loopis_ledger_page(){
    include_once LOOPIS_THEME_DIR .'/includes/functions/everyone/ledger_pagination.php';
	error_log('loopis_ledger_page hit');
	if ( ! isset($_POST['nonce']) || ! wp_verify_nonce(wp_unslash($_POST['nonce']), 'loopis_ledger_nonce') ) {
	wp_send_json_error('Invalid nonce', 403);
	}	
	$options_raw = $_POST['options'] ?? '{}';
	$options = json_decode(wp_unslash($options_raw), true);
	if (!is_array($options)) { $options = []; }

	$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
	$posts_per_page = 50;
	$num = loopis_ledger_fetch_total($options);
	$max_pages= max(1, (int) ceil($num/$posts_per_page));
	$offset = ($page-1)*$posts_per_page;
	$post_ledger = loopis_ledger_fetch($options,['posts_per_page'=>$posts_per_page, 'offset'=>$offset, 'dasc' => 'DESC']);
	$grid_spacing = 'grid-template-columns: 1.4fr 0.8fr 0.6fr 0.6fr;';

	ob_start();?>
	<!--ledger-->

	    <div class="admin-grid" style="<?php echo $grid_spacing ;?>">
	        <div>Event</div>
	        <div>Tid</div>
	        <div>Mynt</div>
	        <div>Klöver</div>
	    </div>

	    <?php foreach ($post_ledger as $entry): 
	        $user_info = get_userdata($entry['user_id']);
	    ?>
	        <div class="admin-grid" style="<?php echo $grid_spacing ;?>">
	            <div><i class="fas fa-info-circle"></i> <?php echo esc_html($entry['event']); ?></div>
	            <div><i class="fa-solid fa-clock"></i> <?php echo esc_html($entry['timestamp']); ?></div>
	            <div><i class="fa-regular fa-circle"></i> <?php echo esc_html($entry['coins']); ?></div>
	            <div><i class="fa-solid fa-clover"></i> <?php echo esc_html($entry['clover']); ?></div>
	        </div>
	    <?php endforeach; ?>
	</div>
	<?php
	$activity = ob_get_clean();

	ob_start();
	loopis_ajax_pagination($max_pages, $page); 
	$pagination = ob_get_clean();

	wp_send_json_success([
		'page' => $page,
		'activity' => $activity,
		'pagination' => $pagination,
		'max_pages' => $max_pages,
	]);
}