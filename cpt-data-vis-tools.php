<?php

//Define the Data Vis Tools custom post type

add_action( 'init', 'register_cpt_data_vis_tool' );

function register_cpt_data_vis_tool() {

    $labels = array(
        'name' => _x( 'Data Vis Tools', 'data_vis_tool' ),
        'singular_name' => _x( 'Data Vis Tool', 'data_vis_tool' ),
        'add_new' => _x( 'Add New', 'data_vis_tool' ),
        'add_new_item' => _x( 'Add New Data Vis Tool', 'data_vis_tool' ),
        'edit_item' => _x( 'Edit Data Vis Tool', 'data_vis_tool' ),
        'new_item' => _x( 'New Data Vis Tool', 'data_vis_tool' ),
        'view_item' => _x( 'View Data Vis Tool', 'data_vis_tool' ),
        'search_items' => _x( 'Search Data Vis Tools', 'data_vis_tool' ),
        'not_found' => _x( 'No data vis tools found', 'data_vis_tool' ),
        'not_found_in_trash' => _x( 'No data vis tools found in Trash', 'data_vis_tool' ),
        'parent_item_colon' => _x( 'Parent Data Vis Tool:', 'data_vis_tool' ),
        'menu_name' => _x( 'Data Vis Tools', 'data_vis_tool' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Used to add tools to the data vis page.',
        'supports' => array( 'title', 'editor' ),
        'taxonomies' => array( 'data_vis_tool_categories' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 49,
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'//,
         //'map_meta_cap'    => true

    );

    register_post_type( 'data_vis_tool', $args );
}

add_action( 'init', 'register_taxonomy_data_vis_tool_categories' );

function register_taxonomy_data_vis_tool_categories() {

    $labels = array(
        'name' => _x( 'Data Vis Tool Categories', 'data_vis_tool_categories' ),
        'singular_name' => _x( 'Data Vis Tool Category', 'data_vis_tool_categories' ),
        'search_items' => _x( 'Search Data Vis Tool Categories', 'data_vis_tool_categories' ),
        'popular_items' => _x( 'Popular Data Vis Tool Categories', 'data_vis_tool_categories' ),
        'all_items' => _x( 'All Data Vis Tool Categories', 'data_vis_tool_categories' ),
        'parent_item' => _x( 'Parent Data Vis Tool Category', 'data_vis_tool_categories' ),
        'parent_item_colon' => _x( 'Parent Data Vis Tool Category:', 'data_vis_tool_categories' ),
        'edit_item' => _x( 'Edit Data Vis Tool Category', 'data_vis_tool_categories' ),
        'update_item' => _x( 'Update Data Vis Tool Category', 'data_vis_tool_categories' ),
        'add_new_item' => _x( 'Add New Data Vis Tool Category', 'data_vis_tool_categories' ),
        'new_item_name' => _x( 'New Data Vis Tool Category', 'data_vis_tool_categories' ),
        'separate_items_with_commas' => _x( 'Separate data vis tool categories with commas', 'data_vis_tool_categories' ),
        'add_or_remove_items' => _x( 'Add or remove data vis tool categories', 'data_vis_tool_categories' ),
        'choose_from_most_used' => _x( 'Choose from the most used data vis tool categories', 'data_vis_tool_categories' ),
        'menu_name' => _x( 'Data Vis Tool Categories', 'data_vis_tool_categories' ),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'hierarchical' => true,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'data_vis_tool_categories', array('data_vis_tool'), $args );
}