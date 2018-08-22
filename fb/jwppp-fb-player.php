<?php
$mediaID = isset($_GET['mediaID']) ? $_GET['mediaID'] : '';
$mediaURL = isset($_GET['mediaURL']) ? $_GET['mediaURL'] : '';
$file = null;
if($mediaID) {
	$file = "https://cdn.jwplayer.com/v2/media/".$mediaID;
	$image = "https://content.jwplatform.com/thumbs/".$mediaID."-1920.jpg";
} elseif($mediaURL) {
	$file = $mediaURL;
	$image = isset($_GET['image']) ? $_GET['image'] : '';
}

$unique = Rand ( 0,1000000);
$div = "jwplayer_unilad_" . $unique;

if($file) {
	?>
	<html>
		<body>
			<script src="https://content.jwplatform.com/libraries/uTe0TMnD.js"></script>
			<div id="<?php echo $div;?>"></div>
			<script type="text/JavaScript">
				playerInstance = jwplayer('<?php echo $div;?>');
				playerInstance.setup({ 
					<?php
					if($mediaID) {
						echo "playlist: '$file',\n";
						echo "image: '$image'\n";
					} else {
						echo "file: '$file',\n";
						echo "image: '$image'\n";
					}
					?>
				});
			</script>
		</body>
	</html>
	<?php
}