
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
<h7>📕 <?php echo $first_name;?>s  bok</h7>
<hr>
<p class="small"> 💡 Register med användaraktivitet</p>

<!--ledger-->

<!-- Sets filters(options for fetching) -->
<div class="ledger-filters">
	<input type="hidden" class="ledger-filter" name="user_id" value=<?php echo $user_id ;?>>
</div>

<!-- Sets generated columns -->

<div class="ledger-hidden">
	<input type="hidden" class="ledger-column" name="post_id" value="Post">
	<input type="hidden" class="ledger-column" name="user_id" value="Användare">
	<input type="hidden" class="ledger-column" name="event" value="Event">
	<input type="hidden" class="ledger-column" name="type" value="Typ">
	<input type="hidden" class="ledger-column" name="description" value="Beskrivning">
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