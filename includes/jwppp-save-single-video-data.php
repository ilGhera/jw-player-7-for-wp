<?php
/**
 * Save all informations of the single video
 * @author ilGhera
 * @package jw-player-for-vip/includes
* @version 2.0.0
 * @param  int $post_id
 */
function jwppp_save_single_video_data( $post_id ) {

	/*Is it a Dashboard player?*/
	$dashboard_player = is_dashboard_player();

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$jwppp_videos = jwppp_get_post_videos( $post_id );

	if ( empty( $jwppp_videos ) ) {
		$jwppp_videos = array(
			'_jwppp-video-url-1' => 1,
		);
	}

	foreach ( $jwppp_videos as $key => $value ) {

		$jwppp_number = explode( '_jwppp-video-url-', $key );
		$number = $jwppp_number[1];

		if ( ! isset( $_POST[ 'hidden-meta-box-nonce-' . $number ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST[ 'hidden-meta-box-nonce-' . $number ], 'jwppp-meta-box-nonce-' . $number ) ) {
			return;
		}

		/*All video post_meta are saved only if the video url is set*/
		if ( isset( $_POST[ '_jwppp-video-url-' . $number ] ) && '' !== $_POST[ '_jwppp-video-url-' . $number ] ) {

			/*Video url*/
			$video = sanitize_text_field( wp_unslash( $_POST[ '_jwppp-video-url-' . $number ] ) );
			if ( ! $video ) {
				delete_post_meta( $post_id, '_jwppp-video-url-' . $number );
			} else {
				update_post_meta( $post_id, '_jwppp-video-url-' . $number, $video );
			}

			/*Video sources*/
			if ( isset( $_POST[ '_jwppp-sources-number-' . $number ] ) ) {

				$sources = sanitize_text_field( wp_unslash( $_POST[ '_jwppp-sources-number-' . $number ] ) );

				for ( $i = 1; $i <= $sources; $i++ ) {
					$source_url = isset( $_POST[ '_jwppp-' . $number . '-source-' . $i . '-url' ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-' . $number . '-source-' . $i . '-url' ] ) ) : '';
					if ( ! $source_url ) {
						delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-url' );
					} else {
						update_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-url', $source_url );
					}

					$source_label = isset( $_POST[ '_jwppp-' . $number . '-source-' . $i . '-label' ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-' . $number . '-source-' . $i . '-label' ] ) ) : '';
					if ( ! $source_label ) {
						delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-label' );
					} else {
						update_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-label', $source_label );
					}
				}

				update_post_meta( $post_id, '_jwppp-sources-number-' . $number, $sources );

			} else {

				$sources = get_post_meta( $post_id, '_jwppp-sources-number-' . $number, true );
				if ( $sources ) {
					for ( $i = 1; $i <= $sources; $i++ ) {
						delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-url' );
						delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-label' );
					}
				}
				delete_post_meta( $post_id, '_jwppp-sources-number-' . $number );
			}

			if ( isset( $_POST[ '_jwppp-' . $number . '-main-source-label' ] ) ) {
				$label = sanitize_text_field( wp_unslash( $_POST[ '_jwppp-' . $number . '-main-source-label' ] ) );
				if ( ! $label ) {
					delete_post_meta( $post_id, '_jwppp-' . $number . '-main-source-label' );
				} else {
					update_post_meta( $post_id, '_jwppp-' . $number . '-main-source-label', $label );
				}
			} else {
				delete_post_meta( $post_id, '_jwppp-' . $number . '-main-source-label' );
			}

			/*Video image*/
			if ( isset( $_POST[ '_jwppp-video-image-' . $number ] ) ) {
				$image = sanitize_text_field( wp_unslash( $_POST[ '_jwppp-video-image-' . $number ] ) );
				if ( ! $image ) {
					delete_post_meta( $post_id, '_jwppp-video-image-' . $number );
				} else {
					update_post_meta( $post_id, '_jwppp-video-image-' . $number, $image );
				}
			} else {
				delete_post_meta( $post_id, '_jwppp-video-image-' . $number );
			}

			/*Video title*/
			if ( isset( $_POST[ '_jwppp-video-title-' . $number ] ) ) {
				$title = sanitize_text_field( wp_unslash( $_POST[ '_jwppp-video-title-' . $number ] ) );
				if ( ! $title ) {
					delete_post_meta( $post_id, '_jwppp-video-title-' . $number );
				} else {
					update_post_meta( $post_id, '_jwppp-video-title-' . $number, $title );
				}
			} else {
				delete_post_meta( $post_id, '_jwppp-video-title-' . $number );
			}

			/*Video description*/
			if ( isset( $_POST[ '_jwppp-video-description-' . $number ] ) ) {
				$description = sanitize_text_field( wp_unslash( $_POST[ '_jwppp-video-description-' . $number ] ) );
				if ( ! $description ) {
					delete_post_meta( $post_id, '_jwppp-video-description-' . $number );
				} else {
					update_post_meta( $post_id, '_jwppp-video-description-' . $number, $description );
				}
			} else {
				delete_post_meta( $post_id, '_jwppp-video-description-' . $number );
			}

			/*Media type*/
			$jwppp_activate_media_type = null;
			if ( isset( $_POST[ 'activate-media-type-hidden-' . $number ] ) ) {
				$jwppp_activate_media_type = isset( $_POST[ '_jwppp-activate-media-type-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-activate-media-type-' . $number ] ) ) : 0;
				update_post_meta( $post_id, '_jwppp-activate-media-type-' . $number, $jwppp_activate_media_type );
			} else {
				delete_post_meta( $post_id, '_jwppp-activate-media-type-' . $number );
			}

			if ( '1' === $jwppp_activate_media_type ) {
				$media_type = isset( $_POST[ '_jwppp-media-type-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-media-type-' . $number ] ) ) : '';
				update_post_meta( $post_id, '_jwppp-media-type-' . $number, $media_type );
			} else {
				delete_post_meta( $post_id, '_jwppp-media-type-' . $number );
			}

			/*Ads tag*/
			if ( isset( $_POST[ '_jwppp-ads-tag-' . $number ] ) ) {
				$jwppp_ads_tag = esc_url_raw( wp_unslash( $_POST[ '_jwppp-ads-tag-' . $number ] ) );
				$jwppp_ads_tag = strpos( $jwppp_ads_tag, 'no-ads' ) !== false ? 'no-ads' : $jwppp_ads_tag;
				update_post_meta( $post_id, '_jwppp-ads-tag-' . $number, $jwppp_ads_tag );
			}

			/*Options based on the player in use*/
			if ( $dashboard_player ) {

				/*Choose player*/
				if ( isset( $_POST[ '_jwppp-choose-player-' . $number ] ) ) {
					$jwppp_choose_player = sanitize_text_field( wp_unslash( $_POST[ '_jwppp-choose-player-' . $number ] ) );
					update_post_meta( $post_id, '_jwppp-choose-player-' . $number, $jwppp_choose_player );
				}

				/*Playlist carousel*/
				if ( isset( $_POST[ 'playlist-carousel-hidden-' . $number ] ) ) {
					$jwppp_playlist_carousel = isset( $_POST[ '_jwppp-playlist-carousel-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-playlist-carousel-' . $number ] ) ) : 0;
					update_post_meta( $post_id, '_jwppp-playlist-carousel-' . $number, $jwppp_playlist_carousel );
				}

				/*Playlist items*/
				if ( isset( $_POST[ '_jwppp-playlist-items' . $number ] ) ) {
					$jwppp_playlist_items = isset( $_POST[ '_jwppp-playlist-items-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-playlist-items-' . $number ] ) ) : 0;
					update_post_meta( $post_id, '_jwppp-playlist-items-' . $number, $jwppp_playlist_items );
				}

				/*Video duration*/
				if ( isset( $_POST[ '_jwppp-video-duration-' . $number ] ) ) {
					$jwppp_video_duration = isset( $_POST[ '_jwppp-video-duration-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-video-duration-' . $number ] ) ) : 0;
					update_post_meta( $post_id, '_jwppp-video-duration-' . $number, $jwppp_video_duration );
				}

				/*Video tags*/
				if ( isset( $_POST[ '_jwppp-video-tags-' . $number ] ) ) {
					$jwppp_video_tags = isset( $_POST[ '_jwppp-video-tags-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-video-tags-' . $number ] ) ) : 0;
					update_post_meta( $post_id, '_jwppp-video-tags-' . $number, $jwppp_video_tags );
				}
			} else {

				/*Autoplay*/
				if ( isset( $_POST[ 'autoplay-hidden-' . $number ] ) ) {
					$jwppp_autoplay = isset( $_POST[ '_jwppp-autoplay-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-autoplay-' . $number ] ) ) : 0;
					update_post_meta( $post_id, '_jwppp-autoplay-' . $number, $jwppp_autoplay );
				}

				/*Mute*/
				if ( isset( $_POST[ 'mute-hidden-' . $number ] ) ) {
					$jwppp_mute = isset( $_POST[ '_jwppp-mute-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-mute-' . $number ] ) ) : 0;
					update_post_meta( $post_id, '_jwppp-mute-' . $number, $jwppp_mute );
				}

				/*Repeat*/
				if ( isset( $_POST[ 'repeat-hidden-' . $number ] ) ) {
					$jwppp_repeat = isset( $_POST[ '_jwppp-repeat-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-repeat-' . $number ] ) ) : 0;
					update_post_meta( $post_id, '_jwppp-repeat-' . $number, $jwppp_repeat );
				}

				/*Single embed*/
				if ( isset( $_POST[ 'single-embed-hidden-' . $number ] ) ) {
					$jwppp_single_embed = isset( $_POST[ '_jwppp-single-embed-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-single-embed-' . $number ] ) ) : 0;
					update_post_meta( $post_id, '_jwppp-single-embed-' . $number, $jwppp_single_embed );
				}

				/*Chapters/ Subtitles option*/
				$jwppp_add_chapters = null;
				if ( isset( $_POST[ 'add-chapters-hidden-' . $number ] ) ) {
					$jwppp_add_chapters = isset( $_POST[ '_jwppp-add-chapters-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-add-chapters-' . $number ] ) ) : 0;
					$jwppp_chapters_subtitles = isset( $_POST[ '_jwppp-chapters-subtitles-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-chapters-subtitles-' . $number ] ) ) : '';

					$jwppp_subtitles_method = ( 'subtitles' === $jwppp_chapters_subtitles && isset( $_POST[ '_jwppp-subtitles-method-' . $number ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-subtitles-method-' . $number ] ) ) : '';

					update_post_meta( $post_id, '_jwppp-add-chapters-' . $number, $jwppp_add_chapters );
					update_post_meta( $post_id, '_jwppp-chapters-subtitles-' . $number, $jwppp_chapters_subtitles );
					update_post_meta( $post_id, '_jwppp-subtitles-method-' . $number, $jwppp_subtitles_method );
				}

				/*Load subtitles*/
				if ( isset( $_POST[ 'subtitles-load-default-hidden-' . $number ] ) ) {
					$jwppp_subtitles_load_default = isset( $_POST[ '_jwppp-subtitles-load-default-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-subtitles-load-default-' . $number ] ) ) : 0;
					update_post_meta( $post_id, '_jwppp-subtitles-load-default-' . $number, $jwppp_subtitles_load_default );
				}

				/*Write subtitles*/
				if ( isset( $_POST[ 'subtitles-write-default-hidden-' . $number ] ) ) {
					$jwppp_subtitles_write_default = isset( $_POST[ '_jwppp-subtitles-write-default-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-subtitles-write-default-' . $number ] ) ) : 0;
					update_post_meta( $post_id, '_jwppp-subtitles-write-default-' . $number, $jwppp_subtitles_write_default );
				}

				if ( '1' === $jwppp_add_chapters ) {

					$chapters = isset( $_POST[ '_jwppp-chapters-number-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-chapters-number-' . $number ] ) ) : '';
					update_post_meta( $post_id, '_jwppp-chapters-number-' . $number, $chapters );

					for ( $i = 1; $i < $chapters + 1; $i++ ) {

						if ( 'subtitles' === $jwppp_chapters_subtitles && 'load' === $jwppp_subtitles_method ) {

							/*Delete old different elements*/
							delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title' );
							delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start' );
							delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end' );
							delete_post_meta( $post_id, '_jwppp-subtitles-write-default-' . $number );

							$sub_url = isset( $_POST[ '_jwppp-' . $number . '-subtitle-' . $i . '-url' ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-' . $number . '-subtitle-' . $i . '-url' ] ) ) : '';
							if ( ! $sub_url ) {
								delete_post_meta( $post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url' );
							} else {
								update_post_meta( $post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url', $sub_url );
							}

							$sub_label = isset( $_POST[ '_jwppp-' . $number . '-subtitle-' . $i . '-label' ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-' . $number . '-subtitle-' . $i . '-label' ] ) ) : '';
							if ( ! $sub_label ) {
								delete_post_meta( $post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label' );
							} else {
								update_post_meta( $post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label', $sub_label );
							}
						} else {

							/*Delete old different elements*/
							delete_post_meta( $post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url' );
							delete_post_meta( $post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label' );
							delete_post_meta( $post_id, '_jwppp-subtitles-load-default-' . $number );

							$title = isset( $_POST[ '_jwppp-' . $number . '-chapter-' . $i . '-title' ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-' . $number . '-chapter-' . $i . '-title' ] ) ) : '';
							if ( ! $title ) {
								delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title' );
							} else {
								update_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title', $title );
							}

							$start = isset( $_POST[ '_jwppp-' . $number . '-chapter-' . $i . '-start' ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-' . $number . '-chapter-' . $i . '-start' ] ) ) : '';
							if ( ! $start ) {
								delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start' );
							} else {
								update_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start', $start );
							}

							$end = isset( $_POST[ '_jwppp-' . $number . '-chapter-' . $i . '-end' ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-' . $number . '-chapter-' . $i . '-end' ] ) ) : '';
							if ( ! $end ) {
								delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end' );
							} else {
								update_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end', $end );
							}
						}
					}
				} else {
					$chapters = isset( $_POST[ '_jwppp-chapters-number-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-chapters-number-' . $number ] ) ) : '';
					for ( $i = 1; $i < $chapters + 1; $i++ ) {
						delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title' );
						delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start' );
						delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end' );
						delete_post_meta( $post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url' );
						delete_post_meta( $post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label' );
						delete_post_meta( $post_id, '_jwppp-chapters-subtitles-' . $number );
						delete_post_meta( $post_id, '_jwppp-subtitles-method-' . $number );
					}
					delete_post_meta( $post_id, '_jwppp-chapters-number-' . $number );
				}
			}
		} else {

			/*Delete all video informations from the db*/
			jwppp_db_delete_video( $post_id, $number );

		}
	}

}
add_action( 'save_post', 'jwppp_save_single_video_data', 10, 1 );
