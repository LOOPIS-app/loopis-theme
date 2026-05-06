<?php
/**
 * Standard LOOPIS mail headers
 * 
 * @return array Mail headers
 */

if (!defined('ABSPATH')) {
    exit;
}

function loopis_mail_headers(): array {
    return array(
        'From: LOOPIS <info@loopis.app>',
        'Content-Type: text/html; charset=UTF-8',
        'Content-Language: sv-SE',
        'X-Emoji-Service: twemoji'
    );
}
