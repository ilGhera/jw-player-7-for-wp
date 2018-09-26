<?php
/**
 * Facebook istant articles player
 * @author ilGhera
 * @package jw-player-7-for-wp/fb
 * @version 1.6.0
 */

$player = isset($_GET['player']) ? $_GET['player'] : '';
$player_url = isset($_GET['player_url']) ? $_GET['player_url'] : '';
$mediaID = isset($_GET['mediaID']) ? $_GET['mediaID'] : '';
$mediaURL = isset($_GET['mediaURL']) ? $_GET['mediaURL'] : '';
$file = null;
if($mediaID) {
	$file = "https://cdn.jwplayer.com/v2/media/" . $mediaID;
	$image = "https://content.jwplatform.com/thumbs/" . $mediaID."-1920.jpg";
} elseif($mediaURL) {
	$file = $mediaURL;
	$image = isset($_GET['image']) ? $_GET['image'] : '';
}

$unique = Rand(0,1000000);
$div = "jwplayer_unilad_" . $unique;

if($file && ($player || $player_url)) {
	echo "<html>";
		echo "<body>";
			if($player) {
				echo "<script src=\"https://content.jwplatform.com/libraries/$player.js\"></script>";			
			} else {
				echo "<script src=\"$player_url\"></script>";							
			}
			echo "<div id=\"$div\"></div>";
			echo "<script type=\"text/JavaScript\">";
				echo "playerInstance = jwplayer('$div');";
				echo "playerInstance.setup({ ";
					if($mediaID) {
						echo "playlist: '$file',\n";
						echo "image: '$image'\n";
					} else {
						echo "file: '$file',\n";
						echo "image: '$image'\n";
					}
				echo "});";
			echo "</script>";
		echo "</body>";
	echo "</html>";
}