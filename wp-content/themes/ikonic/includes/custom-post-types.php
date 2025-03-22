<?php

/*
* Register Project Post type
*/

function create_project_post_type()
{
    $labels = array(
        'name'                  => __('Projects', 'ikonic'),
        'singular_name'         => __('Project', 'ikonic'),
        'menu_name'             => __('Projects', 'ikonic'),
        'name_admin_bar'        => __('Project', 'ikonic'),
        'add_new'               => __('Add New', 'ikonic'),
        'add_new_item'          => __('Add New Project', 'ikonic'),
        'new_item'              => __('New Project', 'ikonic'),
        'edit_item'             => __('Edit Project', 'ikonic'),
        'view_item'             => __('View Project', 'ikonic'),
        'all_items'             => __('All Projects', 'ikonic'),
        'search_items'          => __('Search Projects', 'ikonic'),
        'parent_item_colon'     => __('Parent Projects:', 'ikonic'),
        'not_found'             => __('No projects found.', 'ikonic'),
        'not_found_in_trash'    => __('No projects found in Trash.', 'ikonic'),
    );

    $args = array(
        'label'                 => __('Projects', 'ikonic'),
        'description'           => __('A custom post type for showcasing projects.', 'ikonic'),
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'projects'),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-portfolio', // WordPress Dashicon
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'custom-fields', 'comments'),
        'taxonomies'            => array('category', 'post_tag'), // Enables categories and tags
        'show_in_rest'          => true, // Enables Gutenberg support
        'show_in_nav_menus'     => true,
    );

    register_post_type('project', $args);
}
add_action('init', 'create_project_post_type');


/*
* Register Project Api endpoint
*/

function get_projects_api_endpoint($request)
{
    $args = array(
        'post_type' => 'project',
        'posts_per_page' => -1,
    );
    $projects = get_posts($args);
    $data = array();
    foreach ($projects as $project) {
        $data[] = array(
            'title' => $project->post_title,
            'url' => get_field('project_url', $project->ID),
            'start_date' => get_field('project_start_date', $project->ID),
            'end_date' => get_field('project_end_date', $project->ID),
        );
    }
    return rest_ensure_response($data);
}

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/projects', array(
        'methods' => 'GET',
        'callback' => 'get_projects_api_endpoint',
    ));
});
