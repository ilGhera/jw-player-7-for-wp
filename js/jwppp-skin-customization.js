/**
 * Skin customization based on the player version in use
 * @author ilGhera
 * @package jw-player-for-vip/js
* @version 2.0.0
 */
jQuery( document ).ready( function( $ ) {

	if( typeof jwpppSkin !== 'undefined' ) {

		var player = jwpppSkin.player;
		var nonce =  jwpppSkin.nonce;

		$.getScript( player, function() {
			var version  = jwplayer.version;

			if( version ) {

				var data = {
					'action': 'skin-customization',
					'version': version.split( '+' )[0],
					'hidden-nonce-skin': nonce
				};
				$.post( ajaxurl, data, function( response ) {
					$( '#jwppp-skin' ).html( response );
					$( '.jwppp-color-field' ).wpColorPicker();

					/*Custom skin*/
					if ( 'custom-skin' == $( '#jwppp-skin option:selected' ).attr( 'value' ) ) {
						$( '.custom-skin-url, .custom-skin-name' ).show();
					} else {
						$( '.custom-skin-url, .custom-skin-name' ).hide();
					}

					$( '#jwppp-skin' ).on( 'change', function() {
						if ( 'custom-skin' == $( 'option:selected', this ).attr( 'value' ) ) {
							$( '.custom-skin-url, .custom-skin-name' ).show();
						} else {
							$( '.custom-skin-url, .custom-skin-name' ).hide();
						}
					});

				});

			}

		});

	}

});
