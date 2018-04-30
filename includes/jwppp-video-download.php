<?php

/*
 *
 * JW-PLAYER FOR WORDPRESS - PREMIUM
 * Video download
 *
 */ 
	
$file = $_GET['file'];
$title = basename($file);
	
header("Content-type: application/x-file-to-save"); 
header("Content-Disposition: attachment; filename=".$title); 
readfile($file);
