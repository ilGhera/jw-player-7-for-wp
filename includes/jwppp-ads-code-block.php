<?php
/**
 * The advertising player code block
 * @author ilGhera
 * @package jw-player-for-vip/includes
 * @since 2.0.2
 * @param  int $post_id the post id
 * @param  int $number  the number of the video
 * @return string       the code block
 */
function jwppp_ads_code_block( $post_id, $number ) {

	/*Ads options*/
	$jwppp_show_ads    = sanitize_text_field( get_option( 'jwppp-active-ads' ) );
	$jwppp_ads_client  = sanitize_text_field( get_option( 'jwppp-ads-client' ) );
	$ads_tags          = get_option( 'jwppp-ads-tag' );
	$jwppp_ads_skip    = sanitize_text_field( get_option( 'jwppp-ads-skip' ) );
	$ajaxurl           = admin_url( 'admin-ajax.php' );
	$output            = null;
    
    /*Bidding*/
	$jwppp_bidding               = sanitize_text_field( get_option( 'jwppp-active-bidding' ) );
	$ad_partners                 = get_option( 'jwppp-ad-partners' );
	$jwppp_mediation             = sanitize_text_field( get_option( 'jwppp-mediation' ) );
	$jwppp_floor_price           = sanitize_text_field( get_option( 'jwppp-floor-price' ) );
	$jwppp_range_price_increment = sanitize_text_field( get_option( 'jwppp-range-price-increment' ) );
	$jwppp_range_price_max       = sanitize_text_field( get_option( 'jwppp-range-price-max' ) );
	$jwppp_range_price_min       = sanitize_text_field( get_option( 'jwppp-range-price-min' ) );

    /*Consent management*/
	$active_gdpr        = sanitize_text_field( get_option( 'jwppp-active-gdpr' ) );
	$active_ccpa        = sanitize_text_field( get_option( 'jwppp-active-ccpa' ) );
    $consent            = $active_gdpr || $active_ccpa ? true : false;
	$gdpr_cmp_api       = sanitize_text_field( get_option( 'jwppp-gdpr-cmp-api' ) );
	$gdpr_timeout       = get_option( 'jwppp-gdpr-timeout' ) ? sanitize_text_field( get_option( 'jwppp-gdpr-timeout' ) ) : 10000;
	$default_gdpr_scope = sanitize_text_field( get_option( 'jwppp-default-gdpr-scope' ) );
	$default_gdpr_scope = $default_gdpr_scope ? 'true' : 'false';
	$ccpa_cmp_api       = sanitize_text_field( get_option( 'jwppp-ccpa-cmp-api' ) );
	$ccpa_timeout       = get_option( 'jwppp-ccpa-timeout' ) ? sanitize_text_field( get_option( 'jwppp-ccpa-timeout' ) ) : 10000;


	/*Is the main ads option activated?*/
	if ( 1 === intval( $jwppp_show_ads ) ) {

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

            /*Bidding*/
			if ( $jwppp_bidding ) {
				echo "bids: {\n";
					echo "settings: {\n";
						echo "mediationLayerAdServer: '" . esc_html( $jwppp_mediation ) . "',\n";
                    if ( in_array( $jwppp_mediation, array( 'jwp', 'jwpdfp' ), true ) && $jwppp_floor_price ) {
                        echo 'floorPriceCents: ' . esc_html( $jwppp_floor_price ) * 100 . ",\n";
                    } elseif ( in_array( $jwppp_mediation, array( 'dfp' ), true ) ) {
                        echo "buckets: [{\n";
                        echo 'increment: ' . esc_html( $jwppp_range_price_increment ) . ",\n";
                        echo 'max: ' . esc_html( $jwppp_range_price_max ) . ",\n";
                        echo 'min: ' . esc_html( $jwppp_range_price_min ) . "\n";
                        echo "}],\n";
                    }
                    if ( $consent ) {
                        echo "consentManagement: {\n";
                        if ( $active_gdpr ) {
                            echo "gdpr: {\n";
                            if ( $gdpr_cmp_api ) {
                                echo "cmpApi: '" . esc_html( $gdpr_cmp_api ) . "',\n";
                            }
                            if ( $gdpr_timeout ) {
                                echo 'timeout: ' . intval( $gdpr_timeout ) . ",\n";
                            }
                            echo 'defaultGdprScope: ' . esc_html( $default_gdpr_scope ) . ",\n";
                            echo $active_ccpa ? "},\n" : "}\n";
                        }
                        if ( $active_ccpa ) {
                            echo "usp: {\n";
                            if ( $ccpa_cmp_api ) {
                                echo "cmpApi: '" . esc_html( $ccpa_cmp_api ) . "',\n";
                            }
                            if ( $ccpa_timeout ) {
                                echo 'timeout: ' . intval( $ccpa_timeout ) . ",\n";
                            }
                            echo "}\n";
                        }
                        echo "}\n";
                    }
					echo "},\n";
					echo "bidders: [\n";

                    if ( is_array( $ad_partners  ) ) {

                        foreach ( $ad_partners as $partner ) {

                            if ( is_array( $partner ) ) {

                                echo "{\n";

                                foreach ( $partner as $key => $value ) {

                                    if ( $value ) {
                                        /* Translators: 1: property name 2: property value */
                                        echo sprintf( '%1$s: \'%2$s\',' . "\n", $key, $value );
                                    }

                                }
                                
                                echo "},\n";

                            }

                        }

                    }
                            
					echo "]\n";
				echo "}\n";
			}
			echo "},\n";
		}
	}

	return $output;
}
