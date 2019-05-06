<?php
/**
 * Facebook istant articles player
 * @author ilGhera
 * @package jw-player-for-vip/fb
* @version 2.0.0
 */

function jwppp_fb_player() {

	if ( isset( $_GET['jwp-instant-articles'] ) ) {

		$player = isset( $_GET['player'] ) ? sanitize_text_field( wp_unslash( $_GET['player'] ) ) : '';
		$player_url = isset( $_GET['player_url'] ) ? esc_url_raw( wp_unslash( $_GET['player_url'] ) ) : '';
		$media_id = isset( $_GET['mediaID'] ) ? sanitize_text_field( wp_unslash( $_GET['mediaID'] ) ) : '';
		$media_url = isset( $_GET['mediaURL'] ) ? esc_url_raw( wp_unslash( $_GET['mediaURL'] ) ) : '';
		$license = null;
		$file = null;

		if ( $media_id ) {

			$file = 'https://cdn.jwplayer.com/v2/media/' . $media_id;
			$image = 'https://content.jwplatform.com/thumbs/' . $media_id . '-1920.jpg';

		} elseif ( $media_url ) {

			$file = $media_url;
			$image = isset( $_GET['image'] ) ? esc_url_raw( wp_unslash( $_GET['image'] ) ) : '';

		}

		$unique = wp_rand( 0, 1000000 );
		$div = 'jwplayer_unilad_' . $unique;

		if ( $file && ( $player || $player_url ) ) {
			echo '<html>';
				echo '<body>';
					if ( $player ) {
						echo "<script src=\"" . esc_url( "https://content.jwplatform.com/libraries/$player.js" ) . "\"></script>";
					} else {
						$license = get_option( 'jwppp-licence' );
						echo "<script src=\"" . esc_url( $player_url ) . "\"></script>";
					}
					echo "<div id=\"" . esc_attr( $div ) . "\"></div>";
					echo '<script type="text/JavaScript">';
						echo $license ? "jwplayer.key = " . wp_json_encode( $license ) . "\n," : "";
						echo "playerInstance = jwplayer(" . wp_json_encode( $div ) . ");";
						echo 'playerInstance.setup({ ';
						if ( $media_id ) {
							echo "playlist: " . wp_json_encode( $file ) . ",\n";
							echo "image: " . wp_json_encode( $image ) . "\n";
						} else {
							echo "file: " . wp_json_encode( $file ) . ",\n";
							echo "image: " . wp_json_encode( $image ) . "\n";
						}
						echo '});';
					echo '</script>';
				echo '</body>';
			echo '</html>';
			exit;
		}
	}
}
add_action( 'init', 'jwppp_fb_player' );
