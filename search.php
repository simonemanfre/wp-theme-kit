<?php 
global $wp_query; 
get_header(); 
?>

    <div class="l-container">
        
        <?php
        if ($wp_query->have_posts()) : while ($wp_query->have_posts()) : $wp_query->the_post(); 
    
            get_template_part('partials/article', 'news');

        endwhile; endif; 
        ?>
            
    </div>  

<?php get_footer(); ?>