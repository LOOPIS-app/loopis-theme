
<?php
/**
 * Functions to generate ledger page
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 	Pagination for ajax use
 * 
 * 	@return void - echoes HTML
 */
function loopis_ajax_pagination($max_pages, $page){
	include_once LOOPIS_THEME_DIR .'/templates/post-list/pagination-sql.php';
	$max_pages = max(1, (int) $max_pages);
	$page = max(1, min($max_pages,(int) $page));
	$range = loopis_get_range($max_pages, $page);

	if ($page > 1){
		echo '<button type="button" style="background: none;border: none; color = #777!important;" class="loopis_ajax_button page-numbers" data-page="'.($page-1).'">&lt;</button>';
	}

	foreach($range as $element){
		if ((int) $element === $page){
			echo '<span aria-current="page" class="page-numbers">'.$element.'</span>';
		}elseif ($element === '...'){
			echo '<span aria-current="page" class="page-numbers">...</span>';
		}else{
			echo '<button type="button" style="background: none;border: none; color = #777!important" class="loopis_ajax_button page-numbers" data-page="'.($element).'">'.$element.'</button>';
		}
	}


	if ($page < $max_pages){
		echo '<button type="button" style="background: none;border: none; color = #777 !important;" class="loopis_ajax_button page-numbers" data-page="'.($page+1).'">&gt;</button>';
	}

}

add_action('wp_ajax_loopis_ledger_page', 'loopis_ledger_page');

/**
 *  Ajaxed ledger page generator called by loadLedgerPage in /assets/js/ledger-display.js
 * 
 * 	@return JSON-ed HTML
 */
function loopis_ledger_page(){
	if ( ! isset($_POST['nonce']) || ! wp_verify_nonce(wp_unslash($_POST['nonce']), 'loopis_ledger_nonce') ) {
		wp_send_json_error('Invalid nonce', 403);
	}	
	$options_raw = $_POST['options'] ?? '{}';
	$options = json_decode(wp_unslash($options_raw), true);
	if (!is_array($options)) { $options = []; }
	$cols_raw = $_POST['columns'] ?? '{}';
	$cols = json_decode(wp_unslash($cols_raw), true);
	if (!is_array($cols)) { $cols = []; }
	$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
	$posts_per_page = 50;
	$num = loopis_ledger_fetch_total($options);
	$max_pages= max(1, (int) ceil($num/$posts_per_page));
	$offset = ($page-1)*$posts_per_page;
	$post_ledger = loopis_ledger_fetch($options,['posts_per_page'=>$posts_per_page, 'offset'=>$offset, 'dasc' => 'DESC']);
	$coin_count = ((int) array_key_exists('coins', $cols)) + ((int) array_key_exists('clover', $cols));
	$count_cols = count($cols) - $coin_count;
	$grid_spacing = 'grid-template-columns: repeat(' . $count_cols . ', 1fr)' . ' ' . str_repeat('0.4fr ', $coin_count). ';';

	ob_start();
	echo '↓ ' . $offset .' till ' . min(($offset+$posts_per_page),$num) .' av '. $num . ' Aktiviteter';
	$activity_count = ob_get_clean();


	ob_start();?>
	<!--ledger-->
		<div class="admin-grid" style="<?php echo $grid_spacing ;?>">
			<?php foreach ($cols as $index => $col):?>
	            <div> <i class="<?php echo get_fas($index) ;?>"></i> </div>
			<?php endforeach; ?>
    	</div>

	    <?php foreach ($post_ledger as $entry): ?>
	        <div class="admin-grid" style="<?php echo $grid_spacing ;?>">
				<?php foreach ($cols as $index => $col):?>
					<?php if ($index === 'user_id'): 
						$user_info = get_userdata($entry['user_id']);
					?>
						<div><a href="<?php echo get_author_posts_url($entry['user_id']); ?>"><?php echo esc_html(($user_info->first_name ?? '').' '.($user_info->last_name ?? '')); ?></a></div>
					<?php elseif ($index === 'post_id'): ?>
						<div><a href="<?php echo add_query_arg(array('p' => esc_html($entry[$index])),home_url('/')); ?>"> <?php echo esc_html(get_the_title($entry[$index])); ?></a></div>
					<?php else: ?>
	            		<div> <?php echo esc_html($entry[$index]); ?></div>
					<?php endif; ?>
				<?php endforeach; ?>
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
		'activityCount' => $activity_count,
		'pagination' => $pagination,
		'max_pages' => $max_pages,
	]);
}

/**
 *  Helper, gets font awesome handles for columns
 * 
 * 	Usage example: <i class="<?php echo get_fas('user_id') ;?>">
 * 
 * 	@return string i-class e.g. fa-solid fa-user
 */
function get_fas($handle){
	$options = [
		'user_id' => "fa-solid fa-user",
		'event' => "fas fa-bell",
		'post_id' => "fas fa-gift",
		'type' => "fas fa-info-circle",
		'coins' => "fas fa-coins",
		'clover' => "fa-solid fa-clover",
		'description' => "fas fa-question-circle",
		'location' => "fa-solid fa-location-dot",
		'timestamp' => "fa-solid fa-clock",
	];
	if (isset($options[$handle])){
		return $options[$handle];
	}else{
		return "fas fa-info-circle";
	}
}