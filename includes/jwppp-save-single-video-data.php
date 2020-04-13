<?php
/**
 * Save all informations of the single video
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @since 2.0.2
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
		$number       = isset( $jwppp_number[1] ) ? $jwppp_number[1] : 1;

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
			// if ( isset( $_POST[ '_jwppp-sources-number-' . $number ] ) ) {

				$sources = 1;

				for ( $i = 1; $i <= $sources; $i++ ) {
					$source_url = isset( $_POST[ '_jwppp-' . $number . '-source-' . $i . '-url' ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-' . $number . '-source-' . $i . '-url' ] ) ) : '';
					if ( ! $source_url ) {
						delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-url' );
					} else {
						update_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-url', $source_url );
					}
				}

				update_post_meta( $post_id, '_jwppp-sources-number-' . $number, $sources );

			// } else {

				// $sources = get_post_meta( $post_id, '_jwppp-sources-number-' . $number, true );
				// if ( $sources ) {
				// 	for ( $i = 1; $i <= $sources; $i++ ) {
				// 		delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-url' );
				// 		delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-label' );
				// 	}
				// }
				// delete_post_meta( $post_id, '_jwppp-sources-number-' . $number );
			// }

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

			if ( 1 === intval( $jwppp_activate_media_type ) ) {
				$media_type = isset( $_POST[ '_jwppp-media-type-' . $number ] ) ? sanitize_text_field( wp_unslash( $_POST[ '_jwppp-media-type-' . $number ] ) ) : '';
				update_post_meta( $post_id, '_jwppp-media-type-' . $number, $media_type );
			} else {
				delete_post_meta( $post_id, '_jwppp-media-type-' . $number );
			}

		} else {

			/*Delete all video informations from the db*/
			jwppp_db_delete_video( $post_id, $number );

		}
	}

}
add_action( 'save_post', 'jwppp_save_single_video_data', 10, 1 );
