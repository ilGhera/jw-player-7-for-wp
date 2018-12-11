<?php
/**
 * Video download
 * @author ilGhera
 * @package jw-player-for-vip/includes
 * @version 1.6.0
 */
	
$file = isset($_GET['file']) ? $_GET['file'] : '';
$title = $file ? basename($file) : '';
	
if($file && $title) {
	header("Content-type: application/x-file-to-save"); 
	header("Content-Disposition: attachment; filename=" . $title); 
	readfile($file);
}