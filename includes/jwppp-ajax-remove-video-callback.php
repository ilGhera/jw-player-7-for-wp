<?php
/**
 * Remove video callback
 * @author ilGhera
 * @package jw-player-for-vip/includes
* @version 2.0.0
 */
function jwppp_ajax_remove_video_callback( $post ) {

	if ( isset( $_POST['hidden-nonce-remove-video'] ) && wp_verify_nonce( $_POST['hidden-nonce-remove-video'], 'jwppp-nonce-remove-video' ) ) {
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : '';
		$number = isset( $_POST['number'] ) ? intval( $_POST['number'] ) : '';

		if ( $post_id && $number ) {

			jwppp_db_delete_video( $post_id, $number );

			echo esc_html( wp_unslash( $number ) );

		}
	}

	exit();
}
