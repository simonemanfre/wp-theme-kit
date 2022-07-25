<?php
?>

        </main>
        <footer class="c-site-footer">
            <a href="#top" class="c-top-button j-top">
                <svg class="icon icon-arrow-right">
                    <use xlink:href="#icon-arrow-right"></use>
                </svg>
            </a>
            <div class="c-site-footer__content l-container">
                <?php if(function_exists('get_field') && get_field('footer_contatti', 'option')):
                    the_field('footer_contatti', 'option'); 
                endif; ?>
            </div>
        </footer>
    </div><!-- L-PAGE -->

	<?php wp_footer(); ?>
	
	<?php if(function_exists('get_field') && get_field('html_footer', 'option')):
        the_field('html_footer', 'option'); 
    endif; ?>

    <?php 
    /* CALCELLARE COMMENTO PER INIZIALIZZARE AOS
    <script>
    AOS.init(
        {
            disable: function() {
                var maxWidth = 1024; //SOLO DA DESKTOP
                return window.innerWidth < maxWidth;
            },
            offset: 300,
            duration: 1000,
        }
    );
    </script>
    */ 
    ?>

</body>
</html>