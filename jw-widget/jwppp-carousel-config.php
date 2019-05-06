<?php
/**
 * JW Player carousel widget configuration
 * @author ilGhera
 * @package jw-player-for-vip/jw-widget
* @version 2.0.0
 */
function jwppp_carousel_config() {

	if ( isset( $_GET['jwp-carousel-config'] ) ) {
		/*Get data*/
		$playlist_id    = isset( $_GET['playlist-id'] ) ? sanitize_text_field( wp_unslash( $_GET['playlist-id'] ) ) : '';
		$playlist_url   = false !== strpos( $playlist_id, 'token' ) ? 'https://cdn.jwplayer.com/' . $playlist_id : 'https://cdn.jwplayer.com/v2/playlists/' . $playlist_id;
		$player_id      = isset( $_GET['player-id'] ) ? intval( $_GET['player-id'] ) : '';
		$carousel_style = isset( $_GET['carousel-style'] ) ? json_decode( base64_decode( $_GET['carousel-style'] ) ) : '';

		/*Style*/
		$title = isset( $carousel_style->title ) ? $carousel_style->title : 'More Videos';
		$text_color = isset( $carousel_style->text_color ) ? $carousel_style->text_color : '#fff';
		$background_color = isset( $carousel_style->background_color ) ? $carousel_style->background_color : '#000';
		$icon_color = isset( $carousel_style->icon_color ) ? $carousel_style->icon_color : '#fff';

		if ( $playlist_id && $player_id ) {
			echo '{';
			echo '"widgets": [';
			  echo '{';
				echo '"widgetDivId": ' . wp_json_encode( 'jwppp-playlist-carousel-' . $player_id ) . ',';
				echo '"playlist": ' . wp_json_encode( $playlist_url, JSON_UNESCAPED_SLASHES ) . ',';
				echo '"videoPlayerId": ' . wp_json_encode( 'jwppp-video-' . $player_id ) . ',';
				echo '"header": ' . wp_json_encode( $title ) . ',';
				echo '"textColor": ' . wp_json_encode( $text_color ) . ',';
				echo '"backgroundColor": ' . wp_json_encode( $background_color ) . ',';
				echo '"iconColor": ' . wp_json_encode( $icon_color ) . ',';
				echo '"widgetLayout": "shelf",';
				echo '"widgetSize": "medium"';
			  echo '}';
			echo ']';
			echo '}';
		}

		exit;
	}

}
add_action( 'init', 'jwppp_carousel_config' );
