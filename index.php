<?php get_header(); ?>

    <section id="news-container" class="c-news-container l-container">
        <?php 
        $exclude = array();
        $i = 0; if (have_posts()) : while (have_posts()) : the_post(); 

            get_template_part('partials/article', 'news');
            array_push($exclude, $post->ID);

        $i++; endwhile; endif; ?>
    </section>

    <?php //CARICAMENTO ARTICOLI TRAMITE AJAX ?>
    <?php if($i == get_option('posts_per_page')): ?>

        <section class="c-read-more">
            <div class="l-container">
                <a href="#showall" class="c-button j-loadmore" data-target="#news-container" data-post_type="post" data-exclude="<?php echo implode(',', $exclude); ?>">Carica altri</a>
            </div>
        </section>

    <?php endif; ?>

<?php get_footer(); ?>