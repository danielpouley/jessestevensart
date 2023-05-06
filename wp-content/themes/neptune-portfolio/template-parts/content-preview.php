<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Neptune WP
 */

?>

	    		
<article id="post-<?php the_ID(); ?>" <?php post_class('clear col-3-12 mobile-col-1-1'); ?>>
	<div class="box">
		 <a href="<?php the_permalink(); ?>">
            <?php   
            //can't add background image using wp_add_inline_style(); within loop, this works now, but can be improved!
            if ( has_post_thumbnail() ) { ?>
            	<?php $thumb = get_the_post_thumbnail_url(get_the_ID(),'neptune-portfolio-thumb'); ?>
                    <div class="box-thumb" style="background-image: url('<?php echo esc_url($thumb); ?>');">
                        
                    </div>

            <?php }else {
                $default = get_template_directory_uri().'/img/default-image.jpg';
                echo '<div class="box-thumb" style="background-image: url('.esc_url($default).');"></div>';
            } ?>
        </a>  
        <div class="box-meta">
            <?php 
            the_title( '<h3 class="box-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
				?>
				<!-- TODO:Add a category here -->
		</div>
	</div>
	
</article><!-- #post-<?php the_ID(); ?> -->
