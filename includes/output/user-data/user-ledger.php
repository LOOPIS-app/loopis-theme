
<?php
/**
 * Template for displaying WPUM profile tab content.
 * 
 * Modified by LOOPIS.
 * 
 * Improvements:
 * – Fade out post types not relevant to the user
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include_once LOOPIS_THEME_DIR .'/templates/post-list/pagination-sql.php';
?>

<!-- OUTPUT -->
<p class="small"> 💡 Register med användaraktivitet</p>
<h7>📕 Boken</h7>


<?php
$posts_per_page = 50;
$page = 1;
$options =['user_id'=>$user_id];
$post_ledger = loopis_ledger_fetch($options,['posts_per_page'=>$posts_per_page, 'offset'=>0, 'dasc' => 'DESC']);
$num = loopis_ledger_fetch_total($options);
$max_pages= max(1, (int) ceil($num/$posts_per_page));
$grid_spacing = 'grid-template-columns: 1.2fr 1.6fr 0.8fr 0.8fr;';

?>

<!--ledger-->

<div class="columns"><?php echo '↓ ' . ($page-1)*$posts_per_page .' av '. $num ?>  Aktiviteter</div>	
<hr style="margin-bottom: 2px;">
<div id="ledger" class="logg">
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

<div id="post-pagination" data-max-pages="<?php echo esc_attr($max_pages); ?>" data-page="<?php echo esc_attr($page); ?>">
	<?php loopis_ajax_pagination($max_pages, $page); ?>
</div>
<?php


$nonce = wp_create_nonce('loopis_ledger_nonce');
?>
<script>
document.addEventListener('click', function(e){
	const button = e.target.closest('.loopis_ajax_button');

	if (!button) return;
	console.log("hello");
  	const page = parseInt(button.dataset.page, 10);
  	if (!Number.isFinite(page)) return;
	const nonce = <?php echo wp_json_encode($nonce); ?>;
	const options = <?php echo wp_json_encode($options);?>;
	console.log(options);
	const log = document.getElementById('ledger');
	const pagination = document.getElementById('post-pagination');
	fetch('<?php echo esc_url(admin_url("admin-ajax.php")); ?>', {
	       	method: 'POST',
	       	headers: {
	           	'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
	       	},
	       	body: new URLSearchParams({
	           	action: 'loopis_ledger_page',
	           	options: JSON.stringify(options),
	           	page: page,
			   	nonce: nonce
	       	})
	   	})
	   	.then(r => r.json())
	   	.then(data => {
	       	if (!data.success) return;

	       	log.innerHTML = data.data.activity;
	       	pagination.innerHTML = data.data.pagination;
	       	pagination.dataset.page = data.data.page;
	       	pagination.dataset.maxPages = data.data.max_pages;
	   	});
	});



</script>