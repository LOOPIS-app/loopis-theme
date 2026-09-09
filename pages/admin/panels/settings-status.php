<?php
/**
 * Show status of area settings
 */

global $wpdb;

// Check locker warning status
$locker_warning_value = loopis_get_setting('locker_warning', '0');

if ($locker_warning_value === '0') {
	echo '✅ Varning för skåp är inaktiv<br>';
} else {
	echo '<b>⚠ Varning för skåp är aktiv!</b><br>';
}
$area_privacy_value = loopis_get_setting('area_privacy', 'false');

// Check area locker code
$locker_code_value = loopis_get_setting('locker_code', '0000');
echo '🔒 Kod för skåpet: ' . $locker_code_value . '<br>';

// Check area privacy status
if ($area_privacy_value === 'false') {
	echo '💚 Området är offentligt';
} else {
	echo '⛔️ Området är privat';
}
$area_privacy_value = loopis_get_setting('area_privacy', 'false');