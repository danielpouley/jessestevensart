<?php
/**
 * Neptune Theme Strings
 *
 * @package     Neptune
 * @author      Neptune
 * @copyright   Copyright (c) 2018, Neptune
 * @link        http://neptunewp.com/
 * @since       Neptune 1.0.0
 */

/**
 * Default Strings
 */
if ( ! function_exists( 'neptune_portfolio_default_strings' ) ) {

	/**
	 * Default Strings
	 *
	 * @since 1.0.0
	 * @param  string  $key  String key.
	 * @param  boolean $echo Print string.
	 * @return mixed        Return string or nothing.
	 */
	function neptune_portfolio_default_strings( $key, $echo = true ) {

		$defaults = apply_filters(
			'neptune_portfolio_default_strings', array(

				// Header.
				'string-header-skip-link'                => __( 'Skip to content', 'neptune-portfolio' ),

				// 404 Page Strings.
				'string-404-sub-title'                   => __( 'It looks like the link pointing here was faulty. Maybe try searching?', 'neptune-portfolio' ),

				// Search Page Strings.
				'string-search-nothing-found'            => __( 'Nothing Found', 'neptune-portfolio' ),
				'string-search-nothing-found-message'    => __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'neptune-portfolio' ),
				'string-full-width-search-message'       => __( 'Start typing and press enter to search', 'neptune-portfolio' ),
				'string-full-width-search-placeholder'   => __( 'Start Typing&hellip;', 'neptune-portfolio' ),
				'string-header-cover-search-placeholder' => __( 'Start Typing&hellip;', 'neptune-portfolio' ),
				'string-search-input-placeholder'        => __( 'Search &hellip;', 'neptune-portfolio' ),

				// Comment Template Strings.
				'string-comment-reply-link'              => __( 'Reply', 'neptune-portfolio' ),
				'string-comment-edit-link'               => __( 'Edit', 'neptune-portfolio' ),
				'string-comment-awaiting-moderation'     => __( 'Your comment is awaiting moderation.', 'neptune-portfolio' ),
				'string-comment-title-reply'             => __( 'Leave a Comment', 'neptune-portfolio' ),
				'string-comment-cancel-reply-link'       => __( 'Cancel Reply', 'neptune-portfolio' ),
				'string-comment-label-submit'            => __( 'Post Comment &raquo;', 'neptune-portfolio' ),
				'string-comment-label-message'           => __( 'Type here..', 'neptune-portfolio' ),
				'string-comment-label-name'              => __( 'Name*', 'neptune-portfolio' ),
				'string-comment-label-email'             => __( 'Email*', 'neptune-portfolio' ),
				'string-comment-label-website'           => __( 'Website', 'neptune-portfolio' ),
				'string-comment-closed'                  => __( 'Comments are closed.', 'neptune-portfolio' ),
				'string-comment-navigation-title'        => __( 'Comment navigation', 'neptune-portfolio' ),
				'string-comment-navigation-next'         => __( 'Newer Comments', 'neptune-portfolio' ),
				'string-comment-navigation-previous'     => __( 'Older Comments', 'neptune-portfolio' ),

				// Blog Default Strings.
				'string-blog-page-links-before'          => __( 'Pages:', 'neptune-portfolio' ),
				'string-blog-meta-author-by'             => __( 'By ', 'neptune-portfolio' ),
				'string-blog-meta-leave-a-comment'       => __( 'Leave a Comment', 'neptune-portfolio' ),
				'string-blog-meta-one-comment'           => __( '1 Comment', 'neptune-portfolio' ),
				'string-blog-meta-multiple-comment'      => __( '% Comments', 'neptune-portfolio' ),
				'string-blog-navigation-next'            => __( 'Next Page', 'neptune-portfolio' ) . ' <span class="ast-right-arrow">&rarr;</span>',
				'string-blog-navigation-previous'        => '<span class="ast-left-arrow">&larr;</span> ' . __( 'Previous Page', 'neptune-portfolio' ),

				// Single Post Default Strings.
				'string-single-page-links-before'        => __( 'Pages:', 'neptune-portfolio' ),
				/* translators: 1: Post type label */
				'string-single-navigation-next'          => __( 'Next %s', 'neptune-portfolio' ) . ' <span class="ast-right-arrow">&rarr;</span>',
				/* translators: 1: Post type label */
				'string-single-navigation-previous'      => '<span class="ast-left-arrow">&larr;</span> ' . __( 'Previous %s', 'neptune-portfolio' ),

				// Content None.
				'string-content-nothing-found-message'   => __( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'neptune-portfolio' ),

			)
		);

		$output = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

		/**
		 * Print or return
		 */
		if ( $echo ) {
			echo esc_html($output);
		} else {
			return $output;
		}
	}
}// End if().
