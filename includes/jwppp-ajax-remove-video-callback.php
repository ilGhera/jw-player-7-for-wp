<?php //REMOVE ALL THE VIDEO DETAILS IN THE DATABASE
function jwppp_ajax_remove_video_callback( $post ) {

	$number = $_POST['number'];
	$post_id = $_POST['post_id'];
	delete_post_meta( $post_id, '_jwppp-video-url-' . $number);
	delete_post_meta( $post_id, '_jwppp-video-mobile-url-' . $number);
	delete_post_meta( $post_id, '_jwppp-video-image-' . $number);
	delete_post_meta( $post_id, '_jwppp-video-title-' . $number);
	delete_post_meta( $post_id, '_jwppp-video-description-' . $number);
	delete_post_meta( $post_id, '_jwppp-autoplay-' . $number);
	delete_post_meta( $post_id, '_jwppp-single-embed-' . $number);
	delete_post_meta( $post_id, '_jwppp-add-chapters-' . $number);

	$chapters = get_post_meta( $post_id, '_jwppp-chapters-number-' . $number);
	$count = (int)$chapters+2;
	for($n=1; $n<$count; $n++) {
		delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-title');
		delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-start');
		delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-end');
	}
	delete_post_meta( $post_id, '_jwppp-chapters-number-' . $number);

	echo $number;
	exit();

}