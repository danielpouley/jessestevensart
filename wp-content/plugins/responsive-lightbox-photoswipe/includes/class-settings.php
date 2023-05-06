<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

new Responsive_Lightbox_PhotoSwipe_Settings();

/**
 * Responsive Lightbox PhotoSwipe settings class.
 *
 * @class Responsive_Lightbox_PhotoSwipe_Settings
 */
class Responsive_Lightbox_PhotoSwipe_Settings {

	public function __construct() {
		// set instance
		Responsive_Lightbox()->photoswipe->settings = $this;

		// filters
		add_filter( 'rl_settings_scripts', array( $this, 'scripts_settings' ) );
		add_filter( 'rl_settings_photoswipe_script_configuration', array( $this, 'lightbox_settings') );
		add_filter( 'rl_settings_tabs', array( $this, 'settings_tabs' ) );
		add_filter( 'rl_settings_licenses', array( $this, 'license' ) ); 
	}

	/**
	 * Extend RL settings.
	 */
	public function scripts_settings( $settings ) {
		$settings['photoswipe'] = array(
			'name'		=> __( 'PhotoSwipe', 'rl-photoswipe' ),
			'supports'	=> array( 'inline', 'iframe', 'ajax', 'title', 'caption', 'html_caption' )
		);
		
		return $settings;
	}

