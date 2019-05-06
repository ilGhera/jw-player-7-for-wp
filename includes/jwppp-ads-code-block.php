<?php
/**
 * The advertising player code block
 * @author ilGhera
 * @package jw-player-for-vip/includes
* @version 2.0.0
 * @param  int $post_id the post id
 * @param  int $number  the number of the video
 * @return string       the code block
 */
function jwppp_ads_code_block( $post_id, $number ) {

	/*Ads options*/
	$jwppp_show_ads = sanitize_text_field( get_option( 'jwppp-active-ads' ) );
	$jwppp_ads_client = sanitize_text_field( get_option( 'jwppp-ads-client' ) );
	$ads_tags = get_option( 'jwppp-ads-tag' );
	$jwppp_ads_skip = sanitize_text_field( get_option( 'jwppp-ads-skip' ) );
	$jwppp_bidding = sanitize_text_field( get_option( 'jwppp-active-bidding' ) );
	$jwppp_channel_id = sanitize_text_field( get_option( 'jwppp-channel-id' ) );
	$jwppp_mediation = sanitize_text_field( get_option( 'jwppp-mediation' ) );
	$jwppp_floor_price = sanitize_text_field( get_option( 'jwppp-floor-price' ) );
	$ajaxurl = admin_url( 'admin-ajax.php' );
	$output = null;

	/*Is the main ads option activated?*/
	if ( '1' === $jwppp_show_ads ) {

		/*Single video ads tag*/
		$jwppp_ads_tag = get_post_meta( $post_id, '_jwppp-ads-tag-' . $number, true );

		/*Ads var block*/
		$active_ads_var = sanitize_text_field( get_option( 'jwppp-active-ads-var' ) );

		if ( $active_ads_var ) {

			$ads_var = json_decode( get_option( 'jwppp-ads-var' ), true );
			echo "advertising: {\n";
				if ( $ads_var ) {
					foreach ( $ads_var as $key => $value ) {
						echo wp_json_encode( $key ) . ': ' . str_replace( '\\', '', wp_json_encode( $value ) ) . ', ';
					}
				}
			echo "},\n";

		} elseif ( 'no-ads' === $jwppp_ads_tag ) { //The single video ads option is not activated

			echo "advertising: {},\n";

			return $output;

		} else {

			/*Delete single video ad tag if not available anymore*/
			if ( is_array( $ads_tags ) && ! empty( $ads_tags ) ) {
				if ( ! jwppp_ads_tag_exists( $ads_tags, $jwppp_ads_tag ) ) {
					$jwppp_ads_tag = $ads_tags[0]['url'];
					delete_post_meta( $post_id, '_jwppp-ads-tag-' . $number );
				}
			} elseif ( is_string( $ads_tags ) ) {
				if ( $jwppp_ads_tag !== $ads_tags ) {
					$jwppp_ads_tag = $ads_tags;
					delete_post_meta( $post_id, '_jwppp-ads-tag-' . $number );
				}
			}

			echo "advertising: {\n";
			echo "client: '" . esc_html( $jwppp_ads_client ) . "',\n";
			echo "tag: '" . esc_url_raw( $jwppp_ads_tag ) . "',\n";
			if ( $jwppp_ads_skip ) {
				echo 'skipoffset: ' . esc_html( $jwppp_ads_skip ) . ",\n";
			}
			if ( $jwppp_bidding ) {
				echo "bids: {\n";
					echo "settings: {\n";
						echo "mediationLayerAdServer: '" . esc_html( $jwppp_mediation ) . "',\n";
				if ( in_array( $jwppp_mediation, array( 'jwp', 'jwpdfp' ), true ) && $jwppp_floor_price ) {
					echo 'floorPriceCents: ' . esc_html( $jwppp_floor_price ) * 100 . "\n";
				}
					echo "},\n";
					echo "bidders: [\n";
						echo "{\n";
						echo "name: 'SpotX',\n";
						echo "id: '" . esc_html( $jwppp_channel_id ) . "'\n";
						echo "}\n";
					echo "]\n";
				echo "}\n";
			}
			echo "},\n";
		}
	}

	return $output;
}
