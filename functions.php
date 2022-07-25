<?php

define( 'THEME_URL', get_template_directory_uri() . '/' );
define( 'THEME_DIR', dirname(__FILE__).'/' );

require_once(THEME_DIR . 'inc/trapstudio/security.php');
require_once(THEME_DIR . 'inc/trapstudio/scripts.php');
require_once(THEME_DIR . 'inc/trapstudio/api.php');
require_once(THEME_DIR . 'inc/trapstudio/ajax-api.php');
require_once(THEME_DIR . 'inc/trapstudio/blocks.php');
require_once(THEME_DIR . 'inc/trapstudio/comments.php');
require_once(THEME_DIR . 'inc/trapstudio/utility.php');
//require_once(THEME_DIR . 'inc/trapstudio/woocommerce.php');

//require_once(THEME_DIR . 'inc/trapstudio/shortcodes.php');
//require_once(THEME_DIR . 'inc/trapstudio/wp-gallery.php');


//MENU
add_theme_support( 'nav-menus' );
if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus( array('Primario' => __( 'Navigazione primaria') ) );
	//register_nav_menus( array('Secondario' => __( 'Navigazione secondaria') ) );
}


//THUMBNAILS
add_theme_support('post-thumbnails' );
//add_image_size('customThumbSize', 180, 120, true);


//AGGIUNGO SUPPORTO EXCERPT NELLE PAGINE
add_post_type_support('page', 'excerpt');


//LIMIT EXCERPT (Per limitare le parole dei riassunti)
function tn_custom_excerpt_length( $length ) {
    return 15;
}
add_filter( 'excerpt_length', 'tn_custom_excerpt_length', 999 );


//MOVE JQUERY TO THE FOOTER
function trp_move_jquery_to_footer() {
    wp_scripts()->add_data( 'jquery', 'group', 1 );
    wp_scripts()->add_data( 'jquery-core', 'group', 1 );
    wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
}
add_action( 'wp_enqueue_scripts', 'trp_move_jquery_to_footer' );


//ACF POST 2 POST
//disabilito campi bidirezionali ovunque
add_filter('acf/post2post/update_relationships/default', '__return_false');

//abilito camnpi bidirezionali su field specifici
add_filter('acf/post2post/update_relationships/key=field_619d636148817', '__return_true');


// REMOVE ADMIN BAR
if( function_exists('acf_add_options_page') ) {
    function remove_admin_bar(){
        return false;
    }
    if(!is_admin() && current_user_can('administrator') && get_field('admin_bar', 'option') OR current_user_can('subscriber')) {
        add_filter('show_admin_bar', 'remove_admin_bar');
    }
}


//DISABLE WP-ADMIN FOR SUBSCRIBER
function trp_remove_admin_for_subscriber(){  
    $role = get_role( 'subscriber' );
    $role->remove_cap( 'read' );    
}
add_action( 'admin_init', 'trp_remove_admin_for_subscriber' );


//GET CURRENT POST_ID IN FUNCTION.PHP
function get_current_post_ID() {
    $post_id = get_queried_object_id();

    //codice qui con possibilità di lettura $post_id

}
add_action( 'template_redirect', 'get_current_post_ID' );


//CF7
if (function_exists('wpcf7')) {
    add_filter('wpcf7_autop_or_not', '__return_false');
    //RIMUOVO STILE E SCRIPT CF7 OVUNQUE
    //add_filter( 'wpcf7_load_js', '__return_false' );
    //add_filter( 'wpcf7_load_css', '__return_false' );
}

/*
** PER REGISTRARE CF7 NEI TEMPLATE IN CUI È USATO

//REGISTRO FILE E SCRIPT CF7 IN QUESTO TEMPLATE
if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
    wpcf7_enqueue_scripts();
}
if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
    wpcf7_enqueue_styles();
}
*/


//ASYNC OR DEFER ATTRIBUTES TO SCRIPTS
// only on the front-end
if(!is_admin()) {
    function trp_asyncdefer_attribute($tag, $handle) {
        // if the unique handle/name of the registered script has 'async' in it
        if (strpos($handle, 'async') !== false) {
            // return the tag with the async attribute
            return str_replace( '<script ', '<script async ', $tag );
        }
        // if the unique handle/name of the registered script has 'defer' in it
        else if (strpos($handle, 'defer') !== false) {
            // return the tag with the defer attribute
            return str_replace( '<script ', '<script defer ', $tag );
        }
        // otherwise skip
        else {
            return $tag;
        }
    }
    add_filter('script_loader_tag', 'trp_asyncdefer_attribute', 10, 2);
}

/*
ESAMPLE:
// script to load asynchronously
wp_enqueue_script( 'instantPage-async', get_template_directory_uri() . "/js/instant-page.js", array('jquery'), '1.0', true);

// script to be deferred
wp_enqueue_script( 'instantPage-defer', get_template_directory_uri() . "/js/instant-page.js", array('jquery'), '1.0', true);
*/


