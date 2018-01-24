<!-- START -  SHARING -->
<div name="jwppp-social" id="jwppp-social" class="jwppp-admin" style="display: none;">
	<?php 
	//ACTIVE SHARE?
	$active_share = sanitize_text_field(get_option('jwppp-active-share'));
	if(isset($_POST['share-sent'])) {
		$active_share = isset($_POST['jwppp-active-share']) ? $_POST['jwppp-active-share'] : 0;
		update_option('jwppp-active-share', $active_share);
	}
	//HEADING
	$share_heading = get_option('jwppp-share-heading');
	if(isset($_POST['share-heading'])) {
		$share_heading = sanitize_text_field($_POST['share-heading']);
		update_option('jwppp-share-heading', $share_heading);
	} 
	//EMBED?
	$jwppp_embed_video = sanitize_text_field(get_option('jwppp-embed-video'));
	if(isset($_POST['share-sent'])) {
		$jwppp_embed_video = isset($_POST['jwppp-embed-video']) ? $_POST['jwppp-embed-video'] : 0;
		update_option('jwppp-embed-video', $jwppp_embed_video);
	}

	echo '<form id="jwppp-sharing" name="jwppp-sharing" method="post" action="">';
	echo '<table class="form-table">';

	//ACTIVE SHARE?
	echo '<tr>';
	echo '<th scope="row">' . __('Active Share option', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-share" name="jwppp-active-share" value="1"';
	echo ($active_share == 1) ? ' checked="checked"' : '' ;
	echo ' />';
	echo '<p class="description">' . __('Active <strong>share video</strong> as default option. You\'ll be able to change it on single video.', 'jwppp') . '</p>';
	echo '</td>';
	echo '</tr>';

	//HEADING
	echo '<tr class="share-options">';
	echo '<th scope="row">' . __('Heading', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" id="share-heading" name="share-heading" placeholder="' . __('Share Video', 'jwppp') . '" value="' . $share_heading . '" />';
	echo '<p class="description">' . __('Add a custom heading, default is <strong>Share Video</strong>', 'jwppp') . '</p>';
	echo '</td>';	
	echo '</tr>';

	//EMBED?
	echo '<tr class="share-options">';
	echo '<th scope="row">' . __('Active embed option', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-embed-video" name="jwppp-embed-video" value="1"';
	echo ($jwppp_embed_video == 1) ? ' checked="checked"' : '';
	echo ' />';
	echo '<p class="description">' . __('Active <strong>embed video</strong> as default option. You\'ll be able to change it on single video.', 'jwppp') . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '</table>';

	echo '<input type="hidden" name="share-sent" value="1" />';
	echo '<input class="button button-primary" type="submit" id="submit" value="' . __('Save options', 'jwppp') . '" />';
	echo '</form>';
	?>
</div>
<!-- END - SOCIAL SHARING -->
