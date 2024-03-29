<?php
/**
 * Ads options
 * @author ilGhera
 * @package jw-player-7-for-wp/admin
 * @since 2.2.0
 */
?>
<div name="jwppp-ads" id="jwppp-ads" class="jwppp-admin" style="display: none;">
	
	<?php


	$hide = null;

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

	echo '<form id="jwppp-ads" class="jwppp-settings-form" name="jwppp-ads" method="post" action="">';
	echo '<table class="form-table">';

	/*Active ads?*/
	echo '<tr class="jwppp-active-ads">';
	echo '<th scope="row">' . esc_html( __( 'Active Video Ads', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-ads" name="jwppp-active-ads" value="1" />';
	echo '<p class="description">' . wp_kses( __( 'Add a <strong>Basic Preroll Video Ads</strong>', 'jwppp' ), $allowed_tags ) . '</p>';
	echo '<p class="description">' . wp_kses( __( 'This option is only available with the <u>JW Player Enterprise license</u> -- details <a href="https://www.jwplayer.com/pricing/" target="_blank">here</a> ', 'jwppp' ), $allowed_tags ) . '</p>';
	echo '<td>';
	echo '</tr>';

	/*Ads embed block variable*/
	echo '<tr class="ads-options ads-var-block activation">';
	echo '<th scope="row">' . esc_html( __( 'Ads Variable', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-ads-var" name="jwppp-active-ads-var" value="1" />';
	echo '<p class="description">' . esc_html( __( 'Use an advertising embed block variable', 'jwppp' ) ) . '</p>';
	echo '<td>';
	echo '</tr>';

	/*Ads variable's name*/
	echo '<tr class="ads-options ads-var-block">';
	echo '<th scope="row">' . esc_html( __( 'Ads variable name', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" id="jwppp-ads-var-name" name="jwppp-ads-var-name" disabled="disabled"/>';
	echo '<p class="description">' . esc_html( __( 'Add the name of the advertising variable.', 'jwppp' ) ) . '</p>';
	go_premium();
	echo '<td>';
	echo '</tr>';

	/*Ads client*/
	echo '<tr class="ads-options"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Ads Client' ) ) . '</th>';
	echo '<td>';
	echo '<select id="jwppp-ads-client" name="jwppp-ads-client" />';
	echo '<option name="googima" value="googima">Google IMA</option>';
	echo '<option name="vast" value="vast">Vast</option>';
	echo '</select>';
	echo '<p class="description">' . wp_kses( __( 'Select your ADS Client. More info <a href="https://support.jwplayer.com/customer/portal/articles/1431638-ad-formats-reference" target="_blank">here</a>' ), $allowed_tags ) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Ads tag*/
	echo '<tr class="ads-options tag"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Ad Tags', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<ul style="margin: 0;">';

	/*Nonce*/
	$add_tag_nonce = wp_create_nonce( 'jwppp-nonce-add-tag' );
	wp_localize_script( 'jwppp-admin', 'addTag', array( 'nonce' => $add_tag_nonce ) );
	
	jwppp_ads_tag( 1 );

	echo '</ul>';
	echo '<input type="hidden" name="hidden-total-tags" class="hidden-total-tags" value="" />';
	echo '<p class="description">' . esc_html( __( 'Ad tag URL | Ad Tag Name', 'jwppp' ) ) . '</p>';
	go_premium();
	echo '</td>';
	echo '</tr>';

	/*Skipoffset*/
	echo '<tr class="ads-options"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Ad Skipping', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="number" min="0" step="1" class="small-text" id="jwppp-ads-skip" name="jwppp-ads-skip" value="5" disabled="disabled" />';
	echo '<p class="description">' . esc_html( __( 'Total seconds viewers must watch an ad before being allowed to skip', 'jwppp' ) ) . '</p>';
	go_premium();
	echo '</td>';
	echo '</tr>';

	/*Bidding*/
	echo '<tr class="ads-options jwppp-active-bidding"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Player Bidding', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-bidding" name="jwppp-active-bidding" value="1" />';
	echo '<span class="jwppp-check-label">' . esc_html( __( 'Enable Video Player Bidding', 'jwppp' ) ) . '</span>';
	echo '<p class="description">';
	echo wp_kses( __( 'All the benefits of Header Bidding are now built directly into your JW Player. With a simple one-click integration, you get access to quality demand at scale with reduced latency. SpotX is the leading video ad serving platform and gives publishers control, transparency and insights to maximize revenue.<br><a href="https://support.jwplayer.com/articles/how-to-setup-video-player-bidding" target="_blank">Contact SpotX to get started</a>', 'jwppp' ), $allowed_tags );
	echo '</p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr class="ads-options bidding ad-partners"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Ad partners', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<ul style="margin: 0;">';
    
	/*Nonce*/
	$add_partner_nonce = wp_create_nonce( 'jwppp-nonce-add-partner' );
	wp_localize_script( 'jwppp-admin', 'addPartner', array( 'nonce' => $add_partner_nonce ) );

    jwppp_ad_partner( 1, $hide );

	echo '</ul>';
	echo '<input type="hidden" name="hidden-total-partners" class="hidden-total-partners" value="1" />';
	echo '<p class="description">' . esc_html( __( 'Select your ad partners', 'jwppp' ) ) . '</p>';
    go_premium();
	echo '</td>';
	echo '</tr>';

	/*Mediation*/
	echo '<tr class="ads-options bidding"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Mediation', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<select id="jwppp-mediation" name="jwppp-mediation" />';
	echo '<option name="jwp" class="jwp" value="jwp">' . esc_html( __( 'JW Player', 'jwppp' ) ) . '</option>';
	echo '<option name="jwpdfp" class="jwpdfp" value="jwpdfp">' . esc_html( __( 'JW Player + DFP', 'jwppp' ) ) . '</option>';
	echo '<option name="dfp" class="dfp" value="dfp">' . esc_html( __( 'Google Ad Manager', 'jwppp' ) ) . '</option>';
	echo '<option name="jwpspotx" value="jwpspotx">' . esc_html( __( 'SpotX as Primary Adserver', 'jwppp' ) ) . '</option>';
	echo '</select>';
	echo '<p class="description">' . esc_html( __( 'Select mediation option', 'jwppp' ) ) . '</p>';
    go_premium();
	echo '</td>';
	echo '</tr>';

	/*Floor price*/
	echo '<tr class="ads-options bidding floor-price"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Floor Price', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<span class="currency">$</span>';
	echo '<input type="number" min="0" step="0.01" class="small-text" id="jwppp-floor-price" name="jwppp-floor-price" value="0.10" />';
	echo '<p class="description">' . esc_html( __( 'Set floor price', 'jwppp' ) ) . '</p>';
    go_premium();
	echo '</td>';
	echo '</tr>';

	/*Price range*/
	echo '<tr class="ads-options bidding range-price"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'Price Range', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<span class="currency">$</span>';
	echo '<input type="number" min="0" step="0.01" class="small-text" id="jwppp-range-price-increment" name="jwppp-range-price-increment" />';
	echo '<p class="description">' . esc_html( __( 'Nearest increment to which a bid is rounded down, in bidding currency	', 'jwppp' ) ) . '</p>';
	echo '<span class="currency">$</span>';
	echo '<input type="number" min="0" step="0.01" class="small-text" id="jwppp-range-price-max" name="jwppp-range-price-max" />';
	echo '<p class="description">' . esc_html( __( 'Maximum value of a price bucket, in bidding currency', 'jwppp' ) ) . '</p>';
	echo '<span class="currency">$</span>';
	echo '<input type="number" min="0" step="0.01" class="small-text" id="jwppp-range-price-min" name="jwppp-range-price-min" />';
	echo '<p class="description">' . esc_html( __( 'Minimum value of a price bucket, in bidding currency', 'jwppp' ) ) . '</p>';
    go_premium();
	echo '</td>';
	echo '</tr>';

    /*GDPR*/
	echo '<tr class="ads-options bidding gdpr"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'GDPR', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-gdpr" name="jwppp-active-gdpr" value="1"';
	echo ' />';
	echo '<span class="jwppp-check-label">' . esc_html( __( 'Enable GDPR', 'jwppp' ) ) . '</span>';
	echo '<select id="jwppp-gdpr-cmp-api" class="gdpr" name="jwppp-gdpr-cmp-api" />';
	echo '<option name="iab" class="iab" value="iab"';
	echo '>' . esc_html( __( 'IAB', 'jwppp' ) ) . '</option>';
	echo '<option name="static" class="static" value="static"';
	echo '>' . esc_html( __( 'Static', 'jwppp' ) ) . '</option>';
    echo '</select>';
	echo '<p class="description gdpr">' . esc_html( __( 'The CMP interface that is in use.', 'jwppp' ) ) . '</p>';
	echo '<input type="number" min="1000" step="100" class="small-text gdpr" id="jwppp-gdpr-timeout" name="jwppp-gdpr-timeout" />';
	echo '<p class="description gdpr">' . esc_html( __( 'Length of time (in milliseconds) to allow the CMP to obtain the GDPR consent string', 'jwppp' ) ) . '</p>';
	echo '<select id="jwppp-default-gdpr-scope" class="gdpr" name="jwppp-default-gdpr-scope" />';
	echo '<option name="false" class="false" value="0"';
	echo '>' . esc_html( __( 'False', 'jwppp' ) ) . '</option>';
	echo '<option name="true" class="true" value="1"';
	echo '>' . esc_html( __( 'True', 'jwppp' ) ) . '</option>';
    echo '</select>';
	echo '<p class="description gdpr">' . esc_html( __( 'Defines what the gdprApplies flag should be when the CMP doesn’t respond in time or the static data doesn’t supply.', 'jwppp' ) ) . '</p>';
    go_premium();
	echo '</td>';
	echo '</tr>';

    /*CCPA*/
	echo '<tr class="ads-options bidding ccpa"' . esc_attr( $hide ) . '>';
	echo '<th scope="row">' . esc_html( __( 'CCPA', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-ccpa" name="jwppp-active-ccpa" value="1"';
	echo ' />';
	echo '<span class="jwppp-check-label">' . esc_html( __( 'Enable CCPA', 'jwppp' ) ) . '</span>';
	echo '<select id="jwppp-ccpa-cmp-api" class="ccpa" name="jwppp-ccpa-cmp-api" />';
	echo '<option name="iab" class="iab" value="iab"';
	echo '>' . esc_html( __( 'IAB', 'jwppp' ) ) . '</option>';
	echo '<option name="static" class="static" value="static"';
	echo '>' . esc_html( __( 'Static', 'jwppp' ) ) . '</option>';
    echo '</select>';
	echo '<p class="description ccpa">' . esc_html( __( 'The CMP interface that is in use.', 'jwppp' ) ) . '</p>';
	echo '<input type="number" min="1000" step="100" class="small-text ccpa" id="jwppp-ccpa-timeout" name="jwppp-ccpa-timeout" />';
	echo '<p class="description ccpa">' . esc_html( __( 'Length of time (in milliseconds) to allow the CMP to obtain the GDPR consent string', 'jwppp' ) ) . '</p>';
    go_premium();
	echo '</td>';
	echo '</tr>';

	echo '</table>';

	echo '<input type="hidden" name="ads-sent" value="1" />';
	echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_attr( __( 'Save options', 'jwppp' ) ) . '" disabled="disabled" />';
	echo '</form>';
	?>
</div>
