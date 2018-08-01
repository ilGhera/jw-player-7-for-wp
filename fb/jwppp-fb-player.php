<?php

$mediaID = $_GET['mediaID'];

if ($mediaID) {
$file = "https://cdn.jwplayer.com/v2/media/".$mediaID;
$image = "http://content.jwplatform.com/thumbs/".$mediaID."-1920.jpg";

$unique = Rand ( 0,1000000);
$div = "jwplayer_unilad_".$unique;

}

?>

<html>
<body>
<script src="https://content.jwplatform.com/libraries/uTe0TMnD.js"></script>

<div id="<?php echo $div;?>">
</div>

<script type="text/JavaScript">
	
	playerInstance = jwplayer('<?php echo $div;?>');

	playerInstance.setup({ 
		playlist: '<?php echo $file;?>',
		image: '<?php echo $image;?>'
});
  
</script>
</body>
</html>