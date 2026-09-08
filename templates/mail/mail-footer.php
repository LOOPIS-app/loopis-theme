<?php
/**
 * Standard LOOPIS mail footer for admin/user.
 * 
 * @return string HTML footer
 */

if (!defined('ABSPATH')) {
    exit;
}

function loopis_mail_footer(string $text = ''): string {
    if ($text === 'manager') {
        $icon = LOOPIS_THEME_URI . '/assets/img/LOOPIS_icon_admin.png';
        $text = 'Notifikation till admin på ' . get_bloginfo('name') . '.';
    } elseif(!empty($text)){
        $icon = LOOPIS_THEME_URI . '/assets/img/LOOPIS_icon.png';
    } else {
        $icon = LOOPIS_THEME_URI . '/assets/img/LOOPIS_icon.png';
        $text = 'Ett mail från LOOPIS.app';
    }

    $html = '<table style="border-collapse: collapse;border-top: 1px solid">'
        . '<tbody>'
        . '<tr>'
        . '<td style="padding: 5px 5px 0 0"><img style="height: 32px" src="' . esc_url($icon) . '" alt="LOOPIS_icon" /></td>'
        . '<td style="padding: 5px 10px 0 0">'
        . '<p style="font-size: 11px;font-style: italic;margin: 0;line-height: 1.2">' . esc_html($text) . '</p>'
        . '</td>'
        . '</tr>'
        . '</tbody>'
        . '</table>';

    return $html;
}
