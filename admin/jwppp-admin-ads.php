<!-- START ADS -->
<div name="jwppp-ads" id="jwppp-ads" class="jwppp-admin" style="display: none;">
	<?php

	//ACTIVE ADS?
	$active_ads = sanitize_text_field(get_option('jwppp-active-ads'));
	if(isset($_POST['ads-sent']) ) {
		$active_ads = isset($_POST['jwppp-active-ads']) ? $_POST['jwppp-active-ads'] : 0;
		update_option('jwppp-active-ads', $active_ads);
	}

	//ACTIVE ADS VAR BLOCK
	$active_ads_var = sanitize_text_field(get_option('jwppp-active-ads-var'));
	/*If this option is activated, all the plugin ads options are hidden*/
	$hide = $active_ads_var ? ' style="display: none;"' : '';
	if(isset($_POST['ads-sent']) ) {
		$hide = isset($_POST['jwppp-active-ads-var']) ? ' style="display: none;"' : '';
		$active_ads_var = isset($_POST['jwppp-active-ads-var']) ? $_POST['jwppp-active-ads-var'] : 0;
		update_option('jwppp-active-ads-var', $active_ads_var);
	}

	//ADS VAR BLOCK NAME
	$ads_var_name = sanitize_text_field(get_option('jwppp-ads-var-name'));
	if(isset($_POST['ads-sent']) ) {
		$ads_var_name = isset($_POST['jwppp-ads-var-name']) ? sanitize_text_field($_POST['jwppp-ads-var-name']) : '';
		update_option('jwppp-ads-var-name', $ads_var_name);
	}

	//ADS CLIENT
	$ads_client = sanitize_text_field(get_option('jwppp-ads-client'));
	if(isset($_POST['jwppp-ads-client'])) {
		$ads_client = sanitize_text_field($_POST['jwppp-ads-client']);
		update_option('jwppp-ads-client', $ads_client);
	}

	//ADS TAG
	$ads_tags = get_option('jwppp-ads-tag');
	if(isset($_POST['hidden-total-tags'])) {
		$ads_tags = array();
		for ($i=0; $i < sanitize_text_field($_POST['hidden-total-tags']); $i++) { 
			if(sanitize_text_field($_POST['jwppp-ads-tag-' . ($i + 1)]) !== '') {
				$ads_tags[] = array(
					'label' => sanitize_text_field($_POST['jwppp-ads-tag-label' . ($i + 1)]),
					'url'   => $_POST['jwppp-ads-tag-' . ($i + 1)]
				);
			}
		}
		update_option('jwppp-ads-tag', $ads_tags);
	}

	//SKIPOFFSET
	$ads_skip = sanitize_text_field(get_option('jwppp-ads-skip'));
	if(isset($_POST['jwppp-ads-skip'])) {
		$ads_skip = sanitize_text_field($_POST['jwppp-ads-skip']);
		update_option('jwppp-ads-skip', $ads_skip);
	}

	//BIDDING
	$active_bidding = sanitize_text_field(get_option('jwppp-active-bidding'));
	if( isset($_POST['ads-sent']) ) {
		$active_bidding = isset($_POST['jwppp-active-bidding']) ? $_POST['jwppp-active-bidding'] : 0;
		update_option('jwppp-active-bidding', $active_bidding);
	}

	//CHANNEL ID
	$channel_id = sanitize_text_field(get_option('jwppp-channel-id'));
	if(isset($_POST['jwppp-channel-id'])) {
		$channel_id = sanitize_text_field($_POST['jwppp-channel-id']);
		update_option('jwppp-channel-id', $channel_id);
	}

	//MEDIATION
	$mediation = sanitize_text_field(get_option('jwppp-mediation'));
	if(isset($_POST['jwppp-mediation'])) {
		$mediation = sanitize_text_field($_POST['jwppp-mediation']);
		update_option('jwppp-mediation', $mediation);
	}

	//FLOOR PRICE
	$floor_price = get_option('jwppp-floor-price') ? sanitize_text_field(get_option('jwppp-floor-price')) : '10';
	if(isset($_POST['jwppp-floor-price'])) {
		$floor_price = sanitize_text_field($_POST['jwppp-floor-price']);
		update_option('jwppp-floor-price', $floor_price);
	}

	echo '<form id="jwppp-ads" name="jwppp-ads" method="post" action="">';
	echo '<table class="form-table">';

	//ACTIVE ADS?
	echo '<tr>';
	echo '<th scope="row">' . __('Active Video Ads', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-ads" name="jwppp-active-ads" value="1"';
	echo ($active_ads == 1) ? ' checked="checked"' : '';
	echo ' />';
	echo '<p class="description">' . __('Add a <strong>Basic Preroll Video Ads</strong>', 'jwppp') . '</p>';
	echo '<p class="description">' . __('This option is available only with the <u>Enterprise JW Player license</u>, details <a href="https://www.jwplayer.com/pricing/" target="_blank">here</a>. ', 'jwppp') . '</p>';
	echo '<td>';
	echo '</tr>';

	//ADS EMBED BLOCK VARIABLE
	echo '<tr class="ads-options ads-var-block activation">';
	echo '<th scope="row">' . __('Ads variable', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-ads-var" name="jwppp-active-ads-var" value="1"';
	echo ($active_ads_var == 1) ? ' checked="checked"' : '';
	echo ' />';
	echo '<p class="description">' . __('Use an advertising embed block variable.', 'jwppp') . '</p>';
	echo '<td>';
	echo '</tr>';

	//ADS VARIABLE'S NAME
	echo '<tr class="ads-options ads-var-block"' . (!$active_ads_var ? ' style="display: none;"' : '') . '>';
	echo '<th scope="row">' . __('Ads variable name', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" id="jwppp-ads-var-name" name="jwppp-ads-var-name" value="' . $ads_var_name . '" />';
	echo '<p class="description">' . __('Add the name of the advertising variable.', 'jwppp') . '</p>';
	echo '<td>';
	echo '</tr>';

	//ADS CLIENT
	echo '<tr class="ads-options"' . $hide . '>';
	echo '<th scope="row">' . __('Ads Client') . '</th>';
	echo '<td>';
	echo '<select id="jwppp-ads-client" name="jwppp-ads-client" />';
	echo '<option name="googima" value="googima"';
	echo ($ads_client == 'googima') ? ' selected="selected"' : '';
	echo '>Googima</option>';
	echo '<option name="vast" value="vast"';
	echo ($ads_client == 'vast') ? ' selected="selected"' : '';
	echo '>Vast</option>';
	echo '</select>';
	echo '<p class="description">' . __('Select your ADS Client. More info <a href="https://support.jwplayer.com/customer/portal/articles/1431638-ad-formats-reference" target="_blank">here</a>') . '</p>';
	echo '</td>';
	echo '</tr>';

	//ADS TAG
	echo '<tr class="ads-options tag"' . $hide . '>';
	echo '<th scope="row">' . __('Ads Tag', 'jwppp') . '</th>';
	echo '<td>';
	echo '<ul style="margin: 0;">';
	
	// echo '<input type="text" class="regular-text" id="jwppp-ads-tag" name="jwppp-ads-tag" placeholder="' . __('Add the url of your XML file.', 'jwppp') . '" value="' . $ads_tags . '" />';

		// var_dump($ads_tags);

	$total_tags = 1;
	if(is_array($ads_tags) && !empty($ads_tags)) {
		for ($i=1; $i <= count($ads_tags); $i++) { 
			echo jwppp_ads_tag($i, $ads_tags[$i - 1]);
		}
		$total_tags = count($ads_tags);
	} elseif(is_string($ads_tags)) {
		echo jwppp_ads_tag(1, $ads_tags);
	} else {
		echo jwppp_ads_tag(1);		
	}

	echo '</ul>';
	echo '<input type="hidden" name="hidden-total-tags" class="hidden-total-tags" value="' . esc_html($total_tags) . '" />';
	echo '<p class="description">' . __('Please, set this to the URL of the ad tag that contains the pre-roll ad.', 'jwppp') . '</p>';
	echo '</td>';
	echo '</tr>';

	//SKIPOFFSET
	echo '<tr class="ads-options"' . $hide . '>';
	echo '<th scope="row">' . __('Ad Skipping', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="number" min="0" step="1" class="small-text" id="jwppp-ads-skip" name="jwppp-ads-skip" value="' . $ads_skip . '" />';
	echo '<p class="description">' . __('Please, set an amount of time (seconds) that you want your viewers to watch an ad before being allowed to skip it.', 'jwppp') . '</p>';
	echo '</td>';
	echo '</tr>';

	//BIDDING
	echo '<tr class="ads-options"' . $hide . '>';
	echo '<th scope="row">' . __('Player Bidding', 'jwppp') . '</th>';
	echo '<td>';
	echo '<label>';
	echo '<input type="checkbox" id="jwppp-active-bidding" name="jwppp-active-bidding" value="1"';
	echo ($active_bidding == 1) ? ' checked="checked"' : '';
	echo ' />';
	echo __('Enable Video Player Bidding', 'jwppp');
	echo '<p class="description">' . __('All the benefits of Header Bidding are now built directly into your JW Player. With a simple one-click integration, you get access to quality demand at scale with reduced latency. <a href="https://support.jwplayer.com/articles/how-to-setup-video-player-bidding" target="_blank">Read more</a>', 'jwppp') . '</p>';
	echo '</label>';
	echo '<td>';
	echo '</tr>';	

	//SPOTX CHANNEL ID
	echo '<tr class="ads-options bidding"' . $hide . '>';
	echo '<th scope="row"><img src="' . plugin_dir_url(__DIR__) . 'images/spotx-70.png"></th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" id="jwppp-channel-id" name="jwppp-channel-id" placeholder="' . __('Add a Channel ID', 'jwppp') . '" value="' . $channel_id . '" />';
	echo '<p class="description">' . __('Don\'t you have one? <a href="https://www.spotx.tv/video-player-bidding/" target="blank">Contact SpotX to get started</a>.', 'jwppp') . '</p>';
	echo '</td>';
	echo '</tr>';

	//MEDIATION
	echo '<tr class="ads-options bidding"' . $hide . '>';
	echo '<th scope="row">' . __('Mediation') . '</th>';
	echo '<td>';
	echo '<select id="jwppp-mediation" name="jwppp-mediation" />';
	
	echo '<option name="jwp" class="jwp" value="jwp"';
	echo ($mediation == 'jwp') ? ' selected="selected"' : '';
	echo '>JW Player</option>';


	echo '<option name="jwpdfp" class="jwpdfp" value="jwpdfp"';
	echo ($mediation == 'jwpdfp') ? ' selected="selected"' : '';
	echo '>JW Player + DFP</option>';


	echo '<option name="dfp" class="dfp" value="dfp"';
	echo ($mediation == 'dfp') ? ' selected="selected"' : '';
	echo '>Google Ad Manager</option>';


	echo '<option name="jwpspotx" value="jwpspotx"';
	echo ($mediation == 'jwpspotx') ? ' selected="selected"' : '';
	echo '>SpotX as Primary Adserver</option>';
	echo '</select>';
	echo '<p class="description">' . __('Select one option') . '</p>';
	echo '</td>';
	echo '</tr>';

	//FLOOR PRICE
	echo '<tr class="ads-options bidding floor-price"' . $hide . '>';
	echo '<th scope="row">' . __('Floor Price', 'jwppp') . '</th>';
	echo '<td>';
	echo '<span class="currency">$</span>';
	echo '<input type="number" min="0" step="0.01" class="small-text" id="jwppp-floor-price" name="jwppp-floor-price" value="' . $floor_price . '" />';
	echo '<p class="description">' . __('Please, specify a floor price.', 'jwppp') . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '</table>';

	echo '<input type="hidden" name="ads-sent" value="1" />';
	echo '<input class="button button-primary" type="submit" id="submit" value="' . __('Save options', 'jwppp') . '" />';
	echo '</form>';
	?>
</div>
<!-- END ADS -->
