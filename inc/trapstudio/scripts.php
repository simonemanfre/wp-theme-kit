<?php 

	function site_scripts_and_css() {
        $dati_tema = wp_get_theme();
        $var_array = array();

        //FILE CSS
        wp_enqueue_style( 'normalize', get_template_directory_uri() . "/assets/css/normalize.css", array(), $dati_tema->Version);
        wp_enqueue_style( 'app', get_template_directory_uri() . "/assets/css/app.css", array('normalize'), $dati_tema->Version);
        wp_enqueue_style( 'style', get_stylesheet_uri(), array('normalize'), $dati_tema->Version);
        wp_enqueue_style( 'responsive', get_template_directory_uri() . "/assets/css/responsive.css", array('normalize'), $dati_tema->Version);
        
		//FILE SCRIPTS
        wp_enqueue_script( 'ofi', get_template_directory_uri() . "/assets/js/ofi.min.js", array('jquery'), '1.0', true);
        //wp_enqueue_script( 'headroom', get_template_directory_uri() . "/assets/js/headroom.min.js", array('jquery'), '1.0', true);
        wp_register_script( 'siteScripts', get_template_directory_uri() . "/assets/js/scripts.js", array('jquery'), '1.0', true );
        
        //AOS SOLO IN HOME
        /*
        if(is_front_page() || is_home()):
            wp_enqueue_style( 'aos', get_template_directory_uri() . "/assets/css/aos.css", array('normalize'), $dati_tema->Version);
            wp_enqueue_script( 'aos', get_template_directory_uri() . "/assets/js/aos.js", array('jquery'), '1.0', true);
        endif;
        */

        //FANCYBOX PER MODAL E CAROUSEL SOLO DOVE SERVE
        /*
        if(is_front_page() || is_page_template('p-page.php') | is_singular('post_type') | is_post_type_archive('post_type') | has_block('acf/reviews')):
            $var_array['fancybox'] = true;
            wp_enqueue_style( 'fancybox', get_template_directory_uri() . "/assets/css/fancybox.min.css", array(), $dati_tema->Version);
            wp_enqueue_script( 'fancybox', get_template_directory_uri() . "/assets/js/fancybox.min.js", array(), '1.0', true);
        endif;
        */

        //VARIABILI DA PASSARE A JS
        $var_array['home'] = get_bloginfo('url');

        wp_localize_script('siteScripts', 'php_vars', $var_array );
        
		wp_enqueue_script('siteScripts');
	}
    add_action( 'wp_enqueue_scripts', 'site_scripts_and_css' );
    	
?>