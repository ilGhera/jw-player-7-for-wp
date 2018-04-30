<?php
function jwppp_ajax_add_video_callback( $post ) {

	$number = $_POST['number'];
	$post_id = $_POST['post_id'];
	update_post_meta( $post_id, '_jwppp-video-url-' . $number, 1);
	wp_nonce_field( 'jwppp_save_single_video_data', 'jwppp-meta-box-nonce-' . $number );

	require('jwppp-single-video-box.php');
	
	exit();
}
