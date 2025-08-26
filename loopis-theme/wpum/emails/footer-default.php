<?php
/**
 * The Template for displaying the footer section of the emails.
 *
 * This template can be overridden by copying it to yourtheme/wpum/emails/footer-default.php
 *
 * HOWEVER, on occasion WPUM will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<table style="border-collapse: collapse">
  <tr>
    <td style="vertical-align: middle;padding-right: 5px">
      <img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/LOOPIS_icon.png" alt="LOOPIS_logo" style="height: 32px">
    </td>
    <td style="vertical-align: middle;width: 275px">
      <p style="font-size: 11px;font-style: italic;margin: 0;line-height: 1.2">
        Information från <a href="https://loopis.app">LOOPIS.app</a> <br> angående ditt användarkonto.
      </p>
    </td>
  </tr>
</table>

	</body>
</html>
