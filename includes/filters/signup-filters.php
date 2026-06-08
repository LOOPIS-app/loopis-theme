<?php
/**
 * Filters and actions affecting user signup and registration.
 * 
 * Always included in functions.php
 * 
 * Creates a LOOPIS username (firstname-lastname) on signup with WPUM registration form.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$GLOBALS['loopis_signup_username'] = '';

/**
 * Build a LOOPIS username from first and last name.
 */
function loopis_build_signup_username($first_name, $last_name) {
    $first_name = sanitize_text_field(remove_accents($first_name));
    $last_name = sanitize_text_field(remove_accents($last_name));

    if (function_exists('mb_convert_case')) {
        $first_name = mb_convert_case($first_name, MB_CASE_TITLE, 'UTF-8');
        $last_name = mb_convert_case($last_name, MB_CASE_TITLE, 'UTF-8');
    } else {
        $first_name = ucwords(strtolower($first_name));
        $last_name = ucwords(strtolower($last_name));
    }

    $raw_username = trim($first_name . '-' . $last_name);
    $raw_username = preg_replace('/\s+/', '-', $raw_username);
    $raw_username = preg_replace('/-+/', '-', $raw_username);
    $raw_username = trim($raw_username, '-');

    return sanitize_user($raw_username, true);
}

/**
 * Generate the first available LOOPIS username for WPUM signup.
 */
function loopis_generate_available_signup_username($first_name, $last_name, $excluded_user_id = 0) {
    $base_username = loopis_build_signup_username($first_name, $last_name);
    if ('' === $base_username) {
        return '';
    }

    $username = $base_username;
    $suffix = 2;

    while (loopis_is_signup_username_taken($username, $excluded_user_id)) {
        $username = sanitize_user($base_username . '-' . $suffix, true);
        $suffix++;
    }

    return $username;
}

/**
 * Check whether a generated signup username is already taken by another user.
 */
function loopis_is_signup_username_taken($username, $excluded_user_id = 0) {
    $user_id = username_exists($username);
    if ($user_id && (int) $user_id !== (int) $excluded_user_id) {
        return true;
    }

    $user = get_user_by('slug', sanitize_title($username));

    return $user && (int) $user->ID !== (int) $excluded_user_id;
}

/**
 * Stage the generated LOOPIS username before WPUM creates the user.
 */
function loopis_prepare_signup_username($values) {
    $first_name = isset($values['register']['user_firstname']) ? $values['register']['user_firstname'] : '';
    $last_name = isset($values['register']['user_lastname']) ? $values['register']['user_lastname'] : '';

    $GLOBALS['loopis_signup_username'] = loopis_generate_available_signup_username($first_name, $last_name);
}
add_action('wpum_before_registration_start', 'loopis_prepare_signup_username');

/**
 * Use the generated LOOPIS username when WordPress creates the user login.
 */
function loopis_filter_signup_user_login($user_login) {
    if (!empty($GLOBALS['loopis_signup_username'])) {
        return $GLOBALS['loopis_signup_username'];
    }

    return $user_login;
}
add_filter('pre_user_login', 'loopis_filter_signup_user_login');

/**
 * Keep user_nicename aligned with the generated LOOPIS username on insert.
 */
function loopis_filter_signup_user_nicename($user_nicename) {
    if (!empty($GLOBALS['loopis_signup_username'])) {
        return sanitize_title($GLOBALS['loopis_signup_username']);
    }

    return $user_nicename;
}
add_filter('pre_user_nicename', 'loopis_filter_signup_user_nicename');

/**
 * Set the final LOOPIS login identity during WPUM registration.
 */
function loopis_set_signup_username($user_data, $user_id, $form) {
    unset($form);

    $username = !empty($GLOBALS['loopis_signup_username'])
        ? $GLOBALS['loopis_signup_username']
        : loopis_generate_available_signup_username(
            isset($user_data['first_name']) ? $user_data['first_name'] : '',
            isset($user_data['last_name']) ? $user_data['last_name'] : '',
            $user_id
        );

    if ('' === $username) {
        return $user_data;
    }

    $user_data['ID'] = $user_id;
    $user_data['user_nicename'] = sanitize_title($username);
    $user_data['display_name'] = $username;

    return $user_data;
}
add_filter('wpum_registration_user_data', 'loopis_set_signup_username', 20, 3);

