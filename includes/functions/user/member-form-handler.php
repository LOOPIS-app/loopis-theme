<?php
/**
 * Handle member details form submission.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('template_redirect', 'loopis_theme_hq_handle_member_form_post');

function loopis_theme_hq_handle_member_form_post() {
    if ('POST' !== ($_SERVER['REQUEST_METHOD'] ?? '')) {
        return;
    }

    if (!isset($_POST['loopis_member_nonce'])) {
        return;
    }

    $redirect_url = wp_get_referer();
    if (!$redirect_url) {
        $redirect_url = home_url('/user/?option=member-form');
    }

    if (!is_user_logged_in()) {
        wp_safe_redirect(add_query_arg(array(
            'member_form' => 'error',
            'member_form_fields' => 'general',
        ), $redirect_url));
        exit;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['loopis_member_nonce']));
    if (!wp_verify_nonce($nonce, 'loopis_member_form')) {
        wp_safe_redirect(add_query_arg(array(
            'member_form' => 'error',
            'member_form_fields' => 'general',
        ), $redirect_url));
        exit;
    }

    $user_id = get_current_user_id();

    $postcode_raw = sanitize_text_field(wp_unslash($_POST['wpum_postcode'] ?? ''));
    $phone_raw = sanitize_text_field(wp_unslash($_POST['wpum_phone'] ?? ''));
    $birthyear_raw = sanitize_text_field(wp_unslash($_POST['wpum_birthyear'] ?? ''));
    $gender = sanitize_key(wp_unslash($_POST['wpum_gender'] ?? ''));
    $area = sanitize_key(wp_unslash($_POST['wpum_area'] ?? ''));
    $active = isset($_POST['wpum_active']) ? 'true' : 'false';

    $postcode = preg_replace('/\D+/', '', $postcode_raw);
    $phone_digits = preg_replace('/\D+/', '', $phone_raw);
    $birthyear = preg_replace('/\D+/', '', $birthyear_raw);

    $allowed_genders = array('female', 'male', 'nonbinary', 'other', 'secret');
    $allowed_areas = array('1', '2', '3', '4', '5', 'other');
    $current_year = (int) wp_date('Y');

    $is_valid_postcode = (bool) preg_match('/^\d{5}$/', $postcode);
    $is_valid_phone = (bool) preg_match('/^\d{10}$/', $phone_digits);
    $is_valid_birthyear = (bool) preg_match('/^\d{4}$/', $birthyear)
        && (int) $birthyear >= 1900
        && (int) $birthyear <= $current_year;
    $is_valid_gender = in_array($gender, $allowed_genders, true);
    $is_valid_area = in_array($area, $allowed_areas, true);

    if (!$is_valid_postcode || !$is_valid_phone || !$is_valid_birthyear || !$is_valid_gender || !$is_valid_area) {
        $invalid_fields = array();

        if (!$is_valid_postcode) {
            $invalid_fields[] = 'wpum_postcode';
        }

        if (!$is_valid_phone) {
            $invalid_fields[] = 'wpum_phone';
        }

        if (!$is_valid_birthyear) {
            $invalid_fields[] = 'wpum_birthyear';
        }

        if (!$is_valid_gender) {
            $invalid_fields[] = 'wpum_gender';
        }

        if (!$is_valid_area) {
            $invalid_fields[] = 'wpum_area';
        }

        // Persist fields that passed validation to avoid unnecessary refilling.
        if ($is_valid_postcode) {
            update_user_meta($user_id, 'wpum_postcode', $postcode);
        }

        if ($is_valid_phone) {
            $phone = substr($phone_digits, 0, 3) . '-' . substr($phone_digits, 3);
            update_user_meta($user_id, 'wpum_phone', $phone);
        }

        if ($is_valid_birthyear) {
            update_user_meta($user_id, 'wpum_birthyear', $birthyear);
        }

        if ($is_valid_gender) {
            update_user_meta($user_id, 'wpum_gender', $gender);
        }

        if ($is_valid_area) {
            update_user_meta($user_id, 'wpum_area', $area);
        }

        update_user_meta($user_id, 'wpum_active', $active);

        wp_safe_redirect(add_query_arg(array(
            'member_form' => 'error',
            'member_form_fields' => implode(',', $invalid_fields),
        ), $redirect_url));
        exit;
    }

    // Store phone in canonical form XXX-XXXXXXX.
    $phone = substr($phone_digits, 0, 3) . '-' . substr($phone_digits, 3);

    update_user_meta($user_id, 'wpum_postcode', $postcode);
    update_user_meta($user_id, 'wpum_phone', $phone);
    update_user_meta($user_id, 'wpum_birthyear', $birthyear);
    update_user_meta($user_id, 'wpum_gender', $gender);
    update_user_meta($user_id, 'wpum_area', $area);
    update_user_meta($user_id, 'wpum_active', $active);

    wp_safe_redirect(add_query_arg('member_form', 'success', $redirect_url));
    exit;
}
