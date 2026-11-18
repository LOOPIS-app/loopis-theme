<?php
/**
 * Settings page for area - Webmaster section
 */

// Include settings update helper on this page
require_once LOOPIS_THEME_DIR . '/includes/functions/admin-extra/update-setting.php';

$settings_notice = '';
$settings_notice_type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted_form = isset($_POST['loopis_settings_form']) ? sanitize_key(wp_unslash($_POST['loopis_settings_form'])) : '';

    if ($submitted_form === 'manage_options') {
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

        $settings_notice = $ok ? '✅ Inställningar sparade.' : '⚠️ Kunde inte spara alla inställningar.';
        $settings_notice_type = $ok ? 'success' : 'error';
    }
}

$area_privacy_value = loopis_get_setting('area_privacy', 'false');
$locker_id_value = loopis_get_setting('locker_id', '00000');
$locker_name_value = loopis_get_setting('locker_name', 'locker');
$locker_postal_code_value = loopis_get_setting('locker_postal_code', '00000');
?>

<h1>⚙ <?php echo get_bloginfo('name'); ?></h1>
<hr>
<p class="small">💡 Här gör webmaster inställningar för området.</p>

<?php if (!empty($settings_notice)) : ?>
    <div class="loopis-message <?php echo esc_attr($settings_notice_type); ?>">
        <p><?php echo esc_html($settings_notice); ?></p>
    </div>
<?php endif; ?>

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