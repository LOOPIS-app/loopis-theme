<?php
/**
 * Settings page for area - Admin section
 */

// Include settings update helper on this page
require_once LOOPIS_THEME_DIR . '/includes/functions/admin-extra/update-setting.php';

$settings_notice = '';
$settings_notice_type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted_form = isset($_POST['loopis_settings_form']) ? sanitize_key(wp_unslash($_POST['loopis_settings_form'])) : '';

    if ($submitted_form === 'loopis_admin') {
        check_admin_referer('loopis_settings_loopis_admin');

        $locker_code = isset($_POST['locker_code']) ? sanitize_text_field(wp_unslash($_POST['locker_code'])) : '0000';
        $locker_warning = isset($_POST['locker_warning']) ? '1' : '0';
        $locker_warning_info_raw = isset($_POST['locker_warning_info']) ? sanitize_textarea_field(wp_unslash($_POST['locker_warning_info'])) : '';
        $locker_warning_info = loopis_setting_textarea_to_br($locker_warning_info_raw);
        $locker_warning_header = isset($_POST['locker_warning_header']) ? sanitize_text_field(wp_unslash($_POST['locker_warning_header'])) : '';
        $event_name = isset($_POST['event_name']) ? sanitize_text_field(wp_unslash($_POST['event_name'])) : '';

        $event_name_history = maybe_unserialize(loopis_get_setting('event_name_history', serialize(array())));
        if (!is_array($event_name_history)) {
            $event_name_history = array();
        }

        $previous_event_name = loopis_get_setting('event_name', '🛸 LOOPIS HQ');
        if ($event_name !== '' && $event_name !== $previous_event_name) {
            if ($previous_event_name !== '') {
                array_unshift($event_name_history, $previous_event_name);
            }
            $event_name_history = array_values(array_unique(array_filter($event_name_history)));
        }

        $ok = true;
        $ok = $ok && loopis_update_setting('locker_code', $locker_code);
        $ok = $ok && loopis_update_setting('locker_warning', $locker_warning);
        $ok = $ok && loopis_update_setting('locker_warning_info', $locker_warning_info);
        $ok = $ok && loopis_update_setting('locker_warning_header', $locker_warning_header);
        $ok = $ok && loopis_update_setting('event_name', $event_name);
        $ok = $ok && loopis_update_setting('event_name_history', serialize($event_name_history));

        $settings_notice = $ok ? '✅ Inställningar sparade.' : '⚠️ Kunde inte spara alla inställningar.';
        $settings_notice_type = $ok ? 'success' : 'error';
    }
}

$locker_code_value = loopis_get_setting('locker_code', '0000');
$locker_warning_value = loopis_get_setting('locker_warning', '0');
$locker_warning_info_stored = loopis_get_setting('locker_warning_info', '⚠ Det är mycket saker i skåpen just nu! <br>🐎 Hämta dina saker så snabbt som möjligt.<br> 🐌 Vänta någon dag med att lämna stora saker.');
$locker_warning_info_value = loopis_setting_textarea_from_br($locker_warning_info_stored);
$locker_warning_header_value = loopis_get_setting('locker_warning_header', '⚠ Mycket saker i skåpen!');
$event_name_value = loopis_get_setting('event_name', 'saknas');

$event_name_history_value = maybe_unserialize(loopis_get_setting('event_name_history', serialize(array('🌳 LOOPIS på torget', '🛸 LOOPIS HQ'))));
if (!is_array($event_name_history_value)) {
    $event_name_history_value = array();
}
?>

<h1>⚙ <?php echo get_bloginfo('name'); ?></h1>
<hr>
<p class="small">💡 Här gör admin inställningar för området.</p>

<?php if (!empty($settings_notice)) : ?>
    <div class="loopis-message <?php echo esc_attr($settings_notice_type); ?>">
        <p><?php echo esc_html($settings_notice); ?></p>
    </div>
<?php endif; ?>

<div class="loopis-form-wrapper">
<form class="loopis-form" method="post" action="">
    <?php wp_nonce_field('loopis_settings_loopis_admin'); ?>
    <input type="hidden" name="loopis_settings_form" value="loopis_admin">

    <p>
        <label for="locker_code">Kod till skåpet</label>
        <input id="locker_code" type="text" name="locker_code" value="<?php echo esc_attr($locker_code_value); ?>">
    </p>
    <p>
        <label>
            <input type="checkbox" name="locker_warning" value="1" <?php checked($locker_warning_value, '1'); ?>>
            Visa varning för skåp?
        </label>
    </p>
    <p>
        <label for="locker_warning_header">Varning rubrik</label>
        <input id="locker_warning_header" type="text" name="locker_warning_header" value="<?php echo esc_attr($locker_warning_header_value); ?>">
    </p>
    <p>
        <label for="locker_warning_info">Varning info</label>
        <textarea id="locker_warning_info" name="locker_warning_info" rows="3"><?php echo esc_textarea($locker_warning_info_value); ?></textarea>
    </p>
    <p>
        <label for="event_name">Event</label>
        <input id="event_name" type="text" name="event_name" value="<?php echo esc_attr($event_name_value); ?>">
    </p>

    <?php if (!empty($event_name_history_value)) : ?>
        <p class="small">Tidigare event:</p>
        <p>
            <?php foreach ($event_name_history_value as $historic_name) : ?>
                <span class="big-link white">
                    <a href="#" class="loopis-event-name-option" data-event-name="<?php echo esc_attr($historic_name); ?>"><?php echo esc_html($historic_name); ?></a>
                </span>&nbsp;
            <?php endforeach; ?>
        </p>
    <?php endif; ?>

    <p><input type="submit" value="Spara"></p>
</form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var eventNameInput = document.getElementById('event_name');
    if (!eventNameInput) {
        return;
    }

    var options = document.querySelectorAll('.loopis-event-name-option');
    options.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            eventNameInput.value = this.getAttribute('data-event-name') || '';
            eventNameInput.focus();
        });
    });
});
</script>

<?php
// Locker warning preview
$locker_warning_status = $locker_warning_value === '1' ? 'aktiv' : 'ej aktiv';
?>

<p class="info">💡 Förhandsvisning av varning:</p>
<div style="max-width: 400px;">
<?php
if (!empty($locker_warning_header_value)) {
	echo '<h5>' . esc_html($locker_warning_header_value) . '</h5><hr>';
    if (!empty($locker_warning_info_stored)) {
        echo '<div class="loopis-message warning"><p>' . wp_kses($locker_warning_info_stored, array('br' => array())) . '</p></div>';
	}
}
?>
</div> <!-- max-width: 500px -->
              
<p class="info">💡 Standard för varning:</p>
<div style="max-width: 400px;">
<h5>⚠ Mycket saker i skåpet!</h5><hr>
<div class="loopis-message warning"><p>🐎 Hämta dina saker så snabbt som möjligt.</p></div>
</div> <!-- max-width: 500px -->