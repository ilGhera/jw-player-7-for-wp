<?php
//SKIN SELECT
$jwppp_skin = sanitize_text_field(get_option('jwppp-skin'));
if(isset($_POST['jwppp-skin'])) {
	$jwppp_skin = sanitize_text_field($_POST['jwppp-skin']);
	update_option('jwppp-skin', $jwppp_skin);
}