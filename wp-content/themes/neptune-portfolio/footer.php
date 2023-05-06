<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Neptune WP
 */

?>

	</div><!-- #content -->
</div> <!--#page-->
	<footer id="colophon" class="site-footer">
		<div class="footer-overlay"></div>
		<div class="grid-wide">
		<div class="col-3-12">
			<?php dynamic_sidebar('footer-1');?>
		</div>

		<div class="col-3-12">
			<?php dynamic_sidebar('footer-2');?>
		</div>

		<div class="col-3-12">
			<?php dynamic_sidebar('footer-3');?>
		</div>

		<div class="col-3-12">
			<?php dynamic_sidebar('footer-4');?>
		</div>
		<div class="site-info col-1-1">
			© 2019 Jesse Stevens
			<span class="sep"> | </span>
		<a href='/'>Digital</a> <a href='/traditional/'>Traditional</a> <a href='/about-me/'>About Me</a>
		</div><!-- .site-info -->
	</div>
	</footer><!-- #colophon -->


<?php wp_footer(); ?>

</body>
</html>
