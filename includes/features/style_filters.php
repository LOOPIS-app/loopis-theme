<?php
/**
 * Style filters and related functions.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

/**
 * Function: wrap_links
 * Description: Wrap, shorten & make links clickable
 * 
 * @return string HTML output
 */
function remove_global_styles() {
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles' );
	wp_dequeue_script( 'wp-embed' );
}

add_action('init', 'remove_global_styles');

function remove_theme_styles() {
	wp_dequeue_style( 'gridzone-style' );
    wp_deregister_style( 'gridzone-style' );
}

add_action( 'wp_print_styles', 'remove_theme_styles', 100 );

function remove_wp_styles() {
    wp_dequeue_style( 'wp-block-library' );
    wp_deregister_style( 'wp-block-library' );
}
add_action('wp_enqueue_scripts', 'remove_wp_styles', 100);