//ABILITO RICERCA BACKEND CAMPI ACF
//Per abilitare la ricerca anche nel frontend togliere condizione is_admin()
function cf_search_join( $join ) {
    global $wpdb;

    if ( is_search() && is_admin() ) {    
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    return $join;
}
add_filter('posts_join', 'cf_search_join' );

function cf_search_where( $where ) {
    global $pagenow, $wpdb;

    if ( is_search() && is_admin() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
        /*
        QUERY PER LIMITARE AD UNO SPECIFICO CAMPO ACF
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1) AND (".$wpdb->postmeta.".meta_key LIKE 'nome_campo')", $where );
        */
    }

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );

function cf_search_distinct( $where ) {
    global $wpdb;

    if ( is_search() && is_admin() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );


//USO IL VALORE NUMERICO DI UN RIPETITORE IN UNA META QUERY (controllare i campi ACF o il DB per il nome corretto dei meta)
function my_posts_where( $where ) {
	
	$where = str_replace("meta_key = 'nome_ripetitore_numerico$", "meta_key LIKE 'nome_ripetitore_numerico%", $where);

	return $where;
}
add_filter('posts_where', 'my_posts_where');
/*
META QUERY D'ESEMPIO DA USARE NEL TEMPLATE
'meta_query' => array(
    array(
        'key'     => 'nome_ripetitore_numerico$_nome_subfield',
        'compare' => '=',
        'value'   => $current_post,
    ),  
),
*/


//USO IL VALORE TESTUALE DI UN RIPETITORE IN UNA META QUERY (controllare i campi ACF o il DB per il nome corretto dei meta)
function my_posts_where( $where ) {
	
	$where = str_replace("meta_key = 'nome_ripetitore_testuale$", "meta_key LIKE 'nome_ripetitore_testuale%", $where);

	return $where;
}
add_filter('posts_where', 'my_posts_where');
/*
META QUERY D'ESEMPIO DA USARE NEL TEMPLATE
'meta_query' => array(
    array(
        'key'     => 'nome_ripetitore_testuale$_nome_subfield',
        'compare' => 'LIKE',
        'value'   => '"'.$slug.'"',
    )
),
*/


//BREADCRUMBS NavXT - REMOVE CURRENT ITEM
add_action('bcn_after_fill', 'bcnext_remove_current_item');

function bcnext_remove_current_item($trail) {
	if(isset($trail->breadcrumbs[0]) && $trail->breadcrumbs[0] instanceof bcn_breadcrumb) {
		$types = $trail->breadcrumbs[0]->get_types();
        
		if(is_array($types) && in_array('current-item', $types)) {
			array_shift($trail->breadcrumbs);
		}
	}
}

//TASSONOMIA PRIMARIA YOAST
function trp_get_primary_term($taxonomy) {

    $primary_taxonomy  = new WPSEO_Primary_Term( $taxonomy, get_the_id() );
    $primary_taxonomy = $primary_taxonomy->get_primary_term();

    //FALLBACK PER CATEGORIA PRIMARIA YOAST
    if(!$primary_taxonomy):
        //POST ID IN ITALIANO PER FALLBACK
        $italian_post_id = apply_filters('wpml_object_id', get_the_id(), get_post_type(), true, 'it');
        $primary_taxonomy = new WPSEO_Primary_Term( $taxonomy, $italian_post_id );
        $primary_taxonomy = $primary_taxonomy->get_primary_term();
        $primary_taxonomy = apply_filters('wpml_object_id', $primary_taxonomy, $taxonomy, true);
    endif;

    return $primary_taxonomy; 
}


//LISTA DI TASSONOMIE IN MODO GERARCHICO
function get_taxonomy_hierarchy($taxonomy, $parent = 0 ) {
?>
    <ul class="u-parent">

        <?php
        // get all direct decendents of the $parent
        $terms = get_terms( array(
            'taxonomy' => $taxonomy,
            'parent' => $parent, 
            'orderby'  => 'name',
        ) );

        // go through all the direct decendents of $parent, and gather their children
        
        foreach( $terms as $term ):
            $this_cat = get_queried_object();
            $this_cat_id = $this_cat->term_id;
            
            $child_term = get_terms( array(
                'taxonomy' => $taxonomy,
                'parent' => $term->term_id, 
                'orderby'  => 'name',
            ) );
            ?>
            <li>
                <div class="c-form__field c-form__field--checkbox" id="cat-<?php echo $term->slug ?>">
                    <input type="checkbox" id="<?php echo $term->slug ?>" value="<?php echo $term->slug ?>"<?php if(strpos($_GET['product_cat'], $term->slug) !== FALSE || $this_cat_id == $term->term_id): ?> checked<?php endif; ?>>
                    <span></span>
                    <label for="<?php echo $term->slug ?>"><?php echo $term->name ?></label>
                    <?php if( !empty($child_term) && !is_wp_error($child_term)): ?><div class="j-cat-open icon icon-arrow-right"></div><?php endif;?>
                </div>
    
                <?php 
                // recurse to get the direct decendents of "this" term
                get_taxonomy_hierarchy( $taxonomy , $term->term_id );
                ?>
            </li>
        <?php
        endforeach;
        ?>
    </ul>
<?php
}


//FUNZIONI AL SALVATAGGIO POST
//Elimino righe in tabella custom al salvataggio
function save_attivita($post_id) {
    global $wpdb;

    $wpdb->delete('euducation_attivita', array(
        'id_attivita' => $post_id,
        )
    );
}
add_action( 'save_post_attivita', 'save_attivita' );

//Aggiungo riga in tabella custom con valori dei campi ACF
add_action('acf/save_post', 'my_acf_save_post');
function my_acf_save_post($post_id) {
    global $wpdb;

    $post = get_post($post_id);

    if($post->post_type == 'attivita'):
        $eventi = get_field('date', $post_id);
        foreach($eventi as $evento):

            $wpdb->insert('euducation_attivita', array(
                'id_attivita' => $post_id,
                'data' => $evento['data'],
                'luogo' => $evento['luogo'],
                ), array(
                '%d',
                '%d',
                '%s')
            );

        endforeach;
    endif;
}


// LOGIN CUSTOM LOGO
function my_login_logo_url() {
    return 'http://www.trapstudio.it/';
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Realizzato da Trap Studio';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );


//SVG UPLOAD
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
    }
add_filter('upload_mimes', 'cc_mime_types');


//AGGIUNGO ATTRIBUTO SHORTCODE CF7 (Aggiungo valori dinamici al form modificando lo shortcode di CF7)
add_filter( 'shortcode_atts_wpcf7', 'custom_shortcode_atts_wpcf7_filter', 10, 3 );
function custom_shortcode_atts_wpcf7_filter( $out, $pairs, $atts ) {
  $my_attr = 'attributo-custom';
 
  if ( isset( $atts[$my_attr] ) ) {
    $out[$my_attr] = $atts[$my_attr];
  }
 
  return $out;
}
/*
UTILIZZO IN FORM CF7:
[hidden attributo-custom default:shortcode_attr]

UTILIZZO IN SHORTCODE PHP:
echo do_shortcode('[contact-form-7 id="'.$contact_form_id.'" html_class="c-form" attributo-custom="'.$valore_dinamico.'"]');
*/


//RESPONSIVE EMBEDS
add_filter('embed_oembed_html', 'bs_embed_oembed_html', 99, 4);
function bs_embed_oembed_html($html, $url, $attr, $post_id) {
  return '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
}


//TIMESTAMP
function the_debug_timestamp() {
    echo date("YmdHis");
}

/*
**
DATE AND TIME
**
*/

//OPERAZIONI CON LE DATE
$date_start = '2000-01-01';
$date_end = '2020-12-31';

//creo data
$date_start_create = date_create($date_start);
$date_end_create = date_create($date_end);

//formatto data
$date_start_format = date_format($date_start_create, 'd/m/Y');
$date_start_anno = date_format($date_start_create, 'Y');

$date_end_format = date_format($date_end_create, 'd/m/Y');
$date_end_anno = date_format($date_end_create, 'Y');

//timestamp
$timestamp_start = strtotime($date_start);
$timestamp_end = strtotime($date_end);

//differenza tra date
$days_difference = daysBetween($date_start, $date_end);

//DIFFERENCE IN DAYS BETWEEN 2 DATES
function daysBetween($date1, $date2){
    $date1 = date_create($date1);
    $date2 = date_create($date2);
    $interval = date_diff($date1, $date2);

    return $interval->format('%a');
}


//QUERY SQL con date, esempio completo su progetto IGD
$date_start = '2000-01-01';
$date_end = '2999-01-01';

$sql_query = "SELECT id_utente, DATE(data_score) as data_punteggio
    FROM $nome_tabella
    WHERE DATE(data_score) >= '$date_start' AND DATE(data_score) <= '$date_end'
    "
    
//FORMAT DATE IN ITALIAN LANGUAGE
//date = time()
function timestamp_to_date_italian($date) {       
    $months = array(
            '01' => 'Gennaio', 
            '02' => 'Febbraio', 
            '03' => 'Marzo', 
            '04' => 'Aprile',
            '05' => 'Maggio', 
            '06' => 'Giugno', 
            '07' => 'Luglio', 
            '08' => 'Agosto',
            '09' => 'Settembre', 
            '10' => 'Ottobre', 
            '11' => 'Novembre',
            '12' => 'Dicembre'
        );

    list($day, $month, $year) = explode('-',date('d-m-Y', $date));      
    return $day . ' ' . $months[$month] . ' ' . $year;
}

//funzione per controllare se la data corrisponde ad oggi
function check_date_is_today($date) {
    //prendo data corrente
    $now = current_time('Y-m-d');

    //converto date in timestamp
    $date = strtotime($date);
    $now = strtotime($now);

    //trovo differenza in secondi
    $diff = $now - $date;

    //converto in giorni
    $diff = round($diff / (60 * 60 * 24));

    //se non c'è differenza tra le date vuol dire che l'attività è stata completata oggi
    if($diff == 0):
        return 1;
    else:
        return 0;
    endif;
}

?>