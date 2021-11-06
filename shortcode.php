<?php
/**
 * Register shortcode `tuts`
 *
 * @param [type] $atts
 * @param [type] $content
 * @return void
 */
function rest_api_tutorial_shortcode_callback( $atts, $content = null ) {
    extract( shortcode_atts(
        array(
            'layout'    => 'grid', // grid / list
            'per_page'  => '3', // int number
            'start_cat' => '', // starting category ID
        ), $atts )
    );

    global $post;

    $query_options = array(
        'post_type'           => 'tutorial',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'posts_per_page'      => absint( $per_page ),
    );

    if ( isset( $start_cat ) & !empty( $start_cat ) ) {

        $tax_query_array = array(
            'tax_query' => array(
                array(
                    'taxonomy' => 'tutorial-category',
                    'field'    => 'ID',
                    'terms'    => $start_cat,
                    'operator' => 'IN',
                ) ),
        );

        $query_options = array_merge( $query_options, $tax_query_array );
    }

    $tuts = new WP_Query( $query_options );

    if ( $tuts->have_posts() ) {

        wp_enqueue_script( 'tuts' );

        $output = '';
        $class  = array();

        $class[] = 'recent-tuts';
        $class[] = esc_attr( $layout );

        $output .= '<div class="recent-tuts-wrapper">';

        $args = array(
            'orderby'      => 'name',
            'order'        => 'ASC',
            'fields'       => 'all',
            'child_of'     => 0,
            'parent'       => 0,
            'hide_empty'   => true,
            'hierarchical' => false,
            'pad_counts'   => false,
        );

        $terms = get_terms( 'tutorial-category', $args );

        if ( count( $terms ) != 0 ) {
            $output .= '<div class="term-filter" data-per-page="' . absint( $per_page ) . '">';

            if ( empty( $start_cat ) ) {
                $output .= '<a href="' . esc_url( get_post_type_archive_link( 'tutorial' ) ) . '" class="active">' . esc_html__( 'All', 'Rest API Tutorial' ) . '</a>';
            }

            foreach ( $terms as $term ) {

                $term_class = ( isset( $start_cat ) && !empty( $start_cat ) && $start_cat == $term->term_id ) ? $term->slug . ' active' : $term->slug;
                $term_data  = array();

                $term_data[] = 'data-filter="' . $term->slug . '"';
                $term_data[] = 'data-filter-id="' . $term->term_id . '"';

                $output .= '<a href="' . esc_url( get_term_link( $term->term_id, 'tutorial-category' ) ) . '" class="' . esc_attr( $term_class ) . '" ' . implode( ' ', $term_data ) . '>' . $term->name . '</a>';
            }

            $output .= '</div>';
        }

        $output .= '<ul class="' . implode( ' ', $class ) . '">';
        while ( $tuts->have_posts() ) {
            $tuts->the_post();

            $IMAGE = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full', false );

            $output .= '<li>';

            $output .= '<img src="' . esc_url( $IMAGE[0] ) . '" alt="' . esc_attr( get_the_title() ) . '" />';

            $output .= '<div class="tutorial-content">';

            $output .= '<div class="tutorial-category">';
            $output .= get_the_term_list( get_the_ID(), 'tutorial-category', '', ', ', '' );
            $output .= '</div>';

            if ( '' != get_the_title() ) {
                $output .= '<h4 class="tutorial-title entry-title">';
                $output .= '<a href="' . get_the_permalink() . '" title="' . get_the_title() . '" rel="bookmark">';
                $output .= get_the_title();
                $output .= '</a>';
                $output .= '</h4>';
            }

            if ( '' != get_the_excerpt() && $layout == 'grid' ) {
                $output .= '<div class="tutorial-excerpt">';
                $output .= get_the_excerpt();
                $output .= '</div>';
            }

            $output .= '<div class="tutorial-tag">';
            $output .= get_the_term_list( get_the_ID(), 'tutorial-tag', '', ' ', '' );
            $output .= '</div>';

            $output .= '</div>';

            $output .= '</li>';

        }
        wp_reset_postdata();
        $output .= '</ul>';

        $output .= '</div>';

        return $output;
    }
}

add_shortcode( 'tuts', 'rest_api_tutorial_shortcode_callback' );
