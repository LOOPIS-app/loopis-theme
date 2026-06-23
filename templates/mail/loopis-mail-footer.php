<?php
/**
 * Standard LOOPIS mail footer.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Set source
if (is_main_blog()) {
    $sender = 'LOOPIS.app';    
    } else {
    $area = get_bloginfo('name');  // "Site Title" in WordPress settings
    $sender = 'LOOPIS - ' . $area;    
}

// Set icon and text
$icon = LOOPIS_THEME_URI . '/assets/img/LOOPIS_icon.png';
$text = 'Detta är en notifikation från ' . esc_html($sender);
?>

<table style="border-collapse: collapse;border-top: 1px solid">
<tbody>
<tr>
<td style="padding: 5px 5px 0 0"><img style="height: 32px" src="<?php echo esc_url($icon); ?>" alt="LOOPIS_icon" /></td>
<td style="padding: 5px 10px 0 0">
<p style="font-size: 11px;font-style: italic;margin: 0;line-height: 1.2"><?php echo esc_html($text); ?></p>
</td>
</tr>
</tbody>
</table>