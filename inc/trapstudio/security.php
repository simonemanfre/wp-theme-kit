<?php


function ws_whitelabel(){
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
	remove_action( 'wp_head', 'wp_resource_hints', 2 );


	add_filter('the_generator','remove_wp_version_rss');

	function remove_wp_version_rss() {
	 return'';
	}

	//EMOJI
	function disable_wp_emojicons() {

	  // all actions related to emojis
	  remove_action( 'admin_print_styles', 'print_emoji_styles' );
	  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	  remove_action( 'wp_print_styles', 'print_emoji_styles' );
	  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	  // filter to remove TinyMCE emojis
	  //add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
	}
	add_action( 'init', 'disable_wp_emojicons' );

}
function ws_xmlrpc_disable(){
	// Disable use XML-RPC
	add_filter( 'xmlrpc_enabled', '__return_false' );

	// Disable X-Pingback to header
	add_filter( 'wp_headers', 'disable_x_pingback' );
	function disable_x_pingback( $headers ) {
	    unset( $headers['X-Pingback'] );

	return $headers;
	}

}


function ws_htaccess( $rules ) {
	$new_rules = '# DEFLATE compressione
    <IfModule mod_deflate.c>
    # Compress HTML, CSS, JavaScript, Text, XML and fonts
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
    AddOutputFilterByType DEFLATE application/x-font
    AddOutputFilterByType DEFLATE application/x-font-opentype
    AddOutputFilterByType DEFLATE application/x-font-otf
    AddOutputFilterByType DEFLATE application/x-font-truetype
    AddOutputFilterByType DEFLATE application/x-font-ttf
    AddOutputFilterByType DEFLATE font/opentype
    AddOutputFilterByType DEFLATE font/otf
    AddOutputFilterByType DEFLATE font/ttf
    AddOutputFilterByType DEFLATE image/svg+xml
    AddOutputFilterByType DEFLATE image/x-icon
    AddOutputFilterByType DEFLATE image/jpg
    AddOutputFilterByType DEFLATE image/jpeg
    AddOutputFilterByType DEFLATE image/gif
    AddOutputFilterByType DEFLATE image/png
    # Remove browser bugs
    BrowserMatch ^Mozilla/4 gzip-only-text/html
    BrowserMatch ^Mozilla/4\.0[678] no-gzip
    BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
    Header append Vary User-Agent
    </IfModule>
    # FINE DEFLATE

    ## EXPIRES CACHING ##
    <IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 month"
    ExpiresByType image/x-icon "access 1 year"
    ExpiresByType audio/ogg "access plus 1 year"
    ExpiresByType video/ogg "access plus 1 year"
    ExpiresByType video/mp4 "access plus 1 year"
    ExpiresByType video/webm "access plus 1 year"
    ExpiresByType application/pdf "access 1 month"
    ExpiresByType application/javascript "access 1 month"
    ExpiresByType application/x-javascript "access 1 month"
    ExpiresByType text/javascript "access 1 month"
    ExpiresByType text/x-javascript "access 1 month"
    ExpiresByType application/x-shockwave-flash "access 1 month"
    </IfModule>
    ## EXPIRES CACHING ##

    # SICUREZZA: XML RPC BLOCKING
	<Files xmlrpc.php>
	Order Deny,Allow
	Deny from all
	</Files>
	# SICUREZZA: XML RPC BLOCKING

	# SICUREZZA: FILE SENSIBILI
	<FilesMatch "(^\.|wp-config(-sample)*\.php)">
	    order deny,allow
	    deny from all
	</FilesMatch>
	# SICUREZZA: FILE SENSIBILI

	# SICUREZZA: PHP ERRORS
	<IfModule mod_php5.c>
    php_flag display_errors off
	</IfModule>
	<IfModule mod_php7.c>
	    php_flag display_errors off
	</IfModule>
	# SICUREZZA: PHP ERRORS';
	return $rules . $new_rules;
}
add_filter('mod_rewrite_rules', 'ws_htaccess');


//------
ws_whitelabel();
ws_xmlrpc_disable();

?>