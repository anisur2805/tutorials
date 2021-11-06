<?php
function rest_api_styles_scripts() {
    wp_enqueue_style( 'tuts', plugins_url( '/assets/css/tuts.css', __FILE__ ) );

    wp_register_script( 'tuts', plugins_url( '/assets/js/tuts.js', __FILE__ ), array( 'jquery' ), '', true );
    wp_enqueue_script( 'tuts' );

    wp_localize_script(
        'tuts',
        'tuts_opt',
        array( 'jsonUrl' => rest_url( 'wp/v2/tutorial' ) )
    );

}

add_action( 'wp_enqueue_scripts', 'rest_api_styles_scripts' );
