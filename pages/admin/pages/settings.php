<?php
/**
 * Settings page for area
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include settings update helper on this page
require_once LOOPIS_THEME_DIR . '/includes/functions/admin-extra/update-setting.php';

$can_manage_options = current_user_can('manage_options');
$can_loopis_admin = $can_manage_options || current_user_can('loopis_admin');
$settings_notice = '';
$settings_notice_type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted_form = isset($_POST['loopis_settings_form']) ? sanitize_key(wp_unslash($_POST['loopis_settings_form'])) : '';

    if ($submitted_form === 'manage_options' && $can_manage_options) {
        check_admin_referer('loopis_settings_manage_options');
        $privacy = ! empty($_POST['area_privacy']) ? '1' : '0';
        $area_privacy = $privacy ? 'true' : 'false';
        $locker_id = isset($_POST['locker_id']) ? sanitize_text_field(wp_unslash($_POST['locker_id'])) : '00000';
        $locker_name = isset($_POST['locker_name']) ? sanitize_text_field(wp_unslash($_POST['locker_name'])) : 'locker';
        $locker_postal_code = isset($_POST['locker_postal_code']) ? sanitize_text_field(wp_unslash($_POST['locker_postal_code'])) : '00000';

        $ok = true;
        $ok = $ok && loopis_update_setting('area_privacy', $area_privacy);
        $ok = $ok && loopis_update_setting('locker_id', $locker_id);
        $ok = $ok && loopis_update_setting('locker_name', $locker_name);
        $ok = $ok && loopis_update_setting('locker_postal_code', $locker_postal_code);
        $areas_table = $wpdb->base_prefix . 'loopis_areas';
        $current_blog_id = get_current_blog_id();

        $area_updated = $wpdb->update(
            $areas_table,
            array(
                'privacy'       => $privacy,
                'locker_id'          => $locker_id,
                'locker_name'        => $locker_name,
                'postal_code' => $locker_postal_code,
            ),
            array(
                'blog_id' => $current_blog_id,
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
            ),
            array(
                '%d',
            )
        );

        if ($area_updated === false) {
            $exists = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT 1 FROM {$areas_table} WHERE blog_id = %d LIMIT 1",
                    $current_blog_id
                )
            );

            if (!$exists) {
                $wpdb->insert(
                    $areas_table,
                    array(
                        'blog_id'     => $current_blog_id,
                        'locker_id'   => $locker_id,
                        'locker_name' => $locker_name,
                        'postal_code' => $locker_postal_code,
                        'privacy'     => $privacy,
                    ),
                    array('%d', '%s', '%s', '%s', '%d')
                );
            }else{
                $ok = false;
            }
        }

        $settings_notice = $ok ? '✅ Inställningar sparade.' : '💢 Kunde inte spara alla inställningar.';
        $settings_notice_type = $ok ? 'success' : 'error';
    }

    if ($submitted_form === 'loopis_admin' && $can_loopis_admin) {
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

        $settings_notice = $ok ? '✅ Inställningar sparade.' : '💢 Kunde inte spara alla inställningar.';
        $settings_notice_type = $ok ? 'success' : 'error';
    }
}

$area_privacy_value = loopis_get_setting('area_privacy', 'false');
$locker_id_value = loopis_get_setting('locker_id', '00000');
$locker_name_value = loopis_get_setting('locker_name', 'locker');

$locker_code_value = loopis_get_setting('locker_code', '0000');
$locker_warning_value = loopis_get_setting('locker_warning', '0');
$locker_warning_info_stored = loopis_get_setting('locker_warning_info', '⚠ Det är mycket saker i skåpen just nu! <br>🐎 Hämta dina saker så snabbt som möjligt.<br> 🐌 Vänta någon dag med att lämna stora saker.');
$locker_warning_info_value = loopis_setting_textarea_from_br($locker_warning_info_stored);
$locker_warning_header_value = loopis_get_setting('locker_warning_header', '⚠ Mycket saker i skåpen!');
$locker_postal_code_value = loopis_get_setting('locker_postal_code', '00000');
$event_name_value = loopis_get_setting('event_name', 'saknas');

$event_name_history_value = maybe_unserialize(loopis_get_setting('event_name_history', serialize(array('🌳 LOOPIS på torget', '🛸 LOOPIS HQ'))));
if (!is_array($event_name_history_value)) {
    $event_name_history_value = array();
}
?>

<h1>⚙ <?php echo get_bloginfo('name'); ?></h1>
<hr>
<p class="small">💡 Här gör du inställningar för området.</p>

<?php if (!empty($settings_notice)) : ?>
    <div class="loopis-message <?php echo esc_attr($settings_notice_type); ?>">
        <p><?php echo esc_html($settings_notice); ?></p>
    </div>
<?php endif; ?>

<?php if ($can_loopis_admin) : ?>
    <h3>🦀 Admin</h3>
	<hr>
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
<?php endif; ?>

<?php
// Locker warning preview
$locker_warning_status = $locker_warning_value === '1' ? 'aktiv' : 'ej aktiv';
?>

<div class="admin-block">
<p>💡 Varning för skåp är <b><u><?php echo esc_html($locker_warning_status); ?></u></b>. Här nedanför är en förhandsvisning.</p>
</div> <!-- .admin-block -->
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

<?php if ($can_manage_options) : ?>
    <h3>👽 Webmaster</h3>
	<hr>
	<div class="loopis-form-wrapper">
    <form class="loopis-form" method="post" action="">
        <?php wp_nonce_field('loopis_settings_manage_options'); ?>
        <input type="hidden" name="loopis_settings_form" value="manage_options">

        <p>
            <label>
                <input type="checkbox" name="area_privacy" value="1" <?php checked($area_privacy_value, 'true'); ?>>
                Private area?
            </label>
        </p>
        <p>
            <label for="locker_id">Locker ID</label>
            <input id="locker_id" type="text" name="locker_id" value="<?php echo esc_attr($locker_id_value); ?>">
        </p>
        <p>
            <label for="locker_name">Locker name</label>
            <input id="locker_name" type="text" name="locker_name" value="<?php echo esc_attr($locker_name_value); ?>">
        </p>
        <p>
            <label for="locker_postal_code">Locker postal code</label>
            <input id="locker_postal_code" type="text" name="locker_postal_code" value="<?php echo esc_attr($locker_postal_code_value); ?>">
        </p>

        <p><input type="submit" value="Spara"></p>
    </form>
	</div>
<?php endif; ?>