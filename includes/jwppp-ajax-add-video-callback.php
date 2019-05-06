<?php
/**
 * Add video callback
 * @author ilGhera
 * @package jw-player-for-vip/includes
* @version 2.0.0
 * @return mixed   a new video box
 */
function jwppp_ajax_add_video_callback() {

	if ( isset( $_POST['hidden-nonce-add-video'] ) && wp_verify_nonce( $_POST['hidden-nonce-add-video'], 'jwppp-nonce-add-video' ) ) {
		$number = isset( $_POST['number'] ) ? intval( $_POST['number'] ) : '';
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : '';

		if ( $number && $post_id ) {

			update_post_meta( $post_id, '_jwppp-video-url-' . $number, 1 );
			include( JWPPP_INCLUDES . 'jwppp-single-video-box.php' );

		}
	}

	exit();
}
