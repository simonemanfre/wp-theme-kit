<?php get_header(); ?>

    <header class="c-page-header">
        <h1 class="c-title">Errore 404</h1>
    </header>

    <div class="l-container">
        <article class="c-content">
            <div class="c-content__text">
                <?php if(function_exists('get_field') && get_field('404_text', 'option')):
                    the_field('404_text', 'option');
                endif; ?>
            </div>
        </article>
    </div>

<?php get_footer(); ?>