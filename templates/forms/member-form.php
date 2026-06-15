<?php
/**
 * Render and validate-friendly view for member profile data.
 *
 * Responsibilities:
 * - Preload current usermeta values for the form fields.
 * - Read validation state from query parameters after POST redirect.
 * - Show field-level guidance for invalid/missing values.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$user_id = get_current_user_id();
$wpum_postcode = get_user_meta($user_id, 'wpum_postcode', true);
$wpum_phone = get_user_meta($user_id, 'wpum_phone', true);
$wpum_birthyear = get_user_meta($user_id, 'wpum_birthyear', true);
$wpum_gender = get_user_meta($user_id, 'wpum_gender', true);
$wpum_area = get_user_meta($user_id, 'wpum_area', true);
$wpum_active = get_user_meta($user_id, 'wpum_active', true);
$wpum_active_checked = in_array((string) $wpum_active, array('1', 'true', 'yes', 'on'), true);

// Status from handler redirect after submit.
$member_form_status = sanitize_key(wp_unslash($_GET['member_form'] ?? ''));
$member_form_fields_raw = sanitize_text_field(wp_unslash($_GET['member_form_fields'] ?? ''));
$member_form_fields = array();

if (!empty($member_form_fields_raw)) {
    foreach (explode(',', $member_form_fields_raw) as $field) {
        $field_key = sanitize_key($field);
        if (!empty($field_key)) {
            $member_form_fields[] = $field_key;
        }
    }
}

$member_form_fields = array_values(array_unique($member_form_fields));

// Field-specific helper text used directly under each label.
$member_form_field_messages = array(
    'wpum_postcode' => 'Ange 5 siffror.',
    'wpum_phone' => 'Ange 10 siffror, bindestreck valfritt (t.ex. 070-1234567).',
    'wpum_birthyear' => 'Ange 4 siffror.',
    'wpum_gender' => 'Välj ett alternativ i listan.',
    'wpum_area' => 'Välj ett alternativ i listan.',
    'general' => 'Formuläret kunde inte verifieras. Försök igen.',
);

$postcode_digits = preg_replace('/\D+/', '', (string) $wpum_postcode);
$phone_digits = preg_replace('/\D+/', '', (string) $wpum_phone);
$birthyear_digits = preg_replace('/\D+/', '', (string) $wpum_birthyear);
$allowed_genders = array('female', 'male', 'nonbinary', 'other', 'secret');
$allowed_areas = array('1', '2', '3', '4', '5', 'other');
$current_year = (int) wp_date('Y');

// Derive invalid fields from currently stored values.
$member_form_current_invalid_fields = array();

if (!(bool) preg_match('/^\d{5}$/', $postcode_digits)) {
    $member_form_current_invalid_fields[] = 'wpum_postcode';
}

if (!(bool) preg_match('/^\d{10}$/', $phone_digits)) {
    $member_form_current_invalid_fields[] = 'wpum_phone';
}

if (!(bool) preg_match('/^\d{4}$/', $birthyear_digits)
    || (int) $birthyear_digits < 1900
    || (int) $birthyear_digits > $current_year
) {
    $member_form_current_invalid_fields[] = 'wpum_birthyear';
}

if (!in_array((string) $wpum_gender, $allowed_genders, true)) {
    $member_form_current_invalid_fields[] = 'wpum_gender';
}

if (!in_array((string) $wpum_area, $allowed_areas, true)) {
    $member_form_current_invalid_fields[] = 'wpum_area';
}

if (empty($member_form_fields)) {
    $member_form_fields = $member_form_current_invalid_fields;
}

// Show top warning only for general form errors (nonce/session), not field errors.
$member_form_fields = array_values(array_unique($member_form_fields));
$member_form_show_general_error = 'error' === $member_form_status && in_array('general', $member_form_fields, true);
$member_form_error_message = $member_form_field_messages['general'];
?>


<h2>📋 Medlemsregister</h2>
<hr>
<p class="small">💡 Ange dina aktuella uppgifter.</p>

<?php if ('success' === $member_form_status) : ?>
    <div class="loopis-message success">
        <p>Uppgifterna sparades.</p>
    </div>
<?php elseif ($member_form_show_general_error) : ?>
    <div class="loopis-message warning">
        <p><?php echo esc_html($member_form_error_message); ?></p>
    </div>
<?php endif; ?>

<div class="loopis-form-wrapper" id="member-form">
    <form class="loopis-form" method="post" action="" novalidate>
        <?php // Nonce verified in member-form-handler.php before saving. ?>
        <?php wp_nonce_field('loopis_member_form', 'loopis_member_nonce'); ?>

        <div>
            <label for="member-postcode">Postnummer</label>
            <?php if (in_array('wpum_postcode', $member_form_fields, true)) : ?>
                <p class="error"><?php echo esc_html($member_form_field_messages['wpum_postcode']); ?></p>
            <?php endif; ?>
            <input
                type="text"
                id="member-postcode"
                name="wpum_postcode"
                value="<?php echo esc_attr($wpum_postcode); ?>"
                placeholder="12345"
                inputmode="numeric"
                pattern="[0-9]{5}"
                maxlength="5"
                title="Ange 5 siffror"
                required
            >
        </div>

        <div>
            <label for="member-phone">Telefonnummer</label>
            <?php if (in_array('wpum_phone', $member_form_fields, true)) : ?>
                <p class="error"><?php echo esc_html($member_form_field_messages['wpum_phone']); ?></p>
            <?php endif; ?>
            <input
                type="tel"
                id="member-phone"
                name="wpum_phone"
                value="<?php echo esc_attr($wpum_phone); ?>"
                placeholder="070-1234567"
                inputmode="tel"
                pattern="[0-9]{3}-?[0-9]{7}"
                maxlength="11"
                title="Ange 10 siffror, bindestreck valfritt (t.ex. 070-1234567)"
                required
            >
        </div>

        <div>
            <label for="member-birthyear">Födelseår</label>
            <?php if (in_array('wpum_birthyear', $member_form_fields, true)) : ?>
                <p class="error"><?php echo esc_html($member_form_field_messages['wpum_birthyear']); ?></p>
            <?php endif; ?>
            <input
                type="text"
                id="member-birthyear"
                name="wpum_birthyear"
                value="<?php echo esc_attr($wpum_birthyear); ?>"
                placeholder="1234"
                inputmode="numeric"
                pattern="[0-9]{4}"
                maxlength="4"
                title="Ange 4 siffror"
                required
            >
        </div>

        <div>
            <label for="member-gender">Kön</label>
            <?php if (in_array('wpum_gender', $member_form_fields, true)) : ?>
                <p class="error"><?php echo esc_html($member_form_field_messages['wpum_gender']); ?></p>
            <?php endif; ?>
            <select id="member-gender" name="wpum_gender" required>
                <option value="">Välj</option>
                <option value="female" <?php selected($wpum_gender, 'female'); ?>>Kvinna</option>
                <option value="male" <?php selected($wpum_gender, 'male'); ?>>Man</option>
                <option value="nonbinary" <?php selected($wpum_gender, 'nonbinary'); ?>>Icke-binär</option>
                <option value="other" <?php selected($wpum_gender, 'other'); ?>>Annat</option>
                <option value="secret" <?php selected($wpum_gender, 'secret'); ?>>Vill ej ange</option>
            </select>
        </div>

        <div>
            <label for="member-area">Område</label>
            <?php if (in_array('wpum_area', $member_form_fields, true)) : ?>
                <p class="error"><?php echo esc_html($member_form_field_messages['wpum_area']); ?></p>
            <?php endif; ?>
            <select id="member-area" name="wpum_area" required>
                <option value="">Välj</option>
                <option value="1" <?php selected($wpum_area, '1'); ?>>Bagarmossen</option>
                <option value="2" <?php selected($wpum_area, '2'); ?>>Skarpnäck</option>
                <option value="3" <?php selected($wpum_area, '3'); ?>>Kärrtorp</option>
                <option value="4" <?php selected($wpum_area, '4'); ?>>Björkhagen</option>
                <option value="5" <?php selected($wpum_area, '5'); ?>>Enskede</option>
                <option value="other" <?php selected($wpum_area, 'other'); ?>>Annat</option>
            </select>
        </div>

        <div>
            <label for="member-active">Fortsätt vara medlem</label>
            <input
                type="checkbox"
                id="member-active"
                name="wpum_active"
                value="1"
                <?php checked($wpum_active_checked); ?>
            >
        </div>

        <button type="submit">Spara uppgifter</button>
    </form>
</div><!-- loopis-form -->