
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
<h1>📕 Lokala boken</h1>
<hr>
<p class="small"> 💡 Register med användaraktivitet</p>

<?php
$posts_per_page = 50;
$page = 1;
$offset= ($page-1)*$posts_per_page;
$options =['blog_id' => get_current_blog_id()];
$post_ledger = loopis_ledger_fetch($options,['posts_per_page'=>$posts_per_page, 'offset'=>0, 'dasc' => 'DESC']);
$num = loopis_ledger_fetch_total($options);
$max_pages= max(1, (int) ceil($num/$posts_per_page));
$grid_spacing = 'grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;';
?>

<!--ledger-->
<div class="ledger-filters">
	<select name="event" id="ledger-event" class="ledger-filter">
		<option value="">Alla event</option>
		<?php
		$events = loopis_ledger_column_distinct('event');
		foreach ($events as $event) {
			echo '<option value="' . esc_attr($event['event']) . '">' . esc_html($event['event']) . '</option>';
		}
		?>
	</select>

	<select name="type" id="ledger-type" class="ledger-filter">
		<option value="">Alla Underkategorier</option>
		<?php
		$events = loopis_ledger_column_distinct('type');
		foreach ($events as $event) {
			echo '<option value="' . esc_attr($event['type']) . '">' . esc_html(loopis_ledger_type_output($event['type'])) . '</option>';
		}
		?>
	</select>
	<select name="description" id="ledger-description" class="ledger-filter">
		<option value="">Alla detaljalternativ</option>
		<?php
		$events = loopis_ledger_column_distinct('description');
		foreach ($events as $event) {
			echo '<option value="' . esc_attr($event['description']) . '">' . esc_html($event['description']) . '</option>';
		}
		?>
	</select>	

	<select name="user_id" id="ledger-user" class="ledger-filter">
		<option value="">Alla användare</option>
		<?php
		$users = get_users();
		foreach ($users as $user) {
			echo '<option value="' . esc_attr($user->ID) . '">' . esc_html($user->display_name) . '</option>';
		}
		?>
	</select>

	<button id="ledger-filter-btn" type="button">Filtrera</button>
</div>



<div class="columns"><?php echo '↓ ' . $offset .' till ' . ($offset+$posts_per_page) .' av '. $num ?>  Aktiviteter</div>	
<hr style="margin-bottom: 2px;">
<div id="ledger" class="logg">
    <div class="admin-grid" style="<?php echo $grid_spacing ;?>">
		<div>Post</div>
        <div>Användare</div>
		<div>Event</div>
		<div>Typ</div>
		<div>Beskrivning</div>
        <div>Tid</div>
        <div>Mynt</div>
        <div>Klöver</div>
    </div>

    <?php foreach ($post_ledger as $entry): 
        $user_info = get_userdata($entry['user_id']);
		$post_id = ($entry['post_id'] == 0) ? 'digital': $entry['post_id'];
    ?>
        <div class="admin-grid" style="<?php echo $grid_spacing ;?>">
			<div><i class="fa-solid fa-signs-post"></i> <?php echo $post_id; ?></div>
			<div><a href="<?php echo get_author_posts_url($entry['user_id']); ?>"><i class="fa-solid fa-user"></i><?php echo esc_html(($user_info->first_name ?? '').' '.($user_info->last_name ?? '')); ?></a></div>
            <div><i class="fas fa-info-circle"></i> <?php echo esc_html($entry['event']); ?></div>
			<div><i class="fas fa-info-circle"></i> <?php echo esc_html($entry['type']); ?></div>
			<div><i class="fas fa-info-circle"></i> <?php echo esc_html($entry['description']); ?></div>
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

function getLedgerOptions(){
	const options = {};

	document.querySelectorAll('.ledger-filter').forEach(element => {
		if (element.value!=''){
			options[element.name] = element.value;
		}
	});

	return options;

}

function loadLedgerPage(page=1){
	const nonce = <?php echo wp_json_encode($nonce); ?>;
	const options = getLedgerOptions()
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
}
document.addEventListener('click', function(e){
	const button = e.target.closest('.loopis_ajax_button');

	if (!button) return;

  	const page = parseInt(button.dataset.page, 10);
  	if (!Number.isFinite(page)) return;
	loadLedgerPage(page);

	});


document.getElementById('ledger-filter-btn')?.addEventListener('click', function() {
	loadLedgerPage(1);
});

</script>