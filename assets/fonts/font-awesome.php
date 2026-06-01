<?php
/**
 * Use of Font Awesome (Solid + Regular)
 *
 * Included in header.php + versions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<link rel="preload" href="<?php echo esc_url( LOOPIS_THEME_URI . '/assets/fonts/css/fontawesome.min.css' ); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="<?php echo esc_url( LOOPIS_THEME_URI . '/assets/fonts/css/regular.min.css' ); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="<?php echo esc_url( LOOPIS_THEME_URI . '/assets/fonts/css/solid.min.css' ); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link href="<?php echo esc_url( LOOPIS_THEME_URI . '/assets/fonts/css/fontawesome.min.css' ); ?>" rel="stylesheet">
    <link href="<?php echo esc_url( LOOPIS_THEME_URI . '/assets/fonts/css/regular.min.css' ); ?>" rel="stylesheet">
    <link href="<?php echo esc_url( LOOPIS_THEME_URI . '/assets/fonts/css/solid.min.css' ); ?>" rel="stylesheet">
</noscript>