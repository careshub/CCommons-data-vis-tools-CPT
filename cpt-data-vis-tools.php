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
        'taxonomies' => array( 'category', 'tax_data_tool_types' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 27,
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