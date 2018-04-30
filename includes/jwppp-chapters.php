<?php //ADD CHAPTERS
require('../../../../wp-load.php');

$id = $_GET['id'];
$number = $_GET['number'];
$n_chapters = get_post_meta($id, '_jwppp-chapters-number-' . $number, true);

function return_time($seconds) {
	$hours = floor($seconds / 3600);
	$mins = floor(($seconds - ($hours*3600)) / 60);
	$secs = floor($seconds - $hours*3600 - $mins*60);
	$time = sprintf("%02d", $hours) . ':';
	$time .= sprintf("%02d", $mins) . ':';
	$time .= sprintf("%02d", $secs) . '.000';
	return $time;
}
echo "WEBVTT\n";
echo "\n";

for($i=1; $i<$n_chapters+1; $i++) {

	$start = get_post_meta($id, '_jwppp-' . $number . '-chapter-' . $i . '-start', true);
	$end = get_post_meta($id, '_jwppp-' . $number . '-chapter-' . $i . '-end', true);

	echo "Chapter $i\n";
	echo return_time($start);
	echo ' --> ';
	echo return_time($end) . "\n";
	echo get_post_meta($id, '_jwppp-' . $number . '-chapter-' . $i . '-title', true) . "\n";
	echo "\n";
}