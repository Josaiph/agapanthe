<?php

if (!is_admin()){
	add_action( 'wp_enqueue_scripts', 'am_add_javascript' );
	add_action('wp_print_styles', 'am_add_css');
}

load_theme_textdomain( 'am', TEMPLATEPATH . '/languages' );

add_filter('body_class','am_browser_body_class');
add_filter('the_excerpt', 'am_get_the_excerpt');
add_filter('get_the_excerpt', 'am_get_the_excerpt');
add_action('wp_head','am_IE_head');
add_action('wp_head','am_analytics');
add_action( 'widgets_init', 'am_the_widgets_init' );
add_action('widgets_init', 'am_unregister_default_wp_widgets', 1);
add_filter('the_title','am_has_title');
add_filter( 'the_content', 'am_texturize_shortcode_before' );
add_filter( 'the_content', 'am_formatter', 99 );
add_filter( 'widget_text', 'am_formatter', 99 );
add_action( 'admin_print_styles', 'am_ot_custom_style', 20 );
add_action('init','am_possibly_redirect');
add_action( 'admin_menu', 'am_remove_admin_menu_pages' );
add_action('init', 'am_redirect_dashboard');
add_filter( 'wp_title', 'am_wp_title', 10, 2 );

// This theme uses post thumbnails
add_theme_support( 'post-thumbnails' );

// Add default posts and comments RSS feed links to head
add_theme_support( 'automatic-feed-links' );

// Allow Shortcodes in Sidebar Widgets
add_filter('widget_text', 'do_shortcode');

//Set the Full Width Image value
if ( ! isset( $content_width ) ) $content_width = 900;
?>