<?php
function jwppp_ajax_add_video_callback($post) {
	$number = 2;
	echo '<table class="widefat jwppp-' . esc_attr($number) . '" style="margin: 0.4rem 0; opacity: 0.8;">';
	echo '<tbody class="ui-sortable">';
	echo '<tr class="row">';
	echo '<td class="order">' . esc_attr($number) . '</td>';
	echo '<td class="jwppp-input-wrap" style="width: 100%;">';
	wp_nonce_field( 'jwppp_save_meta_box_data', 'jwppp_meta_box_nonce' );

	
	//JUST A LITTLE OF STYLE
	echo '<style>';
	echo '.question-mark-2 {position:relative; top:0.2rem; left:1rem;}';
	echo '</style>';

	echo '<label for="_jwppp-video-url">';
	echo '<strong>' . esc_html(__( 'Media URL', 'jwppp' )) . '</strong>';
	echo '<a href="http://www.ilghera.com/support/topic/media-formats-supported/" title="More informations" target="_blank"><img class="question-mark-2" src="' . esc_url(plugin_dir_url(__DIR__)) . '/images/question-mark.png" /></a></th>';
	echo '</label> ';
	echo '<p><input type="text" id="_jwppp-video-url" name="_jwppp-video-url" placeholder="' . esc_html(__('Video (YouTube or self-hosted), Audio or Playlist (Premium)', 'jwppp')) . '" disabled="disabled" size="60"  /></p>';

	echo '<a class="button options">' . esc_html(__('More options', 'jwppp')) . '</a>';
	if(get_option('jwppp-position') == 'custom') {
		echo '<code style="display:inline-block;margin:0.1rem 0.5rem 0;color:#888;">[jwp-video n="2"]</code>';
	}

	echo '</td>';
	if($number<2) {
		echo '<td class="add-video"><a class="jwppp-add"><img src="' . esc_url(plugin_dir_url(__DIR__)) . '/images/add-video.png" /></a></td>';
	} else {
		echo '<td class="remove-video"><a class="jwppp-remove" data-numb="' . esc_attr($number) . '"><img src="' . esc_url(plugin_dir_url(__DIR__)) . '/images/remove-video.png" /></a></td>';
	}
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '<div style="padding:1rem; background: #2EBFB0; color: #fff; display: block;">';
	echo esc_html(__('With the Premium version you\'ll be able to embed more videos, each with full options. ' , 'jwppp'));
	echo '<a href="http://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank"></strong>Upgrade</strong></a> ';
	echo '</div>';

	exit();
}