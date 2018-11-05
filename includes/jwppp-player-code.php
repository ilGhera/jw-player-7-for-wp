<?php
/**
 * The player code block
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @version 1.6.0
 * @param  int 	  $p            the post id
 * @param  int    $n            the number of video
 * @param  string $ar           the aspect ratio
 * @param  int    $width        the with of the player
 * @param  int    $height       the height of the player
 * @param  int    $pl_autostart the autostart option for playlists
 * @param  int    $pl_mute      the mute option for playlists
 * @param  int    $pl_repeat    the repeat option for playlists
 * @return string               the block of code
 */
function jwppp_player_code($p, $n, $ar, $width, $height, $pl_autostart, $pl_mute, $pl_repeat) {

	/*Is it a dashboard player?*/
	$dashboard_player = is_dashboard_player();

	/*Default dashboard player informations*/
	if($dashboard_player) {
		$library_parts = explode('https://content.jwplatform.com/libraries/', get_option('jwppp-library'));
		$player_parts = explode('.js', $library_parts[1]);		
	}

	/*Get the id of post/ page*/
	if($p) {
		$p_id = $p;
	} else {
		$p_id = get_the_ID();
	}

	/*Get the number of every single video*/
	$videos = explode(',', $n);
	$jwppp_new_playlist = ( count($videos)>1 ) ? true : false;

	foreach($videos as $number) {

		/*Video url or media id*/
		$jwppp_video_url = get_post_meta($p_id, '_jwppp-video-url-' . $number, true);

		/*Is the video self hosted?*/
		$sh_video = strrpos($jwppp_video_url, 'http') === 0 ? true : false;

		/*Playlist carousel*/
		$jwppp_playlist_carousel = get_post_meta($p_id, '_jwppp-playlist-carousel-' . $number, true);

		if(!$dashboard_player || $sh_video) {

			$video_image = get_post_meta($p_id, '_jwppp-video-image-' . $number, true);
			$video_title = get_post_meta($p_id, '_jwppp-video-title-' . $number, true);
			$video_description = get_post_meta($p_id, '_jwppp-video-description-' . $number, true);
			$jwppp_activate_media_type = get_post_meta($p_id, '_jwppp-activate-media-type-' . $number, true);		
			$jwppp_media_type = get_post_meta($p_id, '_jwppp-media-type-' . $number, true);		
			$jwppp_autoplay = get_post_meta($p_id, '_jwppp-autoplay-' . $number, true);
			$jwppp_mute = get_post_meta($p_id, '_jwppp-mute-' . $number, true);
			$jwppp_repeat = get_post_meta($p_id, '_jwppp-repeat-' . $number, true);
			
			/*Embed option*/
			$jwppp_embed_video = sanitize_text_field(get_option('jwppp-embed-video'));
			$jwppp_single_embed = get_post_meta($p_id, '_jwppp-single-embed-' . $number, true);
			if(!$jwppp_single_embed) {
				$jwppp_single_embed = $jwppp_embed_video;
			}

			$jwppp_download_video = get_post_meta($p_id, '_jwppp-download-video-' . $number, true);
			$jwppp_add_chapters = get_post_meta($p_id, '_jwppp-add-chapters-' . $number, true);
			$jwppp_chapters_subtitles = get_post_meta($p_id, '_jwppp-chapters-subtitles-' . $number, true);
			$jwppp_chapters_number = get_post_meta($p_id, '_jwppp-chapters-number-' . $number, true);
			$jwppp_subtitles_method = get_post_meta($p_id, '_jwppp-subtitles-method-' . $number, true);
			$jwppp_subtitles_load_default = get_post_meta($p_id, '_jwppp-subtitles-load-default-' . $number, true);
			$jwppp_subtitles_write_default = get_post_meta($p_id, '_jwppp-subtitles-write-default-' . $number, true);

		}
	}
	
	/*Check for playlist*/
	$file_info = pathinfo($jwppp_video_url);
	$jwppp_playlist = false;
	if( array_key_exists('extension', $file_info) ) {
		if( in_array( $file_info['extension'], array('xml', 'feed', 'php', 'rss') ) ) {
			$jwppp_playlist = true;
		}
	}

	/*Specific video element defined by his number and the post id*/
	$this_video = $p_id . $number;

	if($dashboard_player && !$sh_video) {
	
		/*Video*/
		$self_content = strpos($jwppp_video_url, 'http');

		/*Check if the security option is activated*/
		$security_embeds = get_option('jwppp-secure-player-embeds');

		/*Choose player*/
		$choose_player = get_post_meta($p_id, '_jwppp-choose-player-' . $number, true) ? esc_html(get_post_meta($p_id, '_jwppp-choose-player-' . $number, true)) : esc_html($player_parts[0]);

		/*Output the player*/
		$output = "<div id='jwppp-video-box-" . esc_attr($this_video) . "' class='jwppp-video-box' data-video='" . esc_attr($n) . "' style=\"margin: 1rem 0;\">\n";

			/*FB Instant Articles*/
			$output .= '<span class="jwppp-instant" style="display: none;" data-video="' . plugin_dir_url(__DIR__) . 'fb/jwppp-fb-player.php?player=' . esc_html($choose_player) . '&mediaID=' . esc_html($jwppp_video_url) . '" data-width="480" data-height="270"></span>';

			$output .= "<div id='jwppp-video-" . esc_attr($this_video) . "' class='jwplayer'>";
			
			/*Loading the player text*/
			if(get_option('jwppp-text')) {
				$output .= esc_html(get_option('jwppp-text'));
			} else {
				$output .= esc_html(__('Loading the player...', 'jwppp'));
			}
			$output .= "</div>\n"; 

		/*Playlist carousel*/
		$output .= $jwppp_playlist_carousel ? jwppp_playlist_carousel($this_video) : '';

		/*Player choose - library*/
		if($security_embeds) {
			$output .= '<script type="text/javascript" src="' . jwppp_get_signed_url(esc_html($choose_player), true) . '"></script>';			
		} else {
			$output .= '<script type="text/javascript" src="https://content.jwplatform.com/libraries/' . esc_html($choose_player) . '.js"></script>';			
		}

		$output .= "<script type='text/javascript'>\n";
			$output .= "var playerInstance_" . esc_html($this_video) . " = jwplayer('jwppp-video-" . esc_html($this_video) . "');\n";
			$output .= "playerInstance_" . esc_html($this_video) . ".setup({\n";

				/*Check if the security option is activated*/
				$security_urls = get_option('jwppp-secure-video-urls');

				if($security_urls) {
					$output .= "playlist: '" . jwppp_get_signed_url(esc_html($jwppp_video_url)) . "',\n";		
				} else {
					$output .= "playlist: 'https://cdn.jwplayer.com/v2/media/" . esc_html($jwppp_video_url) . "',\n";							
				}

				/*Ads block*/
				$output .= jwppp_ads_code_block($p_id, $number);

			$output .= "})\n";

			if($jwppp_playlist_carousel) {
				$carousel_style = sanitize_text_field(get_option('jwppp-playlist-carousel-style'));
				$output .= "var e = new XMLHttpRequest;\n";
				$output .= "e.onreadystatechange = function() { 4 === e.readyState && (e.status >= 200 && JSON.parse(e.responseText).widgets.forEach(function(e) { outPlayerWidget(e) })) }, e.open('GET', '" . plugin_dir_url(__DIR__) . "jw-widget/jwppp-carousel-config.php?playlist-id=" . esc_html($jwppp_video_url) . "&player-id=" . esc_html($this_video) . "&carousel-style=$carousel_style', !0), e.send()\n";
			}

		$output .= "</script>";
		$output .= "</div>";  

	} else {

		$output = "<div id='jwppp-video-box-" . esc_attr($this_video) . "' class='jwppp-video-box' data-video='" . esc_attr($n) . "' style=\"margin: 1rem 0;\">\n";

		/*Player definition*/
		if($dashboard_player) {
			/*Choose player*/
			$choose_player = get_post_meta($p_id, '_jwppp-choose-player-' . $number, true) ? esc_html(get_post_meta($p_id, '_jwppp-choose-player-' . $number, true)) : esc_html($player_parts[0]);
			/*The player used for Facebook Instant Articles*/
			$instant_player = 'player=' . $choose_player;
		} else {
			/*The player used for Facebook Instant Articles*/
			$instant_player = 'player_url=' . get_option('jwppp-library');
		}

		/*FB Instant Articles*/
		$instant_image = $video_image ? $video_image : get_option('jwppp-poster-image');
		$output .= '<span class="jwppp-instant" style="display: none;" data-video="' . plugin_dir_url(__DIR__) . 'fb/jwppp-fb-player.php?' . esc_html($instant_player) . '&mediaURL=' . esc_html($jwppp_video_url) . '&image=' . esc_html($instant_image) . '" data-width="480" data-height="270"></span>';

		$output .= "<div id='jwppp-video-" . esc_attr($this_video) . "' class='jwplayer'>";
		
		/*Loading the player text*/
		if(get_option('jwppp-text')) {
			$output .= esc_html(get_option('jwppp-text'));
		} else {
			$output .= esc_html(__('Loading the player...', 'jwppp'));
		}
		$output .= "</div>\n"; 
		
		if($dashboard_player) {

			/*Player choose - library*/
			$output .= '<script type="text/javascript" src="https://content.jwplatform.com/libraries/' . esc_html($choose_player) . '.js"></script>';			
		}

		$output .= "<script type='text/javascript'>\n";
			$output .= "var playerInstance_" . esc_html($this_video) . " = jwplayer('jwppp-video-" . esc_html($this_video) . "');\n";
			$output .= "playerInstance_" . esc_html($this_video) . ".setup({\n";
				if($jwppp_playlist) {
				    $output .= "playlist: '" . get_post_meta($p_id, '_jwppp-video-url-' . $number, true) . "',\n"; 
				} else {
					if($jwppp_new_playlist) {
						$n=0;
						$output .= "playlist: [\n";
					}
					foreach($videos as $number) {

						$jwppp_video_url = get_post_meta($p_id, '_jwppp-video-url-' . $number, true);
						$jwppp_sources_number = get_post_meta($p_id, '_jwppp-sources-number-' . $number);
						$jwppp_source_1 = get_post_meta($p_id, '_jwppp-' . $number . '-source-1-url', true);
						$video_image = get_post_meta($p_id, '_jwppp-video-image-' . $number, true);
						$video_title = get_post_meta($p_id, '_jwppp-video-title-' . $number, true);
						$video_description = get_post_meta($p_id, '_jwppp-video-description-' . $number, true);
						$jwppp_activate_media_type = get_post_meta($p_id, '_jwppp-activate-media-type-' . $number, true);
						$jwppp_media_type = get_post_meta($p_id, '_jwppp-media-type-' . $number, true);
						$jwppp_autoplay = get_post_meta($p_id, '_jwppp-autoplay-' . $number, true);
						$jwppp_mute = get_post_meta($p_id, '_jwppp-mute-' . $number, true);
						$jwppp_repeat = get_post_meta($p_id, '_jwppp-repeat-' . $number, true);
						$jwppp_single_embed = get_post_meta($p_id, '_jwppp-single-embed-' . $number, true);
						$jwppp_single_embed = $jwppp_single_embed ? $jwppp_single_embed : $jwppp_embed_video;
						$jwppp_download_video = get_post_meta($p_id, '_jwppp-download-video-' . $number, true);
						$jwppp_add_chapters = get_post_meta($p_id, '_jwppp-add-chapters-' . $number, true);
						$jwppp_chapters_number = get_post_meta($p_id, '_jwppp-chapters-number-' . $number, true);
						$jwppp_chapters_subtitles = get_post_meta($p_id, '_jwppp-chapters-subtitles-' . $number, true);
						$jwppp_subtitles_method = get_post_meta($p_id, '_jwppp-subtitles-method-' . $number, true);
						$jwppp_subtitles_load_default = get_post_meta($p_id, '_jwppp-subtitles-load-default-' . $number, true);
						$jwppp_subtitles_write_default = get_post_meta($p_id, '_jwppp-subtitles-write-default-' . $number, true);

						/*Check for a yt video*/
						$youtube = jwppp_search_yt($jwppp_video_url);
						$jwppp_embed_url = $youtube['embed-url'];
						$yt_video_image  = $youtube['video-image'];

						if($jwppp_new_playlist) {
							$output .= "{\n"; 
						}

					    /*Video sources*/
						if($jwppp_source_1) {
							$output .= "sources: [\n";
							$output .= "{\n";
						}

					    $output .= "file: '" . esc_url($jwppp_video_url) . "',\n"; 
					    if($jwppp_sources_number && $jwppp_sources_number[0] > 1) {
					    	$main_source_label = get_post_meta($p_id, '_jwppp-' . $number . '-main-source-label', true);
				      		$output .= ($main_source_label) ? "label: '" . esc_html($main_source_label) . "'\n" : '';
						}
						
						if($jwppp_source_1) {
							$output .= "},\n";
						}

					    if($jwppp_source_1) {
							for($i=1; $i<$jwppp_sources_number[0]+1; $i++) {	
								$source_url = get_post_meta($p_id, '_jwppp-' . $number . '-source-' . $i . '-url', true);
								$source_label = get_post_meta($p_id, '_jwppp-' . $number . '-source-' . $i . '-label', true);
								if($source_url) {
						      		$output .= "{\n";
						      		$output .= "file: '" . esc_url($source_url) . "',\n";
						      		$output .= ($source_label) ? "label: '" . esc_html($source_label) . "'\n" : '';
						      		$output .= "},\n";
								} 
							}
				      	}

				      	if($jwppp_source_1) {
				      		$output .= "],\n";
						}

				      	/*Video title*/
					    if($video_title) {
						    $output .= "title: '" . esc_html($video_title) . "',\n";
						}

						/*Video description*/
						if($video_description) {
						    $output .= "description: '" . esc_html($video_description) . "',\n";
						}

						/*Poster image*/
						if($video_image) {
					    	$output .= "image: '" . esc_url($video_image) . "',\n";
					    } else if(has_post_thumbnail($p_id) && get_option('jwppp-post-thumbnail') === '1') {
					    	$output .=  "image: '" . get_the_post_thumbnail_url() . "',\n";
					    } else if($youtube['yes']) {
					    	$output .= "image: '" . esc_url($yt_video_image) . "',\n";
						} else if(get_option('jwppp-poster-image')) {
						    $output .= "image: '" . get_option('jwppp-poster-image') . "',\n";
						}

						if($jwppp_new_playlist) {
							$output .= "mediaid: '" . esc_attr($this_video) . $n++ . "',\n";
						}

						/*Ads code block*/
						$output .= jwppp_ads_code_block($p_id, $number);

						/*Media type*/
						if($jwppp_media_type) {
					    	$output .= "type: '" . esc_html($jwppp_media_type) . "',\n";
					    }

					    /*Autoplay*/
						if(!$jwppp_new_playlist && $jwppp_autoplay === '1') {
					    	$output .= "autostart: 'true',\n";
					    }

					    /*Mute*/
					    if(!$jwppp_new_playlist && $jwppp_mute === '1') {
					    	$output .= "mute: 'true',\n";
					    }

					    /*Mepeat*/
					    if(!$jwppp_new_playlist && $jwppp_repeat === '1') {
					    	$output .= "repeat: 'true',\n";
					    }

					    /*Aoogle Analytics*/
					    $output .= "ga: {},\n";
					    						
						/*Share*/
						$active_share = sanitize_text_field(get_option('jwppp-active-share'));	
						if(!$jwppp_new_playlist && $active_share === '1') {
							$output .= "sharing: {\n";
								$jwppp_share_heading = sanitize_text_field(get_option('jwppp-share-heading'));
								if($jwppp_share_heading !== null) {
									$output .= "heading: '" . esc_html($jwppp_share_heading) . "',\n";
								} else {
									$output .= "heading: '" . esc_html(__('Share Video', 'jwppp')) . "',\n"; 
								}
								$output .= "sites: ['email','facebook','twitter','pinterest','tumblr','googleplus','reddit','linkedin'],\n";
								if(($jwppp_embed_video || $jwppp_single_embed === '1') && !$jwppp_playlist) {
									$output .= "code: '<iframe src=\"" . esc_url($jwppp_embed_url) . "\"  width=\"640\"  height=\"360\"  frameborder=\"0\"  scrolling=\"auto\"></iframe>'\n";
								}
							$output .= "},\n";
						}

						/*Add chapters/ subtitles*/
						if($jwppp_add_chapters === '1') {
							$output .= "tracks:[\n";

							if($jwppp_chapters_subtitles === 'subtitles' && $jwppp_subtitles_method === 'load') {
								for($i=1; $i<$jwppp_chapters_number+1; $i++) {
									$output .= "{\n";
								    $output .= "file:'" . get_post_meta($p_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url', true) . "',\n";
								    $output .= "kind:'captions',\n";	
								    $output .= "label:'" . get_post_meta($p_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label', true) . "',\n";	
								    if($i==1 && $jwppp_subtitles_load_default === '1') {
								    	$output .= "'default': 'true'\n";
								    }
									$output .= "},\n";
								}

							} else {
								$output .= "{\n";
							    $output .= "file:'" . esc_url(plugin_dir_url(__DIR__))  . "includes/jwppp-chapters.php?id=" . $p_id . "&number=$number',\n";
							    if($jwppp_chapters_subtitles === 'chapters') {
								    $output .= "kind:'chapters'\n";						    	
							    } else if($jwppp_chapters_subtitles === 'subtitles') {
								    $output .= "kind:'captions',\n";		
								    if($jwppp_subtitles_write_default === '1') {
								    	$output .= "'default': 'true'\n";
								    }				    	
							    } else {
							    	$output .= "kind:'thumbnails'\n";
							    }
								$output .= "}\n";
							}

			      			$output .= "],\n";

			      		}

			      		if($jwppp_new_playlist) {
				      		$output .= "},\n";
				      	}
				    }

				    if($jwppp_new_playlist) {
						$output .= "],\n";
					}
				}

				/*Playlist sharing*/
				if($jwppp_new_playlist && $active_share === '1') {
					$output .= "sharing: {\n";
						$jwppp_share_heading = sanitize_text_field(get_option('jwppp-share-heading'));
						if($jwppp_share_heading !== null) {
							$output .= "heading: '" . esc_html($jwppp_share_heading) . "',\n";
						} else {
							$output .= "heading: '" . esc_html(__('Share Video', 'jwppp')) . "',\n"; 
						}
						$output .= "sites: ['email','facebook','twitter','pinterest','tumblr','googleplus','reddit','linkedin'],\n";
					$output .= "},\n";
				}

				/*Shortcode options for playlists*/
				if($jwppp_new_playlist) {
					
					/*Autoplay*/
					if($pl_autostart === '1') {
				    	$output .= "autostart: 'true',\n";
				    }

				    /*Mute*/
				    if($pl_mute === '1') {
				    	$output .= "mute: 'true',\n";
				    }

				    /*Repeat*/
				    if($pl_repeat === '1') {
				    	$output .= "repeat: 'true',\n";
				    }
				}    
			   
			   	/*Options available only with self-hosted player*/
			   	if(!$dashboard_player) {
	
					$output .= jwppp_sh_player_option($ar, $width, $height);

			   	}

		$output .= "});\n";

		/*Check for a YouTube video*/
		$is_yt = jwppp_search_yt('', $number);

		/*Download button*/
		if($jwppp_download_video && !$jwppp_new_playlist && !$is_yt['yes']) {
			$output .= "playerInstance_$this_video.addButton(\n";
				$output .= "'" . esc_url(plugin_dir_url(__DIR__))  . "images/download-icon.svg',\n";
				$output .= "'Download Video',\n";
				$output .= "function() {\n";
					$output .= "var file = playerInstance_$this_video.getPlaylistItem()['file'];\n";
					$output .= "var file_download = '" . esc_url(plugin_dir_url(__DIR__))  . "includes/jwppp-video-download.php?file=' + file;\n";
					$output .= "window.location.href = file_download;\n";
				$output .= "},\n";
				$output .= "'download',\n";
			$output .=")\n";
		}

		if($is_yt['yes'] || $pl_mute === '1') {
			
			/*Volume off*/
			$output .= "playerInstance_$this_video.on('play', function(){\n";
				$output .= "var sound_off = playerInstance_$this_video.getMute();\n";
				$output .= "if(sound_off) {\n";
					$output .= "playerInstance_$this_video.setVolume(0);\n";
				$output .= "}\n";
			$output .= "})\n";
		
		}
		
		$output .= "</script>\n";

		$output .= "</div>"; //jwppp-video-box  

	}

	if(get_post_meta($p_id, '_jwppp-video-url-' . $number, true)) { return $output; }
}