<?php
/*
=== Load parent Styles ===
*/
function dc_enqueue_styles() {
	wp_enqueue_style( 'divi-parent', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'divi-parent' ) );
}
add_action( 'wp_enqueue_scripts', 'dc_enqueue_styles' );