<?php
/**
 * Add video callback
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @version 1.6.0
 * @return mixed   a new video box
 */
function jwppp_ajax_add_video_callback() {

	$number = isset($_POST['number']) ? sanitize_text_field($_POST['number']) : '';
	$post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
	
	if($number && $post_id) {

		update_post_meta( $post_id, '_jwppp-video-url-' . $number, 1);		
		include(plugin_dir_path(__FILE__) . 'jwppp-single-video-box.php');

	}

	exit();
}
