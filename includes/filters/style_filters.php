<?php
/**
 * Filters and actions affecting styles.
 * 
 * Migrated from earlier use in Code Snippets plugin.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove global styles and scripts.
 * 
 * @return void
 */
function remove_global_styles() {
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles' );
}

add_action('init', 'remove_global_styles');


/**
 * Dequeue the default WordPress embed script on the frontend.
 *
 * @return void
 */
function remove_wp_embed_script() {
	wp_dequeue_script( 'wp-embed' );
}

add_action('wp_enqueue_scripts', 'remove_wp_embed_script', 100);


/**
 * Remove default block-library styles from frontend output.
 *
 * @return void
 */
function remove_wp_styles() {
    wp_dequeue_style( 'wp-block-library' );
    wp_deregister_style( 'wp-block-library' );
}
add_action('wp_enqueue_scripts', 'remove_wp_styles', 100);