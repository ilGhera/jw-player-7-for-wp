<?php
/**
 * The player code block
 * @author ilGhera
 * @package jw-player-for-vip/includes
* @version 2.0.0
 * @param  int    $p            the post id
 * @param  int    $n            the number of video
 * @param  string $ar           the aspect ratio
 * @param  int    $width        the with of the player
 * @param  int    $height       the height of the player
 * @param  int    $pl_autostart the autostart option for playlists
 * @param  int    $pl_mute      the mute option for playlists
 * @param  int    $pl_repeat    the repeat option for playlists
 * @return string               the block of code
 */
function jwppp_player_code( $p, $n, $ar, $width, $height, $pl_autostart, $pl_mute, $pl_repeat ) {

	/*Is it a dashboard player?*/
	$dashboard_player = is_dashboard_player();

	/*Default dashboard player informations*/
	if ( $dashboard_player ) {
		$get_library = get_option( 'jwppp-library' );
		if ( strpos( $get_library, 'jwplatform.com' ) !== false ) {
			$library_parts = explode( 'https://content.jwplatform.com/libraries/', $get_library );
		} else {
			$library_parts = explode( 'https://cdn.jwplayer.com/libraries/', $get_library );
		}
		$player_parts = explode( '.js', $library_parts[1] );
	}

	/*Ads var block*/
	$active_ads_var = sanitize_text_field( get_option( 'jwppp-active-ads-var' ) );
	if ( $active_ads_var ) {
		$ads_var_name = sanitize_text_field( get_option( 'jwppp-ads-var-name' ) );
		$ajaxurl = admin_url( 'admin-ajax.php' );
		?>
		<script>
			jQuery(document).ready(function($){
				var ajaxurl = '<?php echo wp_json_encode( $ajaxurl, JSON_UNESCAPED_SLASHES ); ?>';
				var tag = null;

				if(typeof <?php echo trim( wp_json_encode( $ads_var_name ), '"' ); ?> !== 'undefined') {
					tag = <?php echo trim( wp_json_encode( $ads_var_name ), '"' ); ?>;
				}
				var data = {
					'action': 'ads-var',
					'tag': JSON.stringify( tag )
				}
				$.post(ajaxurl, data, function(response){
				})
			})
		</script>
		<?php
	}

	/*Get the id of post/ page*/
	if ( $p ) {
		$p_id = $p;
	} else {
		$p_id = get_the_ID();
	}

	/*Get the number of every single video*/
	$videos = explode( ',', $n );
	$jwppp_new_playlist = ( count( $videos ) > 1 ) ? true : false;

	foreach ( $videos as $number ) {

		/*Video url or media id*/
		$jwppp_video_url = get_post_meta( $p_id, '_jwppp-video-url-' . $number, true );
		$contextual = false !== strpos( $jwppp_video_url, '__CONTEXTUAL__' ) ? true : false;

		/*Is the video self hosted?*/
		$sh_video = strrpos( $jwppp_video_url, 'http' ) === 0 ? true : false;

		/*Playlist carousel*/
		$jwppp_playlist_carousel = get_post_meta( $p_id, '_jwppp-playlist-carousel-' . $number, true );

		if ( ! $dashboard_player || $sh_video ) {

			$video_image = get_post_meta( $p_id, '_jwppp-video-image-' . $number, true );
			$video_title = get_post_meta( $p_id, '_jwppp-video-title-' . $number, true );
			$video_description = get_post_meta( $p_id, '_jwppp-video-description-' . $number, true );
			$jwppp_activate_media_type = get_post_meta( $p_id, '_jwppp-activate-media-type-' . $number, true );
			$jwppp_media_type = get_post_meta( $p_id, '_jwppp-media-type-' . $number, true );
			$jwppp_autoplay = get_post_meta( $p_id, '_jwppp-autoplay-' . $number, true );
			$jwppp_mute = get_post_meta( $p_id, '_jwppp-mute-' . $number, true );
			$jwppp_repeat = get_post_meta( $p_id, '_jwppp-repeat-' . $number, true );

			/*Embed option*/
			$jwppp_embed_video = sanitize_text_field( get_option( 'jwppp-embed-video' ) );
			$jwppp_single_embed = get_post_meta( $p_id, '_jwppp-single-embed-' . $number, true );
			if ( ! $jwppp_single_embed ) {
				$jwppp_single_embed = $jwppp_embed_video;
			}

			$jwppp_add_chapters = get_post_meta( $p_id, '_jwppp-add-chapters-' . $number, true );
			$jwppp_chapters_subtitles = get_post_meta( $p_id, '_jwppp-chapters-subtitles-' . $number, true );
			$jwppp_chapters_number = get_post_meta( $p_id, '_jwppp-chapters-number-' . $number, true );
			$jwppp_subtitles_method = get_post_meta( $p_id, '_jwppp-subtitles-method-' . $number, true );
			$jwppp_subtitles_load_default = get_post_meta( $p_id, '_jwppp-subtitles-load-default-' . $number, true );
			$jwppp_subtitles_write_default = get_post_meta( $p_id, '_jwppp-subtitles-write-default-' . $number, true );

		}
	}

	/*Check for playlist*/
	$file_info = pathinfo( $jwppp_video_url );
	$jwppp_playlist = false;
	if ( array_key_exists( 'extension', $file_info ) ) {
		if ( in_array( $file_info['extension'], array( 'xml', 'feed', 'php', 'rss' ), true ) ) {
			$jwppp_playlist = true;
		}
	}

	/*Specific video element defined by his number and the post id*/
	$this_video = $p_id . $number;

	/*Check if the security option is activated*/
	$security_embeds = get_option( 'jwppp-secure-player-embeds' );

	if ( $dashboard_player && ! $sh_video && $jwppp_video_url ) {

		/*Video*/
		$self_content = strpos( $jwppp_video_url, 'http' );

		/*Choose player*/
		$choose_player = get_post_meta( $p_id, '_jwppp-choose-player-' . $number, true ) ? get_post_meta( $p_id, '_jwppp-choose-player-' . $number, true ) : $player_parts[0];

		/*Output the player*/
		echo "<div id='jwppp-video-box-" . intval( $this_video ) . "' class='jwppp-video-box' data-video='" . esc_attr( $n ) . "' style=\"margin: 1rem 0;\">\n";

			/*FB Instant Articles*/
			echo '<span class="jwppp-instant" style="display: none;" data-video="' . esc_url( home_url() . "/?jwp-instant-articles=1&player=$choose_player&mediaID=$jwppp_video_url" ) . '" data-width="480" data-height="270"></span>';

			echo "<div id='jwppp-video-" . intval( $this_video ) . "' class='jwplayer'>";

				/*Loading the player text*/
				if ( get_option( 'jwppp-text' ) ) {
					echo esc_html( get_option( 'jwppp-text' ) );
				} else {
					echo esc_html( __( 'Loading the player...', 'jwppp' ) );
				}
			echo "</div>\n";

			/*Playlist carousel*/
			$jwppp_playlist_carousel ? jwppp_playlist_carousel( $this_video ) : '';

			/*Player choose - library*/
			if ( $security_embeds ) {
				echo '<script type="text/javascript" src="' . esc_url( jwppp_get_signed_embed( $choose_player ) ) . '"></script>';
			} else {
				echo '<script type="text/javascript" src="' . esc_url( "https://content.jwplatform.com/libraries/$choose_player.js" ) . '"></script>';
			}

			echo "<script type='text/javascript'>\n";
				echo "var playerInstance_" . intval( $this_video ) . " = jwplayer( " . wp_json_encode( "jwppp-video-" . $this_video ) . " );\n";
				echo "playerInstance_" . intval( $this_video ) . ".setup({\n";

					/*Check if the security option is activated*/
					$security_urls = get_option( 'jwppp-secure-video-urls' );

				if ( $security_urls ) {
					echo "playlist: " . wp_json_encode( jwppp_get_signed_url( $jwppp_video_url, false, $contextual ), JSON_UNESCAPED_SLASHES ) . ",\n";
				} else {
					if ( $contextual ) {
						echo "playlist: " . wp_json_encode( "https://cdn.jwplayer.com/v2/playlists/" . $jwppp_video_url, JSON_UNESCAPED_SLASHES ) . ",\n";
					} else {
						echo "playlist: " . wp_json_encode( "https://cdn.jwplayer.com/v2/media/" . $jwppp_video_url, JSON_UNESCAPED_SLASHES ) . ",\n";
					}
				}
	
				/*Ads block*/
				jwppp_ads_code_block( $p_id, $number );

				echo "})\n";

				if ( $jwppp_playlist_carousel ) {
					$carousel_style = sanitize_text_field( get_option( 'jwppp-playlist-carousel-style' ) );
					$playlist_id = $security_urls ? jwppp_get_signed_url( $jwppp_video_url, true, $contextual ) : $jwppp_video_url;

					echo "var e = new XMLHttpRequest;\n";
					echo "e.onreadystatechange = function() { 4 === e.readyState && (e.status >= 200 && JSON.parse(e.responseText).widgets.forEach(function(e) { outPlayerWidget(e) })) }, e.open('GET', '" . home_url() . "?jwp-carousel-config=1&playlist-id=' + encodeURIComponent('$playlist_id') + '&player-id=" . intval( $this_video ) . "&carousel-style=' + encodeURIComponent( '$carousel_style' ), !0), e.send()\n";
				}
			echo '</script>';
		echo '</div>';

	} elseif ( $jwppp_video_url && $sh_video ) {

		echo "<div id='jwppp-video-box-" . intval( $this_video ) . "' class='jwppp-video-box' data-video='" . esc_attr( $n ) . "' style=\"margin: 1rem 0;\">\n";

			/*Player definition*/
			if ( $dashboard_player ) {
				/*Choose player*/
				$choose_player = get_post_meta( $p_id, '_jwppp-choose-player-' . $number, true ) ? get_post_meta( $p_id, '_jwppp-choose-player-' . $number, true ) : $player_parts[0];
				/*The player used for Facebook Instant Articles*/
				$instant_player = 'player=' . $choose_player;
			} else {
				/*The player used for Facebook Instant Articles*/
				$instant_player = 'player_url=' . get_option( 'jwppp-library' );
			}

			/*FB Instant Articles*/
			$instant_image = $video_image ? $video_image : get_option( 'jwppp-poster-image' );
			echo '<span class="jwppp-instant" style="display: none;" data-video="' . esc_url( home_url() . "/?jwp-instant-articles=1&$instant_player&mediaURL=$jwppp_video_url&image=$instant_image" ) . '" data-width="480" data-height="270"></span>';

			echo "<div id='jwppp-video-" . intval( $this_video ) . "' class='jwplayer'>";

				/*Loading the player text*/
				if ( get_option( 'jwppp-text' ) ) {
					echo esc_html( get_option( 'jwppp-text' ) );
				} else {
					echo esc_html( __( 'Loading the player...', 'jwppp' ) );
				}

			echo "</div>\n";

			if ( $dashboard_player ) {
				/*Player choose - library*/
				if ( $security_embeds ) {
					echo '<script type="text/javascript" src="' . esc_url( jwppp_get_signed_embed( $choose_player ) ) . '"></script>';
				} else {
					echo '<script type="text/javascript" src="' . esc_url( "https://content.jwplatform.com/libraries/$choose_player.js" ) . '"></script>';
				}
			}

			echo "<script type='text/javascript'>\n";
				echo "var playerInstance_" . intval( $this_video ) . " = jwplayer(" . wp_json_encode( "jwppp-video-" . $this_video ) . " );\n";
				echo 'playerInstance_' . intval( $this_video ) . ".setup({\n";
				if ( $jwppp_playlist ) {
					echo "playlist: " . wp_json_encode( get_post_meta( $p_id, '_jwppp-video-url-' . $number, true ), JSON_UNESCAPED_SLASHES ) . ",\n";
				} else {
					if ( $jwppp_new_playlist ) {
						$n = 0;
						echo "playlist: [\n";
					}
					foreach ( $videos as $number ) {

						$jwppp_video_url = get_post_meta( $p_id, '_jwppp-video-url-' . $number, true );
						$jwppp_sources_number = get_post_meta( $p_id, '_jwppp-sources-number-' . $number );
						$jwppp_source_1 = get_post_meta( $p_id, '_jwppp-' . $number . '-source-1-url', true );
						$video_image = get_post_meta( $p_id, '_jwppp-video-image-' . $number, true );
						$video_title = get_post_meta( $p_id, '_jwppp-video-title-' . $number, true );
						$video_description = get_post_meta( $p_id, '_jwppp-video-description-' . $number, true );
						$jwppp_activate_media_type = get_post_meta( $p_id, '_jwppp-activate-media-type-' . $number, true );
						$jwppp_media_type = get_post_meta( $p_id, '_jwppp-media-type-' . $number, true );
						$jwppp_autoplay = get_post_meta( $p_id, '_jwppp-autoplay-' . $number, true );
						$jwppp_mute = get_post_meta( $p_id, '_jwppp-mute-' . $number, true );
						$jwppp_repeat = get_post_meta( $p_id, '_jwppp-repeat-' . $number, true );
						$jwppp_single_embed = get_post_meta( $p_id, '_jwppp-single-embed-' . $number, true );
						$jwppp_single_embed = $jwppp_single_embed ? $jwppp_single_embed : $jwppp_embed_video;
						$jwppp_add_chapters = get_post_meta( $p_id, '_jwppp-add-chapters-' . $number, true );
						$jwppp_chapters_number = get_post_meta( $p_id, '_jwppp-chapters-number-' . $number, true );
						$jwppp_chapters_subtitles = get_post_meta( $p_id, '_jwppp-chapters-subtitles-' . $number, true );
						$jwppp_subtitles_method = get_post_meta( $p_id, '_jwppp-subtitles-method-' . $number, true );
						$jwppp_subtitles_load_default = get_post_meta( $p_id, '_jwppp-subtitles-load-default-' . $number, true );
						$jwppp_subtitles_write_default = get_post_meta( $p_id, '_jwppp-subtitles-write-default-' . $number, true );

						/*Check for a yt video*/
						$youtube = jwppp_search_yt( $jwppp_video_url );
						$jwppp_embed_url = $youtube['embed-url'];
						$yt_video_image  = $youtube['video-image'];

						if ( $jwppp_new_playlist ) {
							echo "{\n";
						}

						/*Video sources*/
						if ( $jwppp_source_1 ) {
							echo "sources: [\n";
							echo "{\n";
						}

						echo "file: " . wp_json_encode( $jwppp_video_url, JSON_UNESCAPED_SLASHES ) . ",\n";
						if ( $jwppp_sources_number && $jwppp_sources_number[0] > 1 ) {
							$main_source_label = get_post_meta( $p_id, '_jwppp-' . $number . '-main-source-label', true );
							echo ( $main_source_label ) ? "label: '" . wp_json_encode( $main_source_label ) . "'\n" : '';
						}

						if ( $jwppp_source_1 ) {
							echo "},\n";
						}

						if ( $jwppp_source_1 ) {
							for ( $i = 1; $i < $jwppp_sources_number[0] + 1; $i++ ) {
								$source_url = get_post_meta( $p_id, '_jwppp-' . $number . '-source-' . $i . '-url', true );
								$source_label = get_post_meta( $p_id, '_jwppp-' . $number . '-source-' . $i . '-label', true );
								if ( $source_url ) {
									echo "{\n";
									echo "file: " . wp_json_encode( $source_url, JSON_UNESCAPED_SLASHES ) . ",\n";
									echo ( $source_label ) ? "label: '" . wp_json_encode( $source_label ) . "'\n" : '';
									echo "},\n";
								}
							}
						}

						if ( $jwppp_source_1 ) {
							echo "],\n";
						}

						/*Video title*/
						if ( $video_title ) {
							echo "title: " . wp_json_encode( $video_title ) . ",\n";
						}

						/*Video description*/
						if ( $video_description ) {
							echo "description: " . wp_json_encode( $video_description ) . ",\n";
						}

						/*Poster image*/
						if ( $video_image ) {
							echo "image: " . wp_json_encode( $video_image, JSON_UNESCAPED_SLASHES ) . ",\n";
						} else if ( has_post_thumbnail( $p_id ) && get_option( 'jwppp-post-thumbnail' ) === '1' ) {
							echo "image: " . wp_json_encode( get_the_post_thumbnail_url(), JSON_UNESCAPED_SLASHES ) . ",\n";
						} else if ( $youtube['yes'] ) {
							echo "image: " . wp_json_encode( $yt_video_image, JSON_UNESCAPED_SLASHES ) . ",\n";
						} else if ( get_option( 'jwppp-poster-image' ) ) {
							echo "image: " . wp_json_encode( get_option( 'jwppp-poster-image' ), JSON_UNESCAPED_SLASHES ) . ",\n";
						}

						if ( $jwppp_new_playlist ) {
							echo "mediaid: '" . wp_json_encode( $this_video . $n++ ) . "',\n";
						}

						/*Ads code block*/
						jwppp_ads_code_block( $p_id, $number );

						/*Media type*/
						if ( $jwppp_media_type ) {
							echo "type: " . wp_json_encode( $jwppp_media_type ) . ",\n";
						}

						/*Autoplay*/
						if ( ! $jwppp_new_playlist && '1' === $jwppp_autoplay ) {
							echo "autostart: 'true',\n";
						}

						/*Mute*/
						if ( ! $jwppp_new_playlist && '1' === $jwppp_mute ) {
							echo "mute: 'true',\n";
						}

						/*Mepeat*/
						if ( ! $jwppp_new_playlist && '1' === $jwppp_repeat ) {
							echo "repeat: 'true',\n";
						}

						/*Aoogle Analytics*/
						echo "ga: {},\n";

						/*Share*/
						$active_share = sanitize_text_field( get_option( 'jwppp-active-share' ) );
						if ( ! $jwppp_new_playlist && '1' === $active_share ) {
							echo "sharing: {\n";
								$jwppp_share_heading = sanitize_text_field( get_option( 'jwppp-share-heading' ) );
								if ( null !== $jwppp_share_heading ) {
									echo "heading: " . wp_json_encode( $jwppp_share_heading ) . ",\n";
								} else {
									echo "heading: '" . esc_html( __( 'Share Video', 'jwppp' ) ) . "',\n";
								}
								echo "sites: ['email','facebook','twitter','pinterest','tumblr','googleplus','reddit','linkedin'],\n";
								if ( ( $jwppp_embed_video || '1' === $jwppp_single_embed ) && ! $jwppp_playlist ) {
									echo "code: " . wp_json_encode( "<iframe src='$jwppp_embed_url'  width='640'  height='360'  frameborder='0'  scrolling='auto'></iframe>", JSON_UNESCAPED_SLASHES ) . "\n";
								}
							echo "},\n";
						}

						/*Add chapters/ subtitles*/
						if ( '1' === $jwppp_add_chapters ) {
							echo "tracks:[\n";

							if ( 'subtitles' === $jwppp_chapters_subtitles && 'load' === $jwppp_subtitles_method ) {
								for ( $i = 1; $i < $jwppp_chapters_number + 1; $i++ ) {
									echo "{\n";
									echo "file:" . wp_json_encode( get_post_meta( $p_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url', true ), JSON_UNESCAPED_SLASHES ) . ",\n";
									echo "kind:'captions',\n";
									echo "label:'" . wp_json_encode( get_post_meta( $p_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label', true ) ) . "',\n";
									if ( '1' === $i && '1' === $jwppp_subtitles_load_default ) {
										echo "'default': 'true'\n";
									}
									echo "},\n";
								}
							} else {
								echo "{\n";
								echo "file:" . wp_json_encode( home_url() . '?jwp-chapters=1&id=' . $p_id . '&number=' . $number, JSON_UNESCAPED_SLASHES ) . ",\n";
								if ( 'chapters' === $jwppp_chapters_subtitles ) {
									echo "kind:'chapters'\n";
								} else if ( 'subtitles' === $jwppp_chapters_subtitles ) {
									echo "kind:'captions',\n";
									if ( '1' === $jwppp_subtitles_write_default ) {
										echo "'default': 'true'\n";
									}
								} else {
									echo "kind:'thumbnails'\n";
								}
								echo "}\n";
							}

							echo "],\n";

						}

						if ( $jwppp_new_playlist ) {
							echo "},\n";
						}
					}

					if ( $jwppp_new_playlist ) {
						echo "],\n";
					}
				}

				/*Playlist sharing*/
				if ( $jwppp_new_playlist && '1' === $active_share ) {
					echo "sharing: {\n";
						$jwppp_share_heading = sanitize_text_field( get_option( 'jwppp-share-heading' ) );
						if ( null !== $jwppp_share_heading ) {
							echo "heading: " . wp_json_encode( $jwppp_share_heading ) . ",\n";
						} else {
							echo "heading: '" . esc_html( __( 'Share Video', 'jwppp' ) ) . "',\n";
						}
						echo "sites: ['email','facebook','twitter','pinterest','tumblr','googleplus','reddit','linkedin'],\n";
					echo "},\n";
				}

				/*Shortcode options for playlists*/
				if ( $jwppp_new_playlist ) {

					/*Autoplay*/
					if ( '1' === $pl_autostart ) {
						echo "autostart: 'true',\n";
					}

					/*Mute*/
					if ( '1' === $pl_mute ) {
						echo "mute: 'true',\n";
					}

					/*Repeat*/
					if ( '1' === $pl_repeat ) {
						echo "repeat: 'true',\n";
					}
				}

						/*Options available only with self-hosted player*/
				if ( ! $dashboard_player ) {

					echo esc_html( jwppp_sh_player_option( $ar, $width, $height ) );

				}

			echo "});\n";

			/*Check for a YouTube video*/
			$is_yt = jwppp_search_yt( '', $number );

			if ( $is_yt['yes'] || '1' === $pl_mute ) {

				/*Volume off*/
				echo 'playerInstance_' . intval( $this_video ) . ".on('play', function(){\n";
					echo 'var sound_off = playerInstance_' . intval( $this_video ) . ".getMute();\n";
					echo "if(sound_off) {\n";
						echo 'playerInstance_' . intval( $this_video ) . ".setVolume(0);\n";
					echo "}\n";
				echo "})\n";

			}

			echo "</script>\n";

		echo '</div>'; //jwppp-video-box

	}

}
