<?php

/**
 * JW Player for Wordpress
 * Administration functions
 */

//GET THE SCRIPT REQUIRED FROM THE MENU
function jwppp_register_js_menu() {
	wp_register_script('jwppp-admin-nav', plugin_dir_url(__DIR__) . 'js/jwppp-admin-nav.js', array('jquery'), '1.0', true );
	wp_enqueue_style('jwppp-admin-style', plugin_dir_url(__DIR__) . 'css/jwppp-admin-style.css');
}
add_action( 'admin_init', 'jwppp_register_js_menu' );


function jwppp_js_menu() {
	wp_enqueue_script('jwppp-admin-nav');
}
add_action( 'admin_menu', 'jwppp_js_menu' );


//MENU ITEMS
function jwppp_add_menu() {
	$jwppp_page = add_menu_page( 'JW Player for Wordpress', 'JW Player', 'manage_options', 'jw-player-for-wp', 'jwppp_options', 'dashicons-format-video');
	
	//SCRIPT
	add_action( 'admin_print_scripts-' . $jwppp_page, 'jwppp_js_menu');
	
	return $jwppp_page;
}
add_action( 'admin_menu', 'jwppp_add_menu' );


//ADD COLOR PICKER
function jwppp_add_color_picker() {
    if( is_admin() ) { 
        wp_enqueue_style( 'wp-color-picker' );          
        wp_enqueue_script( 'wp-color-picker', array('jquery'), '', true ); 
    }
}
add_action( 'admin_enqueue_scripts', 'jwppp_add_color_picker' );


//AJAX - CHECK THE PLAYER VERSION FOR SKIN CUSTOMIZATION
function skin_customization_per_version() {
	$admin_page = get_current_screen();
	if($admin_page->base == 'toplevel_page_jw-player-for-wp') {
		$ajaxurl = admin_url("admin-ajax.php");
		$jwplayer = get_option('jwppp-library');
		?>
		<script>
			jQuery(document).ready(function($){
				
				var ajaxurl = '<?php echo $ajaxurl; ?>';
				var player = '<?php echo $jwplayer; ?>';

				$.getScript(player, function(){
					var version  = jwplayer.version;
					var data = {
						'action': 'skin-customization',
						'version': version.split('+')[0]
					}				
					$.post(ajaxurl, data, function(response) {
						$('#jwppp-skin').html(response);
					    $('.jwppp-color-field').wpColorPicker();

				        //CUSTOM SKIN
					    if( $('#jwppp-skin option:selected').attr('value') == 'custom-skin' ) {
					            $('.custom-skin-url, .custom-skin-name').show();
					    } else {
					        $('.custom-skin-url, .custom-skin-name').hide();
					    }
					    
					    $('#jwppp-skin').on('change', function(){
					        if( $('option:selected', this).attr('value') == 'custom-skin' ) {
					            $('.custom-skin-url, .custom-skin-name').show();
					        } else {
					            $('.custom-skin-url, .custom-skin-name').hide();
					        }
					    })

					})
				});
			})
		</script>
		<?php
	}
}
add_action('admin_footer', 'skin_customization_per_version');


function skin_customization_per_version_callback() {
	$version = $_POST['version'];
	if($version < 8) {
		include('skin/jwppp-admin-skin-7.php');	
		update_option('jwppp-player-version', 7);	
	} else {
		include('skin/jwppp-admin-skin-8.php');		
		update_option('jwppp-player-version', 8);	
	}
	exit;
}
add_action('wp_ajax_skin-customization', 'skin_customization_per_version_callback');


//OPTION PAGE
function jwppp_options() {
	
	//CAN YOU?
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'It looks like you do not have sufficient permissions to view this page.', 'jwppp' ) );
	}

//INIZIO TEMPLATE DI PAGINA
echo '<div class="wrap">'; 
echo '<div class="wrap-left" style="float:left; width:70%;">';

	echo '<div id="jwppp-description">';
	    //HEADER
		echo "<h1 class=\"jwppp main\">" . __( 'JW Player for Wordrpess', 'jwppp' ) . "<span style=\"font-size:60%;\"> 1.5.1</span></h1>";
	echo '</div>';