	/**
	 * PhotoSwipe script configuration
	 */
	public function lightbox_settings( $settings ) {
		$settings['prefix'] = 'rl_ps';
		$settings['fields'] = array(
			'loop' => array(
				'title' 		=> __( 'Loop', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Loop slides when using swipe gesture. If set to true you\'ll be able to swipe from last to first image.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'esc_key' => array(
				'title' 		=> __( 'Esc Key', 'rl-photoswipe' ),
				'section'		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Esc keyboard key to close PhotoSwipe.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'arrow_keys' => array(
				'title' 		=> __( 'Arrow Keys', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Keyboard left or right arrow key navigation.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'icon_color' => array(
				'title' 		=> __( 'Icons Color', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'color_picker',
				'parent' 		=> 'photoswipe'
			),
			'bg_color' => array(
				'title' 		=> __( 'Background Color', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'color_picker',
				'parent' 		=> 'photoswipe'
			),
			'bg_opacity' => array(
				'title' 		=> __( 'Background Opacity', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'number',
				'description' 	=> __( 'Should be a number from 0 to 100.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'top_bar_color' => array(
				'title' 		=> __( 'Top Bar Color', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'color_picker',
				'parent' 		=> 'photoswipe'
			),
			'top_bar_opacity' => array(
				'title' 		=> __( 'Top Bar Opacity', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'number',
				'description' 	=> __( 'Should be a number from 0 to 100.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'caption_bar_color' => array(
				'title' 		=> __( 'Caption Bar Color', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'color_picker',
				'parent' 		=> 'photoswipe'
			),
			'caption_bar_opacity' => array(
				'title' 		=> __( 'Caption Bar Opacity', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'number',
				'description' 	=> __( 'Should be a number from 0 to 100.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'caption_text_color' => array(
				'title' 		=> __( 'Caption Text Color', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'color_picker',
				'parent' 		=> 'photoswipe'
			),
			'caption_font_size' => array(
				'title' 		=> __( 'Caption Font Size', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'number',
				'description' 	=> __( 'Select caption font size.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'spacing' => array(
				'title' 		=> __( 'Spacing', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'number',
				'description' 	=> __( 'Spacing ratio between slides.', 'rl-photoswipe' ),
				'append' 		=> '%',
				'parent' 		=> 'photoswipe'
			),
			'max_spread_zoom' => array(
				'title' 		=> __( 'Max Spread Zoom', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'number',
				'description' 	=> __( 'Maximum zoom level when performing spread (zoom) gesture. 2 means that image can be zoomed 2x from original size.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'caption_el' => array(
				'title' 		=> __( 'Caption', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Enable to display image title.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'arrow_el' => array(
				'title' 		=> __( 'Arrow nav', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Enable arrow navigation.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'preloader_el' => array(
				'title' 		=> __( 'Preloader', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Enable loading image preloader.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'close_el' => array(
				'title' 		=> __( 'Close icon', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Enable to show close icon.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'fullscreen_el' => array(
				'title' 		=> __( 'Fullscreen icon', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Enable to show fullscreen icon.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'zoom_el' => array(
				'title' 		=> __( 'Zoom icon', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Enable to show zoom in/out icon.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'counter_el' => array(
				'title' 		=> __( 'Counter icon', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Enable to show image counter icon.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'counter_sep' => array(
				'title' 		=> __( 'Counter separator', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'text',
				'description' 	=> __( 'Enter the image counter separator.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'share_el' => array(
				'title' 		=> __( 'Sharing icon', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Enable to show social sharing icon.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'share_link_to' => array(
				'title'			=> __( 'Share link', 'rl-photoswipe' ),
				'section'		=> 'responsive_lightbox_configuration',
				'type'			=> 'select',
				'description'	=> __( 'Select the sharing link URL.', 'rl-photoswipe' ),
				'options'		=> array(
					'url'	=> __( 'Current URL', 'rl-photoswipe' ),
					'post'	=> __( 'Attachment Page', 'rl-photoswipe' ),
					'file'	=> __( 'Image File', 'rl-photoswipe' )
				),
				'parent' 		=> 'photoswipe'
			),
			'facebook_el' => array(
				'title' => __( 'Facebook sharing', 'responsive-lightbox' ),
				'section' => 'responsive_lightbox_configuration',
				'type' => 'multiple',
				'fields' => array(
					'facebook_el' => array(
						'type' => 'boolean',
						'label' => __( 'Enable sharing on Facebook.', 'rl-photoswipe' ),
						'parent' => 'photoswipe'
					),
					/*
					'facebook_label' => array(
						'type' => 'text',
						'description' => __( 'Enter a Facebook sharing label.', 'rl-photoswipe' ),
						'parent' => 'photoswipe'
					) */
				),
			),
			'google_el' => array(
				'title' => __( 'Google + sharing', 'rl-photoswipe' ),
				'section' => 'responsive_lightbox_configuration',
				'type' => 'multiple',
				'fields' => array(
					'google_el' => array(
						'type' => 'boolean',
						'label' => __( 'Enable sharing on Google +.', 'rl-photoswipe' ),
						'parent' => 'photoswipe'
					),
					/*
					'google_label' => array(
						'type' => 'text',
						'description' => __( 'Enter a Google + sharing label.', 'rl-photoswipe' ),
						'parent' => 'photoswipe'
					) */
				),
			),
			'twitter_el' => array(
				'title' => __( 'Twitter sharing', 'rl-photoswipe' ),
				'section' => 'responsive_lightbox_configuration',
				'type' => 'multiple',
				'fields' => array(
					'twitter_el' => array(
						'type' => 'boolean',
						'label' => __( 'Enable sharing on Twitter.', 'rl-photoswipe' ),
						'parent' => 'photoswipe'
					),
					/*
					'twitter_label' => array(
						'type' => 'text',
						'description' => __( 'Enter a Twitter sharing label.', 'rl-photoswipe' ),
						'parent' => 'photoswipe'
					) */
				),
			),
			'pinterest_el' => array(
				'title' => __( 'Pinterest sharing', 'rl-photoswipe' ),
				'section' => 'responsive_lightbox_configuration',
				'type' => 'multiple',
				'fields' => array(
					'pinterest_el' => array(
						'type' => 'boolean',
						'label' => __( 'Enable sharing on Pinterest.', 'rl-photoswipe' ),
						'parent' => 'photoswipe'
					),
					/*
					'pinterest_label' => array(
						'type' => 'text',
						'description' => __( 'Enter a Pinterest sharing label.', 'rl-photoswipe' ),
						'parent' => 'photoswipe'
					) */
				),
			),
			'download_el' => array(
				'title' => __( 'Download', 'rl-photoswipe' ),
				'section' => 'responsive_lightbox_configuration',
				'type' => 'multiple',
				'fields' => array(
					'download_el' => array(
						'type' => 'boolean',
						'label' => __( 'Enable image download option.', 'rl-photoswipe' ),
						'parent' => 'photoswipe'
					),
					/*
					'download_label' => array(
						'type' => 'text',
						'description' => __( 'Enter a download label.', 'rl-photoswipe' ),
						'parent' => 'photoswipe'
					) */
				),
			),
			'close_on_scroll' => array(
				'title' 		=> __( 'Close on Scroll', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Close gallery on page scroll. Option works just for devices without hardware touch support.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'close_on_vertical_drag' => array(
				'title' 		=> __( 'Close on Vertical Drag', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Close gallery when dragging vertically and when image is not zoomed. Always false when mouse is used.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'pinch_to_close' => array(
				'title' 		=> __( 'Pinch to Close', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Pinch to close gallery gesture. The gallery’s background will gradually fade out as the user zooms out.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'allow_pan_to_next' => array(
				'title' 		=> __( 'Pan to Next', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Allow swipe navigation to next/prev item when current item is zoomed.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'history' => array(
				'title' 		=> __( 'History', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'If set to false disables history module (back button to close gallery, unique URL for each slide).', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'focus' => array(
				'title' 		=> __( 'Focus', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Will set focus on PhotoSwipe element after it\'s open.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
			'modal' => array(
				'title' 		=> __( 'Modal', 'rl-photoswipe' ),
				'section' 		=> 'responsive_lightbox_configuration',
				'type' 			=> 'boolean',
				'label' 		=> __( 'Controls whether PhotoSwipe should expand to take up the entire viewport.', 'rl-photoswipe' ),
				'parent' 		=> 'photoswipe'
			),
		);
		
		return $settings;
	}

	/**
	 * Extend tabs settings.
	 *
	 * @param array $settings
	 * @return array
	 */
	public function settings_tabs( $settings ) {
		$settings['licenses'] = array(
			'name'	 => __( 'Licenses', 'rl-photoswipe' ),
			'key'	 => 'responsive_lightbox_licenses',
			'submit' => 'save_rl_licenses',
			'reset'	 => 'reset_rl_licenses'
		);

		return $settings;
	}
	
	/**
	 * Add license.
	 *
	 * @param array $licenses
	 * @return array
	 */
	public function license( $licenses ) {
		$licenses['photoswipe'] = array(
			'id'		=> 'photoswipe',
			'name'		=> __( 'Photoswipe Lightbox', 'rl-photoswipe' ),
			'item_name'	=> 'Photoswipe Lightbox',
			'item_id'	=> 7133
		);

		return $licenses;
	}
}