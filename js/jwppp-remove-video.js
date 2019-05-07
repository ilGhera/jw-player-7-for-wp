/**
 * Remove a video in a post/ page
 * @author ilGhera
 * @package jw-player-for-vip/js
* @version 2.0.0
 *
 */
jQuery( document ).ready( function( $ ) {

	var postId 	    = jwpppRemoveVideo.postId;
	var removeNonce = jwpppRemoveVideo.removeNonce;
	var tot;
	var string;

	$( document ).on( 'click', '.jwppp-remove', function() {
		var data = {
			'action': 'jwppp_ajax_remove',
			'hidden-nonce-remove-video': removeNonce,
			'number': $( this ).attr( 'data-numb' ),
			'post_id': postId
		};

		$.post( ajaxurl, data, function( response ) {
			var element = '.jwppp-' + response;
			$( element ).remove();

			/*Change playlist-how-to*/
			tot = $( '.jwppp-input-wrap:visible' ).length;
			if ( 1 == tot ) {
				$( '.playlist-how-to' ).hide( 'slow' );
			} else {
				string = [];
				$( '.order:visible' ).each( function( i, el ) {
					string.push( $( el ).html() );
				});
				$( '.playlist-how-to code' ).html( '[jwp-video n="' + string + '"]' );
			}
		});
	});
});
