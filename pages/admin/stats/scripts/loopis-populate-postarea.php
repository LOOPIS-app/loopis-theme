<?php
/**
 * Populate wpum_postarea for users from their wpum_postcode.
 *
 * This file is included by demography.php after an authorized form submission.
 * Existing wpum_postarea values are preserved unless --force is supplied.
 * 
 * Created by CoPiilot, prompted by joxyzan.
 */

if (!defined('ABSPATH')) {
    if (!function_exists('WP_CLI')) {
        exit("Run this via WordPress only.\n");
    }
}

if (!function_exists('get_users') || !function_exists('update_user_meta')) {
    exit("WordPress user functions are unavailable.\n");
}

$arguments = $_SERVER['argv'] ?? [];
$dry_run = in_array('--dry-run', $arguments, true);
$force = in_array('--force', $arguments, true)
    || (isset($_POST['loopis_populate_postarea']) && $_POST['loopis_populate_postarea'] === 'force');

$log = function ($message) {
    if (function_exists('WP_CLI')) {
        WP_CLI::log($message);
        return;
    }

    echo $message . PHP_EOL;
};

$warn = function ($message) {
    if (function_exists('WP_CLI')) {
        WP_CLI::warning($message);
        return;
    }

    echo '[WARNING] ' . $message . PHP_EOL;
};

$city_function = defined('LOOPIS_USERS_DIR')
    ? LOOPIS_USERS_DIR . '/includes/functions/loopis-get-city.php'
    : dirname(__DIR__, 4) . '/loopis-users/includes/functions/loopis-get-city.php';

if (!function_exists('loopis_get_city')) {
    require_once $city_function;
}

$users = get_users([
    'fields'  => ['ID'],
    'blog_id' => 0,
    'number'  => -1,
    'orderby' => 'ID',
    'order'   => 'ASC',
]);

$processed = 0;
$updated = 0;
$skipped = 0;
$invalid = 0;

$log(sprintf('Found %d user(s).%s', count($users), $dry_run ? ' Dry run: no changes will be written.' : ''));

foreach ($users as $user) {
    $processed++;
    $user_id = (int) $user->ID;
    $existing_area = trim((string) get_user_meta($user_id, 'wpum_postarea', true));

    if ($existing_area !== '' && !$force) {
        $skipped++;
        continue;
    }

    $postcode = (string) get_user_meta($user_id, 'wpum_postcode', true);
    $area = loopis_get_city($postcode);

    if (!$area) {
        $invalid++;
        $warn("User {$user_id}: no city found for postcode.");
        continue;
    }

    $updated++;
    if (!$dry_run) {
        update_user_meta($user_id, 'wpum_postarea', $area);
    }
}

$log("Processed: {$processed}");
$log(($dry_run ? 'Would update' : 'Updated') . ": {$updated}");
$log("Skipped existing: {$skipped}");
$log("Without a matching city: {$invalid}");