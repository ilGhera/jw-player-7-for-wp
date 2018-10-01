<?php
/**
 * Remove video callback
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @version 1.6.0
 */
function jwppp_ajax_remove_video_callback( $post ) {

	$post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
	$number = isset($_POST['number']) ? sanitize_text_field($_POST['number']) : '';

	if($post_id && $number) {

		jwppp_db_delete_video($post_id, $number);

		echo $number;

	}

	exit();
}