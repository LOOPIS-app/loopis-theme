<?php
// Load environment variables from theme .env if present (skip on live)
if (!(defined('WP_LIVE') && WP_LIVE)) {
    $loopis_env_path = __DIR__ . '/.env';
    if (is_readable($loopis_env_path)) {
        $loopis_env_lines = file($loopis_env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($loopis_env_lines as $loopis_env_line) {
            $loopis_env_line = trim($loopis_env_line);
            if ($loopis_env_line === '' || str_starts_with($loopis_env_line, '#')) {
                continue;
            }
            if (!str_contains($loopis_env_line, '=')) {
                continue;
            }
            [$loopis_env_key, $loopis_env_value] = explode('=', $loopis_env_line, 2);
            $loopis_env_key = trim($loopis_env_key);
            $loopis_env_value = trim($loopis_env_value);
            if ($loopis_env_key !== '' && getenv($loopis_env_key) === false) {
                putenv("{$loopis_env_key}={$loopis_env_value}");
                $_ENV[$loopis_env_key] = $loopis_env_value;
            }
        }
    }

    // Define Stripe API key from .env file
    if (!defined('LOOPIS_STRIPE_SECRET_KEY')) {
        define('LOOPIS_STRIPE_SECRET_KEY', getenv('LOOPIS_STRIPE_SECRET_KEY') ?: '');
    }
}

// Define empty Stripe API key (fallback if not defined in wp-config or above)
if (!defined('LOOPIS_STRIPE_SECRET_KEY')) {
    define('LOOPIS_STRIPE_SECRET_KEY', '');
}
