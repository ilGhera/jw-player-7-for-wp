<?php
/*
*
* SINGLE VIDEO BOX FOR JW PLAYER FOR WORDPRESS
*
*/


echo '<table class="widefat jwppp-1" style="margin: 0.4rem 0;">';
echo '<tbody class="ui-sortable">';
echo '<tr class="row">';
echo '<td class="order">1</td>';
echo '<td class="jwppp-input-wrap" style="width: 100%;">';
wp_nonce_field( 'jwppp_save_meta_box_data', 'jwppp_meta_box_nonce' );

$video_url = get_post_meta( $post->ID, '_jwppp-video-url-1', true );
$source_url  = get_post_meta($post->ID, '_jwppp-1-source-1-url', true);
$video_title = get_post_meta($post->ID, '_jwppp-video-title-1', true);
$video_description = get_post_meta($post->ID, '_jwppp-video-description-1', true);
$jwppp_embed_video = sanitize_text_field(get_option('jwppp-embed-video'));
$jwppp_activate_media_type = get_post_meta($post->ID, '_jwppp-activate-media-type-1', true);
$jwppp_media_type = get_post_meta($post->ID, '_jwppp-media-type-1', true);


echo '<label for="_jwppp-video-url-1">';
echo '<strong>' . esc_html(__( 'Media URL', 'jwppp' )) . '</strong>';
echo '<a class="question-mark" href="https://www.ilghera.com/support/topic/media-formats-supported/" title="More informations" target="_blank"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
echo '</label> ';
echo '<p><input type="text" id="_jwppp-video-url-1" name="_jwppp-video-url-1" placeholder="' . esc_html(__('Add here your media url', 'jwppp')) . '" value="' . esc_attr( $video_url ) . '" size="60" /></p>';

echo '<a class="button more-options-1">' . esc_html(__('More options', 'jwppp')) . '</a>';
if(get_option('jwppp-position') === 'custom') {
	echo '<code style="display:inline-block;margin:0.1rem 0.5rem 0;color:#888;">[jwp-video n="1"]</code>';
}

?>

<script>
jQuery(document).ready(function($) {
	$('.jwppp-more-options-1').hide();
	$('.more-options-1').click(function() {
		$('.jwppp-more-options-1').toggle('fast');
		// $('.more-options').text('Less options');
		$(this).text(function(i, text) {
			return text == 'More options' ? 'Less options' : 'More options';
		});
	});
	if($('#_jwppp-activate-media-type-1').prop('checked') == false) {
		$('#_jwppp-media-type-1').hide();
	} else {
		$('#_jwppp-media-type-1').show();
	}
	$('#_jwppp-activate-media-type-1').on('change', function(){
		if($(this).prop('checked') == true) {
			$('#_jwppp-media-type-1').show();
		} else {
			$('#_jwppp-media-type-1').hide();
		}
	})
});
</script>

<?php
echo '<div class="jwppp-more-options-1" style="margin-top:2rem;">';

echo '<label for="_jwppp-add-sources-1">';
echo '<strong>' . esc_html(__( 'More sources', 'jwppp' )) . '</strong>';
echo '<a class="question-mark" title="' . esc_html(__('Used for quality toggling and alternate sources.', 'jwppp')) . '"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
echo '</label> ';

echo '<input type="number" class="small-text" style="margin-left:1.8rem; display:inline; position: relative; top:2px;" id="_jwppp-sources-number-1" name="_jwppp-sources-number-1" value="1" disabled="disabled">';
echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank" style="margin-left:0.5rem;">Upgrade</a>';
echo '</p>';
echo '<ul class="sources-1">';
echo '<li id="video-1-source" data-number="1">';	
echo '<input type="text" style="margin-right:1rem;" name="_jwppp-1-source-1-url" id="_jwppp-1-source-1-url" value="' . esc_attr($source_url) . '" placeholder="' . esc_html(__('Source url', 'jwppp')) . '" size="60" />';
echo '</li>';
echo '</ul>';

echo '<label for="_jwppp-video-image-1">';
echo '<strong>' . esc_html(__( 'Video poster image', 'jwppp' )) . ' | </strong><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</label> ';
echo '<p><input type="text" id="_jwppp-video-image-1" name="_jwppp-video-image-1" placeholder="' . esc_html(__('Add a different poster image for this video', 'jwppp')) . '" size="60" disabled="disabled" /></p>';

echo '<label for="_jwppp-video-title-1">';
echo '<strong>' . esc_html(__( 'Video title', 'jwppp' )) . '</strong>';
echo '</label> ';
echo '<p><input type="text" id="_jwppp-video-title-1" name="_jwppp-video-title-1" placeholder="' . esc_html(__('Add a title to your video', 'jwppp')) . '" value="' . esc_attr( $video_title ) . '" size="60" /></p>';

