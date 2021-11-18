<?php
/*
Plugin Name: Tutorials
Plugin URI: https://www.github.com/anisur2805/tutorials
Description: Amazing Tutorials
Author: Anisur Rahman
Version: 1.0
Author URI: https://www.github.com/anisur2805
 */
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Load require files
 */
require_once plugin_dir_path( __FILE__ ) . './shortcode.php';
require_once plugin_dir_path( __FILE__ ) . './enqueue.php';

/**
 * Register post type
 *
 * @return void
 */
function rest_api_tutorial() {

    $labels = array(
        'name'               => esc_html__( 'Tutorials', 'rest_api_tutorial' ),
        'singular_name'      => esc_html__( 'Tutorial', 'rest_api_tutorial' ),
        'add_new'            => esc_html__( 'Add new Tutorial', 'rest_api_tutorial' ),
        'add_new_item'       => esc_html__( 'Add new tutorial', 'rest_api_tutorial' ),
        'edit_item'          => esc_html__( 'Edit tutorial', 'rest_api_tutorial' ),
        'new_item'           => esc_html__( 'New tutorial', 'rest_api_tutorial' ),
        'all_items'          => esc_html__( 'All tutorials', 'rest_api_tutorial' ),
        'view_item'          => esc_html__( 'View tutorial', 'rest_api_tutorial' ),
        'search_items'       => esc_html__( 'Search tutorials', 'rest_api_tutorial' ),
        'not_found'          => esc_html__( 'No tutorials found', 'rest_api_tutorial' ),
        'not_found_in_trash' => esc_html__( 'No tutorials found in trash', 'rest_api_tutorial' ),
        'parent_item_colon'  => '',
        'menu_name'          => esc_html__( 'Tutorials', 'rest_api_tutorial' ),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'tutorial', 'with_front' => false ),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-portfolio',
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'          => true,
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'rest_base'             => 'tutorial',
    );

    register_post_type( 'tutorial', $args );

	/**
	 * Register Category 
	 */
    register_taxonomy( 'tutorial-category', 'tutorial', array(
        'hierarchical'          => true,
        'labels'                => array(
            'name'              => esc_html__( 'Category', 'rest_api_tutorial' ),
            'singular_name'     => esc_html__( 'Category', 'rest_api_tutorial' ),
            'search_items'      => esc_html__( 'Search category', 'rest_api_tutorial' ),
            'all_items'         => esc_html__( 'All categories', 'rest_api_tutorial' ),
            'parent_item'       => esc_html__( 'Parent category', 'rest_api_tutorial' ),
            'parent_item_colon' => esc_html__( 'Parent category', 'rest_api_tutorial' ),
            'edit_item'         => esc_html__( 'Edit category', 'rest_api_tutorial' ),
            'update_item'       => esc_html__( 'Update category', 'rest_api_tutorial' ),
            'add_new_item'      => esc_html__( 'Add new category', 'rest_api_tutorial' ),
            'new_item_name'     => esc_html__( 'New category', 'rest_api_tutorial' ),
            'menu_name'         => esc_html__( 'Categories', 'rest_api_tutorial' ),
        ),
        'rewrite'               => array(
            'slug'         => 'tutorial-category',
            'with_front'   => true,
            'hierarchical' => true,
        ),
        'show_in_nav_menus'     => true,
        'show_tagcloud'         => true,
        'show_admin_column'     => true,
        'show_in_rest'          => true,
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_base'             => 'tutorial_category',
    ) );

    register_taxonomy( 'tutorial-tag', 'tutorial', array(
        'hierarchical'          => false,
        'labels'                => array(
            'name'              => esc_html__( 'Tutorials tags', 'rest_api_tutorial' ),
            'singular_name'     => esc_html__( 'Tutorials tag', 'rest_api_tutorial' ),
            'search_items'      => esc_html__( 'Search tutorial tags', 'rest_api_tutorial' ),
            'all_items'         => esc_html__( 'All tutorial tags', 'rest_api_tutorial' ),
            'parent_item'       => esc_html__( 'Parent tutorial tags', 'rest_api_tutorial' ),
            'parent_item_colon' => esc_html__( 'Parent tutorial tag:', 'rest_api_tutorial' ),
            'edit_item'         => esc_html__( 'Edit tutorial tag', 'rest_api_tutorial' ),
            'update_item'       => esc_html__( 'Update tutorial tag', 'rest_api_tutorial' ),
            'add_new_item'      => esc_html__( 'Add new tutorial tag', 'rest_api_tutorial' ),
            'new_item_name'     => esc_html__( 'New tutorial tag', 'rest_api_tutorial' ),
            'menu_name'         => esc_html__( 'Tags', 'rest_api_tutorial' ),
        ),
        'rewrite'               => array(
            'slug'         => 'tutorial-tag',
            'with_front'   => true,
            'hierarchical' => false,
        ),
        'show_in_rest'          => true,
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'rest_base'             => 'tutorial_tag',
    ) );
}

add_action( 'init', 'rest_api_tutorial' );


/**
 * Register rest api available fields
 *
 * @return void
 */
function rest_api_tutorial_register_rest_fields(){
 
    register_rest_field('tutorial',
        'tutorial_category_attr',
        array(
            'get_callback'    => 'rest_api_tutorial_categories',
            'update_callback' => null,
            'schema'          => null
        )
    );
 
    register_rest_field('tutorial',
        'tutorial_tag_attr',
        array(
            'get_callback'    => 'rest_api_tutorial_tags',
            'update_callback' => null,
            'schema'          => null
        )
    );
 
    register_rest_field('tutorial',
        'tutorial_image_src',
        array(
            'get_callback'    => 'rest_api_tutorial_image',
            'update_callback' => null,
            'schema'          => null
        )
    );
 
}
add_action('rest_api_init','rest_api_tutorial_register_rest_fields');


function rest_api_tutorial_categories($object,$field_name,$request){
    $terms_result = array();
    $terms =  wp_get_post_terms( $object['id'], 'tutorial-category');
    foreach ($terms as $term) {
        $terms_result[$term->term_id] = array($term->name,get_term_link($term->term_id));
    }
    return $terms_result;
}
 
function rest_api_tutorial_tags($object,$field_name,$request){
    $terms_result = array();
    $terms =  wp_get_post_terms( $object['id'], 'tutorial-tag');
    foreach ($terms as $term) {
        $terms_result[$term->term_id] = array($term->name,get_term_link($term->term_id));
    }
    return $terms_result;
}
 
function rest_api_tutorial_image($object,$field_name,$request){
 
    $img = wp_get_attachment_image_src($object['featured_media'],'full');
     
    return $img[0];
}