<?php

//TODO DISABILITARE EDITOR VISUALE ACF
//add_filter('acf/settings/show_admin', '__return_false');

// PAGE OPTION
$dati_tema = wp_get_theme();
$title_option_page = 'Opzioni '.$dati_tema->Name;

acf_add_options_sub_page(array(
    'page_title' 	    => $title_option_page,
    'menu_slug' 	    => 'acf-options',
    'parent_slug'       => 'themes.php',
    'update_button'     => __('Aggiorna', 'acf'),
    'updated_message'   => __("Opzioni aggiornate", 'acf'),
));	

function my_acf_init() {
    // API KEY
    acf_update_setting('google_api_key', get_field('api', 'option'));
    
    // GUTENBERG BLOCK REGISTRATION
    if( function_exists('acf_register_block') ) {
        
        // Button coupon
        acf_register_block(array(
            'name'				=> 'custom-block',
            'title'				=> 'Custom Block',
            'render_callback'	=> 'trp_acf_block_render_callback',
            'enqueue_assets'    => 'trp_acf_block_enqueue_assets',
            'category'			=> 'custom',
            'icon'				=> 'plus-alt2', //https://developer.wordpress.org/resource/dashicons/
        ));
    }
}
add_action('acf/init', 'my_acf_init');

// GUTENBERG CATEGORIES REGISTRATION
function trp_block_categories( $categories, $block_editor_context ) {
    return array_merge(
        array(
            array(
                'slug' => 'custom',
                'title' => 'Custom Blocks',
                'icon'  => 'wordpress',
            ),
        ),
        $categories
    );
}
add_filter( 'block_categories_all', 'trp_block_categories', 10, 2 );

function trp_acf_block_render_callback( $block ) {
    
    // convert name ("acf/example") into path friendly slug ("example")
    $slug = str_replace('acf/', '', $block['name']);
    
    // include a template part from within the "template-parts/blocks" folder
    if( file_exists( get_theme_file_path("/partials/blocks/content-{$slug}.php") ) ) {
        include( get_theme_file_path("/partials/blocks/content-{$slug}.php") );
    }
}

function trp_acf_block_enqueue_assets( $block ) {

    $dati_tema = wp_get_theme();
	// convert name ("acf/example") into path friendly slug ("example")
	$slug = str_replace('acf/', '', $block['name']);
	
	// include CSS file if exists
	if( file_exists( get_theme_file_path("/partials/blocks/assets/css/content-{$slug}.css") ) ) {
        wp_enqueue_style( $slug, get_template_directory_uri() . "/partials/blocks/assets/css/content-{$slug}.css", array(), $dati_tema->Version);
	}	
	// include JS file if exists
	if( file_exists( get_theme_file_path("/partials/blocks/assets/js/content-{$slug}.js") ) ) {
        wp_enqueue_style( $slug, get_template_directory_uri() . "/partials/blocks/assets/js/content-{$slug}.js", array(), $dati_tema->Version);
	}
}

//REMOVE ADMIN BAR FOR ADMINISTRATOR IF OPTION FIELD IS CHECKED
if(!is_admin() && current_user_can('administrator') && get_field('admin_bar', 'option')){
    add_filter('show_admin_bar', '__return_false');
}

?>