<?php
/**
 * Support notification mail content template.
 * 
 * @param string $mail_intro The introductory text for the mail
 * @param string $mail_outro The concluding text for the mail
 * @param string $post_content The content of the post
 * @return string HTML wrapped content
 */

if (!defined('ABSPATH')) {
    exit;
}

function loopis_mail_template(string $mail_intro, string $mail_outro, string $post_content): string {
    return '<p>' . $mail_intro . '</p>
    <p style="padding: 10px;font-size: 18px;font-style: italic;background: #f5f5f5;border-radius: 10px">' . $post_content . '</p>
    <p>' . $mail_outro . '</p>';
}