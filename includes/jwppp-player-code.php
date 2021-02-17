<?php
/**
 * The player code block
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @since 2.1.1
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
		$contextual      = false !== strpos( $jwppp_video_url, '__CONTEXTUAL__' ) ? true : false;

		/*Is the video self hosted?*/
		$sh_video = strrpos( $jwppp_video_url, 'http' ) === 0 ? true : false;

		/*Playlist carousel*/
		$jwppp_playlist_carousel = get_post_meta( $p_id, '_jwppp-playlist-carousel-' . $number, true );
        $video_title             = get_post_meta( $p_id, '_jwppp-video-title-' . $number, true );
        $video_description       = get_post_meta( $p_id, '_jwppp-video-description-' . $number, true );

        /*Video image*/
        $video_image = jwppp_get_poster_image( $p_id, $jwppp_video_url );

        /*SEO items props*/
        $itemprop_title = $video_title ? $video_title : get_the_title( $p_id );
        $itemprop_image = $video_image ? $video_image : get_the_post_thumbnail_url( $p_id );

        $itemprop_description = null;

        if( $video_description ) {
            $itemprop_description = $video_description;
        } elseif( has_excerpt( $p_id ) ) {
            $itemprop_description = get_the_excerpt( $p_id );
        }

		if ( ! $dashboard_player || $sh_video ) {

			$jwppp_activate_media_type = get_post_meta( $p_id, '_jwppp-activate-media-type-' . $number, true );
			$jwppp_media_type = get_post_meta( $p_id, '_jwppp-media-type-' . $number, true );

			/*Embed option*/
			$jwppp_embed_video = sanitize_text_field( get_option( 'jwppp-embed-video' ) );
			$jwppp_single_embed = $jwppp_embed_video;

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

	if ( $dashboard_player && ! $sh_video && $jwppp_video_url ) {

		/*Video*/
		$self_content  = strpos( $jwppp_video_url, 'http' );

		/*Output the player*/
		echo "<div id='jwppp-video-box-" . intval( $this_video ) . "' class='jwppp-video-box' itemscope itemtype='http://schema.org/VideoObject' data-video='" . esc_attr( $n ) . "' style=\"margin: 1rem 0;\">\n";

            $video_duration = get_duration_iso_format( get_post_meta( $p_id, '_jwppp-video-duration-' . $number, true ) ); 

            echo $itemprop_title ? "<meta itemprop='name' content='" . esc_attr( $itemprop_title ) . "'/>\n" : null;
            echo $itemprop_description ? "<meta itemprop='description' content='" .  esc_attr( $itemprop_description ) . "'/>\n" : null;
            echo $video_duration ? "<meta itemprop='duration' content='" .  esc_attr( $video_duration ) . "'/>\n" : null;
            echo $itemprop_image ? "<meta itemprop='thumbnailUrl' content='" . esc_attr( $itemprop_image ) . "'/>\n" : null;
            echo "<meta itemprop='uploadDate' content='" .  esc_attr( get_the_date( 'c', $p_id ) ) . "'/>\n";
            echo "<meta itemprop='contentUrl' content='" . esc_attr( 'https://cdn.jwplayer.com/v2/media/' . $jwppp_video_url ) . "'/>\n";

			echo "<div id='jwppp-video-" . intval( $this_video ) . "' class='jwplayer'>";

				/*Loading the player text*/
				echo esc_html( __( 'Loading the player...', 'jwppp' ) );
				
			echo "</div>\n";

			echo "<script type='text/javascript'>\n";
				echo "var playerInstance_" . intval( $this_video ) . " = jwplayer( " . wp_json_encode( "jwppp-video-" . $this_video ) . " );\n";
				echo "playerInstance_" . intval( $this_video ) . ".setup({\n";

				echo "playlist: " . wp_json_encode( "https://cdn.jwplayer.com/v2/media/" . $jwppp_video_url, JSON_UNESCAPED_SLASHES ) . ",\n";
	
				echo "})\n";
			echo '</script>';
		echo '</div>';

	} elseif ( $jwppp_video_url && $sh_video ) {

		echo "<div id='jwppp-video-box-" . intval( $this_video ) . "' class='jwppp-video-box' itemscope itemtype='http://schema.org/VideoObject' data-video='" . esc_attr( $n ) . "' style=\"margin: 1rem 0;\">\n";

            echo $itemprop_title ? "<meta itemprop='name' content='" . esc_attr( $itemprop_title ) . "'/>\n" : null;
            echo $itemprop_description ? "<meta itemprop='description' content='" .  esc_attr( $itemprop_description ) . "'/>\n" : null;
            echo $itemprop_image ? "<meta itemprop='thumbnailUrl' content='" . esc_attr( $itemprop_image ) . "'/>\n" : null;
            echo "<meta itemprop='uploadDate' content='" .  esc_attr( get_the_date( 'c', $p_id ) ) . "'/>\n";
            echo "<meta itemprop='contentUrl' content='" . esc_attr( 'https://cdn.jwplayer.com/v2/media/' . $jwppp_video_url ) . "'/>\n";

			echo "<div id='jwppp-video-" . intval( $this_video ) . "' class='jwplayer'>";



				/*Loading the player text*/
				echo esc_html( __( 'Loading the player...', 'jwppp' ) );
				
			echo "</div>\n";

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
						$jwppp_sources_number = 1;
						$jwppp_source_1 = get_post_meta( $p_id, '_jwppp-' . $number . '-source-1-url', true );
						$video_title = get_post_meta( $p_id, '_jwppp-video-title-' . $number, true );
						$video_description = get_post_meta( $p_id, '_jwppp-video-description-' . $number, true );
						$jwppp_activate_media_type = get_post_meta( $p_id, '_jwppp-activate-media-type-' . $number, true );
						$jwppp_media_type = get_post_meta( $p_id, '_jwppp-media-type-' . $number, true );
						$jwppp_single_embed = $jwppp_embed_video;

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

						if ( $jwppp_source_1 ) {
							echo "},\n";
						}

						if ( $jwppp_source_1 ) {
							for ( $i = 1; $i < $jwppp_sources_number + 1; $i++ ) {
								$source_url = get_post_meta( $p_id, '_jwppp-' . $number . '-source-' . $i . '-url', true );
								if ( $source_url ) {
									echo "{\n";
									echo "file: " . wp_json_encode( $source_url, JSON_UNESCAPED_SLASHES ) . ",\n";
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
						if ( has_post_thumbnail( $p_id ) && 1 === intval( get_option( 'jwppp-post-thumbnail' ) ) ) {
							echo "image: " . wp_json_encode( get_the_post_thumbnail_url(), JSON_UNESCAPED_SLASHES ) . ",\n";
						} else if ( $youtube['yes'] ) {
							echo "image: " . wp_json_encode( $yt_video_image, JSON_UNESCAPED_SLASHES ) . ",\n";
						} else if ( get_option( 'jwppp-poster-image' ) ) {
							echo "image: " . wp_json_encode( get_option( 'jwppp-poster-image' ), JSON_UNESCAPED_SLASHES ) . ",\n";
						}

						if ( $jwppp_new_playlist ) {
							echo "mediaid: '" . wp_json_encode( $this_video . $n++ ) . "',\n";
						}

						/*Media type*/
						if ( $jwppp_media_type ) {
							echo "type: " . wp_json_encode( $jwppp_media_type ) . ",\n";
						}

						/*Aoogle Analytics*/
						echo "ga: {},\n";

						/*Share*/
						$active_share = sanitize_text_field( get_option( 'jwppp-active-share' ) );
						if ( ! $jwppp_new_playlist && 1 === intval( $active_share ) ) {
							echo "sharing: {\n";
								$jwppp_share_heading = sanitize_text_field( get_option( 'jwppp-share-heading' ) );
								if ( null !== $jwppp_share_heading ) {
									echo "heading: " . wp_json_encode( $jwppp_share_heading ) . ",\n";
								} else {
									echo "heading: '" . esc_html( __( 'Share Video', 'jwppp' ) ) . "',\n";
								}
								echo "sites: ['email','facebook','twitter','pinterest','tumblr','googleplus','reddit','linkedin'],\n";
								if ( $jwppp_embed_video && ! $jwppp_playlist ) {
									echo "code: " . wp_json_encode( "<iframe src='$jwppp_embed_url'  width='640'  height='360'  frameborder='0'  scrolling='auto'></iframe>", JSON_UNESCAPED_SLASHES ) . "\n";
								}
							echo "},\n";
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
				if ( $jwppp_new_playlist && 1 === intval( $active_share ) ) {
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

				/*Options available only with self-hosted player*/
				if ( ! $dashboard_player ) {

					echo esc_html( jwppp_sh_player_option( $ar, $width, $height ) );

				}

			echo "});\n";

			/*Check for a YouTube video*/
			$is_yt = jwppp_search_yt( '', $number );

			if ( $is_yt['yes'] || 1 === intval( $pl_mute ) ) {

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
