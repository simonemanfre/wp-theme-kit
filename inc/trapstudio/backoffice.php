<?php 

//SVG UPLOAD
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


//ADMIN STYLE E SCRIPT
function admin_scripts() {
    wp_enqueue_style( 'admin', get_template_directory_uri() . '/assets/css/admin.css', array(), null);
}
add_action( 'admin_enqueue_scripts', 'admin_scripts' );


//LOGIN STYLE E SCRIPT
/*

PER AGGIUNGERE CSS E JS ALLA PAGINA DI LOGIN

function login_scripts() {
    $dati_tema = wp_get_theme();

    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/assets/css/login.css', $dati_tema->Version );
    wp_enqueue_script( 'custom-login', get_stylesheet_directory_uri() . '/assets/js/login.js', $dati_tema->Version, true );
}
add_action( 'login_enqueue_scripts', 'login_scripts' );
*/


//REMOVE ADMIN BAR FOR USER
if(!current_user_can('edit_posts')){
    add_filter('show_admin_bar', '__return_false');
}


//REDIRECT TO DASHBOARD FOR USER
function trp_get_dashboard_url() {
    /*
    Modificare url dashboard 
    oppure ricordarsi di assegnare il template p-dashboard.php
    */
    $args = array(
        'posts_per_page' => 1,
        'post_type' => 'page',
        'meta_key' => '_wp_page_template',
        'meta_value' => 'p-dashboard.php',
        'fields' => 'ids'
    );
    $admin_page = get_posts($args);
    if($admin_page):
        $url = get_the_permalink($admin_page[0]);
    else:
        $url = get_home_url();
    endif;

    return $url;
}

//redirect dashboard
function wp_admin_redirect() {
    if (!current_user_can( 'edit_posts' ) && !wp_doing_ajax() ) {
        $url = trp_get_dashboard_url();

        wp_safe_redirect( $url);
        exit;
    }
}
add_action( 'admin_init', 'wp_admin_redirect', 1 );

//redirect after login
function login_redirect( $redirect_to, $request, $user ){
    if($redirect_to):
        $url = $redirect_to;
    else:
        $url = trp_get_dashboard_url();
    endif;

    return $url;
}
add_filter( 'login_redirect', 'login_redirect', 10, 3 );

// CUSTOM LOGIN
//login css inline
function my_login_inline_css() {
    /*
    PRENDERE VALORI CAMPI ACF OPTIONS
    $color1 = get_field('colore_primario', 'option');
    $color2 = get_field('colore_secondario', 'option');
    $text_color1 = get_field('colore_testo_primario', 'option');
    $text_color2 = get_field('colore_testo_secondario', 'option');

    <style type="text/css">
        AGGIUNGERE VARIABILI CSS AL LOGIN
        :root {
        --primary-color: <?php echo $color1 ?>;
        --secondary-color: <?php echo $color2 ?>;
        --text-primary-color: <?php echo $text_color1 ?>;
        --text-secondary-color: <?php echo $text_color2 ?>;
        }
    </style>
    */
?>
    <?php if(get_field('contact_logo', 'option')): ?>
        <style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(<?php echo wp_get_attachment_image_url(get_field('contact_logo','option'), 'large'); ?>);
            }
        </style>
    <?php endif; ?>
<?php
}
add_action('login_head', 'my_login_inline_css');

//logo url
function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

/*

//login message
function my_login_message() {
    if($_GET['action'] == 'register'):
        return '<h2>Aggiungere messaggio form Registrazione</h2>';
    else:
        return '<h2>Aggiungere messaggio form Login</h2>';
    endif;
}
add_filter( 'login_message', 'my_login_message' );

//login form
function my_login_form() {
    echo '<p><strong>aggiungere cose extra al form di login qui</strong></p>';
}
add_action( 'login_form', 'my_login_form' );

//register form
function my_register_form() {
    echo '<p><strong>aggiungere cose extra al form di registrazione qui</strong></p>';
}
add_action( 'register_form', 'my_register_form' );

//login footer
function my_login_footer() {
    echo 'FOOTER';
}
add_action( 'login_footer', 'my_login_footer' );
*/

?>