<?php
/**
 * Ads options
 * @author ilGhera
 * @package jw-player-for-vip/admin
* @version 2.0.0
 */
?>
<div name="jwppp-ads" id="jwppp-ads" class="jwppp-admin" style="display: none;">
	<?php

	/*Active ads?*/
	$active_ads = sanitize_text_field( get_option( 'jwppp-active-ads' ) );
	if ( isset( $_POST['ads-sent'], $_POST['hidden-nonce-ads'] ) && wp_verify_nonce( $_POST['hidden-nonce-ads'], 'jwppp-nonce-ads' ) ) {
		$active_ads = isset( $_POST['jwppp-active-ads'] ) ? sanitize_text_field( wp_unslash( $_POST['jwppp-active-ads'] ) ) : 0;
		update_option( 'jwppp-active-ads', $active_ads );
	}

	/*Active ads var block*/
	$active_ads_var = sanitize_text_field( get_option( 'jwppp-active-ads-var' ) );

	/*If this option is activated, all the plugin ads options are hidden*/
	$hide = $active_ads_var ? ' style="display: none;"' : '';

	if ( isset( $_POST['ads-sent'] ) ) {
		$hide = isset( $_POST['jwppp-active-ads-var'] ) ? ' style="display: none;"' : '';
		$active_ads_var = isset( $_POST['jwppp-active-ads-var'] ) ? sanitize_text_field( wp_unslash( $_POST['jwppp-active-ads-var'] ) ) : 0;
		update_option( 'jwppp-active-ads-var', $active_ads_var );
	}

	/*Ads var block name*/
	$ads_var_name = sanitize_text_field( get_option( 'jwppp-ads-var-name' ) );
	if ( isset( $_POST['ads-sent'] ) ) {
		$ads_var_name = isset( $_POST['jwppp-ads-var-name'] ) ? sanitize_text_field( wp_unslash( $_POST['jwppp-ads-var-name'] ) ) : '';
		update_option( 'jwppp-ads-var-name', $ads_var_name );
	}

	/*Ads client*/
	$ads_client = sanitize_text_field( get_option( 'jwppp-ads-client' ) );
	if ( isset( $_POST['jwppp-ads-client'] ) ) {
		$ads_client = sanitize_text_field( wp_unslash( $_POST['jwppp-ads-client'] ) );
		update_option( 'jwppp-ads-client', $ads_client );
	}

	/*Ads tag*/
	$ads_tags = get_option( 'jwppp-ads-tag' );
	if ( isset( $_POST['hidden-total-tags'] ) ) {
		$ads_tags = array();
		for ( $i = 0; $i < sanitize_text_field( wp_unslash( $_POST['hidden-total-tags'] ) ); $i++ ) {
			if ( isset( $_POST[ 'jwppp-ads-tag-' . ( $i + 1 ) ], $_POST[ 'jwppp-ads-tag-label' . ( $i + 1 ) ] ) ) {
				$ads_tags[] = array(
					'label' => sanitize_text_field( wp_unslash( $_POST[ 'jwppp-ads-tag-label' . ( $i + 1 ) ] ) ),
					'url'   => esc_url_raw( wp_unslash( $_POST[ 'jwppp-ads-tag-' . ( $i + 1 ) ] ) ),
				);
			}
		}
		update_option( 'jwppp-ads-tag', $ads_tags );
	}

	/*Skipoffset*/
	$ads_skip = sanitize_text_field( get_option( 'jwppp-ads-skip' ) );
	if ( isset( $_POST['jwppp-ads-skip'] ) ) {
		$ads_skip = sanitize_text_field( wp_unslash( $_POST['jwppp-ads-skip'] ) );
		update_option( 'jwppp-ads-skip', $ads_skip );
	}

	/*Bidding*/
	$active_bidding = sanitize_text_field( get_option( 'jwppp-active-bidding' ) );
	if ( isset( $_POST['ads-sent'] ) ) {
		$active_bidding = isset( $_POST['jwppp-active-bidding'] ) ? sanitize_text_field( wp_unslash( $_POST['jwppp-active-bidding'] ) ) : 0;
		update_option( 'jwppp-active-bidding', $active_bidding );
	}

	/*Channel id*/
	$channel_id = sanitize_text_field( get_option( 'jwppp-channel-id' ) );
	if ( isset( $_POST['jwppp-channel-id'] ) ) {
		$channel_id = sanitize_text_field( wp_unslash( $_POST['jwppp-channel-id'] ) );
		update_option( 'jwppp-channel-id', $channel_id );
	}

	/*Mediation*/
	$mediation = sanitize_text_field( get_option( 'jwppp-mediation' ) );
	if ( isset( $_POST['jwppp-mediation'] ) ) {
		$mediation = sanitize_text_field( wp_unslash( $_POST['jwppp-mediation'] ) );
		update_option( 'jwppp-mediation', $mediation );
	}

	/*Floor price*/
	$floor_price = get_option( 'jwppp-floor-price' ) ? sanitize_text_field( get_option( 'jwppp-floor-price' ) ) : '10';
	if ( isset( $_POST['jwppp-floor-price'] ) ) {
		$floor_price = sanitize_text_field( wp_unslash( $_POST['jwppp-floor-price'] ) );
		update_option( 'jwppp-floor-price', $floor_price );
	}

	/*Define the allowed tags for wp_kses*/
	$allowed_tags = array(
		'u' => [],
		'strong' => [],
		'a' => [
			'href'   => [],
			'target' => [],
		],
		'br' => [],
	);

	echo '<form id="jwppp-ads" name="jwppp-ads" method="post" action="">';
	echo '<table class="form-table">';

	/*Active ads?*/
	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Active Video Ads', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-ads" name="jwppp-active-ads" value="1"';
	echo ( '1' === $active_ads ) ? ' checked="checked"' : '';
	echo ' />';
	echo '<p class="description">' . wp_kses( __( 'Add a <strong>Basic Preroll Video Ads</strong>', 'jwppp' ), $allowed_tags ) . '</p>';
	echo '<p class="description">' . wp_kses( __( 'This option is only available with the <u>JW Player Enterprise license</u> -- details <a href="https://www.jwplayer.com/pricing/" target="_blank">here</a> ', 'jwppp' ), $allowed_tags ) . '</p>';
	echo '<td>';
	echo '</tr>';

	/*Ads embed block variable*/
	echo '<tr class="ads-options ads-var-block activation">';
	echo '<th scope="row">' . esc_html( __( 'Ads Variable', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-ads-var" name="jwppp-active-ads-var" value="1"';
	echo ( '1' === $active_ads_var ) ? ' checked="checked"' : '';
	echo ' />';
	echo '<p class="description">' . esc_html( __( 'Use an advertising embed block variable', 'jwppp' ) ) . '</p>';
	echo '<td>';
	echo '</tr>';

	/*Ads variable's name*/
	echo '<tr class="ads-options ads-var-block"' . ( ! $active_ads_var ? ' style="display: none;"' : '' ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Ads variable name', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" id="jwppp-ads-var-name" name="jwppp-ads-var-name" value="' . esc_attr( $ads_var_name ) . '" />';
	echo '<p class="description">' . esc_html( __( 'Add the name of the advertising variable.', 'jwppp' ) ) . '</p>';
	echo '<td>';
	echo '</tr>';

	/*Ads client*/
	echo '<tr class="ads-options"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Ads Client' ) ) . '</th>';
	echo '<td>';
	echo '<select id="jwppp-ads-client" name="jwppp-ads-client" />';
	echo '<option name="googima" value="googima"';
	echo ( 'googima' === $ads_client ) ? ' selected="selected"' : '';
	echo '>Google IMA</option>';
	echo '<option name="vast" value="vast"';
	echo ( 'vast' === $ads_client ) ? ' selected="selected"' : '';
	echo '>Vast</option>';
	echo '</select>';
	echo '<p class="description">' . wp_kses( __( 'Select your ADS Client. More info <a href="https://support.jwplayer.com/customer/portal/articles/1431638-ad-formats-reference" target="_blank">here</a>' ), $allowed_tags ) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Ads tag*/

	/*Nonce*/
	$add_tag_nonce = wp_create_nonce( 'jwppp-nonce-add-tag' );
	wp_localize_script( 'jwppp-admin', 'addTag', array( 'nonce' => $add_tag_nonce ) );

	echo '<tr class="ads-options tag"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Ad Tags', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<ul style="margin: 0;">';

	$total_tags = 1;
	if ( is_array( $ads_tags ) && ! empty( $ads_tags ) ) {
		for ( $i = 1; $i <= count( $ads_tags ); $i++ ) {
			jwppp_ads_tag( $i, $ads_tags[ $i - 1 ] );
		}
		$total_tags = count( $ads_tags );
	} elseif ( is_string( $ads_tags ) ) {
		jwppp_ads_tag( 1, $ads_tags );
	} else {
		jwppp_ads_tag( 1 );
	}

	echo '</ul>';
	echo '<input type="hidden" name="hidden-total-tags" class="hidden-total-tags" value="' . esc_attr( $total_tags ) . '" />';
	echo '<p class="description">' . esc_html( __( 'Ad tag URL | Ad Tag Name', 'jwppp' ) ) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Skipoffset*/
	echo '<tr class="ads-options"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Ad Skipping', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="number" min="0" step="1" class="small-text" id="jwppp-ads-skip" name="jwppp-ads-skip" value="' . esc_attr( $ads_skip ) . '" />';
	echo '<p class="description">' . esc_html( __( 'Total seconds viewers must watch an ad before being allowed to skip', 'jwppp' ) ) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Bidding*/
	echo '<tr class="ads-options"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Player Bidding', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<label>';
	echo '<input type="checkbox" id="jwppp-active-bidding" name="jwppp-active-bidding" value="1"';
	echo ( '1' === $active_bidding ) ? ' checked="checked"' : '';
	echo ' />';
	echo esc_html( __( 'Enable Video Player Bidding', 'jwppp' ) );
	echo '<p class="description">';
	echo wp_kses( __( 'All the benefits of Header Bidding are now built directly into your JW Player. With a simple one-click integration, you get access to quality demand at scale with reduced latency. SpotX is the leading video ad serving platform and gives publishers control, transparency and insights to maximize revenue.<br><a href="https://support.jwplayer.com/articles/how-to-setup-video-player-bidding" target="_blank">Contact SpotX to get started</a>', 'jwppp' ), $allowed_tags );
	echo '</p>';
	echo '</label>';
	echo '<td>';
	echo '</tr>';

	/*Spotx channel id*/
	echo '<tr class="ads-options bidding"' . esc_attr( $hide ) . '>';
	echo '<th scope="row"><img src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/spotx-70.png"></th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" id="jwppp-channel-id" name="jwppp-channel-id" placeholder="' . esc_attr( __( 'Add a Channel ID', 'jwppp' ) ) . '" value="' . esc_attr( $channel_id ) . '" />';
	echo '<p class="description">' . wp_kses( __( 'Channel ID', 'jwppp' ), $allowed_tags ) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Mediation*/
	echo '<tr class="ads-options bidding"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Mediation', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<select id="jwppp-mediation" name="jwppp-mediation" />';

	echo '<option name="jwp" class="jwp" value="jwp"';
	echo ( 'jwp' === $mediation ) ? ' selected="selected"' : '';
	echo '>' . esc_html( __( 'JW Player', 'jwppp' ) ) . '</option>';


	echo '<option name="jwpdfp" class="jwpdfp" value="jwpdfp"';
	echo ( 'jwpdfp' === $mediation ) ? ' selected="selected"' : '';
	echo '>' . esc_html( __( 'JW Player + DFP', 'jwppp' ) ) . '</option>';


	echo '<option name="dfp" class="dfp" value="dfp"';
	echo ( 'dfp' === $mediation ) ? ' selected="selected"' : '';
	echo '>' . esc_html( __( 'Google Ad Manager', 'jwppp' ) ) . '</option>';


	echo '<option name="jwpspotx" value="jwpspotx"';
	echo ( 'jwpspotx' === $mediation ) ? ' selected="selected"' : '';
	echo '>' . esc_html( __( 'SpotX as Primary Adserver', 'jwppp' ) ) . '</option>';
	echo '</select>';
	echo '<p class="description">' . esc_html( __( 'Select mediation option', 'jwppp' ) ) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Floor price*/
	echo '<tr class="ads-options bidding floor-price"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Floor Price', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<span class="currency">$</span>';
	echo '<input type="number" min="0" step="0.01" class="small-text" id="jwppp-floor-price" name="jwppp-floor-price" value="' . esc_attr( $floor_price ) . '" />';
	echo '<p class="description">' . esc_html( __( 'Set floor price', 'jwppp' ) ) . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '</table>';

	/*Add nonce to the form*/
	wp_nonce_field( 'jwppp-nonce-ads', 'hidden-nonce-ads' );

	echo '<input type="hidden" name="ads-sent" value="1" />';
	echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_attr( __( 'Save options', 'jwppp' ) ) . '" />';
	echo '</form>';
	?>
</div>
