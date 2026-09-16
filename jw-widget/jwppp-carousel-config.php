<?php
/**
 * JW Player carousel widget configuration
 *
 * @author ilGhera
 * @package jw-player-7-for-wp-premium/jw-widget
 * @version 2.0.0
 */
function jwppp_carousel_config() {

	if ( isset( $_GET['jwp-carousel-config'] ) ) {
		/*Get data*/
		$playlist_id    = isset( $_GET['playlist-id'] ) ? sanitize_text_field( wp_unslash( $_GET['playlist-id'] ) ) : '';
		$playlist_url   = false !== strpos( $playlist_id, 'token' ) ? 'https://cdn.jwplayer.com/' . $playlist_id : 'https://cdn.jwplayer.com/v2/playlists/' . $playlist_id;
		$player_id      = isset( $_GET['player-id'] ) ? intval( $_GET['player-id'] ) : 0;
		$carousel_style = isset( $_GET['carousel-style'] ) ? json_decode( base64_decode( sanitize_text_field( wp_unslash( $_GET['carousel-style'] ) ), true ) ) : null;

		/*Style - every value is sanitized, colors must be valid hex*/
		$title            = isset( $carousel_style->title ) ? sanitize_text_field( $carousel_style->title ) : 'More Videos';
		$text_color       = isset( $carousel_style->text_color ) ? sanitize_hex_color( $carousel_style->text_color ) : '';
		$background_color = isset( $carousel_style->background_color ) ? sanitize_hex_color( $carousel_style->background_color ) : '';
		$icon_color       = isset( $carousel_style->icon_color ) ? sanitize_hex_color( $carousel_style->icon_color ) : '';

		$text_color       = $text_color ? $text_color : '#fff';
		$background_color = $background_color ? $background_color : '#000';
		$icon_color       = $icon_color ? $icon_color : '#fff';

		if ( ! $playlist_id || ! $player_id ) {
			wp_send_json_error( null, 400 );
		}

		$config = array(
			'widgets' => array(
				array(
					'widgetDivId'     => 'jwppp-playlist-carousel-' . $player_id,
					'playlist'        => $playlist_url,
					'videoPlayerId'   => 'jwppp-video-' . $player_id,
					'header'          => $title,
					'textColor'       => $text_color,
					'backgroundColor' => $background_color,
					'iconColor'       => $icon_color,
					'widgetLayout'    => 'shelf',
					'widgetSize'      => 'medium',
				),
			),
		);

		/*JSON content type + HTML special chars encoded, so nothing is ever rendered as markup*/
		wp_send_json( $config, 200, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
	}

}
add_action( 'init', 'jwppp_carousel_config' );
