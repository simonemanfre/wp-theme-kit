<?php 
get_header(); 

//PRENDO OGGETTO CORRENTE
$current_cat = get_queried_object();

//PRENDO TUTTE LE CATEGORIE
$categories = get_categories();
?>

    <?php 
    //LISTA DI TASSONOMIE IN MODO GERARCHICO
    get_taxonomy_hierarchy('product_cat'); 
    ?>

<?php get_footer(); ?>