?>
	    
	<h2 id="jwppp-admin-menu" class="nav-tab-wrapper">
		<a href="#" data-link="jwppp-settings" class="nav-tab nav-tab-active" onclick="return false;"><?php echo __('Settings', 'jwppp'); ?></a>
		<a href="#" data-link="jwppp-skin" class="nav-tab" onclick="return false;"><?php echo __('Skin', 'jwppp'); ?></a>
		<a href="#" data-link="jwppp-subtitles" class="nav-tab" onclick="return false;"><?php echo __('Subtitles', 'jwppp'); ?></a>
		<a href="#" data-link="jwppp-related" class="nav-tab" onclick="return false;"><?php echo __('Related videos', 'jwppp'); ?></a>
		<a href="#" data-link="jwppp-social" class="nav-tab" onclick="return false;"><?php echo __('Sharing', 'jwppp'); ?></a>    
		<a href="#" data-link="jwppp-ads" class="nav-tab" onclick="return false;"><?php echo __('Ads', 'jwppp'); ?></a>                                        
	</h2>


	<!-- START - SETTINGS -->
 	<div name="jwppp-settings" id="jwppp-settings" class="jwppp-admin" style="display: block;">

 		<?php

 			echo '<form id="jwppp-options" method="post" action="">';
 			echo '<table class="form-table">';

			//JW PLAYER LIBRARY URL
			$library = sanitize_text_field(get_option('jwppp-library'));
 			if(isset($_POST['jwppp-library'])) {
 				$library = sanitize_text_field($_POST['jwppp-library']);
 				update_option('jwppp-library', $library);
 			}

 			//JUST A LITTLE OF STYLE
 			echo '<style>';
 			echo '.question-mark {position:relative; float:right; top:2px; right:3rem;}';
 			echo '</style>';

 			echo '<tr>';
 			echo '<th scope="row">' . __('Player library URL', 'jwppp');
 			echo '<a href="https://www.ilghera.com/documentation/setup-the-player/" title="More informations" target="_blank"><img class="question-mark" src="' . plugin_dir_url(__DIR__) . 'images/question-mark.png" /></a></th>';
 			echo '<td>';
 			echo '<input type="text" class="regular-text" id="jwppp-library" name="jwppp-library" placeholder="https://content.jwplatform.com/libraries/jREFGDT.js" value="' . $library . '" />';
 			echo '<p class="description">You can use a cloud or a self hosted library.</p>';
 			echo '</td>';
 			echo '</tr>';

 			//JW PLAYER LICENCE KEY
 			$licence = sanitize_text_field(get_option('jwppp-licence'));
 			if(isset($_POST['jwppp-licence'])) {
 				$licence = sanitize_text_field($_POST['jwppp-licence']);
 				update_option('jwppp-licence', $licence);
 			}
 			echo '<tr>';
 			echo '<th scope="row">' . __('JWP Licence Key', 'jwppp');
 			echo '<a href="https://www.ilghera.com/support/topic/jw-player-self-hosted-setup/" title="More informations" target="_blank"><img class="question-mark" src="' . plugin_dir_url(__DIR__) . 'images/question-mark.png" /></a></th>';
 			echo '<td>';
 			echo '<input type="text" class="regular-text" id="jwppp-licence" name="jwppp-licence" placeholder="Only for self-hosted players" value="' . $licence . '" />';
 			echo '<p class="description">' . __('Self hosted player? Please, add your JW Player license key.', 'jwppp') . '</p>';
 			echo '</td>';
 			echo '</tr>';

 			//POST TYPES WITH WHICH USE THE PLUGIN
 			$jwppp_get_types = array('post', 'page');

 			echo '<tr>';
 			echo '<th scope="row">' . __('Post types', 'jwppp') . '</th>';
 			echo '<td>';

 			foreach($jwppp_get_types as $type) {
 					$var_type = get_option('jwppp-type-' . $type);
 					if(isset($_POST['done'])) {
 						$var_type = isset($_POST[$type]) ? $_POST[$type] : 0;
 						update_option('jwppp-type-' . $type, $var_type);
 					}
	 				echo '<input type="checkbox" name="' . $type . '" id="' . $type . '" value="1"';
	 				echo ($var_type == 1) ? 'checked="checked"' : '';
	 				echo ' /><span class="jwppp-type">' . ucfirst($type) . '</span><br>';
 			}
 			echo '<p class="description">' . __('Select the type of content where display videos.', 'jwppp') . '<br>';
 			echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a> ' . __('for custom post types.', 'jwppp') . '</p>';
 			echo '</td>';
 			echo '</tr>';


 			//BEFORE OR AFTER THE CONTENT
 			$position = get_option('jwppp-position');
 			if(isset($_POST['position'])) {
 				$position = $_POST['position'];
 				update_option('jwppp-position', $_POST['position']);
 			}

 			echo '<th scope="row">' . __('Video Player position', 'jwppp') . '</th>';
 			echo '<td>';
 			echo '<select id="position" name="position" />';
 			echo '<option id="before-content"  name="before-content" value="before-content"';
 			echo ($position == 'before-content') ? ' selected="selected"' : '';
 			echo ' />' . __('Before the content', 'jwppp');
 			echo '<option id="after-content"  name="after-content" value="after-content"';
 			echo ($position == 'after-content') ? ' selected="selected"' : '';
 			echo ' />' . __('After the content', 'jwppp');
 			echo '<option id="custom"  name="custom" value="custom"';
 			echo ($position == 'custom') ? ' selected="selected"' : '';
 			echo ' />' . __('Custom', 'jwppp');
 			echo '</select>';
 			echo '<p class="description">' . __('Select the location where you want the video player is displayed.', 'jwppp') . '<br>';
 			echo __('For custom position use the shortcode <b>[jwp-video]</b>.', 'jwppp') . '</p>';
 			echo '</td>';
 			echo '</tr>';

 			//TEXT
 			echo '<tr>';
 			echo '<th scope="row">' . __('Video text', 'jwppp') . '</th>';
 			echo '<td>';
 			echo '<textarea cols="40" rows="2" id="jwppp-text" name="jwppp-text" disabled="disabled" placeholder="' . __('Loading the player...', 'jwppp') . '"></textarea>';
 			echo '<p class="description">' . __('Add custom text that appears while the player is loading.', 'jwppp') . '<br>';
 			echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
 			echo '</td>';
 			echo '</tr>';

 			//POSTER IMAGE
 			$poster_image = sanitize_text_field(get_option('jwppp-poster-image'));
 			if(isset($_POST['poster-image'])) {
 				$poster_image = sanitize_text_field($_POST['poster-image']);
 				update_option('jwppp-poster-image', $poster_image);
 			}

 			echo '<tr>';
 			echo '<th scope="row">' . __('Default poster image', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="poster-image" name="poster-image" value="' . $poster_image . '" />';
			echo '<p class="description">' . __('Add the url of a default poster image.', 'jwppp') . '</p>';
			echo '<td>';
 			echo '</tr>';

 			//POST THUMBNAIL AS POSTER IMAGE
 			$thumbnail = sanitize_text_field(get_option('jwppp-post-thumbnail'));
 			if(isset($_POST['done'])) {
 				$thumbnail = isset($_POST['post-thumbnail']) ? $_POST['post-thumbnail'] : 0;
 				update_option('jwppp-post-thumbnail', $thumbnail);
 			}

 			echo '<tr>';
 			echo '<th scope="row">' . __('Post thumbnail', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="checkbox" id="post-thumbnail" name="post-thumbnail" ';
			echo ($thumbnail == 1) ? ' checked="checked"' : '';
			echo 'value="1" />' . __('Use the post thumbnail', 'jwppp');
			echo '<p class="description">' . __('When present, use the post thumbnail as poster image.', 'jwppp') . '</p>';
			echo '<td>';
 			echo '</tr>';
 		
 			//PLAYER FIXED WIDTH
 			$jwppp_player_width = sanitize_text_field(get_option('jwppp-player-width'));
 			if(isset($_POST['jwppp-player-width'])) {
 				$jwppp_player_width = sanitize_text_field($_POST['jwppp-player-width']);
 				update_option('jwppp-player-width', $jwppp_player_width);
 			}

 			//PLAYER FIXED HEIGHT
 			$jwppp_player_height = sanitize_text_field(get_option('jwppp-player-height'));
 			if(isset($_POST['jwppp-player-height'])) {
 				$jwppp_player_height = sanitize_text_field($_POST['jwppp-player-height']);
 				update_option('jwppp-player-height', $jwppp_player_height);
 			}

 			//FIXED DIMENSIONS OR RESPONSIVE? 
 			echo '<tr>';
 			echo '<th scope="row">Player dimensions</th>';
 			echo '<td>';
 			echo '<select id="jwppp-method-dimensions" name="jwppp-method-dimensions" disabled="disabled" />';
 			echo '<option name="fixed" id="fixed" value="fixed" ';
 			echo 'selected="selected"';
 			echo '>' . __('Fixed', 'jwppp') . '</option>';
 			echo '</select>';
 			echo '<p class="description">' . __('Select how define the measures of the player.', 'jwppp') . '<br>';
 			echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a> for a responsive player</p>';
 			echo '</td>';
 			echo '</tr>';

 			//PLAYER FIXED WIDTH & HEIGHT
 			echo '<tr class="more-fixed">';
 			echo '<th scope="row">' . __('Fixed measures', 'jwppp') . '</th>';
 			echo '<td>';
 			echo '<input type="number" min="1" step="1" class="small-text" id="jwppp-player-width" name="jwppp-player-width" value="';
			echo ($jwppp_player_width != null) ? $jwppp_player_width : '640';
			echo '" />';
			echo ' x ';
			echo '<input type="number" min="1" step="1" class="small-text" id="jwppp-player-height" name="jwppp-player-height" value="';
			echo ($jwppp_player_height != null) ? $jwppp_player_height : '360';
			echo '" />';
 			echo '<p class="description"></p>';
 			echo '</td>';
 			echo '</tr>';

			//LOGO
			echo '<tr>';
			echo '<th scope="row">' . __('Logo', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="jwppp-logo" name="jwppp-logo" disabled="disabled" ';
			echo 'placeholder="' . __('Image url', 'jwppp') . '" value="" />';
			echo '<p class="description">' . __('Add your logo to the player.', 'jwppp') . '<br>';
 			echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
			echo '</td>';
			echo '</tr>';

			//LOGO POSITION
			echo '<tr>';
			echo '<th scope="row">' . __('Logo Position', 'jwppp') . '</th>';
			echo '<td>';
			echo '<select id="jwppp-logo-vertical" name="jwppp-logo-vertical" disabled="disabled"/>';
			echo '<option id="top" name="top" value="top" selected="selected">Top</option>';
			echo '<option id="bottom" name="bottom" value="bottom">Bottom</option>';
			echo '</select>';
			echo '<select style="margin-left: 0.5rem;" id="jwppp-logo-horizontal" name="jwppp-logo-horizontal" disabled="disabled" />';
			echo '<option id="right" name="right" value="right" selected="selected">Right</option>';
			echo '<option id="left" name="left" value="left">Left</option>';
			echo '</select>';
			echo '<p class="description">' . __('Choose the logo position.', 'jwppp') . '</p>';
 			echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
			echo '</td>';
			echo '</tr>';
			
			//LOGO LINK
			echo '<tr>';
			echo '<th scope="row">' . __('Logo Link', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="jwppp-logo-link" name="jwppp-logo-link" disabled="disabled" ';
			echo 'placeholder="' . __('Link url', 'jwppp') . '" value="" />';
			echo '<p class="description">' . __('Add a link to the logo.', 'jwppp') . '<br>';
 			echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<th scope="row">' . __('Next Up', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="jwppp-next-up" name="jwppp-next-up" disabled="disabled" ';
			echo 'placeholder="' . __('Next Up', 'jwppp') . '" value="Next Up" />';
			echo '<p class="description">' . __('Add a different text for the "Next Up" prompt', 'jwppp') . '</p>';
 			echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';	
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<th scope="row">' . __('Playlist tooltip', 'jwppp') . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" id="jwppp-playlist-tooltip" name="jwppp-playlist-tooltip" disabled="disabled"';
			echo 'placeholder="' . __('Playlist', 'jwppp') . '" value="Playlist" />';
			echo '<p class="description">' . __('Add a different text for the tooltip in Playlist mode', 'jwppp') . '</p>';
 			echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';	
			echo '</td>';
			echo '</tr>';


 			echo '</table>';

 			echo '<input type="hidden" name="done" value="1" />';
 			echo '<input type="submit" class="button button-primary" value="' . __('Save changes ', 'jwppp') . '" />';
 			echo '</form>';
 		?>
 	</div>
	<!-- END - SETTINGS -->


	<!-- START - SKIN -->
	<?php
	if(get_option('jwppp-player-version') == 7) {
		require('skin/jwppp-admin-skin-7-options.php');
	}
	?>

	<div name="jwppp-skin" id="jwppp-skin" class="jwppp-admin" style="display: none;">
		<div class="jwppp-alert"><?php echo __('Skin customization options depends from the JW Player version in use.<br>Please add first the <b><i>Player library URL</i></b>', 'jwppp'); ?></div>		
	</div>
	<!-- END - SKIN -->


	<?php include('jwppp-admin-subtitles.php'); ?>

	<?php include('jwppp-admin-related-videos.php'); ?>

	<?php include('jwppp-admin-sharing.php'); ?>

	<?php include('jwppp-admin-ads.php'); ?>


	</div><!-- WRAP LEFT -->
	<div class="wrap-right" style="float:left; width:30%; text-align:center; padding-top:3rem;">
		<iframe width="300" height="800" scrolling="no" src="https://www.ilghera.com/images/jwppp-iframe.html"></iframe>
	</div>
	<div class="clear"></div>

</div>

<?php

}

//JWPPP FOOTER TEXT
function jwppp_footer_text($text) {
	$screen = get_current_screen();
	if($screen->id == 'toplevel_page_jw-player-for-wp') {
		$text = __('If you like <strong>JW Player for Wordpress</strong>, please give it a <a href="https://wordpress.org/support/view/plugin-reviews/jw-player-7-for-wp?filter=5#postform" target="_blank">★★★★★</a> rating. Thanks in advance! ', 'jwppp');
		echo $text;
	} else {
		echo $text;
	}
}
add_filter('admin_footer_text', 'jwppp_footer_text');
