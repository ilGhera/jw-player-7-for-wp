<?php
/**
 * Share options
 * @author ilGhera
 * @package jw-player-for-vip/admin
 * @version 1.6.0
 */
?>
<div name="jwppp-social" id="jwppp-social" class="jwppp-admin" style="display: none;">
	<?php 

	/*Active share?*/
	$active_share = sanitize_text_field(get_option('jwppp-active-share'));
	if(isset($_POST['share-sent'])) {
		$active_share = isset($_POST['jwppp-active-share']) ? sanitize_text_field($_POST['jwppp-active-share']) : 0;
		update_option('jwppp-active-share', $active_share);
	}
	/*Heading*/
	$share_heading = get_option('jwppp-share-heading');
	if(isset($_POST['share-heading'])) {
		$share_heading = sanitize_text_field($_POST['share-heading']);
		update_option('jwppp-share-heading', $share_heading);
	} 
	/*Embed?*/
	$jwppp_embed_video = sanitize_text_field(get_option('jwppp-embed-video'));
	if(isset($_POST['share-sent'])) {
		$jwppp_embed_video = isset($_POST['jwppp-embed-video']) ? sanitize_text_field($_POST['jwppp-embed-video']) : 0;
		update_option('jwppp-embed-video', $jwppp_embed_video);
	}

	/*Define the allowed tags for wp_kses*/
	$allowed_tags = array(
		'u' => [],
		'strong' => [],
		'a' => [
			'href'   => [],
			'target' => []
		]
	);

	echo '<form id="jwppp-sharing" name="jwppp-sharing" method="post" action="">';
	echo '<table class="form-table">';

	/*Active share?*/
	echo '<tr>';
	echo '<th scope="row">' . esc_html(__('Activate Sharing Option', 'jwppp')) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-share" name="jwppp-active-share" value="1"';
	echo ($active_share === '1') ? ' checked="checked"' : '' ;
	echo ' />';
	echo '<p class="description">' . esc_html(__('Activate sharing option -- can be changed on single video.', 'jwppp')) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Heading*/
	echo '<tr class="share-options">';
	echo '<th scope="row">' . esc_html(__('Heading', 'jwppp')) . '</th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" id="share-heading" name="share-heading" placeholder="' . esc_html(__('Share Video', 'jwppp')) . '" value="' . esc_html($share_heading) . '" />';
	echo '<p class="description">' . wp_kses(__('Add a custom heading, default is <strong>Share Video</strong>', 'jwppp'), $allowed_tags) . '</p>';
	echo '</td>';	
	echo '</tr>';

	/*Embed?*/
	echo '<tr class="share-options">';
	echo '<th scope="row">' . esc_html(__('Active embed option', 'jwppp')) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-embed-video" name="jwppp-embed-video" value="1"';
	echo ($jwppp_embed_video === '1') ? ' checked="checked"' : '';
	echo ' />';
	echo '<p class="description">' . wp_kses(__('Active <strong>embed video</strong> as default option. You\'ll be able to change it on single video.', 'jwppp'), $allowed_tags) . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '</table>';

	echo '<input type="hidden" name="share-sent" value="1" />';
	echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_html(__('Save options', 'jwppp')) . '" />';
	echo '</form>';
	?>
</div>