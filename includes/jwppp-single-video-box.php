<?php
/**
 * Single video box
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @version 1.6.0
 */

$dashboard_player = is_dashboard_player();
$player_position = get_option('jwppp-position');

$output  = '<!-- jwppp video number ' . esc_attr($number) . ' -->';
$output .= '<table class="widefat jwppp-' . esc_attr($number) . '" style="margin: 0.4rem 0; width: 100%;">';
	$output .= '<tbody class="ui-sortable">';

		/*Class for preview image dimensions*/
		$image_class = null;
		if($player_position !== 'custom') {
			$image_class = ' small'; 
		} elseif(!$dashboard_player) {
			$image_class = ' medium'; 
		} 

		$output .= '<tr class="row">';
			$output .= '<td class="order">' . esc_attr($number) . '</td>';
			$output .= '<td class="jwppp-input-wrap' . esc_html($image_class) . '" style="width: 100%; padding-bottom: 1rem; position: relative;">';
				wp_nonce_field( 'jwppp_save_single_video_data', 'jwppp-meta-box-nonce-' . $number );

				/*Single video details*/
				$video_url = get_post_meta($post_id, '_jwppp-video-url-' . $number, true );
				$video_title = get_post_meta($post_id, '_jwppp-video-title-' . $number, true);

				$video_description = get_post_meta($post_id, '_jwppp-video-description-' . $number, true);

				$playlist_items = get_post_meta($post_id, '_jwppp-playlist-items-' . $number, true);
				$video_duration = get_post_meta($post_id, '_jwppp-video-duration-' . $number, true);
				$video_tags = get_post_meta($post_id, '_jwppp-video-tags-' . $number, true);

				/*Is the video self hosted?*/
				$sh_video = strrpos($video_url, 'http') === 0 ? true : false;

				$sources_number = get_post_meta($post_id, '_jwppp-sources-number-' . $number, true);
				$main_source_label = get_post_meta($post_id, '_jwppp-' . $number . '-main-source-label', true );

				$media_details = json_decode(stripslashes(get_post_meta($post_id, '_jwppp-media-details', true)));

				/*Video selected details*/
				$output .= '<div class="jwppp-video-details jwppp-video-details-' . esc_attr($number) . '">';
					$output .= '';

						$output .= $video_title ? '<span>Title</span>: ' . esc_html($video_title) . '</br>' : '';
						$output .= $video_description ? '<span>Description</span>: ' . esc_html($video_description) . '</br>' : '';
						$output .= $playlist_items ? '<span>Items</span>: ' . esc_html($playlist_items) . '</br>' : '';
						$output .= $video_duration ? '<span>Duration</span>: ' . esc_html($video_duration) . '</br>' : '';
						$output .= $video_tags ? '<span>Tags</span>: ' . esc_html($video_tags) . '</br>' : '';

				$output .= '</div>';	

				/*Thumbnail*/
				$video_image = null;
				if($video_url && $video_url !== '1') {
					if($sh_video) {
						$video_image = get_post_meta($post_id, '_jwppp-video-image-' . $number, true);
					} else {
						$single_video_image = 'https://cdn.jwplayer.com/thumbs/' . $video_url . '-720.jpg';
						if(@getimagesize($single_video_image)) {
							$video_image = $single_video_image;
						} else {
							$video_image = plugin_dir_url(__DIR__) . 'images/playlist4.png';
						}
					}
				}

				/*Poster image preview*/
				if($video_image) {
					$output .= '<img class="poster-image-preview ' . esc_attr($number) . (!$dashboard_player ? ' small' : '') . '" src="' . esc_url($video_image) . '">';
				}	

				/*A cloud palyer allows to get contents from the JW Dasboard */
				if($dashboard_player) {

					$output .= '<ul class="jwppp-video-toggles ' . esc_attr($number) . '">';
						$output .= '<li data-video-type="choose"' . (!$sh_video ? ' class="active"' : '') . '>' . esc_html(__('Choose', 'jwppp')) . '</li>';
						$output .= '<li data-video-type="add-url"' . ($sh_video ? ' class="active"' : '') . '>' . esc_html(__('Add url', 'jwppp')) . '</li>';
						$output .= '<div class="clear"></div>';
					$output .= '</ul>';

					/*Select media content*/
					$output .= '<div class="jwppp-toggle-content ' . esc_attr($number) . ' choose' . (!$sh_video ? ' active' : '') . '">';
						$output .= '<p>';

							$output .= '<input type="text" autocomplete="off" id="_jwppp-video-title-' . esc_attr($number) . '" class="jwppp-search-content choose" data-number="' . esc_attr($number) . '" placeholder="' . esc_html(__('Select video/playlist or search by ID', 'jwppp')) . '" style="margin-right:1rem;" value="' . esc_html($video_title) . '"><br>';

							$output .= '<input type="hidden" name="_jwppp-video-url-' . esc_attr($number) . '" id="_jwppp-video-url-' . esc_attr($number) . '" class="choose" value="' . esc_html($video_url) . '">';


							$output .= '<input type="hidden" name="_jwppp-video-title-' . esc_attr($number) . '" id="_jwppp-video-title-' . esc_attr($number) . '" class="choose" value="' . esc_html($video_title) . '">';
							$output .= '<input type="hidden" name="_jwppp-video-description-' . esc_attr($number) . '" id="_jwppp-video-description-' . esc_attr($number) . '" class="choose" value="' . esc_html($video_description) . '">';
							$output .= '<input type="hidden" name="_jwppp-playlist-items-' . esc_attr($number) . '" id="_jwppp-playlist-items-' . esc_attr($number) . '" class="choose" value="' . esc_html($playlist_items) . '">';
							$output .= '<input type="hidden" name="_jwppp-video-duration-' . esc_attr($number) . '" id="_jwppp-video-duration-' . esc_attr($number) . '" class="choose" value="' . esc_html($video_duration) . '">';
							$output .= '<input type="hidden" name="_jwppp-video-tags-' . esc_attr($number) . '" id="_jwppp-video-tags-' . esc_attr($number) . '" class="choose" value="' . esc_html($video_tags) . '">';

							$output .= '<ul id="_jwppp-video-list-' . esc_attr($number) . '" data-number="' . esc_attr($number) . '" class="jwppp-video-list">';
								$output .= '<span class="jwppp-list-container"></span>';
							$output .= '</ul>';

						$output .= '</p>';		
					$output .= '</div>';	

				} 

				/*Input url, both with cloud and self-hosted players*/
				$output .= $dashboard_player ? '<div class="jwppp-toggle-content ' . esc_attr($number) . ' add-url' . ($sh_video ? ' active' : '') . '">' : '';

					if(!$dashboard_player) {
						$output .= '<label for="_jwppp-video-url-' . esc_attr($number) . '">';
							$output .= '<strong>' . esc_html(__( 'Media URL', 'jwppp' )) . '</strong>';
							$output .= '<a class="question-mark" href="https://www.ilghera.com/support/topic/media-formats-supported/" title="More informations" target="_blank"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
						$output .= '</label> ';
					}

					$output .= '<p>';
						$output .= '<input type="text" id="_jwppp-video-url-' . esc_attr($number) . '" class="jwppp-url" name="_jwppp-video-url-' . esc_attr($number) . '" placeholder="' . esc_html(__('Add here your media url', 'jwppp')) . '" ';
						$output .= ($video_url !== '1') ? 'value="' . esc_attr( $video_url ) . '" ' : 'value="" ';
						$output .= 'size="60" />';

						$output .= '<input type="text" name="_jwppp-' . esc_attr($number) . '-main-source-label" id ="_jwppp-' . esc_attr($number) . '-main-source-label" class="source-label-' . esc_attr($number) . '" style="margin-right:1rem; display: none;';
						$output .= '" value="' . esc_html($main_source_label) . '" placeholder="' . esc_html(__('Label (HD, 720p, 360p)', 'jwppp')) . '" size="30" />';

					$output .= '</p>';

				$output .= $dashboard_player ? '</div>' : '';

				/*Display shortcode*/
				if(get_option('jwppp-position') === 'custom') {
					$output .= '<code style="display:inline-block;margin:0.1rem 0.5rem 0 0.2rem;color:#888;">[jwp-video n="' . esc_attr($number) . '"]</code>';
				}

				$output .= '<a class="button more-options-' . esc_attr($number) . '">' . esc_html(__('Show options', 'jwppp')) . '</a>';

				/*Single video tools*/
				$output .= jwppp_video_tools($post_id, $number, $sh_video);
				?>
				<script>
					jQuery(document).ready(function($){
						var number = <?php echo $number; ?>;
						var post_id = <?php echo $post_id; ?>;
						jwppp_single_video(number, post_id);
					})
				</script>
				<?php

				$output .= '</div>';
			$output .= '</td>';

			if($number < 2) {
				$output .= '<td class="add-video"><a class="jwppp-add"><img src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/add-video.png" /></a></td>';
			} else {
				$output .= '<td class="remove-video"><a class="jwppp-remove" data-numb="' . esc_attr($number) . '"><img src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/remove-video.png" /></a></td>';
			}

		$output .= '</tr>';
	$output .= '</tbody>';
$output .= '</table>';

echo $output;