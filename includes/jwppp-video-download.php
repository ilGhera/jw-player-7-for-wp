<?php
/**
 * Video download
 * @author ilGhera
 * @package jw-player-for-vip/includes
 * @version 1.6.0
 */

function jwppp_video_download() {

	if ( isset( $_GET['jwp-video-download'] ) ) {

		$file = isset( $_GET['file'] ) ? esc_url_raw( wp_unslash( $_GET['file'] ) ) : '';
		$title = $file ? basename( $file ) : '';

		if ( $file && $title ) {
			header( 'Content-type: application/x-file-to-save' );
			header( 'Content-Disposition: attachment; filename=' . esc_attr( $title ) );
			readfile( $file );
		}

		exit;
	}

}
add_action( 'init', 'jwppp_video_download' );
