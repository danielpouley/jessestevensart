<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Neptune WP
 */

get_header(); ?>

	<div id="primary" class="content-area grid-wide">
		<main id="main" class="site-main">

			<section class="error-404 not-found" style="color:white;">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'neptune-portfolio' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content" >
					<p><?php esc_html_e( 'It looks like nothing was found at this location.' ); ?></p>
					<p><a href="/">Go back</a></p>
						
					</p>

					<?php
						//get_search_form();

						//the_widget( 'WP_Widget_Recent_Posts' );
					?>

					<div class="widget widget_categories">
			

					</div><!-- .widget -->

					<?php

						/* translators: %1$s: smiley */
						//$archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'neptune-portfolio' ), convert_smilies( ':)' ) ) . '</p>';
						//the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );

						//the_widget( 'WP_Widget_Tag_Cloud' );
					?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
