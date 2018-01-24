<!-- START ADS -->
<div name="jwppp-ads" id="jwppp-ads" class="jwppp-admin" style="display: none;">
	<?php
	//ACTIVE ADS?
	$active_ads = sanitize_text_field(get_option('jwppp-active-ads'));
	if( isset($_POST['ads-sent']) ) {
		$active_ads = isset($_POST['jwppp-active-ads']) ? $_POST['jwppp-active-ads'] : 0;
		update_option('jwppp-active-ads', $active_ads);
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
	echo '<p class="description">' . __('A valid license for the Advertising edition of JW Player is required. The Free, Premium, and Enterprise editions do not support this function.', 'jwppp') . '</p>';
	echo '<td>';
	echo '</tr>';

	//ADS CLIENT
	echo '<tr class="ads-options">';
	echo '<th scope="row">' . __('Ads Client') . '</th>';
	echo '<td>';
	echo '<select id="jwppp-ads-client" name="jwppp-ads-client" disabled="disabled"/>';
	echo '<option name="vast" value="vast"';
	echo ' selected="selected"';
	echo '>Vast</option>';
	echo '</select>';
	echo '<p class="description">' . __('Select your ADS Client. More info <a href="https://support.jwplayer.com/customer/portal/articles/1431638-ad-formats-reference" target="_blank">here</a>') . '<br>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</tr>';

	//ADS TAG
	echo '<tr class="ads-options">';
	echo '<th scope="row">' . __('Ads Tag', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" id="jwppp-ads-tag" name="jwppp-ads-tag" placeholder="' . __('Add the url of your XML file.', 'jwppp') . '" disabled="disabled" />';
	echo '<p class="description">' . __('Please, set this to the URL of the ad tag that contains the pre-roll ad.', 'jwppp') . '<br>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</td>';
	echo '</tr>';

	//SKIPOFFSET
	echo '<tr class="ads-options">';
	echo '<th scope="row">' . __('Ad Skipping', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="number" min="0" step="1" class="small-text" id="jwppp-ads-skip" name="jwppp-ads-skip" value="5" disabled="disabled" />';
	echo '<p class="description">' . __('Please, set an amount of time (seconds) that you want your viewers to watch an ad before being allowed to skip it', 'jwppp') . '<br>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</td>';
	echo '</tr>';

	echo '</table>';
	echo '<input class="button button-primary" type="submit" id="submit" disabled="disabled" value="' . __('Save options', 'jwppp') . '" />';
	
	echo '</form>';
	?>
</div>
<!-- END ADS -->