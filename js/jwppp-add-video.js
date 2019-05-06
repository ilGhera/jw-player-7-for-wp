/**
 * Add a new video in post/ page
 * @author ilGhera
 * @package jw-player-for-vip/js
* @version 2.0.0
 *
 */
jQuery( document ).ready( function( $ ) {

	var postId 	    = jwpppAddVideo.postId;
	var addNonce    = jwpppAddVideo.addNonce;
	var removeNonce = jwpppAddVideo.removeNonce;

	$( '.jwppp-add' ).on( 'click', function() {
		var number = parseInt( $( '.order:visible' ).last().html() ) + 1;
		var firstNonce = $( '#jwppp-meta-box-nonce-1' ).val();
		var dataRemove;
		var element;
		var tot;
		var string;
		var dataAdd = {
			'action': 'jwppp_ajax_add',
			'hidden-nonce-add-video': addNonce,
			'number': number,
			'post_id': postId
		};

		$.post( ajaxurl, dataAdd, function( response ) {
			$( '#jwppp-box .inside' ).append( response );

			$( '.jwppp-remove' ).bind( 'click', function() {
				dataRemove = {
					'action': 'jwppp_ajax_remove',
					'hidden-nonce-remove-video': removeNonce,
					'number': $( this ).attr( 'data-numb' ),
					'post_id': postId
				};

				$.post( ajaxurl, dataRemove, function( response ) {
					element = '.jwppp-' + response;
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

			/*Change playlist-how-to*/
			$( '.playlist-how-to' ).show( 'slow' );
			tot = $( '.jwppp-input-wrap:visible' ).length;
			string = [];
			$( '.order:visible' ).each( function( i, el ) {
				string.push( $( el ).html() );
			});
			$( '.playlist-how-to code' ).html( '[jwp-video n="' + string + '"]' );

		});
	});
});
