
<?php
/**
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once LOOPIS_THEME_DIR .'/templates/post-list/pagination-sql.php';
function loopis_ajax_pagination($max_pages, $page){
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
