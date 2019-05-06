<?php
/**
 * Single video tools
 * @author ilGhera
 * @package jw-player-for-vip/includes
* @version 2.0.0
 * @param  int $post_id    the post id
 * @param  int $number     the video number
 * @param  bool $sh_video  is the video sel-hosted?
 * @return mixed           all the tools for the single video
 */
function jwppp_video_tools( $post_id, $number, $sh_video ) {

	/*Is a Dashboard player?*/
	$dashboard_player = is_dashboard_player();

	/*Single video details*/
	$video_title       = get_post_meta( $post_id, '_jwppp-video-title-' . $number, true );
	$video_description = get_post_meta( $post_id, '_jwppp-video-description-' . $number, true );

	/*Used only with self hosted vidoes*/
	$video_image                   = get_post_meta( $post_id, '_jwppp-video-image-' . $number, true );
	$add_chapters                  = get_post_meta( $post_id, '_jwppp-add-chapters-' . $number, true );
	$jwppp_chapters_subtitles      = get_post_meta( $post_id, '_jwppp-chapters-subtitles-' . $number, true );
	$jwppp_subtitles_method        = get_post_meta( $post_id, '_jwppp-subtitles-method-' . $number, true );
	$jwppp_subtitles_load_default  = get_post_meta( $post_id, '_jwppp-subtitles-load-default-' . $number, true );
	$jwppp_subtitles_write_default = get_post_meta( $post_id, '_jwppp-subtitles-write-default-' . $number, true );
	$jwppp_activate_media_type     = get_post_meta( $post_id, '_jwppp-activate-media-type-' . $number, true );
	$jwppp_media_type              = get_post_meta( $post_id, '_jwppp-media-type-' . $number, true );
	$jwppp_autoplay                = get_post_meta( $post_id, '_jwppp-autoplay-' . $number, true );
	$jwppp_mute                    = get_post_meta( $post_id, '_jwppp-mute-' . $number, true );
	$jwppp_repeat                  = get_post_meta( $post_id, '_jwppp-repeat-' . $number, true );
	$jwppp_share_video             = get_option( 'jwppp-active-share' );
	$jwppp_embed_video             = get_option( 'jwppp-embed-video' );

	$jwppp_single_embed = null;
	if ( isset( $_POST[ '_jwppp-single-embed-' . $number ], $_POST[ 'hidden-meta-box-nonce-' . $number ] ) && wp_verify_nonce( $_POST[ 'hidden-meta-box-nonce-' . $number ], 'jwppp-meta-box-nonce-' . $number ) ) {
		$jwppp_single_embed = sanitize_text_field( wp_unslash( $_POST[ '_jwppp-single-embed-' . $number ] ) );
	} else {
		$jwppp_single_embed = get_post_meta( $post_id, '_jwppp-single-embed-' . $number, true );
	}

	/*Ads tags*/
	$active_ads    = get_option( 'jwppp-active-ads' );
	$ads_tags      = get_option( 'jwppp-ads-tag' );
	$jwppp_ads_tag = get_post_meta( $post_id, '_jwppp-ads-tag-' . $number, true );

	/*Ads var block*/
	$active_ads_var = get_option( 'jwppp-active-ads-var' );

	echo '<div class="jwppp-more-options-' . esc_attr( $number ) . '" style="margin-top:2rem; display: none;">';

	if ( $dashboard_player ) {

		/*Choose player*/
		echo '<div class="jwppp-single-option-' . esc_attr( $number ) . ' cloud-option choose-player" style="opacity: 0;"></div>';
	}

	/*More sources*/
	echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '"' . ( $dashboard_player && ! $sh_video ? ' style="display: none;"' : '' ) . '>';
		echo '<label for="_jwppp-add-sources-' . esc_attr( $number ) . '">';
		echo '<strong>' . esc_html( __( 'More sources', 'jwppp' ) ) . '</strong>';
		echo '<a class="question-mark" title="' . esc_attr( __( 'Used for quality toggling and alternate sources.', 'jwppp' ) ) . '"><img class="question-mark" src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/question-mark.png" /></a></th>';
		echo '</label> ';

	if ( get_post_meta( $post_id, '_jwppp-sources-number-' . $number, true ) ) {
		$sources = get_post_meta( $post_id, '_jwppp-sources-number-' . $number, true );
	} else {
		$sources = 1;
	}

		echo '<input type="number" class="small-text" style="margin-left:1.8rem; display:inline; position: relative; top:2px;" id="_jwppp-sources-number-' . esc_attr( $number ) . '" name="_jwppp-sources-number-' . esc_attr( $number ) . '" value="' . esc_attr( $sources ) . '">';

		echo '</p>';

		echo '<ul class="sources-' . esc_attr( $number ) . '">';

	for ( $n = 1; $n < $sources + 1; $n++ ) {
		$source_url  = get_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $n . '-url', true );
		$source_label = get_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $n . '-label', true );
		echo '<li id="video-' . esc_attr( $number ) . '-source" data-number="' . esc_attr( $n ) . '">';
		echo '<input type="text" style="margin-right:1rem;" name="_jwppp-' . esc_attr( $number ) . '-source-' . esc_attr( $n ) . '-url" id="_jwppp-' . esc_attr( $number ) . '-source-' . esc_attr( $n ) . '-url" value="' . esc_attr( $source_url ) . '" placeholder="' . esc_attr( __( 'Source URL', 'jwppp' ) ) . '" size="60" />';
		echo '<input type="text" name="_jwppp-' . esc_attr( $number ) . '-source-' . esc_attr( $n ) . '-label" class="source-label-' . esc_attr( $number ) . '" style="margin-right:1rem;' . ( $sources <= 1 ? ' display: none;' : '' ) . '" value="' . esc_attr( $source_label ) . '" placeholder="' . esc_attr( __( 'Label (HD, 720p, 360p)', 'jwppp' ) ) . '" size="30" />';
		echo '</li>';
	}

		echo '</ul>';
	echo '</div>';

	/*Poster image*/
	echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '"' . ( $dashboard_player && ! $sh_video ? ' style="display: none;"' : '' ) . '>';
		echo '<label for="_jwppp-video-image-' . esc_attr( $number ) . '">';
		echo '<strong>' . esc_html( __( 'Video poster image', 'jwppp' ) ) . '</strong>';
		echo '</label> ';
		echo '<p class="poster-image-container-' . esc_attr( $number ) . '">';
		echo '<input type="text" id="_jwppp-video-image-' . esc_attr( $number ) . '" name="_jwppp-video-image-' . esc_attr( $number ) . '" placeholder="' . esc_attr( __( 'Poster Image URL', 'jwppp' ) ) . '" value="' . esc_attr( $video_image ) . '" size="60" />';
		echo '</p>';
	echo '</div>';

	/*Video title*/
	echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '"' . ( $dashboard_player && ! $sh_video ? ' style="display: none;"' : '' ) . '>';
		echo '<label for="_jwppp-video-title-' . esc_attr( $number ) . '">';
		echo '<strong>' . esc_html( __( 'Video title', 'jwppp' ) ) . '</strong>';
		echo '</label> ';
		echo '<p><input type="text" id="_jwppp-video-title-' . esc_attr( $number ) . '" class="jwppp-title" name="_jwppp-video-title-' . esc_attr( $number ) . '" placeholder="' . esc_attr( __( 'Video Title', 'jwppp' ) ) . '" value="' . esc_attr( $video_title ) . '" size="60" /></p>';
	echo '</div>';

	/*Video description*/
	echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '"' . ( $dashboard_player && ! $sh_video ? ' style="display: none;"' : '' ) . '>';
		echo '<label for="_jwppp-video-description-' . esc_attr( $number ) . '">';
		echo '<strong>' . esc_html( __( 'Video description', 'jwppp' ) ) . '</strong>';
		echo '</label> ';
		echo '<p><input type="text" id="_jwppp-video-description-' . esc_attr( $number ) . '" name="_jwppp-video-description-' . esc_attr( $number ) . '" class="jwppp-description" placeholder="' . esc_attr( __( 'Video Description', 'jwppp' ) ) . '" value="' . esc_attr( $video_description ) . '" size="60" /></p>';
	echo '</div>';

	/*Ad tag*/
	if ( $active_ads && ! $active_ads_var ) {
		echo '<div class="jwppp-single-option-' . esc_attr( $number ) . ' cloud-option">';
			echo '<label for="_jwppp-ads-tag-' . esc_attr( $number ) . '"><strong>' . esc_html( __( 'Select Ad Tag', 'jwppp' ) ) . '</strong></label>';
			echo '<p>';
			echo '<select class="jwppp-ads-tag" name="_jwppp-ads-tag-' . esc_attr( $number ) . '">';
		if ( $ads_tags ) {
			if ( is_array( $ads_tags ) && ! empty( $ads_tags ) ) {
				for ( $i = 0; $i < count( $ads_tags ); $i++ ) {
					echo '<option name="' . esc_attr( $ads_tags[ $i ]['label'] ) . '" ';
					echo 'value="' . esc_url( $ads_tags[ $i ]['url'] ) . '"';
					echo ( $jwppp_ads_tag === $ads_tags[ $i ]['url'] ? ' selected="selected"' : '' ) . '>';
					if ( $ads_tags[ $i ]['label'] ) {
						echo esc_html( $ads_tags[ $i ]['label'] );
					} else {
						echo 'Tag ';
						echo esc_html( $i ) + 1;
					}
					echo '</option>';
				}
			} elseif ( is_string( $ads_tags ) ) {
				echo '<option name="0" value="' . esc_attr( $ads_tags ) . '">' . esc_html( $ads_tags ) . '</option>';
			}

			/*Advertise disabled*/
			echo '<option name="" value="no-ads"' . ( 'no-ads' === $jwppp_ads_tag ? ' selected="selected"' : '' ) . '>' . esc_html( __( 'No ad tag', 'jwppp' ) ) . '</option>';

		} else {
			echo '<option name="" value="">' . esc_html( __( 'No ad tags available', 'jwppp' ) ) . '</option>';
		}

			echo '</select>';
			echo '</p>';
		echo '</div>';
	}

	/*Playlist carousel*/
	if ( $dashboard_player ) {
		$jwppp_playlist_carousel = get_post_meta( $post_id, '_jwppp-playlist-carousel-' . $number, true );
		echo '<div class="jwppp-single-option-' . esc_attr( $number ) . ' cloud-option playlist-carousel-container ' . esc_attr( $number ) . '"' . ( $jwppp_playlist_carousel ? ' style="display: inline-block;"' : '' ) . '>';
			echo '<label for="_jwppp-playlist-carousel-' . esc_attr( $number ) . '">';
			echo '<input type="checkbox" id="_jwppp-playlist-carousel-' . esc_attr( $number ) . '" name="_jwppp-playlist-carousel-' . esc_attr( $number ) . '" value="1"';
			echo ( '1' === $jwppp_playlist_carousel ) ? ' checked="checked"' : '';
			echo ' />';
			echo '<strong>' . esc_html( __( 'Show a carousel with the playlist\'s video thumbnails.', 'jwppp' ) ) . '</strong>';
			echo '</label>';
			echo '<input type="hidden" name="playlist-carousel-hidden-' . esc_attr( $number ) . '" value="1" />';
		echo '</div>';
	}

	/*Media type*/
	echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '"' . ( $dashboard_player && ! $sh_video ? ' style="display: none;"' : '' ) . '>';
		echo '<p>';
		echo '<label for="_jwppp-activate-media-type-' . esc_attr( $number ) . '">';
		echo '<input type="checkbox" id="_jwppp-activate-media-type-' . esc_attr( $number ) . '" name="_jwppp-activate-media-type-' . esc_attr( $number ) . '" value="1"';
		echo ( '1' === $jwppp_activate_media_type ) ? ' checked="checked"' : '';
		echo ' />';
		echo '<strong>' . esc_html( __( 'Force a media type', 'jwppp' ) ) . '</strong>';
		echo '<a class="question-mark" title="' . esc_attr( __( 'Only required when a file extension is missing or not recognized', 'jwppp' ) ) . '"><img class="question-mark" src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/question-mark.png" /></a></th>';
		echo '</label>';
		echo '<input type="hidden" name="activate-media-type-hidden-' . esc_attr( $number ) . '" value="1" />';

		echo '<select style="position: relative; left:2rem; display:inline;" id="_jwppp-media-type-' . esc_attr( $number ) . '" name="_jwppp-media-type-' . esc_attr( $number ) . '">';
		echo '<option name="mp4" value="mp4"';
		echo ( 'mp4' === $jwppp_media_type ) ? ' selected="selected"' : '';
		echo '>mp4</option>';
		echo '<option name="flv" value="flv"';
		echo ( 'flv' === $jwppp_media_type ) ? ' selected="selected"' : '';
		echo '>flv</option>';
		echo '<option name="mp3" value="mp3"';
		echo ( 'mp3' === $jwppp_media_type ) ? ' selected="selected"' : '';
		echo '>mp3</option>';
		echo '</select>';
		echo '</p>';
	echo '</div>';

	/*Options available only with self-hosted player*/
	if ( ! $dashboard_player ) {

		/*Autoplay*/
		echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '">';
			echo '<p>';
			echo '<label for="_jwppp-autoplay-' . esc_attr( $number ) . '">';
			echo '<input type="checkbox" id="_jwppp-autoplay-' . esc_attr( $number ) . '" name="_jwppp-autoplay-' . esc_attr( $number ) . '" value="1"';
			echo ( '1' === $jwppp_autoplay ) ? ' checked="checked"' : '';
			echo ' />';
			echo '<strong>' . esc_html( __( 'Autostart on page load', 'jwppp' ) ) . '</strong>';
			echo '</label>';
			echo '<input type="hidden" name="autoplay-hidden-' . esc_attr( $number ) . '" value="1" />';
			echo '</p>';
		echo '</div>';

		/*Mute*/
		echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '">';
			echo '<p>';
			echo '<label for="_jwppp-mute-' . esc_attr( $number ) . '">';
			echo '<input type="checkbox" id="_jwppp-mute-' . esc_attr( $number ) . '" name="_jwppp-mute-' . esc_attr( $number ) . '" value="1"';
			echo ( '1' === $jwppp_mute ) ? ' checked="checked"' : '';
			echo ' />';
			echo '<strong>' . esc_html( __( 'Mute', 'jwppp' ) ) . '</strong>';
			echo '</label>';
			echo '<input type="hidden" name="mute-hidden-' . esc_attr( $number ) . '" value="1" />';
			echo '</p>';
		echo '</div>';

		/*Repeat*/
		echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '">';
			echo '<p>';
			echo '<label for="_jwppp-repeat-' . esc_attr( $number ) . '">';
			echo '<input type="checkbox" id="_jwppp-repeat-' . esc_attr( $number ) . '" name="_jwppp-repeat-' . esc_attr( $number ) . '" value="1"';
			echo ( '1' === $jwppp_repeat ) ? ' checked="checked"' : '';
			echo ' />';
			echo '<strong>' . esc_html( __( 'Repeat', 'jwppp' ) ) . '</strong>';
			echo '</label>';
			echo '<input type="hidden" name="repeat-hidden-' . esc_attr( $number ) . '" value="1" />';
			echo '</p>';
		echo '</div>';

		/*Embed video*/
		if ( $jwppp_share_video ) {
			if ( '1' === $jwppp_single_embed ) {
				$checked = 'checked="checked"';
			} elseif ( '0' === $jwppp_single_embed ) {
				$checked = '';
			} elseif ( ! $jwppp_single_embed ) {
				$checked = ( '1' === $jwppp_embed_video ) ? 'checked="checked"' : '';
			}

			echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '">';
				echo '<p>';
				echo '<label for="_jwppp-single-embed-' . esc_attr( $number ) . '">';
				echo '<input type="checkbox" id="_jwppp-single-embed-' . esc_attr( $number ) . '" name="_jwppp-single-embed-' . esc_attr( $number ) . '" value="1"';
				echo ' ' . esc_attr( $checked );
				echo ' />';
				echo '<strong>' . esc_html( __( 'Allow to embed this video', 'jwppp' ) ) . '</strong>';
				echo '</label>';
				echo '<input type="hidden" name="single-embed-hidden-' . esc_attr( $number ) . '" value="1" />';
				echo '</p>';
			echo '</div>';
		}

		/*Chapters & subtitles*/
		echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '">';
			echo '<p>';
			echo '<label for="_jwppp-add-chapters-' . esc_attr( $number ) . '">';
			echo '<input type="checkbox" id="_jwppp-add-chapters-' . esc_attr( $number ) . '" name="_jwppp-add-chapters-' . esc_attr( $number ) . '" value="1"';
			echo ( '1' === $add_chapters ) ? ' checked="checked"' : '';
			echo ' />';
			echo '<strong><span class="add-chapters ' . esc_attr( $number ) . '">';
		if ( '1' === esc_html( $add_chapters ) ) {
			echo esc_html( __( 'Add', 'jwppp' ) );
		} else {
			echo esc_html( __( 'Add Chapters, Subtitles or Preview Thumbnails', 'jwppp' ) );
		}
			echo '</span></strong>';
			echo '</label>';
			echo '<input type="hidden"function name="add-chapters-hidden-' . esc_attr( $number ) . '" value="1" />';

			echo '<select style="margin-left:0.5rem;" name="_jwppp-chapters-subtitles-' . esc_attr( $number ) . '" id="_jwppp-chapters-subtitles-' . esc_attr( $number ) . '">';
			echo '<option name="chapters" id="chapters" value="chapters"';
			echo ( 'chapters' === $jwppp_chapters_subtitles ) ? ' selected="selected"' : '';
			echo '>Chapters</option>';
			echo '<option name="subtitles" id="subtitles" value="subtitles"';
			echo ( 'subtitles' === $jwppp_chapters_subtitles ) ? ' selected="selected"' : '';
			echo '>Subtitles</option>';
			echo '<option name="thumbnails" id="thumbnails" value="thumbnails"';
			echo ( 'thumbnails' === $jwppp_chapters_subtitles ) ? ' selected="selected"' : '';
			echo '>Thumbnails</option>';
			echo '</select>';

			/*Subtitles method selector*/
			echo '<select style="margin-left:0.3rem;" name="_jwppp-subtitles-method-' . esc_attr( $number ) . '" id="_jwppp-subtitles-method-' . esc_attr( $number ) . '">';
			echo '<option name="manual" id="manual" value="manual"';
			echo ( 'manual' === $jwppp_subtitles_method ) ? ' selected="selected"' : '';
			echo '>Write subtitles</option>';
			echo '<option name="load" id="load" value="load"';
			echo ( 'load' === $jwppp_subtitles_method ) ? ' selected="selected"' : '';
			echo '>Load subtitles</option>';
			echo '</select>';

		if ( get_post_meta( $post_id, '_jwppp-chapters-number-' . $number, true ) ) {
			$chapters = get_post_meta( $post_id, '_jwppp-chapters-number-' . $number, true );
		} else {
			$chapters = 1;
		}

			echo '<input type="number" class="small-text" style="margin-left:0.3rem; display:inline; position: relative; top:2px;" id="_jwppp-chapters-number-' . esc_attr( $number ) . '" name="_jwppp-chapters-number-' . esc_attr( $number ) . '" value="' . esc_attr( $chapters ) . '">';

			echo '</p>';

			echo '<ul class="chapters-subtitles-' . esc_attr( $number ) . '">';
		for ( $i = 1; $i < $chapters + 1; $i++ ) {
			$title = get_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title', true );
			$start = get_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start', true );
			$end = get_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end', true );
			echo '<li id="video-' . esc_attr( $number ) . '-chapter" data-number="' . esc_attr( $i ) . '">';
			echo '<input type="text" style="margin-right:1rem;" name="_jwppp-' . esc_attr( $number ) . '-chapter-' . esc_attr( $i ) . '-title" value="' . esc_attr( $title ) . '"';

			if ( 'subtitles' === $jwppp_chapters_subtitles ) {
				echo 'placeholder="' . esc_attr( __( 'Subtitle', 'jwppp' ) ) . '"';
			} elseif ( 'thumbnails' === $jwppp_chapters_subtitles ) {
				echo 'placeholder="' . esc_attr( __( 'Thumbnail url', 'jwppp' ) ) . '"';
			} else {
				echo 'placeholder="' . esc_attr( __( 'Chapter title', 'jwppp' ) ) . '"';
			}

			echo ' size="60" />';
			echo '    ' . esc_html( __( 'Start', 'jwppp' ) ) . '    <input type="number" name="_jwppp-' . esc_attr( $number ) . '-chapter-' . esc_attr( $i ) . '-start" style="margin-right:1rem;" min="0" step="1" class="small-text" value="' . esc_attr( $start ) . '" />';
			echo '    ' . esc_html( __( 'End', 'jwppp' ) ) . '    <input type="number" name="_jwppp-' . esc_attr( $number ) . '-chapter-' . esc_attr( $i ) . '-end" style="margin-right:0.5rem;" min="1" step="1" class="small-text" value="' . esc_attr( $end ) . '" />';
			echo esc_html( __( 'in seconds', 'jwpend' ) );

			/*Subtitles activated by default*/
			if ( '1' === $i ) {
				echo '<label for="_jwppp-subtitles-write-default-' . esc_attr( $number ) . '" style="display: inline-block; margin-left: 1rem;">';
				echo '<input type="checkbox" id="_jwppp-subtitles-write-default-' . esc_attr( $number ) . '" name="_jwppp-subtitles-write-default-' . esc_attr( $number ) . '" value="1"';
				echo ( '1' === $jwppp_subtitles_write_default ) ? ' checked="checked"' : '';
				echo ' />';
				echo esc_html( __( 'Default', 'jwppp' ) );
				echo '<a class="question-mark" title="' . esc_attr( __( 'These subtitles will be activated by default', 'jwppp' ) ) . '"><img class="question-mark" src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/question-mark.png" /></a></th>';
				echo '</label>';
				echo '<input type="hidden" name="subtitles-write-default-hidden-' . esc_attr( $number ) . '" value="1" />';
			}

			echo '</li>';
		}
			echo '</ul>';

			echo '<ul class="load-subtitles-' . esc_attr( $number ) . '">';

		for ( $n = 1; $n < $chapters + 1; $n++ ) {
			$url  = get_post_meta( $post_id, '_jwppp-' . $number . '-subtitle-' . $n . '-url', true );
			$label = get_post_meta( $post_id, '_jwppp-' . $number . '-subtitle-' . $n . '-label', true );
			echo '<li id="video-' . esc_attr( $number ) . '-subtitle" data-number="' . esc_attr( $n ) . '">';
			echo '<input type="text" style="margin-right:1rem;" name="_jwppp-' . esc_attr( $number ) . '-subtitle-' . esc_attr( $n ) . '-url" value="' . esc_url( $url ) . '" placeholder="' . esc_attr( __( 'Subtitles file url (VTT, SRT, DFXP)', 'jwppp' ) ) . '" size="60" />';
			echo '<input type="text" name="_jwppp-' . esc_attr( $number ) . '-subtitle-' . esc_attr( $n ) . '-label" style="margin-right:1rem;" value="' . esc_attr( $label ) . '" placeholder="' . esc_attr( __( 'Label (EN, IT, FR )', 'jwppp' ) ) . '" size="30" />';

			if ( '1' === $n ) {
				echo '<label for="_jwppp-subtitles-load-default-' . esc_attr( $number ) . '">';
				echo '<input type="checkbox" id="_jwppp-subtitles-load-default-' . esc_attr( $number ) . '" name="_jwppp-subtitles-load-default-' . esc_attr( $number ) . '" value="1"';
				echo ( '1' === $jwppp_subtitles_load_default ) ? ' checked="checked"' : '';
				echo ' />';
				echo esc_html( __( 'Default', 'jwppp' ) );
				echo '<a class="question-mark" title="' . esc_attr( __( 'These first subtitles will be activated by default', 'jwppp' ) ) . '"><img class="question-mark" src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/question-mark.png" /></a></th>';
				echo '</label>';
				echo '<input type="hidden" name="subtitles-load-default-hidden-' . esc_attr( $number ) . '" value="1" />';
			}

			echo '</li>';
		}

			echo '</ul>';
		echo '</div>';
	}

}
