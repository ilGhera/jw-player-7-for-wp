/**
 * Remove a video in a post/ page
 * @author ilGhera
 * @package jw-player-7-for-wp/js
 * @since 2.0.0
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

        if ( data.number <  $( '.jwppp-input-wrap:visible' ).length ) {
            data.rebase = 1;

            $('#jwppp-box .inside').html( jwpppRemoveVideo.loading );

        }

		$.post( ajaxurl, data, function( response ) {

            console.log( 'RESPONSE', response );

            if ( ! data.rebase ) {

                var element = '.jwppp-' + response;
                $( element ).remove();

            } else {

                setTimeout(function(){
                    $('#jwppp-box .inside').html(response);
                }, 1000)

            }

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
