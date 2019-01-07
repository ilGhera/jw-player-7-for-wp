<?php
/**
 * The advertising player code block
 * @author ilGhera
 * @package jw-player-for-vip/includes
 * @version 1.6.0
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

	$output = null;

	/*Is the main ads option activated?*/
	if ( '1' === $jwppp_show_ads ) {

		/*Single video ads tag*/
		$jwppp_ads_tag = get_post_meta( $post_id, '_jwppp-ads-tag-' . $number, true );

		/*Ads var block*/
		$active_ads_var = sanitize_text_field( get_option( 'jwppp-active-ads-var' ) );

		if ( $active_ads_var ) {
			$ads_var_name = sanitize_text_field( get_option( 'jwppp-ads-var-name' ) );
			?>
				<script>
					jQuery(document).ready(function($){
						var tag = null;
						if(typeof <?php echo esc_html( $ads_var_name ); ?> !== 'undefined') {
							tag = <?php echo esc_html( $ads_var_name ); ?>;
						}
						var data = {
							'action': 'ads-var-name',
							'tag': tag
						}
						$.post(ajaxurl, data, function(response){
						})
					})

				</script>
				<?php
				$ads_var = get_option( 'jwppp-ads-var' );

				$output .= "advertising: {\n";
				if ( is_array( $ads_var ) ) {
					foreach ( $ads_var as $key => $value ) {
						$output .= "'$key': '" . str_replace( '\\', '', $value ) . "',\n";
					}
				}
				$output .= "},\n";

				return $output;

		} elseif ( 'no-ads' === $jwppp_ads_tag ) { //The single video ads option is not activated

			$output .= "advertising: {},\n";

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

			$output .= "advertising: {\n";
			$output .= "client: '" . esc_html( $jwppp_ads_client ) . "',\n";
			$output .= "tag: '" . $jwppp_ads_tag . "',\n";
			if ( $jwppp_ads_skip ) {
				$output .= 'skipoffset: ' . esc_html( $jwppp_ads_skip ) . ",\n";
			}
			if ( $jwppp_bidding ) {
				$output .= "bids: {\n";
					$output .= "settings: {\n";
						$output .= "mediationLayerAdServer: '" . esc_html( $jwppp_mediation ) . "',\n";
				if ( in_array( $jwppp_mediation, array( 'jwp', 'jwpdfp' ) ) && $jwppp_floor_price ) {
					$output .= 'floorPriceCents: ' . esc_html( $jwppp_floor_price ) * 100 . "\n";
				}
					$output .= "},\n";
					$output .= "bidders: [\n";
						$output .= "{\n";
						$output .= "name: 'SpotX',\n";
						$output .= "id: '" . esc_html( $jwppp_channel_id ) . "'\n";
						$output .= "}\n";
					$output .= "]\n";
					// $output .= "";

				$output .= "}\n";
			}
			$output .= "},\n";
		}
	}

	return $output;
}
