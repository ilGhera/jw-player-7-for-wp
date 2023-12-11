<?php
/**
 * Remove video callback
 *
 * @author ilGhera
 * @package jw-player-for-vip/includes
 *
 * @since 2.0.0
 */
function jwppp_ajax_remove_video_callback() {

	if ( isset( $_POST['hidden-nonce-remove-video'] ) && wp_verify_nonce( $_POST['hidden-nonce-remove-video'], 'jwppp-nonce-remove-video' ) ) {
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : '';
		$number  = isset( $_POST['number'] ) ? intval( $_POST['number'] ) : '';
        $rebase  = isset( $_POST['rebase'] ) ? intval( $_POST['rebase'] ) : '';

		if ( $post_id && $number ) {

			jwppp_db_delete_video( $post_id, $number );

            if ( $rebase ) {

                jwppp_rebase_post_videos( $post_id, $number );

                $post = get_post( $post_id );
                jwppp_meta_box_callback( $post );

            } else {

                echo esc_html( wp_unslash( $number ) );

            }

		}
	}

	exit();
}
add_action( 'wp_ajax_jwppp_ajax_remove', 'jwppp_ajax_remove_video_callback' );

