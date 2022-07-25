<?php 
get_header(); 

//PRENDO OGGETTO CORRENTE
$current_cat = get_queried_object();

//PRENDO TUTTE LE CATEGORIE
$categories = get_categories();

//PRENDO PAGINA BLOG E LA IMPOSTO COME POST CORRENTE PER PRENDERE INFORMAZIONI (TITOLO, IMMAGINE IN EVIDENZA, CUSTOM FIELDS)
$post = get_option('page_for_posts');
setup_postdata($post);
?>

    <ul>
        <?php 
        //CICLO LE CATEGORIE PER STAMPARE UNA NAVIGAZIONE SECONDARIA (CONFRONTO CON CURRENT_CAT PER SELEZIONARE LA VOCE DI MENU CORRENTE)
        $i = 0; foreach($categories as $cat):
            if($i > 2):
                break;
            else:
        ?>
            <li><a class="c-button <?php if($cat->term_id == $current_cat->term_id): echo 'u-is-active'; endif; ?>" href="<?php echo get_category_link($cat) ?>"><?php echo $cat->name ?></a></li>
        <?php 
            endif;
        $i++; endforeach; 
        ?>
    </ul>
    
    <?php 
    //LOOP DEFAULT PER GLI ARTICOLI
    if (have_posts()) : while (have_posts()) : the_post(); 
    ?>

        <?php 
        //ELIMINARE TAG HTML DAL CONTENUTO
        $content = apply_filters( 'the_content', get_the_content() );
        echo strip_tags($content,"<b><strong><br><p><ul><li>");
        ?>

    <?php endwhile; endif; ?>

    <section id="news-container" class="c-news-container l-container">
        <?php 
        //LOOP CON CONTATORE ED ARRAY PER ESCLUDERE ARTICOLI CORRENTI
        $exclude = array();
        $i = 0; if (have_posts()) : while (have_posts()) : the_post(); 

            get_template_part('partials/article', 'news');
            array_push($exclude, $post->ID);

        $i++; endwhile; endif; ?>
    </section>

    <?php //CARICAMENTO ARTICOLI TRAMITE AJAX, PASSO IL TIPO DI POST, GLI ARTICOLI DA ESCLUDERE E LA CATEGORIA CORRENTE ?>
    <?php if($i == get_option('posts_per_page')): ?>

        <section class="c-read-more">
            <div class="l-container">
                <a href="#showall" class="c-button j-loadmore" data-post_type="post" data-exclude="<?php echo implode(',', $exclude); ?>" data-category="<?php echo $current_cat->slug ?>">Carica altri</a>
            </div>
        </section>

    <?php endif; ?>

<?php get_footer(); ?>