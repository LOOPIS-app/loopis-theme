
<?php
/**
 * First draft of site 
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

<!--ledger-->

<!-- Sets filters(options for fetching) -->
<div class="ledger-filters">
	<input type="hidden" class="ledger-filter" name="blog_id" value=<?php echo get_current_blog_id();?>>
	
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

<!-- Sets generated columns -->

<div class="ledger-hidden">
	<input type="hidden" class="ledger-column" name="post_id" value="Post">
	<input type="hidden" class="ledger-column" name="user_id" value="Användare">
	<input type="hidden" class="ledger-column" name="event" value="Event">
	<input type="hidden" class="ledger-column" name="type" value="Typ">
	<input type="hidden" class="ledger-column" name="description" value="Beskrivning">
	<input type="hidden" class="ledger-column" name="location" value="Plats">
	<input type="hidden" class="ledger-column" name="timestamp" value="Tid">
	<input type="hidden" class="ledger-column" name="coins" value="Mynt">			
	<input type="hidden" class="ledger-column" name="clover" value="Klöver">
</div>

<!-- Husks(for customisation reasons) -->

<div id="activity-count" class="columns"></div>	
<hr style="margin-bottom: 2px;">

<div id="ledger" class="logg">
</div>

<div id="post-pagination" data-max-pages="" data-page="">
</div>

<?php
$nonce = wp_create_nonce('loopis_ledger_nonce');
?>
<!-- pass important info -->
<script>
  window.LoopisLedger = {
    nonce: <?php echo wp_json_encode($nonce); ?>,
    ajaxUrl: <?php echo wp_json_encode( admin_url("admin-ajax.php") ); ?>
  };
</script>
<!-- get main script -->
<script src="<?php echo esc_url( LOOPIS_THEME_URI . '/assets/js/ledger-display.js' ); ?>" defer></script>
<script>
	// get first page
	document.addEventListener('DOMContentLoaded',()=>{
		loadLedgerPage(1);
	});
</script>