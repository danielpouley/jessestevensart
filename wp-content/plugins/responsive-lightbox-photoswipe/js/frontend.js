( function ( $ ) {

	/**
	 * Hook into doResponsiveLightbox event
	 */
	$( document ).on( 'doResponsiveLightbox', function ( event ) {
		var script = event.script,
			selector = event.selector,
			args = event.args;

		if ( typeof script === 'undefined' || typeof selector === 'undefined' ) {
			return false;
		}

		if ( script === 'photoswipe' ) {
			var selectors = [];

			$( 'a[data-rel*="' + selector + '"]' ).each( function ( i, item ) {
				var attr = $( item ).attr( 'data-rel' );

				if ( typeof attr !== 'undefined' && attr !== false ) {
					selectors.push( attr );
				}
			} );

			if ( selectors.length > 0 ) {
				var last_image = '';

				// prevent multiple instances
				if ( ! ( $( '.pswp' ).length > 0 ) || ! ( typeof event.pagination_type !== 'undefined' ) ) {
					// add template to dom
					$( 'body' ).append( rlPsArgs.template );
				}

				// make unique
				selectors = $.unique( selectors );

				$( selectors ).each( function ( i, sel ) {
					var pic = $( document ).find( '[data-rel*="' + sel + '"]' ),
						phsw_items = [];

					if ( typeof event.pagination_type !== 'undefined' ) {
						pic.each( function() {
							$( this ).off( 'click' );
						} );
					}

					var getItems = function () {
						var items = [];

						pic.each( function ( j ) {
							var element = $( this );

							// skip videos
							if ( element.data( 'rel' ).match( /lightbox-video/ ) !== null ) {
								return;
							}

							var size = element.data( 'size' );

							if ( typeof size === 'undefined' || size === '' ) {
								// fallback to large size
								element.data( 'size', args.sizeFallback );

								// mark unknown size
								element.data( 'unknown-size', 1 );

								var img = new Image();

								img.onload = function () {
									// add data-size attribute to item
									element.data( 'size', this.width + 'x' + this.height );

									// update dimensions
									phsw_items[j].w = this.width;
									phsw_items[j].h = this.height;
								}

								img.src = $( this ).attr( 'href' );
							}

							// add index
							element.data( 'index', j );

							var title = element.data( 'rl_title' ),
								caption = element.data( 'rl_caption' ),
								size = element.data( 'size' ).split( 'x' ),
								content = element.data( 'rl_content' );
								description = '';

							if ( typeof title !== 'undefined' && title !== '' ) {
								description = '<div class="pswp__caption__title">' + title + '</div>';
							}

							if ( typeof caption !== 'undefined' && caption !== '' ) {
								description = description + '<div class="pswp__caption__caption">' + caption + '</div>';
							}

							if ( typeof content !== 'undefined' ) {
								var html = '';

								if ( content === 'inline' ) {
									html = $( element.attr( 'href' ) )[0].outerHTML;
								} else if ( content === 'iframe' ) {
									html = '<iframe src="' + element.attr( 'href' ) + '" />';
								} else if ( content === 'ajax' ) {
									$.post( element.attr( 'href' ) ).always( function( data ) {
										html = 'a' + data;
									} );
								}

								var item = {
									el: element,
									html: '<div class="rl-ps-content-container"><div class="rl-ps-content-' + content + '">' + html + '</div></div>'
								};
							} else {
								var item = {
									el: element,
									src: element.attr( 'href' ),
									w: size[0],
									h: size[1],
									title: description !== '' ? description : element.attr( 'title' )
								};
							}

							items.push( item );
						} );

						return items;
					}

					var pswp = $( '.pswp' )[0];

					// get items
					phsw_items = getItems();

					pic.on( 'click', function ( event ) {
						var element = $( this );

						// skip videos
						if ( element.data( 'rel' ).match( /lightbox-video/ ) !== null ) {
							return;
						}

						event.preventDefault();

						args.shareButtons = [];

						if ( parseBoolean( args.facebookEl ) ) {
							args.shareButtons.push( { id: 'facebook', label: args.facebookLabel, url: '//www.facebook.com/sharer/sharer.php?u={{url}}' } );
						}

						if ( parseBoolean( args.googleEl ) ) {
							args.shareButtons.push( { id: 'google', label: args.googleLabel, url: '//plus.google.com/share?url={{url}}' } );
						}

						if ( parseBoolean( args.twitterEl ) ) {
							args.shareButtons.push( { id: 'twitter', label: args.twitterLabel, url: '//twitter.com/intent/tweet?text={{text}}&url={{url}}' } );
						}

						if ( parseBoolean( args.pinterestEl ) ) {
							args.shareButtons.push( { id: 'pinterest', label: args.pinterestLabel, url: '//www.pinterest.com/pin/create/button/' +
									'?url={{url}}&media={{image_url}}&description={{text}}' } );
						}

						if ( parseBoolean( args.downloadEl ) ) {
							args.shareButtons.push( { id: 'download', label: args.downloadLabel, url: '{{raw_image_url}}', download: true } );
						}

						var options = {
							captionEl: parseBoolean( args.captionEl ),
							arrowEl: parseBoolean( args.arrowEl ),
							preloaderEl: parseBoolean( args.preloaderEl ),
							closeEl: parseBoolean( args.closeEl ),
							fullscreenEl: parseBoolean( args.fullscreenEl ),
							zoomEl: parseBoolean( args.zoomEl ),
							shareEl: parseBoolean( args.shareEl ),
							counterEl: parseBoolean( args.counterEl ),
							indexIndicatorSep: ' ' + String( args.indexIndicatorSep ) + ' ',
							shareButtons: args.shareButtons,
							sizeFallback: String( args.sizeFallback ),
							loop: parseBoolean( args.loop ),
							escKey: parseBoolean( args.escKey ),
							arrowKeys: parseBoolean( args.arrowKeys ),
							bgOpacity: parseFloat( args.bgOpacity ),
							spacing: parseInt( args.spacing ),
							maxSpreadZoom: parseInt( args.maxSpreadZoom ),
							closeOnScroll: parseBoolean( args.closeOnScroll ),
							closeOnVerticalDrag: parseBoolean( args.closeOnVerticalDrag ),
							pinchToClose: parseBoolean( args.pinchToClose ),
							allowPanToNext: parseBoolean( args.allowPanToNext ),
							history: parseBoolean( args.history ),
							focus: parseBoolean( args.focus ),
							modal: parseBoolean( args.modal ),
							index: element.data( 'index' ),
							getThumbBoundsFn: function ( index ) {
								// find thumbnail element
								var thumbnail = phsw_items[index].el[0].getElementsByTagName( 'img' )[0];

								// get window scroll Y
								var pageYScroll = window.pageYOffset || document.documentElement.scrollTop;

								// get position of element relative to viewport
								if ( typeof thumbnail !== 'undefined' ) {
									var rect = thumbnail.getBoundingClientRect();

									// w = width
									return { x: rect.left, y: rect.top + pageYScroll, w: rect.width };
								} else {
									return { x: 0, y: pageYScroll, w: 0 };
								}
							},
							getPageURLForShare: function () {
								var url = $( phsw.currItem.el[0] ).data( 'sharing-url' );

								if ( typeof url !== 'undefined' && url !== '' ) {
									return url;
								} else {
									return window.location.href;
								}
							}
						};

						var phsw = new PhotoSwipe( pswp, PhotoSwipeUI_Default, phsw_items, options );

						// initialize PhotoSwipe
						phsw.init();

						phsw.listen( 'afterChange', function () {
							last_image = phsw.currItem.src;

							// trigger image view
							rl_view_image( script, last_image );
						} );

						phsw.listen( 'initialZoomInEnd', function () {
							last_image = phsw.currItem.src;

							// trigger image view
							rl_view_image( script, last_image );
						} );

						phsw.listen( 'destroy', function () {
							// trigger image hide
							rl_hide_image( script, last_image );
						} );

					} );

				} );
			}
		}
	} );

	/**
	 * Helper: parseBoolean function
	 */
	function parseBoolean( string ) {
		switch ( String( string ).toLowerCase() ) {
			case 'true':
			case '1':
			case 'yes':
				return true;

			case 'false':
			case '0':
			case 'no':
			case '':
				return false;

			default:
				// you could throw an error, but 'undefined' seems a more logical reply
				return undefined;
		}
	}

} )( jQuery );