<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package resi
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
    	<div class="grid grid-pad">
        	<div class="col-1-1">
                <div class="site-info">
                
                	<?php if( get_theme_mod( 'active_byline' ) == '') : ?> 
                
						<?php if ( get_theme_mod( 'resi_footerid' ) ) : ?>
                        
        					<?php echo wp_kses_post( get_theme_mod( 'resi_footerid' )); // footer id ?>
                            
						<?php else : ?>
                    	
                        	<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'resi' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'resi' ), 'WordPress' ); ?></a>
                    
                    		<span class="sep"> | </span>
                        
    						<?php printf( esc_html__( 'Theme: %1$s by %2$s', 'resi' ), 'Resi', '<a href="http://modernthemes.net" rel="designer">modernthemes.net</a>' ); ?> 
                            
						<?php endif; ?> 
                 
        			<?php endif; ?> 
                    
                </div><!-- .site-info -->
          	</div><!-- col -->
      	</div><!-- grid -->
	</footer><!-- #colophon -->
    
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
