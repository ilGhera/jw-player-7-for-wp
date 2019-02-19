<?php
/**
 * Facebook istant articles player
 * @author ilGhera
 * @package jw-player-for-vip/fb
 * @version 1.6.0
 */

function jwppp_fb_player() {

	if ( isset( $_GET['jwp-instant-articles'] ) ) {

		$player = isset( $_GET['player'] ) 		   ? sanitize_text_field( $_GET['player'] ) : '';
		$player_url = isset( $_GET['player_url'] ) ? esc_url_raw( $_GET['player_url'] ) : '';
		$mediaID = isset( $_GET['mediaID'] ) 	   ? sanitize_text_field( $_GET['mediaID'] ) : '';
		$mediaURL = isset( $_GET['mediaURL'] ) 	   ? esc_url_raw( $_GET['mediaURL'] ) : '';

		$file = null;
		if ( $mediaID ) {
			$file = 'https://cdn.jwplayer.com/v2/media/' . $mediaID;
			$image = 'https://content.jwplatform.com/thumbs/' . $mediaID . '-1920.jpg';
		} elseif ( $mediaURL ) {
			$file = $mediaURL;
			$image = isset( $_GET['image'] ) ? esc_url_raw( $_GET['image'] ) : '';
		}

		$unique = Rand( 0, 1000000 );
		$div = 'jwplayer_unilad_' . $unique;

		if ( $file && ( $player || $player_url ) ) {
			echo '<html>';
				echo '<body>';
					if ( $player ) {
						echo "<script src=\"https://content.jwplatform.com/libraries/" . esc_html( $player ) . ".js\"></script>";
					} else {
						echo "<script src=\"" . esc_url( $player_url ) . "\"></script>";
					}
					echo "<div id=\"" . esc_attr( $div ) . "\"></div>";
					echo '<script type="text/JavaScript">';
						echo "playerInstance = jwplayer(" . wp_json_encode( $div ) . ");";
						echo 'playerInstance.setup({ ';
						if ( $mediaID ) {
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
