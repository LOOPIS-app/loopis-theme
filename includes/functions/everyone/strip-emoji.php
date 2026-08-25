<?php
/**
 * Remove emoji characters from a string.
 * Useful for titles and excerpts where emojis should not be rendered in compact lists.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('strip_emoji')) {
    function strip_emoji($text) {
        if (!is_string($text)) {
            return '';
        }

        return preg_replace('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', '', $text);
    }
}
