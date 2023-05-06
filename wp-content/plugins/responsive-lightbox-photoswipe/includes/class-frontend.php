<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

new Responsive_Lightbox_PhotoSwipe_Frontend();

/**
 * Responsive Lightbox PhotoSwipe frontend class.
 *
 * @class Responsive_Lightbox_PhotoSwipe_Frontend
 */
class Responsive_Lightbox_PhotoSwipe_Frontend {

	public function __construct() {
		// set instance
		Responsive_Lightbox()->photoswipe->frontend = $this;

		// actions
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initialize lightbox actions and filters.
	 *
	 * @return void
	 */
	public function init() {
		if ( ! is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && Responsive_Lightbox()->options['settings']['script'] === 'photoswipe' ) {
			// actions
			add_action( 'rl_lightbox_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

			// filters
			add_filter( 'rl_lightbox_scripts', array( $this, 'lightbox_scripts' ) );
			add_filter( 'rl_lightbox_styles', array( $this, 'lightbox_styles' ) );
			add_filter( 'rl_lightbox_args', array( $this, 'lightbox_args' ) );
			add_filter( 'rl_lightbox_image_link', array( $this, 'lightbox_image_link' ), 10, 2 );
			add_filter( 'rl_lightbox_gallery_link', array( $this, 'lightbox_gallery_link' ), 10, 2 );
			add_filter( 'rl_lightbox_content_link', array( $this, 'lightbox_content_link' ), 10, 2 );
		}
	}

	/**
	 * Enqueue default scripts and styles.
	 *
	 * @return void
	 */
	public function wp_enqueue_scripts() {
		wp_register_script(
			'responsive-lightbox-photoswipe', plugins_url( 'js/frontend.js', dirname( __FILE__ ) ), array( 'jquery' ), '', ( Responsive_Lightbox()->options['settings']['loading_place'] === 'header' ? false : true )
		);

		wp_localize_script(
			'responsive-lightbox-photoswipe',
			'rlPsArgs',
			array(
				'template' => '
					<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="pswp__bg"></div>
					<div class="pswp__scroll-wrap">
						<div class="pswp__container">
							<div class="pswp__item"></div>
							<div class="pswp__item"></div>
							<div class="pswp__item"></div>
						</div>
						<div class="pswp__ui pswp__ui--hidden">
							<div class="pswp__top-bar">
								<div class="pswp__counter"></div>
								<button class="pswp__button pswp__button--close" title="' . __( 'Close (Esc)', 'rl-photoswipe' ) . '"></button>
								<button class="pswp__button pswp__button--share" title="' . __( 'Share', 'rl-photoswipe' ) . '"></button>
								<button class="pswp__button pswp__button--fs" title="' . __( 'Toggle fullscreen', 'rl-photoswipe' ) . '"></button>
								<button class="pswp__button pswp__button--zoom" title="' . __( 'Zoom in/out', 'rl-photoswipe' ) . '"></button>
								<div class="pswp__preloader">
									<div class="pswp__preloader__icn">
									  <div class="pswp__preloader__cut">
										<div class="pswp__preloader__donut"></div>
									  </div>
									</div>
								</div>
							</div>
							<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
								<div class="pswp__share-tooltip"></div>
							</div>
							<button class="pswp__button pswp__button--arrow--left" title="' . __( 'Previous (arrow left)', 'rl-photoswipe' ) . '">
							</button>
							<button class="pswp__button pswp__button--arrow--right" title="' . __( 'Next (arrow right)', 'rl-photoswipe' ) . '">
							</button>
							<div class="pswp__caption">
								<div class="pswp__caption__center"></div>
							</div>
						</div>
					</div>
				</div>'
			)
		);

		wp_register_script(
			'responsive-lightbox-photoswipe-js', plugins_url( 'assets/photoswipe.min.js', dirname( __FILE__ ) ), array( 'jquery' ), '', ( Responsive_Lightbox()->options['settings']['loading_place'] === 'header' ? false : true )
		);

		wp_register_script(
			'responsive-lightbox-photoswipe-ui', plugins_url( 'assets/photoswipe-ui-default.min.js', dirname( __FILE__ ) ), array( 'jquery' ), '', ( Responsive_Lightbox()->options['settings']['loading_place'] === 'header' ? false : true )
		);

		wp_register_style(
			'responsive-lightbox-photoswipe-css', plugins_url( 'assets/photoswipe.css', dirname( __FILE__ ) )
		);

		wp_register_style(
			'responsive-lightbox-photoswipe-skin', plugins_url( 'assets/default-skin/default-skin.css', dirname( __FILE__ ) )
		);
		
		// add inline style
		$custom_style = '
			.pswp .pswp__bg {
				background-color: rgb(' . implode( ',', $this->hex2rgb( Responsive_Lightbox()->options['configuration']['photoswipe']['bg_color'] ) ) . ');
			}
			.pswp button:hover {
				background-color: transparent;
			}
			.pswp .pswp__button:before {
				color: ' . Responsive_Lightbox()->options['configuration']['photoswipe']['icon_color'] . ';
			}
			.pswp .pswp__top-bar {
				background-color: rgba(' . implode( ',', $this->hex2rgb( Responsive_Lightbox()->options['configuration']['photoswipe']['top_bar_color'] ) ) . ',' . (int) Responsive_Lightbox()->options['configuration']['photoswipe']['top_bar_opacity'] / 100 . ');
				color: #fff;
			}
			.pswp .pswp__top-bar button {
				color: #fff;
			}
			.pswp .pswp__caption {
				background-color: rgba(' . implode( ',', $this->hex2rgb( Responsive_Lightbox()->options['configuration']['photoswipe']['caption_bar_color'] ) ) . ',' . (int) Responsive_Lightbox()->options['configuration']['photoswipe']['caption_bar_opacity'] / 100 . ');
			}
			.pswp .pswp__caption__center {
				color: ' . Responsive_Lightbox()->options['configuration']['photoswipe']['caption_text_color'] . ';
				font-size: ' . (int) Responsive_Lightbox()->options['configuration']['photoswipe']['caption_font_size'] . 'px;
				text-align: center;
			}
			.pswp a.pswp__share--google:hover {
				background: #d34836;
				color: #fff;
			}
			.rl-ps-content-container {
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate( -50%, -50% );
				margin: 0;
			}
			.rl-ps-content-inline {
				padding: 10%;
				background: #fff;
			}
		';
		
		wp_add_inline_style( 'responsive-lightbox-photoswipe-skin', $custom_style );
	}
	
	/**
	 * Extend RL scripts.
	 *
	 * @param array $scripts
	 * @return array
	 */
	public function lightbox_scripts( $scripts ) {
		$scripts[] = 'responsive-lightbox-photoswipe';
		$scripts[] = 'responsive-lightbox-photoswipe-js';
		$scripts[] = 'responsive-lightbox-photoswipe-ui';

		return $scripts;
	}
	
	/**
	 * Extend RL styles.
	 *
	 * @param array $styles
	 * @return array
	 */
	public function lightbox_styles( $styles ) {
		$styles[] = 'responsive-lightbox-photoswipe-css';
		$styles[] = 'responsive-lightbox-photoswipe-skin';

		return $styles;
	}
	
	/**
	 * Extend RL args with photoswipe vars.
	 *
	 * @param array $args
	 * @return array
	 */
	public function lightbox_args( $args ) {
		return wp_parse_args(
			$args,
			array(
				'sizeFallback'			=> get_option( 'large' . '_size_w' ) . 'x' . get_option( 'large' . '_size_h' ),
				'loop'					=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['loop'] ),
				'escKey'				=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['esc_key'] ),
				'arrowKeys'				=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['arrow_keys'] ),
				'bgOpacity'				=> (int) Responsive_Lightbox()->options['configuration']['photoswipe']['bg_opacity'] / 100,
				'spacing'				=> (int) Responsive_Lightbox()->options['configuration']['photoswipe']['spacing'],
				'maxSpreadZoom'			=> (int) Responsive_Lightbox()->options['configuration']['photoswipe']['max_spread_zoom'],
				'captionEl'				=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['caption_el'] ),
				'arrowEl'				=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['arrow_el'] ),
				'preloaderEl'			=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['preloader_el'] ),
				'closeEl'				=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['close_el'] ),
				'fullscreenEl'			=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['fullscreen_el'] ),
				'zoomEl'				=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['zoom_el'] ),
				'counterEl'				=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['counter_el'] ),
				'indexIndicatorSep'		=> trim( esc_attr( Responsive_Lightbox()->options['configuration']['photoswipe']['counter_sep'] ) ),
				'facebookEl'			=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['facebook_el'] ),
				'facebookLabel'			=> trim( __( 'Share on Facebook', 'rl-photoswipe' ) ),
				'googleEl'				=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['google_el'] ),
				'googleLabel'			=> trim( __( 'Share on Google +', 'rl-photoswipe' ) ),
				'twitterEl'				=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['twitter_el'] ),
				'twitterLabel'			=> trim( __( 'Share on Twitter', 'rl-photoswipe' ) ),
				'pinterestEl'			=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['pinterest_el'] ),
				'pinterestLabel'		=> trim( __( 'Share on Pinterest', 'rl-photoswipe' ) ),
				'downloadEl'			=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['download_el'] ),
				'downloadLabel'			=> trim( __( 'Download image', 'rl-photoswipe' ) ),
				'shareEl'				=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['share_el'] ),
				'shareLinkTo'			=> Responsive_Lightbox()->options['configuration']['photoswipe']['share_link_to'],
				'closeOnScroll'			=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['close_on_scroll'] ),
				'closeOnVerticalDrag'	=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['close_on_vertical_drag'] ),
				'pinchToClose'			=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['pinch_to_close'] ),
				'allowPanToNext'		=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['allow_pan_to_next'] ),
				'history'				=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['history'] ),
				'focus'					=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['focus'] ),
				'modal'					=> (int) ( (bool) Responsive_Lightbox()->options['configuration']['photoswipe']['modal'] )
			)
		);
	}

	
	
	/*
	
	
	
	// try regex to get file size from filename
		preg_match_all( '/[\d]+x[\d]+$/', $args['link_parts'][1], $src );

		// image url
		$url = $args['link_parts'][1] . '.' . $args['link_parts'][2];

		// any results?
		if ( ! empty( $src[0] ) ) {
			// get last dimension
			$dimensions = explode( 'x', end( $src[0] ) );

			// prepare dimensions
			$src = array( $url, $dimensions[0], $dimensions[1] );
		} else {
			// if not found, get attachment full size
			if ( $args['image_id'] > 0 ) {
				$src = wp_get_attachment_image_src( $args['image_id'], 'full' );

				if ( $src !== false )
					$src = array( $url, 0, 0 );
			// not attachment?
			} else {
				$image_data = function_exists( 'rl_get_image_size_by_url' ) ? rl_get_image_size_by_url( $url ) : getimagesize( $url );

				if ( $image_data !== false )
					$src = array( $url, $image_data[0], $image_data[1] );
			}
		}

		$sharing_url = '';

		if ( $args['settings']['script']['share_el'] ) {
			// get sharing link
			switch ( $args['settings']['script']['share_link_to'] ) {
				case 'file':
					if ( $args['image_id'] > 0 ) {
						$src = wp_get_attachment_image_src( $args['image_id'], 'full' );

						if ( $src !== false )
							$sharing_url = $src[0];
						else
							$sharing_url = $url;
					}
					else {
						$sharing_url = $url;
					}
					break;

				case 'post':
					if ( $args['image_id'] > 0 )
						$sharing_url = get_permalink( $args['image_id'] );
					else
						$sharing_url = $url;
					break;

				case 'url':
				default:
					global $wp;

					$sharing_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
			}
		}

		$link = preg_replace( '/(<a.*?)>/is', '$1' . ( $sharing_url !== '' ? ' data-sharing-url="' . $sharing_url . '"' : '' ) . ' data-size="' . $src[1] . 'x' . $src[2] . '">', $link );

		return $link;
	
	*/
	
	
	
	
	
	
	/**
	 * Add lightbox to image links.
	 *
	 * @param string $link Image link
	 * @param array $args Link arguments
	 * @return string Updated image link
	 */
	public function lightbox_image_link( $link, $args ) {
		// try regex to get file size from filename
		preg_match_all( '/[\d]+x[\d]+$/', $args['link_parts'][1], $src );

		// image size
		$size = array( 0, 0 );

		// image url
		$url = $args['link_parts'][1] . '.' . $args['link_parts'][2];

		// any results?
		if ( ! empty( $src[0] ) ) {
			// get last dimension in case there are more than 1 size
			$dimensions = explode( 'x', end( $src[0] ) );

			// prepare dimensions
			$size = array( (int) $dimensions[0], (int) $dimensions[1] );
		} else {
			// if not found dimensions, get attachment full size
			if ( $args['image_id'] > 0 ) {
				$image = wp_get_attachment_image_src( $args['image_id'], 'full' );

				// valid data?
				if ( $image !== false ) {
					$fullsize_url = $image[0];
					$size = array( $image[1], $image[2] );
				}
			// is it URL, not attachment?
			} else {
				$image = function_exists( 'rl_get_image_size_by_url' ) ? rl_get_image_size_by_url( $url ) : getimagesize( $url );

				if ( $image !== false )
					$size = array( $image[0], $image[1] );
			}
		}

		if ( $args['settings']['script']['share_el'] ) {
			// get sharing link
			switch ( $args['settings']['script']['share_link_to'] ) {
				case 'file':
					if ( $args['image_id'] > 0 ) {
						if ( isset( $fullsize_url ) )
							$url = $fullsize_url;
						else {
							$image = wp_get_attachment_image_src( $args['image_id'], 'full' );

							// valid data?
							if ( $image !== false )
								$url = $image[0];
						}
					}
					break;

				case 'post':
					if ( $args['image_id'] > 0 )
						$url = get_permalink( $args['image_id'] );
					break;

				case 'url':
				default:
					global $wp;

					$url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
			}
		}

		$link = preg_replace( '/(<a.*?)>/is', '$1' . ( $args['settings']['script']['share_el'] ? ' data-sharing-url="' . $url . '"' : '' ) . ' data-size="' . $size[0] . 'x' . $size[1] . '">', $link );

		return $link;
	}

	/**
	 * Add lightbox to gallery image links.
	 *
	 * @param string $link Image link
	 * @param array $args Link arguments
	 * @return string Updated image link
	 */
	public function lightbox_gallery_link( $link, $args ) {
		$sharing_url = '';

		if ( $args['settings']['script']['share_el'] ) {
			// get sharing link
			switch ( $args['settings']['script']['share_link_to'] ) {
				case 'file':
					$src = wp_get_attachment_image_src( $id, 'full' );
					$sharing_url = $src[0];
					break;

				case 'post':
					$sharing_url = get_permalink( $id );
					break;

				case 'url':
				default:
					global $wp;

					$sharing_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
					break;
			}
		}

		// add size
		$link = preg_replace( '/(<a.*?)>/is', '$1 ' . ( ! empty( $args['src'] ) ? ' data-size="' . $args['src'][1] . 'x' . $args['src'][2] . '"' : '' ) . ( $sharing_url !== '' ? ' data-sharing-url="' . $sharing_url . '"' : '' ) . '>', $link );

		return $link;
	}

	/**
	 * Add lightbox to content links.
	 *
	 * @param string $link Content link
	 * @param array $args Content arguments
	 * @return string Updated content link
	 */
	public function lightbox_content_link( $link, $args ) {
		return $link;

		if ( in_array( $args['content'], $args['supports'], true ) ) {
			// link already contains data-rel attribute?
			if ( preg_match( '/<a.*?(?:data-rel)=(?:\'|")(.*?)(?:\'|").*?>/is', $link, $result ) === 1 )
				$link = preg_replace( '/data-rel=(\'|")(.*?)(\'|")/is', 'data-rel="' . $args['selector'] . '-content-' . base64_encode( $result[1] ) . '"', $link );
			else
				$link = preg_replace( '/(<a.*?)>/is', '$1 data-rel="' . $args['selector'] . '-content-' . $args['link_number'] . '">', $link );

			// inline
			if ( $args['content'] === 'inline' )
				$link = preg_replace( '/(<a.*?href=(?:\'|"))(.*?)(\'|")(.*?>)/is', '$1$2$3 data-src="$2" $4', $link );
			// iframe
			elseif ( $args['content'] === 'iframe' )
				$link = preg_replace( '/(<a.*?)>/is', '$1 data-type="' . $args['content'] . '">', $link );
			// ajax
			elseif ( $args['content'] === 'ajax' ) {
				$link = preg_replace( '/(<a.*?href=(?:\'|"))(.*?)(\'|")(.*?>)/is', '$1$2$3 data-src="$2" $4', $link );
				$link = preg_replace( '/(<a.*?)>/is', '$1 data-type="' . $args['content'] . '">', $link );
			}
		}

		return $link;
	}

	/**
	 * Convert hex color to rgb color.
	 * 
	 * @param type $color
	 * @return array
	 */
	public function hex2rgb( $color ) {
		if ( $color[0] == '#' )
			$color = substr( $color, 1 );

		if ( strlen( $color ) == 6 )
			list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		elseif ( strlen( $color ) == 3 )
			list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		else
			return false;

		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );

		return array( $r, $g, $b );
	}
}