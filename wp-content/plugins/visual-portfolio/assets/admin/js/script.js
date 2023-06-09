/*!
 * Name    : Visual Portfolio
 * Version : 1.14.1
 * Author  : nK https://nkdev.info
 */
import { debounce } from 'throttle-debounce';

const $ = jQuery;
const {
    ajaxurl,
    VPAdminVariables,
    Tooltip,
} = window;

const $body = $( 'body' );

// select shortcode text in input
$body.on( 'focus', '[name="vp_list_shortcode"], [name="vp_filter_shortcode"], [name="vp_sort_shortcode"]', function() {
    this.select();
} );
$body.on( 'click', '.vp-onclick-selection', function() {
    window.getSelection().selectAllChildren( this );
} );

// Post format metabox show/hide
const $videoMetabox = $( '#vp_format_video' );
const $videoFormatCheckbox = $( '#post-format-video' );
function toggleVideoMetabox() {
    $videoMetabox[ $videoFormatCheckbox.is( ':checked' ) ? 'show' : 'hide' ]();
}
if ( $videoMetabox.length && $videoFormatCheckbox.length ) {
    toggleVideoMetabox();
    $body.on( 'change', '[name=post_format]', () => {
        toggleVideoMetabox();
    } );
}
let oembedAjax = null;
function runAjaxVideoOembed( $this ) {
    oembedAjax = $.ajax( {
        url: ajaxurl,
        method: 'GET',
        dataType: 'json',
        data: {
            action: 'vp_find_oembed',
            q: $this.val(),
            nonce: VPAdminVariables.nonce,
        },
        complete( data ) {
            const json = data.responseJSON;
            if ( json && typeof json.html !== 'undefined' ) {
                $this.next( '.vp-oembed-preview' ).html( json.html );
            }
        },
    } );
}
runAjaxVideoOembed = debounce( 300, runAjaxVideoOembed );
$body.on( 'change input', '.vp-input[name="video_url"]', function() {
    if ( oembedAjax !== null ) {
        oembedAjax.abort();
    }

    const $this = $( this );
    $this.next( '.vp-oembed-preview' ).html( '' );

    runAjaxVideoOembed( $this );
} );

// Popper.js
if ( typeof Tooltip !== 'undefined' ) {
    $( '[data-hint]:not([data-hint=""]):not([data-hint="false"])' ).each( function() {
        const $this = $( this );
        // eslint-disable-next-line no-new
        new Tooltip( this, {
            placement: $this.attr( 'data-hint-place' ) || 'top',
            title: $this.attr( 'data-hint' ),
            container: $( 'body' )[ 0 ],
            boundariesElement: 'viewport',
        } );
    } );
}
