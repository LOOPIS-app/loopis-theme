<?php
/**
 * Standard LOOPIS mail footer for admin/user.
 * 
 * @return string HTML footer
 */

if (!defined('ABSPATH')) {
    exit;
}

function loopis_mail_footer(string $role = ''): string {
    if ($role === 'manager') {
        $icon = LOOPIS_THEME_URI . '/assets/img/LOOPIS_icon_admin.png';
        $text = 'Notifikation till admin på ' . get_bloginfo('name') . '.';
    } else {
        $icon = LOOPIS_THEME_URI . '/assets/img/LOOPIS_icon.png';
        $text = 'Gå till LOOPIS.app för att hantera annonsen eller skriva ett svar.';
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
