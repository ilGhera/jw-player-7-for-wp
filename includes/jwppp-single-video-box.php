<?php
/**
 * Single video box
 * @author ilGhera
 * @package jw-player-for-vip/includes
* @version 2.0.0
 */

$dashboard_player = is_dashboard_player();
$player_position = get_option( 'jwppp-position' );

echo '<!-- jwppp video number ' . esc_html( $number ) . ' -->';
echo '<table class="widefat jwppp-' . esc_attr( $number ) . '" style="margin: 0.4rem 0; width: 100%;">';
	echo '<tbody class="ui-sortable">';

		/*Class for preview image dimensions*/
		$image_class = null;
		if ( 'custom' !== $player_position ) {
			$image_class = ' small';
		} elseif ( ! $dashboard_player ) {
			$image_class = ' medium';
		}

		echo '<tr class="row">';
			echo '<td class="order" style="width: 2.5%;">' . esc_html( $number ) . '</td>';
			echo '<td class="jwppp-input-wrap' . esc_attr( $image_class ) . '" style="width: 95%; padding-bottom: 1rem; position: relative;">';

				/*Nonce*/
				wp_nonce_field( 'jwppp-meta-box-nonce-' . $number, 'hidden-meta-box-nonce-' . $number );

				/*Single video details*/
				$video_url = get_post_meta( $post_id, '_jwppp-video-url-' . $number, true );
				$video_title = get_post_meta( $post_id, '_jwppp-video-title-' . $number, true );

				$video_description = get_post_meta( $post_id, '_jwppp-video-description-' . $number, true );

				$playlist_items = get_post_meta( $post_id, '_jwppp-playlist-items-' . $number, true );
				$video_duration = get_post_meta( $post_id, '_jwppp-video-duration-' . $number, true );
				$video_tags = get_post_meta( $post_id, '_jwppp-video-tags-' . $number, true );

				/*Is the video self hosted?*/
				$sh_video = strrpos( $video_url, 'http' ) === 0 ? true : false;

				$sources_number = get_post_meta( $post_id, '_jwppp-sources-number-' . $number, true );
				$main_source_label = get_post_meta( $post_id, '_jwppp-' . $number . '-main-source-label', true );

				$media_details = json_decode( stripslashes( get_post_meta( $post_id, '_jwppp-media-details', true ) ) );

				/*Video selected details*/
				echo '<div class="jwppp-video-details jwppp-video-details-' . esc_attr( $number ) . '">';
					echo '';

						echo $video_title ? '<span>Title</span>: ' . esc_html( $video_title ) . '</br>' : '';
						echo $video_description ? '<span>Description</span>: ' . esc_html( $video_description ) . '</br>' : '';
						echo $playlist_items ? '<span>Items</span>: ' . esc_html( $playlist_items ) . '</br>' : '';
						echo $video_duration ? '<span>Duration</span>: ' . esc_html( $video_duration ) . '</br>' : '';
						echo $video_tags ? '<span>Tags</span>: ' . esc_html( $video_tags ) . '</br>' : '';

				echo '</div>';

				/*Thumbnail*/
				$video_image = null;
				if ( $video_url && '1' !== $video_url ) {
					if ( $sh_video ) {
						$video_image = get_post_meta( $post_id, '_jwppp-video-image-' . $number, true );
					} else {
						$single_video_image = 'https://cdn.jwplayer.com/thumbs/' . $video_url . '-720.jpg';
						if ( @getimagesize( $single_video_image ) ) {
							$video_image = $single_video_image;
						} else {
							$video_image = plugin_dir_url( __DIR__ ) . 'images/playlist4.png';
						}
					}
				}

				/*Poster image preview*/
				if ( $video_image ) {
					echo '<img class="poster-image-preview ' . esc_attr( $number ) . ( ! $dashboard_player ? ' small' : '' ) . '" src="' . esc_url( $video_image ) . '">';
				}

				/*A cloud palyer allows to get contents from the JW Dasboard */
				if ( $dashboard_player ) {

					echo '<ul class="jwppp-video-toggles ' . esc_attr( $number ) . '">';
						echo '<li data-video-type="choose"' . ( ! $sh_video ? ' class="active"' : '' ) . '>' . esc_html( __( 'Choose', 'jwppp' ) ) . '</li>';
						echo '<li data-video-type="add-url"' . ( $sh_video ? ' class="active"' : '' ) . '>' . esc_html( __( 'Add url', 'jwppp' ) ) . '</li>';
						echo '<div class="clear"></div>';
					echo '</ul>';

					/*Select media content*/
					echo '<div class="jwppp-toggle-content ' . esc_attr( $number ) . ' choose' . ( ! $sh_video ? ' active' : '' ) . '">';
						echo '<p>';

							echo '<input type="text" autocomplete="off" id="_jwppp-video-title-' . esc_attr( $number ) . '" class="jwppp-search-content choose" data-number="' . esc_attr( $number ) . '" placeholder="' . esc_attr( __( 'Select video/playlist or search by ID', 'jwppp' ) ) . '" style="margin-right:1rem;" value="' . esc_attr( $video_title ) . '"><br>';

							echo '<input type="hidden" name="_jwppp-video-url-' . esc_attr( $number ) . '" id="_jwppp-video-url-' . esc_attr( $number ) . '" class="choose" value="' . esc_attr( $video_url ) . '">';


							echo '<input type="hidden" name="_jwppp-video-title-' . esc_attr( $number ) . '" id="_jwppp-video-title-' . esc_attr( $number ) . '" class="choose" value="' . esc_attr( $video_title ) . '">';
							echo '<input type="hidden" name="_jwppp-video-description-' . esc_attr( $number ) . '" id="_jwppp-video-description-' . esc_attr( $number ) . '" class="choose" value="' . esc_attr( $video_description ) . '">';
							echo '<input type="hidden" name="_jwppp-playlist-items-' . esc_attr( $number ) . '" id="_jwppp-playlist-items-' . esc_attr( $number ) . '" class="choose" value="' . esc_attr( $playlist_items ) . '">';
							echo '<input type="hidden" name="_jwppp-video-duration-' . esc_attr( $number ) . '" id="_jwppp-video-duration-' . esc_attr( $number ) . '" class="choose" value="' . esc_attr( $video_duration ) . '">';
							echo '<input type="hidden" name="_jwppp-video-tags-' . esc_attr( $number ) . '" id="_jwppp-video-tags-' . esc_attr( $number ) . '" class="choose" value="' . esc_attr( $video_tags ) . '">';

							echo '<ul id="_jwppp-video-list-' . esc_attr( $number ) . '" data-number="' . esc_attr( $number ) . '" class="jwppp-video-list">';
								echo '<span class="jwppp-list-container"></span>';
							echo '</ul>';

						echo '</p>';
					echo '</div>';

				}

				/*Input url, both with cloud and self-hosted players*/
				echo $dashboard_player ? '<div class="jwppp-toggle-content ' . esc_attr( $number ) . ' add-url' . ( $sh_video ? ' active' : '' ) . '">' : '';

				if ( ! $dashboard_player ) {
					echo '<label for="_jwppp-video-url-' . esc_attr( $number ) . '">';
						echo '<strong>' . esc_html( __( 'Media URL', 'jwppp' ) ) . '</strong>';
						echo '<a class="question-mark" href="https://www.ilghera.com/support/topic/media-formats-supported/" title="More informations" target="_blank"><img class="question-mark" src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/question-mark.png" /></a></th>';
					echo '</label> ';
				}

				echo '<p>';
					echo '<input type="text" id="_jwppp-video-url-' . esc_attr( $number ) . '" class="jwppp-url" name="_jwppp-video-url-' . esc_attr( $number ) . '" placeholder="' . esc_attr( __( 'Media URL', 'jwppp' ) ) . '" ';
					echo ( '1' !== $video_url ) ? 'value="' . esc_attr( $video_url ) . '" ' : 'value="" ';
					echo 'size="60" />';

					echo '<input type="text" name="_jwppp-' . esc_attr( $number ) . '-main-source-label" id ="_jwppp-' . esc_attr( $number ) . '-main-source-label" class="source-label-' . esc_attr( $number ) . '" style="margin-right:1rem; display: none;';
					echo '" value="' . esc_attr( $main_source_label ) . '" placeholder="' . esc_attr( __( 'Label (HD, 720p, 360p)', 'jwppp' ) ) . '" size="30" />';

				echo '</p>';

				echo $dashboard_player ? '</div>' : '';

				/*Display shortcode*/
				if ( get_option( 'jwppp-position' ) === 'custom' ) {
					echo '<code style="display:inline-block;margin:0.1rem 0.5rem 0 0.2rem;color:#888;">[jwp-video n="' . esc_attr( $number ) . '"]</code>';
				}

				echo '<a class="button more-options-' . esc_attr( $number ) . '">' . esc_html( __( 'Show options', 'jwppp' ) ) . '</a>';

				/*Single video tools*/
				jwppp_video_tools( $post_id, $number, $sh_video );
				?>
				<script>
					jQuery(document).ready(function($){
						var number = <?php echo wp_json_encode( $number ); ?>;
						var post_id = <?php echo wp_json_encode( $post_id ); ?>;
						JWPPPSingleVideo(number, post_id);
					})
				</script>
				<?php

				echo '</div>';
			echo '</td>';

			if ( $number < 2 ) {
				echo '<td class="add-video" style="width: 2.5%;"><a class="jwppp-add"><img src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/add-video.png" /></a></td>';
			} else {
				echo '<td class="remove-video" style="width: 2.5%;"><a class="jwppp-remove" data-numb="' . esc_attr( $number ) . '"><img src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/remove-video.png" /></a></td>';
			}

		echo '</tr>';
	echo '</tbody>';
echo '</table>';
