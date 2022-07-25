<?php 
//GET TERMS
function trp_get_taxonomy($taxonomy, $args = array()) {

    $defaults = array(
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
    );

    $parsed_args = wp_parse_args( $args, $defaults );

    $terms = get_terms( $parsed_args );

    return $terms;
}

//GET POSTS
function trp_get_posts($args) {

    $defaults = array(
        'post_type' => 'post',
        'numberposts' => 5,
        'suppress_filters' => false,
        'fields' => 'ids',
    );

    $parsed_args = wp_parse_args( $args, $defaults );

    $posts = get_posts( $parsed_args );

    return $posts;
}

//QUERY POSTS
function trp_query($args) {

    $defaults = array(
        'post_type' => 'post',
        'posts_per_page' => 5,
        'suppress_filters' => false,
        'no_found_rows' => true,
        'fields' => 'ids',
    );

    $parsed_args = wp_parse_args( $args, $defaults );

    //CACHE TAX QUERY
    $update_term = false;
    if(isset($args['tax_query'])):
        $update_term = true;
    endif;
    $parsed_args['update_post_term_cache'] = $update_term;

    //CACHE META QUERY
    $update_meta = false;
    if(isset($args['meta_query'])):
        $update_meta = true;
    endif;
    $parsed_args['update_post_meta_cache'] = $update_meta;

    $query_posts = new WP_Query( $parsed_args );

    return $query_posts;
}

/*
ESEMPIO DI UTILIZZO:
//se passo una 'tax_query' o una 'meta_query' vengono aggiornati in automatico i valori di 'update_post_term_cache' e 'update_post_meta_cache'
//in caso serva la paginazione passare 'no_found_rows' => false, 

    $args = array(
        'post_type' => 'products',
        'posts_per_page' => 20,
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy'  => 'product_cat',
                'field'     => 'slug',
                'terms'     => 'carte-crypto-cashback'
            ),
        ),
    );
    $query_products = trp_query($args);
*/