echo '<label for="_jwppp-video-description-1">';
echo '<strong>' . esc_html(__( 'Video description', 'jwppp' )) . '</strong>';
echo '</label> ';
echo '<p><input type="text" id="_jwppp-video-description-1" name="_jwppp-video-description-1" placeholder="' . esc_html(__('Add a description to your video', 'jwppp')) . '" value="' . esc_attr( $video_description ) . '" size="60" /></p>';

echo '<p>';
echo '<label for="_jwppp-activate-media-type-1">';
echo '<input type="checkbox" id="_jwppp-activate-media-type-1" name="_jwppp-activate-media-type-1" value="1"';
echo ($jwppp_activate_media_type === '1') ? ' checked="checked"' : '';
echo ' />';
echo '<strong>' . esc_html(__('Force a media type', 'jwppp')) . '</strong>';
echo '<a class="question-mark" title="' . esc_html(__('Only required when a file extension is missing or not recognized', 'jwppp')) . '"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
echo '</label>';
echo '<input type="hidden" name="activate-media-type-hidden-1" value="1" />';

echo '<select style="position: relative; left:2rem; display:inline;" id="_jwppp-media-type-1" name="_jwppp-media-type-1">';
echo '<option name="mp4" value="mp4"';
echo ($jwppp_media_type === 'mp4') ? ' selected="selected"' : '';
echo '>mp4</option>';
echo '<option name="flv" value="flv"';
echo ($jwppp_media_type === 'flv') ? ' selected="selected"' : '';
echo '>flv</option>';
echo '<option name="mp3" value="mp3"';
echo ($jwppp_media_type === 'mp3') ? ' selected="selected"' : '';
echo '>mp3</option>';
echo '</select>';
echo '</p>';

echo '<p>';
echo '<label for="_jwppp-autoplay-1">';
echo '<input type="checkbox" id="_jwppp-autoplay-1" name="_jwppp-autoplay-1" value="1" disabled="disabled"';
echo ' />';
echo '<strong>' . esc_html(__('Autostarting on page load.', 'jwppp')) . '</strong> | <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</label>';
echo '<input type="hidden" name="autoplay-hidden-1" value="1" />';
echo '</p>';

echo '<p>';
echo '<label for="_jwppp-mute-1">';
echo '<input type="checkbox" id="_jwppp-mute-1" name="_jwppp-mute-1" value="1" disabled="disabled"';
echo ' />';
echo '<strong>' . esc_html(__('Mute the video during playback.', 'jwppp')) . '</strong> | <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</label>';
echo '<input type="hidden" name="mute-hidden-1" value="1" />';
echo '</p>';

echo '<p>';
echo '<label for="_jwppp-repeat-1">';
echo '<input type="checkbox" id="_jwppp-repeat-1" name="_jwppp-repeat-1" value="1" disabled="disabled"';
echo ' />';
echo '<strong>' . esc_html(__('Repeat the video during playback.', 'jwppp')) . '</strong> | <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</label>';
echo '<input type="hidden" name="repeat-hidden-1" value="1" />';
echo '</p>';

echo '<p>';
echo '<label for="_jwppp-single-embed-1">';
echo '<input type="checkbox" id="_jwppp-single-embed-1" name="_jwppp-single-embed-1" value="1" disabled="disabled"';
echo ($jwppp_embed_video === '1') ? ' checked="checked"' : '';
echo ' />';
echo '<strong>' . esc_html(__('Allow to embed this video', 'jwppp')) . '</strong> | <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</label>';
echo '<input type="hidden" name="single-embed-hidden-1" value="1" />';
echo '</p>';

echo '<p>';
echo '<label for="_jwppp-download-video-1">';
echo '<input type="checkbox" id="_jwppp-download-video-1" name="_jwppp-download-video-1" value="1" disabled="disabled"';
echo ' />';
echo '<strong>' . esc_html(__('Allow to download this video', 'jwppp')) . '</strong> | <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</label>';
echo '<input type="hidden" name="download-video-hidden-1" value="1" />';
echo '</p>';

echo '<p>';
echo '<label for="_jwppp-add-chapters-1">';
echo '<input type="checkbox" id="_jwppp-add-chapters-1" name="_jwppp-add-chapters-1" value="1" disabled="disabled"';
echo ' />';
echo '<strong>' . esc_html(__('Add Chapters, Subtitles or Preview Thumbnails', 'jwppp')) . '</strong> | <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</label>';
echo '<input type="hidden"function name="add-chapters-hidden-1" value="1" />';

echo '</div>';
echo '</td>';
$number = 1;
if($number<2) {
	echo '<td class="add-video"><a class="jwppp-add"><img src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/add-video.png" /></a></td>';
} else {
	echo '<td class="remove-video"><a class="jwppp-remove" data-numb="1"><img src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/remove-video.png" /></a></td>';
}
echo '</tr>';
echo '</tbody>';
echo '</table>';