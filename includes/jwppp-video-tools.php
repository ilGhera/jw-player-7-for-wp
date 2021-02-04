<?php
/**
 * Single video tools
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @version 2.0.0
 * @since 2.0.2
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
	$jwppp_activate_media_type = get_post_meta( $post_id, '_jwppp-activate-media-type-' . $number, true );
	$jwppp_media_type          = get_post_meta( $post_id, '_jwppp-media-type-' . $number, true );

	/*Share*/
	$jwppp_share_video = get_option( 'jwppp-active-share' );
	$jwppp_embed_video = get_option( 'jwppp-embed-video' );

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

		echo '<input type="number" class="small-text" style="margin-left:1.8rem; display:inline; position: relative; top:2px;" id="_jwppp-sources-number-' . esc_attr( $number ) . '" name="_jwppp-sources-number-' . esc_attr( $number ) . '" value="1" disabled="disabled">';

		echo '</p>';

		echo '<ul class="sources-' . esc_attr( $number ) . '">';

	for ( $n = 1; $n < 2; $n++ ) {
		$source_url  = get_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $n . '-url', true );
		$source_label = get_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $n . '-label', true );
		echo '<li id="video-' . esc_attr( $number ) . '-source" data-number="' . esc_attr( $n ) . '">';
		echo '<input type="text" style="margin-right:1rem;" name="_jwppp-' . esc_attr( $number ) . '-source-' . esc_attr( $n ) . '-url" id="_jwppp-' . esc_attr( $number ) . '-source-' . esc_attr( $n ) . '-url" value="' . esc_attr( $source_url ) . '" placeholder="' . esc_attr( __( 'Source URL', 'jwppp' ) ) . '" size="60" />';
		echo '<input type="text" name="_jwppp-' . esc_attr( $number ) . '-source-' . esc_attr( $n ) . '-label" class="source-label-' . esc_attr( $number ) . '" style="margin-right:1rem;' . ( 1 <= 1 ? ' display: none;' : '' ) . '" value="' . esc_attr( $source_label ) . '" placeholder="' . esc_attr( __( 'Label (HD, 720p, 360p)', 'jwppp' ) ) . '" size="30" />';
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
		echo '<input type="text" id="_jwppp-video-image-' . esc_attr( $number ) . '" name="_jwppp-video-image-' . esc_attr( $number ) . '" placeholder="' . esc_attr( __( 'Poster Image URL', 'jwppp' ) ) . '" disabled="disabled" size="60" />';
		go_premium( null, true );
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
	echo '<div class="jwppp-single-option-' . esc_attr( $number ) . ' cloud-option">';
		echo '<label for="_jwppp-ads-tag-' . esc_attr( $number ) . '"><strong>' . esc_html( __( 'Select Ad Tag', 'jwppp' ) ) . '</strong></label>';
		echo '<p>';
		echo '<select class="jwppp-ads-tag" name="_jwppp-ads-tag-' . esc_attr( $number ) . '">';		
		echo '<option name="" value="">' . esc_html( __( 'No ad tags available', 'jwppp' ) ) . '</option>';
		echo '</select>';
		go_premium( null, true );
		echo '</p>';
	echo '</div>';

	/*Media type*/
	echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '"' . ( $dashboard_player && ! $sh_video ? ' style="display: none;"' : '' ) . '>';
		echo '<p>';
		echo '<label for="_jwppp-activate-media-type-' . esc_attr( $number ) . '">';
		echo '<input type="checkbox" id="_jwppp-activate-media-type-' . esc_attr( $number ) . '" name="_jwppp-activate-media-type-' . esc_attr( $number ) . '" value="1"';
		echo ( 1 === intval( $jwppp_activate_media_type ) ) ? ' checked="checked"' : '';
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
			echo '<input type="checkbox" id="_jwppp-autoplay-' . esc_attr( $number ) . '" name="_jwppp-autoplay-' . esc_attr( $number ) . '" value="1" disabled="disabled" />';
			echo '<strong>' . esc_html( __( 'Autostart on page load', 'jwppp' ) ) . '</strong>';
			echo '</label>';
			echo '</p>';
		echo '</div>';

		/*Mute*/
		echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '">';
			echo '<p>';
			echo '<label for="_jwppp-mute-' . esc_attr( $number ) . '">';
			echo '<input type="checkbox" id="_jwppp-mute-' . esc_attr( $number ) . '" name="_jwppp-mute-' . esc_attr( $number ) . '" value="1" disabled="disabled" />';
			echo '<strong>' . esc_html( __( 'Mute', 'jwppp' ) ) . '</strong>';
			echo '</label>';
			echo '</p>';
		echo '</div>';

		/*Repeat*/
		echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '">';
			echo '<p>';
			echo '<label for="_jwppp-repeat-' . esc_attr( $number ) . '">';
			echo '<input type="checkbox" id="_jwppp-repeat-' . esc_attr( $number ) . '" name="_jwppp-repeat-' . esc_attr( $number ) . '" value="1" disabled="disabled" />';
			echo '<strong>' . esc_html( __( 'Repeat', 'jwppp' ) ) . '</strong>';
			echo '</label>';
			echo '</p>';
		echo '</div>';

		/*Embed video*/
		if ( $jwppp_share_video ) {

			$checked = ( '1' === $jwppp_embed_video ) ? 'checked="checked"' : '';

			echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '">';
				echo '<p>';
				echo '<label for="_jwppp-single-embed-' . esc_attr( $number ) . '">';
				echo '<input type="checkbox" id="_jwppp-single-embed-' . esc_attr( $number ) . '" name="_jwppp-single-embed-' . esc_attr( $number ) . '" value="1"';
				echo ' disabled="disabled"' . esc_attr( $checked ) . '/>';
				echo '<strong>' . esc_html( __( 'Allow to embed this video', 'jwppp' ) ) . '</strong>';
				echo '</label>';
				echo '</p>';
			echo '</div>';
		}

		/*Chapters & subtitles*/
		echo '<div class="jwppp-single-option-' . esc_attr( $number ) . '">';
			echo '<p>';
			echo '<label for="_jwppp-add-chapters-' . esc_attr( $number ) . '">';
			echo '<input type="checkbox" id="_jwppp-add-chapters-' . esc_attr( $number ) . '" name="_jwppp-add-chapters-' . esc_attr( $number ) . '" value="1" disabled="disabled" />';
			echo '<strong><span class="add-chapters ' . esc_attr( $number ) . '">';
			echo esc_html( __( 'Add Chapters, Subtitles or Preview Thumbnails', 'jwppp' ) );
			echo '</span></strong>';
			echo '</label>';
			go_premium( __( 'Upgrade for a full control of your videos', 'jwppp' ), true );
		echo '</div>';
	}

}
