<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
  	
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="theme-color" content="#000000">
    
    <title><?php wp_title(' - ', true, 'right'); ?> <?php if (!function_exists('wpseo_activate')): bloginfo('name'); endif; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--[if lt IE 9]>
      <script src="<?php bloginfo('template_url'); ?>/js/html5shiv.js"></script>
      <script src="<?php bloginfo('template_url'); ?>/js/respond.min.js"></script>
    <![endif]-->
    
	<?php wp_head(); ?>
    
    <?php if(function_exists('get_field') && get_field('css_custom', 'option')): ?>
        <style>
            <?php the_field('css_custom', 'option'); ?>
        </style>
    <?php endif; ?>    
    
    <?php if(function_exists('get_field') && get_field('html_header', 'option')):
        the_field('html_header', 'option');
    endif; ?>
</head>

<body <?php body_class(); ?>>

    <div class="l-page">
        <header class="c-site-header">
            <div class="c-site-header__content l-container">
                <?php //LOGO ?>
                <a class="c-logo" href="<?php bloginfo('url'); ?>">
                    <img src="<?php bloginfo('template_url'); ?>/img/logo.jpg">
                </a> 

                <a class="c-toggle j-toggle">
                    <span class="c-toggle__item"></span>
                    <span class="c-toggle__item"></span>
                    <span class="c-toggle__item"></span>
                </a>

                <?php //MENU ?>
                <nav class="c-site-nav">                          
                    <ul class="c-site-nav__menu">
                        <?php
                            $args = array(
                            'theme_location' => 'Primario',
                            'depth'    => 1,
                            'items_wrap' => '%3$s',
                            'container' => ''
                            );

                            wp_nav_menu($args);
                        ?>
                    </ul>
                </nav> 

                <?php //RICERCA ?>
                <form method="GET" action="<?php echo get_bloginfo('url') ?>" class="c-form c-form--search">
                    <div class="c-form__field">
                        <input type="text" name="s" id="search" placeholder="Cerca">
                        <button class="icon icon-search"></button>
                    </div>
                </form>
            </div>
        </header>
        <main class="l-main">