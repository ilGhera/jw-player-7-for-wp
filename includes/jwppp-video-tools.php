<?php
/**
 * Single video tools
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @version 1.6.0
 * @param  int $post_id    the post id
 * @param  int $number     the video number
 * @param  bool $sh_video  is the video sel-hosted?
 * @return mixed           all the tools for the single video
 */
function jwppp_video_tools($post_id, $number, $sh_video) {
	
	/*Is a Dashboard player?*/
	$dashboard_player = is_dashboard_player();
	
	/*Single video details*/
	$video_title 	   = get_post_meta($post_id, '_jwppp-video-title-' . $number, true);
	$video_description = get_post_meta($post_id, '_jwppp-video-description-' . $number, true);

	/*Used only with self hosted vidoes*/
	$video_image 				   = get_post_meta($post_id, '_jwppp-video-image-' . $number, true);
	$add_chapters				   = get_post_meta($post_id, '_jwppp-add-chapters-' . $number, true);
	$jwppp_chapters_subtitles      = get_post_meta($post_id, '_jwppp-chapters-subtitles-' . $number, true);
	$jwppp_subtitles_method        = get_post_meta($post_id, '_jwppp-subtitles-method-' . $number, true);
	$jwppp_subtitles_load_default  = get_post_meta($post_id, '_jwppp-subtitles-load-default-' . $number, true);
	$jwppp_subtitles_write_default = get_post_meta($post_id, '_jwppp-subtitles-write-default-' . $number, true);
	$jwppp_activate_media_type     = get_post_meta($post_id, '_jwppp-activate-media-type-' . $number, true);
	$jwppp_media_type 			   = get_post_meta($post_id, '_jwppp-media-type-' . $number, true);
	$jwppp_autoplay 			   = get_post_meta($post_id, '_jwppp-autoplay-' . $number, true);
	$jwppp_mute 				   = get_post_meta($post_id, '_jwppp-mute-' . $number, true);
	$jwppp_repeat 				   = get_post_meta($post_id, '_jwppp-repeat-' . $number, true);
	$jwppp_share_video 			   = get_option('jwppp-active-share');
	$jwppp_download_video 		   = get_post_meta($post_id, '_jwppp-download-video-' . $number, true);
	$jwppp_embed_video			   = get_option('jwppp-embed-video');

	$jwppp_single_embed = null;
	if(isset($_POST['_jwppp-single-embed-' . $number])) {
		$jwppp_single_embed = sanitize_text_field($_POST['_jwppp-single-embed-' . $number]);
	} else {
		$jwppp_single_embed = get_post_meta($post_id, '_jwppp-single-embed-' . $number, true);	
	}

	/*Ads tags*/
	$active_ads    = get_option('jwppp-active-ads');
	$ads_tags	   = get_option('jwppp-ads-tag');
	$jwppp_ads_tag = get_post_meta($post_id, '_jwppp-ads-tag-' . $number, true);

	/*Ads var block*/
	$active_ads_var = get_option('jwppp-active-ads-var');

	/*Choose player*/
	$choose_player = get_post_meta($post_id, '_jwppp-choose-player-' . $number, true);

	$output  = '<div class="jwppp-more-options-' . esc_attr($number) . '" style="margin-top:2rem; display: none;">';

	if($dashboard_player) {

		/*API*/
		$api = new jwppp_dasboard_api();

		/*Choose player*/
		$output .= '<div class="jwppp-single-option-' . esc_attr($number) . ' cloud-option">';
			
			$players = $api->get_players();
			$library_parts = explode('https://content.jwplatform.com/libraries/', get_option('jwppp-library'));

			$output .= '<label for="_jwppp-choose-player-' . esc_attr($number) . '"><strong>' . esc_html(__('Select Player', 'jwppp')) . '</strong></label>';
			$output .= '<p>';
			$output .= '<select class="jwppp-choose-player" name="_jwppp-choose-player-' . esc_attr($number) . '">';

			if(is_array($players) && !empty($players)) {
				foreach($players as $player) { 
					$selected = false;
					if($choose_player && $choose_player === $player['key']) {
						$selected = true;
					} elseif(!$choose_player && $library_parts[1] === $player['key'] . '.js') {
						$selected = true;
					}
					$output .= '<option name="' . sanitize_text_field($player['key']) . '" value="' . sanitize_text_field($player['key']) . '"' . ($selected ? ' selected="selected"' : '') . '>' . sanitize_text_field($player['name']) . '</option>';
				}
			}

			$output .= '</select>';
			$output .= '</p>';

		$output .= '</div>';
	}


	/*More sources*/
	$output .= '<div class="jwppp-single-option-' . esc_attr($number) . '"' . ($dashboard_player && !$sh_video ? ' style="display: none;"' : '') . '>';
		$output .= '<label for="_jwppp-add-sources-' . esc_attr($number) . '">';
		$output .= '<strong>' . esc_html(__( 'More sources', 'jwppp' )) . '</strong>';
		$output .= '<a class="question-mark" title="' . esc_attr(__('Used for quality toggling and alternate sources.', 'jwppp')) . '"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
		$output .= '</label> ';

		if(get_post_meta($post_id, '_jwppp-sources-number-' . $number, true)) {
			$sources = get_post_meta($post_id, '_jwppp-sources-number-' .  $number, true);
		} else {
			$sources = 1;
		}

		$output .= '<input type="number" class="small-text" style="margin-left:1.8rem; display:inline; position: relative; top:2px;" id="_jwppp-sources-number-' .  esc_attr($number) . '" name="_jwppp-sources-number-' .  esc_attr($number) . '" value="' . esc_attr($sources) . '">';

		$output .= '</p>';

		$output .= '<ul class="sources-' . esc_attr($number) . '">';

		for($n=1; $n<$sources+1; $n++) {
			$source_url  = get_post_meta($post_id, '_jwppp-' . $number . '-source-' . $n . '-url', true);
			$source_label = get_post_meta($post_id, '_jwppp-' . $number . '-source-' . $n . '-label', true);
			$output .= '<li id="video-' . esc_attr($number) . '-source" data-number="' . esc_attr($n) . '">';	
			$output .= '<input type="text" style="margin-right:1rem;" name="_jwppp-' . esc_attr($number) . '-source-' . esc_attr($n) . '-url" id="_jwppp-' . esc_attr($number) . '-source-' . esc_attr($n) . '-url" value="' . esc_url($source_url) . '" placeholder="' . esc_html(__('Source url', 'jwppp')) . '" size="60" />';
			$output .= '<input type="text" name="_jwppp-' . esc_attr($number) . '-source-' . esc_attr($n) . '-label" class="source-label-' . esc_attr($number) . '" style="margin-right:1rem;' . ($sources <= 1 ? ' display: none;' : '') . '" value="' . esc_attr($source_label) . '" placeholder="' . esc_html(__('Label (HD, 720p, 360p)', 'jwppp')) . '" size="30" />';
			$output .= '</li>';
		}

		$output .= '</ul>';
	$output .= '</div>';


	/*Poster image*/
	$output .= '<div class="jwppp-single-option-' . esc_attr($number) . '"' . ($dashboard_player && !$sh_video ? ' style="display: none;"' : '') . '>';
		$output .= '<label for="_jwppp-video-image-' . esc_attr($number) . '">';
		$output .= '<strong>' . esc_html(__( 'Video poster image', 'jwppp' )) . '</strong>';
		$output .= '</label> ';
		$output .= '<p class="poster-image-container-' . $number . '">';
		$output .= '<input type="text" id="_jwppp-video-image-' . esc_attr($number) . '" name="_jwppp-video-image-' . esc_attr($number) .'" placeholder="' . esc_html(__('Add a different poster image for this video', 'jwppp')) . '" value="' . esc_attr( $video_image ) . '" size="60" />';
		$output .= '</p>';
	$output .= '</div>';


	/*Video title*/
	$output .= '<div class="jwppp-single-option-' . esc_attr($number) . '"' . ($dashboard_player && !$sh_video ? ' style="display: none;"' : '') . '>';
		$output .= '<label for="_jwppp-video-title-' . esc_attr($number) . '">';
		$output .= '<strong>' . esc_html(__( 'Video title', 'jwppp' )) . '</strong>';
		$output .= '</label> ';
		$output .= '<p><input type="text" id="_jwppp-video-title-' . esc_attr($number) . '" class="jwppp-title" name="_jwppp-video-title-' . esc_attr($number) . '" placeholder="' . esc_html(__('Add a title to your video', 'jwppp')) . '" value="' . esc_attr( $video_title ) . '" size="60" /></p>';
	$output .= '</div>';


	/*Video description*/
	$output .= '<div class="jwppp-single-option-' . esc_attr($number) . '"' . ($dashboard_player && !$sh_video ? ' style="display: none;"' : '') . '>';
		$output .= '<label for="_jwppp-video-description-' . esc_attr($number) . '">';
		$output .= '<strong>' . esc_html(__( 'Video description', 'jwppp' )) . '</strong>';
		$output .= '</label> ';
		$output .= '<p><input type="text" id="_jwppp-video-description-' . esc_attr($number) . '" name="_jwppp-video-description-' . esc_attr($number) . '" placeholder="' . esc_html(__('Add a description to your video', 'jwppp')) . '" value="' . esc_attr( $video_description ) . '" size="60" /></p>';
	$output .= '</div>';


	/*Ad tag*/
	if($active_ads && !$active_ads_var) {
		$output .= '<div class="jwppp-single-option-' . esc_attr($number) . ' cloud-option">';
			$output .= '<label for="_jwppp-ads-tag-' . esc_attr($number) . '"><strong>' . esc_html(__('Select Ad tag', 'jwppp')) . '</strong></label>';
			$output .= '<p>';
			$output .= '<select class="jwppp-ads-tag" name="_jwppp-ads-tag-' . esc_attr($number) . '">';
				if($ads_tags) {
					if(is_array($ads_tags) && !empty($ads_tags)) {
						for ($i=0; $i < count($ads_tags); $i++) { 
							$output .= '<option name="' . esc_html($ads_tags[$i]['label']) . '" value="' . $ads_tags[$i]['url'] . '"' . ($jwppp_ads_tag === $ads_tags[$i]['url'] ? ' selected="selected"' : '') . '>' . ($ads_tags[$i]['label'] ? esc_html($ads_tags[$i]['label']) : 'Tag ' . (esc_attr($i) + 1)) . '</option>';
						}
					} elseif(is_string($ads_tags)) {
						$output .= '<option name="0" value="' . $ads_tags . '">' . $ads_tags . '</option>';
					}
					
					/*Advertise disabled*/
					$output .= '<option name="" value="no-ads"' . ($jwppp_ads_tag === 'no-ads' ? ' selected="selected"' : '') . '>' . esc_html(__('No advertise for this video', 'jwppp')) . '</option>';		

				} else {
					$output .= '<option name="" value="">' . esc_html(__('No ad tags available', 'jwppp')) . '</option>';		
				}

			$output .= '</select>';
			$output .= '</p>';
		$output .= '</div>';
	}


	/*Playlist carousel*/
	if($dashboard_player && !$sh_video) {
		$jwppp_playlist_carousel = get_post_meta($post_id, '_jwppp-playlist-carousel-' . $number, true);
		$output .= '<div class="jwppp-single-option-' . esc_attr($number) . ' cloud-option playlist-carousel-container ' . esc_attr($number) . '"' . ($jwppp_playlist_carousel ? ' style="display: inline-block;"' : '') . '>';
			$output .= '<label for="_jwppp-playlist-carousel-' . esc_attr($number) . '">';
			$output .= '<input type="checkbox" id="_jwppp-playlist-carousel-' . esc_attr($number) . '" name="_jwppp-playlist-carousel-' . esc_attr($number) . '" value="1"';
			$output .= ($jwppp_playlist_carousel === '1') ? ' checked="checked"' : '';
			$output .= ' />';
			$output .= '<strong>' . esc_html(__('Show a carousel with the playlist\'s video thumbnails.', 'jwppp')) . '</strong>';
			$output .= '</label>';
			$output .= '<input type="hidden" name="playlist-carousel-hidden-' . esc_attr($number) . '" value="1" />';
		$output .= '</div>';	
	}


	/*Media type*/
	$output .= '<div class="jwppp-single-option-' . esc_attr($number) . '"' . ($dashboard_player && !$sh_video ? ' style="display: none;"' : '') . '>';
		$output .= '<p>';
		$output .= '<label for="_jwppp-activate-media-type-' . esc_attr($number) . '">';
		$output .= '<input type="checkbox" id="_jwppp-activate-media-type-' . esc_attr($number) . '" name="_jwppp-activate-media-type-' . esc_attr($number) . '" value="1"';
		$output .= ($jwppp_activate_media_type === '1') ? ' checked="checked"' : '';
		$output .= ' />';
		$output .= '<strong>' . esc_html(__('Force a media type', 'jwppp')) . '</strong>';
		$output .= '<a class="question-mark" title="' . esc_html(__('Only required when a file extension is missing or not recognized', 'jwppp')) . '"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
		$output .= '</label>';
		$output .= '<input type="hidden" name="activate-media-type-hidden-' . esc_attr($number) . '" value="1" />';

		$output .= '<select style="position: relative; left:2rem; display:inline;" id="_jwppp-media-type-' . esc_attr($number) . '" name="_jwppp-media-type-' . esc_attr($number) . '">';
		$output .= '<option name="mp4" value="mp4"';
		$output .= ($jwppp_media_type === 'mp4') ? ' selected="selected"' : '';
		$output .= '>mp4</option>';
		$output .= '<option name="flv" value="flv"';
		$output .= ($jwppp_media_type === 'flv') ? ' selected="selected"' : '';
		$output .= '>flv</option>';
		$output .= '<option name="mp3" value="mp3"';
		$output .= ($jwppp_media_type === 'mp3') ? ' selected="selected"' : '';
		$output .= '>mp3</option>';
		$output .= '</select>';
		$output .= '</p>';
	$output .= '</div>';

	/*Options available only with self-hosted player*/
	if(!$dashboard_player) {

		/*Autoplay*/
		$output .= '<div class="jwppp-single-option-' . esc_attr($number) . '">';
			$output .= '<p>';
			$output .= '<label for="_jwppp-autoplay-' . esc_attr($number) . '">';
			$output .= '<input type="checkbox" id="_jwppp-autoplay-' . esc_attr($number) . '" name="_jwppp-autoplay-' . esc_attr($number) . '" value="1"';
			$output .= ($jwppp_autoplay === '1') ? ' checked="checked"' : '';
			$output .= ' />';
			$output .= '<strong>' . esc_html(__('Autostart on page load', 'jwppp')) . '</strong>';
			$output .= '</label>';
			$output .= '<input type="hidden" name="autoplay-hidden-' . esc_attr($number) . '" value="1" />';
			$output .= '</p>';
		$output .= '</div>';


		/*Mute*/
		$output .= '<div class="jwppp-single-option-' . esc_attr($number) . '">';
			$output .= '<p>';
			$output .= '<label for="_jwppp-mute-' . esc_attr($number) . '">';
			$output .= '<input type="checkbox" id="_jwppp-mute-' . esc_attr($number) . '" name="_jwppp-mute-' . esc_attr($number) . '" value="1"';
			$output .= ($jwppp_mute === '1') ? ' checked="checked"' : '';
			$output .= ' />';
			$output .= '<strong>' . esc_html(__('Mute', 'jwppp')) . '</strong>';
			$output .= '</label>';
			$output .= '<input type="hidden" name="mute-hidden-' . esc_attr($number) . '" value="1" />';
			$output .= '</p>';
		$output .= '</div>';


		/*Repeat*/
		$output .= '<div class="jwppp-single-option-' . esc_attr($number) . '">';
			$output .= '<p>';
			$output .= '<label for="_jwppp-repeat-' . esc_attr($number) . '">';
			$output .= '<input type="checkbox" id="_jwppp-repeat-' . esc_attr($number) . '" name="_jwppp-repeat-' . esc_attr($number) . '" value="1"';
			$output .= ($jwppp_repeat === '1') ? ' checked="checked"' : '';
			$output .= ' />';
			$output .= '<strong>' . esc_html(__('Repeat', 'jwppp')) . '</strong>';
			$output .= '</label>';
			$output .= '<input type="hidden" name="repeat-hidden-' . esc_attr($number) . '" value="1" />';
			$output .= '</p>';
		$output .= '</div>';


		/*Embed video*/
		if($jwppp_share_video) {
			if($jwppp_single_embed === '1') {
				$checked = 'checked="checked"';
			} elseif($jwppp_single_embed === '0') {
				$checked = '';
			} elseif(!$jwppp_single_embed) {
				$checked = ($jwppp_embed_video === '1') ? 'checked="checked"' : '';
			}

			$output .= '<div class="jwppp-single-option-' . esc_attr($number) . '">';
				$output .= '<p>';
				$output .= '<label for="_jwppp-single-embed-' . esc_attr($number) . '">';
				$output .= '<input type="checkbox" id="_jwppp-single-embed-' . esc_attr($number) . '" name="_jwppp-single-embed-' . esc_attr($number) . '" value="1"';
				$output .= ' ' . $checked;
				$output .= ' />';
				$output .= '<strong>' . esc_html(__('Allow to embed this video', 'jwppp')) . '</strong>';
				$output .= '</label>';
				$output .= '<input type="hidden" name="single-embed-hidden-' . esc_attr($number) . '" value="1" />';
				$output .= '</p>';
			$output .= '</div>';
		}

		/*Download video*/
		$output .= '<div class="jwppp-single-option-' . esc_attr($number) . '">';
			$output .= '<p>';
			$output .= '<label for="_jwppp-download-video-' . esc_attr($number) . '">';
			$output .= '<input type="checkbox" id="_jwppp-download-video-' . esc_attr($number) . '" name="_jwppp-download-video-' . esc_attr($number) . '" value="1"';
			$output .= ($jwppp_download_video === '1') ? ' checked="checked"' : '';
			$output .= ' />';
			$output .= '<strong>' . esc_html(__('Allow download', 'jwppp')) . '</strong>';
			$output .= '<a class="question-mark" title="' . esc_html(__('Only with self-hosted videos and not with playlists.', 'jwppp')) . '"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
			$output .= '</label>';
			$output .= '<input type="hidden" name="download-video-hidden-' . esc_attr($number) . '" value="1" />';
			$output .= '</p>';
		$output .= '</div>';


		/*Chapters & subtitles*/
		$output .= '<div class="jwppp-single-option-' . esc_attr($number) . '">';
			$output .= '<p>';
			$output .= '<label for="_jwppp-add-chapters-' . esc_attr($number) . '">';
			$output .= '<input type="checkbox" id="_jwppp-add-chapters-' . esc_attr($number) . '" name="_jwppp-add-chapters-' . esc_attr($number) . '" value="1"';
			$output .= ($add_chapters === '1') ? ' checked="checked"' : '';
			$output .= ' />';
			$output .= '<strong><span class="add-chapters ' . esc_attr($number) . '">';
			$output .= ($add_chapters === '1') ? esc_html(__('Add', 'jwppp')) : esc_html(__('Add Chapters, Subtitles or Preview Thumbnails', 'jwppp'));
			$output .= '</span></strong>';
			$output .= '</label>';
			$output .= '<input type="hidden"function name="add-chapters-hidden-' . esc_attr($number) . '" value="1" />';

			$output .= '<select style="margin-left:0.5rem;" name="_jwppp-chapters-subtitles-' . esc_attr($number) . '" id="_jwppp-chapters-subtitles-' . esc_attr($number) . '">';
			$output .= '<option name="chapters" id="chapters" value="chapters"';
			$output .= ($jwppp_chapters_subtitles === 'chapters') ? ' selected="selected"' : '';
			$output .= '>Chapters</option>';
			$output .= '<option name="subtitles" id="subtitles" value="subtitles"';
			$output .= ($jwppp_chapters_subtitles === 'subtitles') ? ' selected="selected"' : '';
			$output .= '>Subtitles</option>';
			$output .= '<option name="thumbnails" id="thumbnails" value="thumbnails"';
			$output .= ($jwppp_chapters_subtitles === 'thumbnails') ? ' selected="selected"' : '';
			$output .= '>Thumbnails</option>';
			$output .= '</select>';

			/*Subtitles method selector*/
			$output .= '<select style="margin-left:0.3rem;" name="_jwppp-subtitles-method-' . esc_attr($number) . '" id="_jwppp-subtitles-method-' . esc_attr($number) . '">';
			$output .= '<option name="manual" id="manual" value="manual"';
			$output .= ($jwppp_subtitles_method === 'manual') ? ' selected="selected"' : '';
			$output .= '>Write subtitles</option>';
			$output .= '<option name="load" id="load" value="load"';
			$output .= ($jwppp_subtitles_method === 'load') ? ' selected="selected"' : '';
			$output .= '>Load subtitles</option>';
			$output .= '</select>';

			if(get_post_meta($post_id, '_jwppp-chapters-number-' . $number, true)) {
				$chapters = get_post_meta($post_id, '_jwppp-chapters-number-' .  $number, true);
			} else {
				$chapters = 1;
			}

			$output .= '<input type="number" class="small-text" style="margin-left:0.3rem; display:inline; position: relative; top:2px;" id="_jwppp-chapters-number-' .  esc_attr($number) . '" name="_jwppp-chapters-number-' .  esc_attr($number) . '" value="' . esc_attr($chapters) . '">';

			$output .= '</p>';

			$output .= '<ul class="chapters-subtitles-' . esc_attr($number) . '">';
			for($i=1; $i<$chapters+1; $i++) {
				$title = get_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title', true);
				$start = get_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start', true);
				$end = get_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end', true);		
				$output .= '<li id="video-' . esc_attr($number) . '-chapter" data-number="' . esc_attr($i) . '">';
				$output .= '<input type="text" style="margin-right:1rem;" name="_jwppp-' . esc_attr($number) . '-chapter-' . esc_attr($i) . '-title" value="' . esc_html($title) . '"';

				if($jwppp_chapters_subtitles === 'subtitles') {
					$output .= 'placeholder="' . esc_html(__('Subtitle', 'jwppp')) . '"';
				} elseif($jwppp_chapters_subtitles == 'thumbnails') {
					$output .= 'placeholder="' . esc_html(__('Thumbnail url', 'jwppp')) . '"';
				} else {
					$output .= 'placeholder="' . esc_html(__('Chapter title', 'jwppp')) . '"';
				}

				$output .= ' size="60" />';
				$output .= '    ' . esc_html(__('Start', 'jwppp')) . '    <input type="number" name="_jwppp-' . esc_attr($number) . '-chapter-' . esc_attr($i) . '-start" style="margin-right:1rem;" min="0" step="1" class="small-text" value="' . esc_attr($start) . '" />';
				$output .= '    ' . esc_html(__('End', 'jwppp')) . '    <input type="number" name="_jwppp-' . esc_attr($number) . '-chapter-' . esc_attr($i) . '-end" style="margin-right:0.5rem;" min="1" step="1" class="small-text" value="' . esc_attr($end) . '" />';
				$output .= esc_html(__('in seconds', 'jwpend'));

				/*Subtitles activated by default*/
				if($i === '1') {
					$output .= '<label for="_jwppp-subtitles-write-default-' . esc_attr($number) . '" style="display: inline-block; margin-left: 1rem;">';
					$output .= '<input type="checkbox" id="_jwppp-subtitles-write-default-' . esc_attr($number) . '" name="_jwppp-subtitles-write-default-' . esc_attr($number) . '" value="1"';
					$output .= ($jwppp_subtitles_write_default === '1') ? ' checked="checked"' : '';
					$output .= ' />';
					$output .= esc_html(__('Default', 'jwppp'));
					$output .= '<a class="question-mark" title="' . esc_html(__('These subtitles will be activated by default', 'jwppp')) . '"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
					$output .= '</label>';
					$output .= '<input type="hidden" name="subtitles-write-default-hidden-' . esc_attr($number) . '" value="1" />';
				}

				$output .= '</li>';
			}
			$output .= '</ul>';

			$output .= '<ul class="load-subtitles-' . esc_attr($number) . '">';

			for($n=1; $n<$chapters+1; $n++) {
				$url  = get_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $n . '-url', true);
				$label = get_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $n . '-label', true);
				$output .= '<li id="video-' . esc_attr($number) . '-subtitle" data-number="' . esc_attr($n) . '">';	
				$output .= '<input type="text" style="margin-right:1rem;" name="_jwppp-' . esc_attr($number) . '-subtitle-' . esc_attr($n) . '-url" value="' . esc_url($url) . '" placeholder="' . esc_html(__('Subtitles file url (VTT, SRT, DFXP)', 'jwppp')) . '" size="60" />';
				$output .= '<input type="text" name="_jwppp-' . esc_attr($number) . '-subtitle-' . esc_attr($n) . '-label" style="margin-right:1rem;" value="' . esc_html($label) . '" placeholder="' . esc_html(__('Label (EN, IT, FR )', 'jwppp')) . '" size="30" />';

				if($n==1) {
					$output .= '<label for="_jwppp-subtitles-load-default-' . esc_attr($number) . '">';
					$output .= '<input type="checkbox" id="_jwppp-subtitles-load-default-' . esc_attr($number) . '" name="_jwppp-subtitles-load-default-' . esc_attr($number) . '" value="1"';
					$output .= ($jwppp_subtitles_load_default === '1') ? ' checked="checked"' : '';
					$output .= ' />';
					$output .= esc_html(__('Default', 'jwppp'));
					$output .= '<a class="question-mark" title="' . esc_html(__('These first subtitles will be activated by default', 'jwppp')) . '"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
					$output .= '</label>';
					$output .= '<input type="hidden" name="subtitles-load-default-hidden-' . esc_attr($number) . '" value="1" />';
				}

				$output .= '</li>';
			}

			$output .= '</ul>';
		$output .= '</div>';
	}

	
	return $output;
}