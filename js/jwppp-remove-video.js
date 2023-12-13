/**
 * Remove a video in a post/ page
 *
 * @author ilGhera
 * @package jw-player-7-for-wp-premium/js
 *
 * @since 2.0.0
 *
 */
jQuery( document ).ready( function( $ ) {

	var postId 	    = jwpppRemoveVideo.postId;
	var removeNonce = jwpppRemoveVideo.removeNonce;
    var loadingGif  = jwpppRemoveVideo.loading; 
    var height = $('#jwppp-box .inside').height();
    var width  = $('#jwppp-box .inside').width();
	var tot;
	var string;

    /**
     * Add a loading GIF to the container
     *
     * @return void
     */
    var rebaseLoading = function( height, width ) {
        $(loadingGif).height( height );
        $(loadingGif).width( width );
        $('#jwppp-box .inside').html( loadingGif );
    }

    /**
     * Events on removing a video
     *
     * @return void
     */
	$( document ).on( 'click', '.jwppp-remove', function() {
		var data = {
			'action': 'jwppp_ajax_remove',
			'hidden-nonce-remove-video': removeNonce,
			'number': $( this ).attr( 'data-numb' ),
			'post_id': postId
		};

        if ( data.number <  $( '.jwppp-input-wrap:visible' ).length ) {
            data.rebase = 1;

            // Add a loading GIF
            rebaseLoading( height, width );

        }

		$.post( ajaxurl, data, function( response ) {

            if ( ! data.rebase ) {

                var element = '.jwppp-' + response;
                $( element ).remove();

            } else {

                setTimeout(function(){
                    $('#jwppp-box .inside').html(response);
                }, 1000)

            }

			/* Change playlist-how-to */
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
