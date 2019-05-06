<?php
/**
 * Video chapters
 * @author ilGhera
 * @package jw-player-for-vip/includes
* @version 2.0.0
 * @return string   the chapters set by the publisher in a WEBVTT file
 */
function jwppp_video_chapters() {

	if ( isset( $_GET['jwp-chapters'] ) ) {
		/*Get the video informations*/
		$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : '';
		$number = isset( $_GET['number'] ) ? intval( $_GET['number'] ) : '';
		$n_chapters = get_post_meta( $id, '_jwppp-chapters-number-' . $number, true );

		if ( $id && $number && $n_chapters >= 1 ) {
			echo "WEBVTT\n";
			echo "\n";

			for ( $i = 1; $i < $n_chapters + 1; $i++ ) {

				$start = get_post_meta( $id, '_jwppp-' . $number . '-chapter-' . $i . '-start', true );
				$end = get_post_meta( $id, '_jwppp-' . $number . '-chapter-' . $i . '-end', true );

				echo 'Chapter' . esc_html( $i ) . "\n";
				echo esc_html( return_time( $start ) );
				echo ' --> ';
				echo esc_html( return_time( $end ) ) . "\n";
				echo esc_html( get_post_meta( $id, '_jwppp-' . $number . '-chapter-' . $i . '-title', true ) ) . "\n";
				echo "\n";
			}
		}

		exit;
	}
}
add_action( 'init', 'jwppp_video_chapters' );


/**
 * Set the time format
 * @param  int $seconds the time in seconds set by the publisher
 * @return string       the formatted time for the WEVTT file
 */
function return_time( $seconds ) {
	$hours = floor( $seconds / 3600 );
	$mins = floor( ( $seconds - ( $hours * 3600 ) ) / 60 );
	$secs = floor( $seconds - $hours * 3600 - $mins * 60 );
	$time = sprintf( '%02d', $hours ) . ':';
	$time .= sprintf( '%02d', $mins ) . ':';
	$time .= sprintf( '%02d', $secs ) . '.000';
	return $time;
}
