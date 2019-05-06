/**
 * Player check, used for changing the admin options available
 * @author ilGhera
 * @package jw-player-for-vip/js
* @version 2.0.0
 */
jQuery( document ).ready( function( $ ) {

	var nonce = jwpppPlayerCheck.nonce;

	$( '#jwppp-library' ).on( 'change', function() {
		var player = $( '#jwppp-library' ).val();
		var data = {
			'action': 'player_check',
			'player': player,
			'hidden-nonce-player-check': nonce
		};
		$.post( ajaxurl, data, function( response ) {
			if ( 'done' === response ) {
				location.reload();
			}
		});
	});
});
