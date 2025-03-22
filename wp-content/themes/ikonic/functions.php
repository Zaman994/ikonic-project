<?php

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/*
* Enqueue styles and scripts
*/
function custom_theme_enqueue_styles()
{
    // Enqueue main stylesheet
    wp_enqueue_style('custom-theme-style', get_stylesheet_uri(), array(), null);

    // Enqueue main JS
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/custom.js', array('jquery'), null, true);

    // Localize the script with data
    wp_localize_script('custom-script', 'custom_script_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'), // AJAX URL
        'nonce'    => wp_create_nonce('custom_script_nonce'), // Nonce for security
        'home_url' => home_url(), // Home URL
    ));
}
add_action('wp_enqueue_scripts', 'custom_theme_enqueue_styles');

/*
* Admin Panel Assets
*/
function enqueue_custom_admin_scripts($hook)
{
    // Load script only on specific admin pages if needed
    if ($hook !== 'toplevel_page_unused-media') {
        return;
    }

    wp_enqueue_script('custom-admin-script', get_template_directory_uri() . '/js/custom-admin.js', array('jquery'), null, true);

    // Localize script for AJAX
    wp_localize_script('custom-admin-script', 'custom_admin_script_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('custom_admin_script_nonce'),
    ));
}
add_action('admin_enqueue_scripts', 'enqueue_custom_admin_scripts');

/*
* Register Menu and Theme Setup
*/
if (!function_exists('register_custom_menu')) {
    function register_custom_menu()
    {
        register_nav_menu('primary', __('Primary Menu', 'ikonic'));
    }
}
add_action('init', 'register_custom_menu');

if (!function_exists('custom_theme_setup')) {
    function custom_theme_setup()
    {
        // Add support for custom logo
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
        ));

        // Add support for post thumbnails (featured images)
        add_theme_support('post-thumbnails');

        // Add support for automatic feed links
        add_theme_support('automatic-feed-links');

        // Enable HTML5 markup
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

        // Add theme support for title tag
        add_theme_support('title-tag');
    }
}
add_action('after_setup_theme', 'custom_theme_setup');

class Custom_Walker_Nav_Menu extends Walker_Nav_Menu
{
    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= '<ul class="sub-menu">';
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $class_names = implode(' ', $item->classes);
        $output .= '<li class="' . esc_attr($class_names) . '">';
        $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
    }
}


/*
* Include Required Files
*/
$includes = array(
    '/includes/custom-post-types.php',
    '/includes/filter.php',
    '/includes/unused-media.php',
);

foreach ($includes as $file) {
    $filepath = get_template_directory() . $file;
    if (file_exists($filepath)) {
        require_once $filepath;
    }
}
