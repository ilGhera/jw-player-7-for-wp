<?php
/**
 * Video download
 * @author ilGhera
 * @package jw-player-for-vip/includes
 * @version 1.6.0
 */

function jwppp_video_download() {

	if ( isset( $_GET['jwp-video-download'] ) ) {

		$file = isset( $_GET['file'] ) ? esc_url_raw( $_GET['file'] ) : '';
		$title = $file ? basename( $file ) : '';

		if ( $file && $title ) {
			header( 'Content-type: application/x-file-to-save' );
			header( 'Content-Disposition: attachment; filename=' . esc_attr( $title ) );
			readfile( $file );
			
			// global $wp_filesystem;			 
			
			// if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
				
			// 	require_once(ABSPATH . 'wp-admin/includes/file.php');

			//     $creds = request_filesystem_credentials( site_url() );
			//     wp_filesystem($creds);
			// }
			// $name = wp_parse_url( $file );
			// error_log('TEST1: ' . $wp_filesystem->get_contents($file));
			// error_log('TEST: ' . $wp_filesystem->get_contents($file));
			// $wp_filesystem->get_contents();
		}
	
		exit;
	}

}
add_action( 'init', 'jwppp_video_download' );