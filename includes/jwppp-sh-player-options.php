<?php
/**
 * Self hosted player options
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @since 2.0.0
 * @param  string $ar     aspect ratio
 * @param  string $width  the player width
 * @param  string $height the player height
 * @return string         the block code
 */
function jwppp_sh_player_option( $ar = '', $width = '', $height = '' ) {

	/*Get the options*/
	$jwppp_player_width = sanitize_text_field( get_option( 'jwppp-player-width' ) );
	$jwppp_player_height = sanitize_text_field( get_option( 'jwppp-player-height' ) );

	$player_version = sanitize_text_field( get_option( 'jwppp-player-version' ) );

	/*Skin customization - jwp7*/
	$jwppp_skin = sanitize_text_field( get_option( 'jwppp-skin' ) );
	$jwppp_skin_name = $jwppp_skin;

	$jwppp_skin_color_active = sanitize_text_field( get_option( 'jwppp-skin-color-active' ) );
	$jwppp_skin_color_inactive = sanitize_text_field( get_option( 'jwppp-skin-color-inactive' ) );
	$jwppp_skin_color_background = sanitize_text_field( get_option( 'jwppp-skin-color-background' ) );

	$jwppp_logo = sanitize_text_field( get_option( 'jwppp-logo' ) );
	$jwppp_logo_vertical = sanitize_text_field( get_option( 'jwppp-logo-vertical' ) );
	$jwppp_logo_horizontal = sanitize_text_field( get_option( 'jwppp-logo-horizontal' ) );
	$active_share = sanitize_text_field( get_option( 'jwppp-active-share' ) );
	$jwppp_embed_video = sanitize_text_field( get_option( 'jwppp-embed-video' ) );
	$jwppp_next_up = sanitize_text_field( get_option( 'jwppp-next-up' ) );
	$jwppp_playlist_tooltip = sanitize_text_field( get_option( 'jwppp-playlist-tooltip' ) );

	/*Player code*/

	/*Player dimensions*/
	if ( $width && $height ) {

		echo "width: " . wp_json_encode( $width ) . ",\n";
		echo "height: " . wp_json_encode( $height ) . ",\n";

	} else {

		echo "width: ";
		echo ( null !== $jwppp_player_width ) ? wp_json_encode( $jwppp_player_width ) : '640';
		echo ",\n";
		echo "height: ";
		echo ( null !== $jwppp_player_height ) ? wp_json_encode( $jwppp_player_height ) : '360';
		echo ",\n";

	}

	/*Skin*/
	echo "skin: {\n";
	if ( '7' === $player_version ) {

		echo 'none' !== $jwppp_skin_name ? "name: " . wp_json_encode( $jwppp_skin_name ) . ",\n" : '';
		echo $jwppp_skin_color_active ? "active: " . wp_json_encode( $jwppp_skin_color_active ) . ",\n" : '';
		echo $jwppp_skin_color_inactive ? "inactive: " . wp_json_encode( $jwppp_skin_color_inactive ) . ",\n" : '';
		echo $jwppp_skin_color_background ? "background: " . wp_json_encode( $jwppp_skin_color_background ) . ",\n" : '';

	} 

	echo "},\n";

	/*Logo*/
	if ( null !== $jwppp_logo ) {
		echo "logo: {\n";
		echo "file: " . wp_json_encode( $jwppp_logo, JSON_UNESCAPED_SLASHES ) . ",\n";
		echo "position: '" . trim( wp_json_encode( $jwppp_logo_vertical ), '"' ) . '-' . trim( wp_json_encode( $jwppp_logo_horizontal ), '"' ) . "',\n";
		echo "},\n";
	}

	/*Localization*/
	echo "localization: {\n";
	if ( $jwppp_next_up ) {
		echo "nextUp: " . wp_json_encode( $jwppp_next_up ) . ",\n";
	}
	if ( $jwppp_playlist_tooltip ) {
		echo "playlist: " . wp_json_encode( $jwppp_playlist_tooltip ) . ",\n";
	}
	echo "},\n";

}
