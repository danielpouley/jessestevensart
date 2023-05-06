<?php
/*
Plugin Name: Responsive Lightbox - PhotoSwipe
Description: Responsive Lightbox extension. Beautiful, fully responsive image lightbox for mobile and desktop with pinch & zoom support.
Version: 1.1.2
Author: dFactory
Author URI: https://dfactory.eu/
Plugin URI: https://dfactory.eu/products/photoswipe-lightbox/
License: MIT License
License URI: http://opensource.org/licenses/MIT
Text Domain: rl-photoswipe
Domain Path: /languages

Responsive Lightbox - PhotoSwipe
Copyright (C) 2016-2018, Digital Factory - info@digitalfactory.pl

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Responsive Lightbox Lightcase class.
 *
 * @class Responsive_Lightbox_Lightcase
 */
class Responsive_Lightbox_PhotoSwipe {

	const VERSION = '1.1.2';

	private static $_instance;
	private $notices = array();

	/**
	 *
	 */
	private function __construct() {
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		
		// actions
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'run_updater'), 0 );
		
		// filters
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_settings_link' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_extend_links' ), 10, 2 );
	}

	private function __clone() {}
	private function __wakeup() {}

	/**
	 *
	 */
	public static function instance() {
		if ( self::$_instance === null )
			self::$_instance = new self();

		return self::$_instance;
	}
	
	/**
	 * Plugin init
	 */
	public function init() {
		if ( class_exists( 'Responsive_Lightbox' ) ) {
			// check version
			if ( version_compare( Responsive_Lightbox()->defaults['version'], '1.6.0', '<' ) ) {
				$this->add_notice( __( 'Responsive Lightbox - PhotoSwipe extension requires at least version 1.6.0 of Responsive Lightbox to work.', 'rl-photoswipe' ), 'error' );

				return;
			}

			// set instance
			Responsive_Lightbox()->photoswipe = $this;

			include_once( plugin_dir_path( __FILE__ ) . 'includes/class-settings.php' );
			include_once( plugin_dir_path( __FILE__ ) . 'includes/class-frontend.php' );

			Responsive_Lightbox()->defaults['configuration']['photoswipe'] = array(
				'loop' 						=> true,
				'esc_key' 					=> true,
				'arrow_keys' 				=> true,
				'bg_color'					=> '#000000',
				'bg_opacity' 				=> 70,
				'top_bar_color'				=> '#000000',
				'top_bar_opacity' 			=> 50,
				'caption_bar_color'			=> '#000000',
				'caption_bar_opacity' 		=> 50,
				'icon_color'				=> '#ffffff',
				'caption_text_color'		=> '#bbbbbb',
				'caption_font_size'			=> 13,
				'spacing' 					=> 12,
				'max_spread_zoom' 			=> 2,
				'caption_el'				=> true,
				'arrow_el'					=> true,
				'preloader_el'				=> true,
				'close_el'					=> true,
			    'fullscreen_el'				=> true,
			    'zoom_el'					=> true,
			    'counter_el'				=> true,
				'counter_sep'				=> '/',
				'share_el'					=> true,
				'share_link_to'				=> 'url',
				'facebook_el'				=> true,
				// 'facebook_label'			=> __( 'Share on Facebook', 'rl-photoswipe' ),
				'google_el'					=> true,
				// 'google_label'				=> __( 'Share on Google +', 'rl-photoswipe' ),
				'twitter_el'				=> true,
				// 'twitter_label'				=> __( 'Share on Twitter', 'rl-photoswipe' ),
				'pinterest_el'				=> true,
				// 'pinterest_label'			=> __( 'Share on Pinterest', 'rl-photoswipe' ),
				'download_el'				=> true,
				// 'download_label'			=> __( 'Download image', 'rl-photoswipe' ),
				'close_on_scroll' 			=> false,
				'close_on_vertical_drag' 	=> true,
				'pinch_to_close' 			=> true,
				'allow_pan_to_next' 		=> true,
				'history' 					=> false,
				'focus' 					=> true,
				'modal' 					=> true
			);

			Responsive_Lightbox()->options['configuration']['photoswipe'] = array_merge( Responsive_Lightbox()->defaults['configuration']['photoswipe'], ( ( $array = get_option( 'responsive_lightbox_configuration' ) ) === false ? array() : ! empty( $array['photoswipe'] ) ? $array['photoswipe'] : array() ) );
		} else
			$this->add_notice( __( 'Responsive Lightbox - PhotoSwipe extension requires Responsive Lightbox plugin activated to work.', 'rl-photoswipe' ), 'error' );

		// load plugin updater class
		if ( ! class_exists( 'Responsive_Lightbox_Updater' ) )
			include_once( plugin_dir_path( __FILE__)  . 'includes/class-updater.php' );
	}
	
	/**
	 * Activation function.
	 */
	public function activate( $networkwide ) {
		if ( is_multisite() && $networkwide ) {
			global $wpdb;

			$current_blog_id = $wpdb->blogid;
			$blogs_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT blog_id FROM ' . $wpdb->blogs, '' ) );

			foreach ( $blogs_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				$this->activate_single();
				$activated_blogs[] = (int) $blog_id;
			}

			switch_to_blog( $current_blog_id );
		} else
			$this->activate_single();
	}
	
	/**
	 * Dectivation function.
	 */
	public function deactivate( $networkwide ) {
		if ( is_multisite() && $networkwide ) {
			global $wpdb;

			$current_blog_id = $wpdb->blogid;
			$blogs_ids = $wpdb->get_col( $wpdb->prepare( 'SELECT blog_id FROM ' . $wpdb->blogs, '' ) );

			if ( ( $activated_blogs = get_site_option( 'responsive_lightbox_activated_blogs', false, false ) ) === false )
				$activated_blogs = array();

			foreach ( $blogs_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				$this->deactivate_single( true );

				if ( in_array( (int) $blog_id, $activated_blogs, true ) )
					unset( $activated_blogs[array_search( $blog_id, $activated_blogs )] );
			}

			switch_to_blog( $current_blog_id );
		} else
			$this->deactivate_single();
	}
	
	/**
	 * Single blog activation function.
	 */
	public function activate_single() {	
		$licenses = get_option( 'responsive_lightbox_licenses' );

		if ( ! empty( $licenses ) && is_array( $licenses ) )
			update_option( 'responsive_lightbox_licenses', array_merge( array( 'photoswipe' => array( 'license' => '', 'status' => false ) ), $licenses ) );
		else
			add_option( 'responsive_lightbox_licenses', array( 'photoswipe' => array( 'license' => '', 'status' => false ) ), '', 'no' );
	}
	
	/**
	 * Single blog deactivation function.
	 */
	public function deactivate_single( $multi = false ) {
		if ( $multi === true ) {
			$options = get_option( 'responsive_lightbox_settings' );
			$check = $options['deactivation_delete'];
		} else
			$check = Responsive_Lightbox()->options['settings']['deactivation_delete'];

		if ( $check === true ) {
			$licenses = get_option( 'responsive_lightbox_licenses' );
		
			if ( $licenses && is_array( $licenses ) ) {
				if ( isset( $licenses['photoswipe'] ) )
					unset( $licenses['photoswipe'] );

				if ( ! empty( $licenses ) )
					update_option( 'responsive_lightbox_licenses', $licenses );
				else
					delete_option( 'responsive_lightbox_licenses' );
			}
		}
	}

	/**
	 * Load textdomain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'rl-photoswipe', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Add admin notices.
	 */
	public function add_notice( $html = '', $status = 'error', $paragraph = true, $network = false ) {
		$this->notices[] = array(
			'html' 		=> $html,
			'status' 	=> $status,
			'paragraph' => $paragraph
		);

		add_action( 'admin_notices', array( $this, 'display_notice') );

		if ( $network )
			add_action( 'network_admin_notices', array( $this, 'display_notice') );
	}

	/**
	 * Print admin notices.
	 */
	public function display_notice() {
		foreach( $this->notices as $notice ) {
			echo '
			<div class="' . $notice['status'] . '">
				' . ( $notice['paragraph'] ? '<p>' : '' ) . '
				' . $notice['html'] . '
				' . ( $notice['paragraph'] ? '</p>' : '' ) . '
			</div>';
		}
	}

	/**
	 * Add links to Settings page
	 */
	public function plugin_settings_link( $links, $file ) {
		if ( ! is_admin() || ! current_user_can( 'install_plugins' ) )
			return $links;

		$plugin = plugin_basename( __FILE__ );

		if ( $file == $plugin )
			array_unshift( $links, sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=responsive-lightbox-configuration&section=photoswipe' ), __( 'Settings', 'rl-photoswipe' ) ) );

		return $links;
	}

	/**
	 * Add link to Support Forum
	 */
	public function plugin_extend_links( $links, $file ) {
		if ( ! current_user_can( 'install_plugins' ) )
			return $links;

		$plugin = plugin_basename( __FILE__ );

		if ( $file == $plugin )
			return array_merge( $links, array( sprintf( '<a href="http://www.dfactory.eu/support/forum/responsive-lightbox/photoswipe/" target="_blank">%s</a>', __( 'Support', 'rl-photoswipe' ) ) ) );

		return $links;
	}

	/**
	 * Run plugin updater class
	 */
	public function run_updater() {
		// setup the updater
		if ( class_exists( 'Responsive_Lightbox_Updater' ) ) {
			$updater = new Responsive_Lightbox_Updater(
				'https://dfactory.eu', __FILE__, array(
				'version'	 => self::VERSION,
				'license'	 => ( $array = get_option( 'responsive_lightbox_licenses' ) ) == false ? '' : $array['photoswipe']['license'],
				'item_name'	 => 'Photoswipe Lightbox',
				'item_id'	 => 7133,
				'author'	 => 'dfactory',
				'url'		 => home_url()
				)
			);
		}
	}
}

function Responsive_Lightbox_PhotoSwipe() {
	static $instance;

	// first call to instance() initializes the plugin
	if ( $instance === null || ! ( $instance instanceof Responsive_Lightbox_PhotoSwipe ) )
		$instance = Responsive_Lightbox_PhotoSwipe::instance();

	return $instance;
}

Responsive_Lightbox_PhotoSwipe();