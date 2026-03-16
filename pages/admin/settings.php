<?php
/**
 * Settings page
 * Settings available for roles administrator and manager.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>⚙ Inställningar</h1>
<hr>
<p class="small">💡 Här gör du inställningar.</p>

<h3>⚠ Varningar</h3>
<div class="columns">
    <div class="column1">↓ Fullt skåp?</div>
    <div class="column2 small">locker_id</div>
</div>
<hr>

<?php
// Extra php functions?
include_once LOOPIS_THEME_DIR . '/includes/functions/admin-extra/update-locker.php';

// Persist locker full toggles
if (
	isset($_POST['locker_full_toggle_nonce'])
	&& wp_verify_nonce($_POST['locker_full_toggle_nonce'], 'locker_full_toggle')
) {
	$locker_full_updates = isset($_POST['locker_full']) && is_array($_POST['locker_full'])
		? array_map('sanitize_text_field', $_POST['locker_full'])
		: array();

	global $wpdb;
	$table = $wpdb->prefix . 'loopis_lockers';
	$locker_ids = $wpdb->get_col("SELECT locker_id FROM $table");

	foreach ($locker_ids as $locker_id) {
		$enabled = in_array($locker_id, $locker_full_updates, true) ? 1 : 0;
		update_locker($locker_id, 'locker_full', $enabled);
	}
}

// Load current locker states
global $wpdb;
$table = $wpdb->prefix . 'loopis_lockers';
$lockers = $wpdb->get_results("SELECT locker_id, locker_name, locker_full FROM $table ORDER BY locker_id");

// Render table of lockers with inline toggle
echo '<form method="post">';
wp_nonce_field('locker_full_toggle', 'locker_full_toggle_nonce');

if (!empty($lockers)) {
	echo '<table class="admin-table">';
	echo '<tbody>';
	foreach ($lockers as $locker) {
		echo '<tr>';
		echo '<td style="width:1%; white-space:nowrap;">';
		echo '<input type="checkbox" name="locker_full[]" value="' . esc_attr($locker->locker_id) . '" ' . checked((int) $locker->locker_full, 1, false) . ' onclick="if(!confirm(\'Vill du aktivera/deaktivera varningen för fullt skåp?\')){this.checked=!this.checked;return false;} this.form.submit();">';
		echo '</td>';
		echo '<td style="padding-left:6px;">' . esc_html($locker->locker_name ?: '—') . '</td>';
		echo '<td style="text-align:right;">' . esc_html($locker->locker_id) . '</td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
} else {
	echo '<p>💢 Inga skåp finns.</p>';
}

echo '</form>';

insert_spacer(20);

// Preview the warning shown to end users
echo '<p class="info">💡 Här nedanför ser du den varning de användare som ska hämta/lämna ser överst på startsidan.</p>';

$full_warning = loopis_get_setting('locker_full_warning', '');
if (!empty($full_warning)) {
	echo '<h5>⚠ Mycket saker i skåpen!</h5><hr>';
	echo '<div class="wpum-message warning"><p>' . wp_kses_post(nl2br($full_warning)) . '</p></div>';
}