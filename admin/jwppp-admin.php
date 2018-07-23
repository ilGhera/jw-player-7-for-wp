<?php

/**
 * JW Player for Wordpress - Premium
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
	$jwppp_page = add_menu_page( 'JW Player for Wordpress - Premium', 'JW Player', 'manage_options', 'jw-player-for-wp', 'jwppp_options', 'dashicons-format-video');
	
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
        wp_enqueue_script( 'sh-video-script', plugin_dir_url(__DIR__) . 'js/jwppp-sh-video.js' );

        /*Select2*/
	    wp_enqueue_style('select2-style', plugin_dir_url(__DIR__) . 'css/select2.min.css');
	    wp_enqueue_script('select2', plugin_dir_url(__DIR__) . 'js/select2.min.js', array('jquery'));
	    wp_enqueue_script('jwppp-select2', plugin_dir_url(__DIR__) . 'js/jwppp-select2.js', array('jquery'));
    }
}
add_action( 'admin_enqueue_scripts', 'jwppp_add_color_picker' );


//VALIDATE COLOR
function jwppp_check_color( $value ) { 
    if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) || $value == '' ) {     
        return true;
    }
    return false;
}


//AJAX - CHECK THE PLAYER VERSION FOR SKIN CUSTOMIZATION
function skin_customization_per_version() {
	$admin_page = get_current_screen();
	if($admin_page->base === 'toplevel_page_jw-player-for-wp') {
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
	$version = isset($_POST['version']) ? sanitize_text_field($_POST['version']) : '';
	if($version){
		if($version < 8) {
			include(plugin_dir_path(__FILE__) . 'skin/jwppp-admin-skin-7.php');	
			update_option('jwppp-player-version', 7);	
		} else {
			include(plugin_dir_path(__FILE__) . 'skin/jwppp-admin-skin-8.php');		
			update_option('jwppp-player-version', 8);	
		}		
	}
	exit;
}
add_action('wp_ajax_skin-customization', 'skin_customization_per_version_callback');


//CHECK FOR CAPTION STYLE
function jwppp_caption_style() {
	$options = array(
		'jwppp-subtitles-color',
		'jwppp-subtitles-font-size',
		'jwppp-subtitles-font-family',
		'jwppp-subtitles-opacity',
		'jwppp-subtitles-back-color',
		'jwppp-subtitles-back-opacity'
	);
	foreach ($options as $option) {
		if(get_option($option)) {
			return true;
			continue;
		}
	}
	return false;
}


//CHECK IF THE PLAYER COMES FROM JWP DASHBOARD 
function is_dashboard_player($player = null) {
	
	$jwplayer = $player ? $player : get_option('jwppp-library');
	$output = false;

	if($jwplayer) {
		if(strpos($jwplayer, 'jwplatform.com') !== false) {
			$output = true;
		}
	}

	return $output;
}


//AJAX - UPDATE THE OPTIONS PAGE ON PLAYER LIBRARY CHANGES 
function jwppp_player_check() {
	$screen = get_current_screen();
	if($screen->id === 'toplevel_page_jw-player-for-wp') {
		?>
		<script>
			jQuery(document).ready(function($){
				$('#jwppp-library').on('change', function() {
					var player = $('#jwppp-library').val();
					var data = {
						'action': 'player_check',
						'player': player
					}
					$.post(ajaxurl, data, function(response){
						if(response === 'done') {
							location.reload();
						}
					})					
				})
			})
		</script>
		<?php
	}
}
add_action('admin_footer', 'jwppp_player_check');


//CALLBACK - UPDATE OPTIONS PAGE
function jwppp_player_check_callback() {
	$player = isset($_POST['player']) ? $_POST['player'] : '';
	if($player) {
		update_option('jwppp-library', $player);
		echo 'done';
	}
	exit;
}
add_action('wp_ajax_player_check', 'jwppp_player_check_callback');


//OPTION PAGE
function jwppp_options() {
	
	//CAN YOU?
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die(esc_html( __( 'It looks like you do not have sufficient permissions to view this page.', 'jwppp' )) );
	}

	//IS IT A DASHBOARD PLAYER?
	$dashboard_player = is_dashboard_player();

	//START PAGE TEMPLATE
	echo '<div class="wrap">'; 
	echo '<div class="wrap-left" style="float:left; width:70%;">';

	echo '<div id="jwppp-description">';
	    //HEADER
		echo "<h1 class=\"jwppp main\">" . esc_html(__( 'JW Player for Wordpress - Premium', 'jwppp' )) . "<span style=\"font-size:60%;\"> 1.5.2</span></h1>";
	echo '</div>';

	?>
	    
	<h2 id="jwppp-admin-menu" class="nav-tab-wrapper">
		<a href="#" data-link="jwppp-settings" class="nav-tab nav-tab-active" onclick="return false;"><?php esc_html_e( __('Settings', 'jwppp')); ?></a>
		<?php if(!$dashboard_player) { ?>
			<a href="#" data-link="jwppp-skin" class="nav-tab" onclick="return false;"><?php esc_html_e( __('Skin', 'jwppp')); ?></a>
			<a href="#" data-link="jwppp-subtitles" class="nav-tab" onclick="return false;"><?php esc_html_e( __('Subtitles', 'jwppp')); ?></a>
			<a href="#" data-link="jwppp-related" class="nav-tab" onclick="return false;"><?php esc_html_e( __('Related videos', 'jwppp')); ?></a>
			<a href="#" data-link="jwppp-social" class="nav-tab" onclick="return false;"><?php esc_html_e( __('Sharing', 'jwppp')); ?></a>    
			<a href="#" data-link="jwppp-ads" class="nav-tab" onclick="return false;"><?php esc_html_e( __('Ads', 'jwppp')); ?></a>
		<?php } ?>         
	</h2>


	<!-- START - SETTINGS -->
 	<div name="jwppp-settings" id="jwppp-settings" class="jwppp-admin" style="display: block;">

 		<?php

 			echo '<form id="jwppp-options" method="post" action="">';
 			echo '<table class="form-table">';

 			//PLUGIN PREMIUM KEY
			$key = sanitize_text_field(get_option('jwppp-premium-key'));
			if( isset($_POST['jwppp-premium-key']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
				$key = sanitize_text_field($_POST['jwppp-premium-key']);
				update_option('jwppp-premium-key', $key);
			}
			echo '<tr>';
			echo '<th scope="row">' . esc_html(__('Premium Key', 'jwppp')) . '</th>';
			echo '<td>';
			echo '<input type="text" class="regular-text" name="jwppp-premium-key" id="jwppp-premium-key" placeholder="' . esc_html(__('Add your Premium Key', 'jwppp' )) . '" value="' . esc_html($key) . '" />';
			echo '<p class="description">' . wp_kses(__('Please, paste here the <strong>Premium Key</strong> that you received buying this plugin.<br>You\'ll be able to keep upgraded with the new versions of JW Player for Wordpress - Premium.', 'jwppp'), array('strong' =>[], 'br' => [])) . '</p>';
			echo '</td>';
			echo '</tr>';

			//JW PLAYER LIBRARY URL
			$library = sanitize_text_field(get_option('jwppp-library'));
 			if( isset($_POST['jwppp-library']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
 				$library = sanitize_text_field($_POST['jwppp-library']);
 				update_option('jwppp-library', $library);
 			}

 			//JUST A LITTLE OF STYLE
 			echo '<style>';
 			echo '.question-mark {position:relative; float:right; top:2px; right:3rem;}';
 			echo '</style>';

 			echo '<tr>';
 			echo '<th scope="row">' . esc_html(__('Player library URL', 'jwppp'));
 			echo '<a href="https://www.ilghera.com/documentation/setup-the-player/" title="More informations" target="_blank"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
 			echo '<td>';
 			echo '<input type="text" class="regular-text" id="jwppp-library" name="jwppp-library" placeholder="https://content.jwplatform.com/libraries/jREFGDT.js" value="' . esc_url($library) . '" />';
 			echo '<p class="description">You can use a cloud or a self hosted library.</p>';
 			echo '</td>';
 			echo '</tr>';

 			if(!$dashboard_player) {

	 			//JW PLAYER LICENCE KEY
	 			$licence = sanitize_text_field(get_option('jwppp-licence'));
	 			if( isset($_POST['jwppp-licence']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
	 				$licence = sanitize_text_field($_POST['jwppp-licence']);
	 				update_option('jwppp-licence', $licence);
	 			}

	 			echo '<tr>';
	 			echo '<th scope="row">' . esc_html(__('JWP Licence Key', 'jwppp'));
	 			echo '<a href="https://www.ilghera.com/support/topic/jw-player-self-hosted-setup/" title="More informations" target="_blank"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
	 			echo '<td>';
	 			echo '<input type="text" class="regular-text" id="jwppp-licence" name="jwppp-licence" placeholder="Only for self-hosted players" value="' . sanitize_text_field($licence) . '" />';
	 			echo '<p class="description">' . sanitize_text_field(__('Self hosted player? Please, add your JW Player license key.', 'jwppp')) . '</p>';
	 			echo '</td>';
	 			echo '</tr>'; 				

 			} else {

	 			$api_key = sanitize_text_field(get_option('jwppp-api-key'));
	 			if( isset($_POST['jwppp-api-key']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
	 				$api_key = sanitize_text_field($_POST['jwppp-api-key']);
	 				update_option('jwppp-api-key', $api_key);
	 			}
	 			$api_secret = sanitize_text_field(get_option('jwppp-api-secret'));
	 			if( isset($_POST['jwppp-api-secret']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
	 				$api_secret = sanitize_text_field($_POST['jwppp-api-secret']);
	 				update_option('jwppp-api-secret', $api_secret);
	 			}

 				echo '<tr>';
	 			echo '<th scope="row">' . esc_html(__('API Credentials', 'jwppp'));
	 			echo '<td>';
	 			echo '<input type="text" class="regular-text" id="jwppp-api-key" name="jwppp-api-key" placeholder="' . esc_html(__('Add your API Key', 'jwppp')) . '" value="' . sanitize_text_field($api_key) . '" /><br>';
	 			echo '<input type="text" class="regular-text" id="jwppp-api-secret" name="jwppp-api-secret" placeholder="' . esc_html(__('Add your API Secret', 'jwppp')) . '" value="' . sanitize_text_field($api_secret) . '" />';
	 			echo '<p class="description">' . sanitize_text_field(__('Please add your API Credentials.', 'jwppp')) . '</p>';
	 			
				$api = new jwppp_dasboard_api();

				if($api->args_check() && !$api->account_validation()) {
					echo '<span class="jwppp-alert api">' . esc_html('It seems like your API Credentials are not correct.', 'jwppp') . '</span>';
				}

	 			echo '</td>';
 				echo '</tr>';

 			}

 			//POST TYPES WITH WHICH USE THE PLUGIN
 			$jwppp_get_types = get_post_types(array('public' => true));
 			$exclude = array('attachment', 'nav_menu_item');

 			echo '<tr>';
 			echo '<th scope="row">' . sanitize_text_field(__('Post types', 'jwppp')) . '</th>';
 			echo '<td>';

 			foreach($jwppp_get_types as $type) {
 				if(!in_array($type, $exclude)) {

 					$var_type = get_option('jwppp-type-' . $type);
					if( isset($_POST['done']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
						$var_type = isset($_POST[$type]) ? sanitize_text_field($_POST[$type]) : 0;
						update_option('jwppp-type-' . $type, $var_type);
					}
	 				echo '<input type="checkbox" name="' . esc_html($type) . '" id="' . esc_html($type) . '" value="1"';
	 				echo ($var_type === '1') ? 'checked="checked"' : '';
	 				echo ' /><span class="jwppp-type">' . ucfirst(esc_html($type)) . '</span><br>';
 				}
 			}
 			echo '<p class="description">' . esc_html(__('Select the type of content where display videos.', 'jwppp')) . '<br>';
 			echo '</td>';
 			echo '</tr>';

 			//BEFORE OR AFTER THE CONTENT
 			$position = get_option('jwppp-position');
 			if( isset($_POST['position']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
 				$position = sanitize_text_field($_POST['position']);
 				update_option('jwppp-position', $position);
 			}

 			echo '<th scope="row">' . esc_html(__('Video Player position', 'jwppp')) . '</th>';
 			echo '<td>';
 			echo '<select id="position" name="position" />';
 			echo '<option id="before-content"  name="before-content" value="before-content"';
 			echo ($position === 'before-content') ? ' selected="selected"' : '';
 			echo ' />' . esc_html(__('Before the content', 'jwppp'));
 			echo '<option id="after-content"  name="after-content" value="after-content"';
 			echo ($position === 'after-content') ? ' selected="selected"' : '';
 			echo ' />' . esc_html(__('After the content', 'jwppp'));
 			echo '<option id="custom"  name="custom" value="custom"';
 			echo ($position === 'custom') ? ' selected="selected"' : '';
 			echo ' />' . esc_html(__('Custom', 'jwppp'));
 			echo '</select>';
 			echo '<p class="description">' . esc_html(__('Select the location where you want the video player is displayed.', 'jwppp')) . '<br>';
 			echo wp_kses(__('For custom position use the shortcode <b>[jwp-video]</b>.', 'jwppp'), array('b' => [])) . '</p>';
 			echo '</td>';
 			echo '</tr>';

 			if(!$dashboard_player) {

	 			//TEXT
				$jwppp_text = sanitize_text_field(get_option('jwppp-text'));
				if(isset($_POST['jwppp-text']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_text = sanitize_text_field($_POST['jwppp-text']);
					update_option('jwppp-text', $jwppp_text);
				}

	 			echo '<tr>';
	 			echo '<th scope="row">' . esc_html(__('Video text', 'jwppp')) . '</th>';
	 			echo '<td>';
	 			echo '<textarea cols="40" rows="2" id="jwppp-text" name="jwppp-text" disabled="disabled" placeholder="' . esc_html(__('Loading the player...', 'jwppp')) . '"></textarea>';
	 			echo '<p class="description">' . esc_html(__('Add custom text that appears while the player is loading.', 'jwppp')) . '<br>';
	 			echo '</td>';
	 			echo '</tr>';

	 			//POSTER IMAGE
	 			$poster_image = sanitize_text_field(get_option('jwppp-poster-image'));
	 			if( isset($_POST['poster-image']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
	 				$poster_image = sanitize_text_field($_POST['poster-image']);
	 				update_option('jwppp-poster-image', $poster_image);
	 			}

	 			echo '<tr>';
	 			echo '<th scope="row">' . esc_html(__('Default poster image', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="text" class="regular-text" id="poster-image" name="poster-image" value="' . esc_url($poster_image) . '" />';
				echo '<p class="description">' . esc_html(__('Add the url of a default poster image.', 'jwppp')) . '</p>';
				echo '<td>';
	 			echo '</tr>';

	 			//POST THUMBNAIL AS POSTER IMAGE
	 			$thumbnail = sanitize_text_field(get_option('jwppp-post-thumbnail'));
	 			if( isset($_POST['done']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
	 				$thumbnail = isset($_POST['post-thumbnail']) ? sanitize_text_field($_POST['post-thumbnail']) : 0;
	 				update_option('jwppp-post-thumbnail', $thumbnail);
	 			}

	 			echo '<tr>';
	 			echo '<th scope="row">' . esc_html(__('Post thumbnail', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="checkbox" id="post-thumbnail" name="post-thumbnail" ';
				echo ($thumbnail === '1') ? ' checked="checked"' : '';
				echo 'value="1" />' . esc_html(__('Use the post thumbnail', 'jwppp'));
				echo '<p class="description">' . esc_html(__('When present, use the post thumbnail as poster image.', 'jwppp')) . '</p>';
				echo '<td>';
	 			echo '</tr>';

	 			//FIXED DIMENSIONS OR RESPONSIVE?
	 			$jwppp_method_dimensions = sanitize_text_field(get_option('jwppp-method-dimensions'));
	 			if(isset($_POST['jwppp-method-dimensions']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
	 				$jwppp_method_dimensions = sanitize_text_field($_POST['jwppp-method-dimensions']);
	 				update_option('jwppp-method-dimensions', $jwppp_method_dimensions);
	 			}

	 			//PLAYER FIXED WIDTH
	 			$jwppp_player_width = sanitize_text_field(get_option('jwppp-player-width'));
	 			if( isset($_POST['jwppp-player-width']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
	 				$jwppp_player_width = sanitize_text_field($_POST['jwppp-player-width']);
	 				update_option('jwppp-player-width', $jwppp_player_width);
	 			}

	 			//PLAYER FIXED HEIGHT
	 			$jwppp_player_height = sanitize_text_field(get_option('jwppp-player-height'));
	 			if( isset($_POST['jwppp-player-height']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
	 				$jwppp_player_height = sanitize_text_field($_POST['jwppp-player-height']);
	 				update_option('jwppp-player-height', $jwppp_player_height);
	 			}

	 			//PLAYER %
	 			$jwppp_responsive_width = sanitize_text_field(get_option('jwppp-responsive-width'));
	 			if(isset($_POST['jwppp-responsive-width']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
	 				$jwppp_responsive_width = sanitize_text_field($_POST['jwppp-responsive-width']);
	 				update_option('jwppp-responsive-width', $jwppp_responsive_width);
	 			}

	 			//PLAYER ASPECT RATIO
	 			$jwppp_aspectratio = sanitize_text_field(get_option('jwppp-aspectratio'));
	 			if(isset($_POST['jwppp-aspectratio']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
	 				$jwppp_aspectratio = sanitize_text_field($_POST['jwppp-aspectratio']);
	 				update_option('jwppp-aspectratio', $jwppp_aspectratio);
	 			}

	 			//FIXED DIMENSIONS OR RESPONSIVE? 
	 			echo '<tr>';
	 			echo '<th scope="row">Player dimensions</th>';
	 			echo '<td>';
	 			echo '<select id="jwppp-method-dimensions" name="jwppp-method-dimensions" />';
	 			echo '<option name="fixed" id="fixed" value="fixed" ';
	 			echo ($jwppp_method_dimensions === 'fixed') ? 'selected="selected"' : '';
	 			echo '>' . esc_html(__('Fixed', 'jwppp')) . '</option>';
	 			echo '<option name="responsive" id="responsive" value="responsive"';
	 			echo ($jwppp_method_dimensions === 'responsive') ? 'selected="selected"' : '';
	 			echo '>' . esc_html(__('Responsive', 'jwppp')) . '</option>';
	 			echo '</select>';
	 			echo '<p class="description">' . esc_html(__('Select how define the measures of the player.', 'jwppp')) . '<br>';
	 			echo '</td>';
	 			echo '</tr>';

	 			//PLAYER FIXED WIDTH & HEIGHT
	 			echo '<tr class="more-fixed">';
	 			echo '<th scope="row">' . esc_html(__('Fixed measures', 'jwppp')) . '</th>';
	 			echo '<td>';
	 			echo '<input type="number" min="1" step="1" class="small-text" id="jwppp-player-width" name="jwppp-player-width" value="';
				echo ($jwppp_player_width !== null) ? esc_html($jwppp_player_width) : '640';
				echo '" />';
				echo ' x ';
				echo '<input type="number" min="1" step="1" class="small-text" id="jwppp-player-height" name="jwppp-player-height" value="';
				echo ($jwppp_player_height !== null) ? esc_html($jwppp_player_height) : '360';
				echo '" />';
	 			echo '<p class="description"></p>';
	 			echo '</td>';
	 			echo '</tr>';

	 			//PLAYER %
				echo '<tr class="more-responsive">';
				echo '<th scope="row">' . esc_html(__('Player width', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="number" min="10" step="5" class="small-text" id="jwppp-responsive-width" name="jwppp-responsive-width" value="';
				echo ($jwppp_responsive_width !== null) ? sanitize_text_field($jwppp_responsive_width) : '100';
				echo '" /> %';
				echo '<p class="description">' . esc_html(__('Add the player\'s width (eg. 80%)', 'jwppp')) . '</p>';
				echo '</td>';
				echo '</tr>';

				//PLAYER ASPECT RATIO
				echo '<tr class="more-responsive">';
				echo '<th scope="row">' . esc_html(__('Aspect ratio', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<select id="jwppp-aspectratio" name="jwppp-aspectratio" class="small-text" />';
				echo '<option name="16:10" value="16:10"';
				echo ($jwppp_aspectratio === '16:10') ? ' selected="selected"' : '';
				echo '>16:10</option>';
				echo '<option name="16:9" value="16:9"';
				echo ($jwppp_aspectratio === '16:9') ? ' selected="selected"' : '';
				echo '>16:9</option>';
				echo '<option name="4:3" value="4:3"';
				echo ($jwppp_aspectratio === '4:3') ? ' selected="selected"' : '';
				echo '>4:3</option>';
				echo '</select>';
				echo '<p class="description">' . esc_html(__('Select the aspect ratio of the player', 'jwppp')) . '</p>';
				echo '</td>';
				echo '</tr>';


				//LOGO
				$jwppp_logo = sanitize_text_field(get_option('jwppp-logo'));
				if(isset($_POST['jwppp-logo']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_logo = sanitize_text_field($_POST['jwppp-logo']);
					update_option('jwppp-logo', $jwppp_logo);
				}

				//LOGO POSITION
				$jwppp_logo_vertical = sanitize_text_field(get_option('jwppp-logo-vertical'));
	 			if(isset($_POST['jwppp-logo-vertical']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
	 				$jwppp_logo_vertical = sanitize_text_field($_POST['jwppp-logo-vertical']);
	 				update_option('jwppp-logo-vertical', $jwppp_logo_vertical);
	 			}
				$jwppp_logo_horizontal = sanitize_text_field(get_option('jwppp-logo-horizontal'));
	 			if(isset($_POST['jwppp-logo-horizontal']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
	 				$jwppp_logo_horizontal = sanitize_text_field($_POST['jwppp-logo-horizontal']);
	 				update_option('jwppp-logo-horizontal', $jwppp_logo_horizontal);
	 			}

				//LOGO LINK
				$jwppp_logo_link = sanitize_text_field(get_option('jwppp-logo-link'));
				if(isset($_POST['jwppp-logo-link']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_logo_link = sanitize_text_field($_POST['jwppp-logo-link']);
					update_option('jwppp-logo-link', $jwppp_logo_link);
				}

				//NEXT UP
				$jwppp_next_up = sanitize_text_field(get_option('jwppp-next-up'));
				if(isset($_POST['jwppp-next-up']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_next_up = sanitize_text_field($_POST['jwppp-next-up']);
					update_option('jwppp-next-up', $jwppp_next_up);
				}


				//PLAYLIST TOOLTIP
				$jwppp_playlist_tooltip = sanitize_text_field(get_option('jwppp-playlist-tooltip'));
				if(isset($_POST['jwppp-playlist-tooltip']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_playlist_tooltip = sanitize_text_field($_POST['jwppp-playlist-tooltip']);
					update_option('jwppp-playlist-tooltip', $jwppp_playlist_tooltip);
				}


				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Logo', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="text" class="regular-text" id="jwppp-logo" name="jwppp-logo" disabled="disabled" ';
				echo 'placeholder="' . esc_html(__('Image url', 'jwppp')) . '" value="" />';
				echo '<p class="description">' . esc_html(__('Add your logo to the player.', 'jwppp')) . '<br>';
				echo '</td>';
				echo '</tr>';

				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Logo Position', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<select id="jwppp-logo-vertical" name="jwppp-logo-vertical" />';
				echo '<option id="top" name="top" value="top"';
				echo ($jwppp_logo_vertical === 'top') ? ' selected="selected"' : '';
				echo '>Top</option>';
				echo '<option id="bottom" name="bottom" value="bottom"';
				echo ($jwppp_logo_vertical === 'bottom') ? ' selected="selected"' : '';
				echo '>Bottom</option>';
				echo '</select>';
				echo '<select style="margin-left: 0.5rem;" id="jwppp-logo-horizontal" name="jwppp-logo-horizontal" />';
				echo '<option id="right" name="right" value="right"';
				echo ($jwppp_logo_horizontal === 'right') ? ' selected="selected"' : '';
				echo '>Right</option>';
				echo '<option id="left" name="left" value="left"';
				echo ($jwppp_logo_horizontal === 'left') ? ' selected="selected"' : '';
				echo '>Left</option>';
				echo '</select>';
				echo '<p class="description">' . esc_html(__('Choose the logo position.', 'jwppp')) . '</p>';
				echo '</td>';
				echo '</tr>';

				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Logo Link', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="text" class="regular-text" id="jwppp-logo-link" name="jwppp-logo-link" disabled="disabled" ';
				echo 'placeholder="' . esc_html(__('Link url', 'jwppp')) . '" value="" />';
				echo '<p class="description">' . esc_html(__('Add a link to the logo.', 'jwppp')) . '<br>';
				echo '</td>';
				echo '</tr>';

				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Next Up', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="text" class="regular-text" id="jwppp-next-up" name="jwppp-next-up" disabled="disabled" ';
				echo 'placeholder="' . esc_html(__('Next Up', 'jwppp')) . '" value="Next Up" />';
				echo '<p class="description">' . esc_html(__('Add a different text for the "Next Up" prompt', 'jwppp')) . '</p>';
				echo '</td>';
				echo '</tr>';

				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Playlist tooltip', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="text" class="regular-text" id="jwppp-playlist-tooltip" name="jwppp-playlist-tooltip" disabled="disabled"';
				echo 'placeholder="' . esc_html(__('Playlist', 'jwppp')) . '" value="Playlist" />';
				echo '<p class="description">' . esc_html(__('Add a different text for the tooltip in Playlist mode', 'jwppp')) . '</p>';
				echo '</td>';
				echo '</tr>';

			}

 			echo '</table>';

 			echo '<input type="hidden" name="done" value="1" />';

 			/*Add nonce to the form*/
 			wp_nonce_field('jwppp-nonce-options', 'hidden-nonce-options');

 			echo '<input type="submit" class="button button-primary" value="' . esc_html(__('Save changes ', 'jwppp')) . '" />';
 			echo '</form>';
 		?>
 	</div>
	<!-- END - SETTINGS -->

	
	<!-- START - SKIN -->
	<?php
	if(get_option('jwppp-player-version') == '7') {
		
		require(plugin_dir_path(__FILE__) . 'skin/jwppp-admin-skin-7-options.php');

	} elseif(get_option('jwppp-player-version') === '8') {
	
		require(plugin_dir_path(__FILE__) . 'skin/jwppp-admin-skin-8-options.php');

	}
	?>

	<div name="jwppp-skin" id="jwppp-skin" class="jwppp-admin" style="display: none;">
		<div class="jwppp-alert"><?php esc_html_e(__('Skin customization options depends from the JW Player version in use.<br>Please add first the <b><i>Player library URL</i></b>', 'jwppp')); ?></div>		
	</div>
	<!-- END - SKIN -->


	<?php include(plugin_dir_path(__FILE__) . 'jwppp-admin-subtitles.php'); ?>

	<?php include(plugin_dir_path(__FILE__) . 'jwppp-admin-related-videos.php'); ?>

	<?php include(plugin_dir_path(__FILE__) . 'jwppp-admin-sharing.php'); ?>

	<?php include(plugin_dir_path(__FILE__) . 'jwppp-admin-ads.php'); ?>


	</div><!-- WRAP LEFT -->
	<div class="wrap-right" style="float:left; width:30%; text-align:center; padding-top:3rem;">
		<iframe width="300" height="800" scrolling="no" src="http://www.ilghera.com/images/jwppp-premium-iframe.html"></iframe>
	</div>
	<div class="clear"></div>

</div>

<?php

}

//JWPPP FOOTER TEXT
function jwppp_footer_text($text) {
	$screen = get_current_screen();
	if($screen->id === 'toplevel_page_jw-player-for-wp') {
		$text = __('If you like <strong>JW Player for Wordpress - Premium</strong>, please give it a <a href="http://www.ilghera.com/product/jw-player-7-for-wordpress-premium" target="_blank">★★★★★</a> rating. Thanks in advance! ', 'jwppp');
	}

	$allowed_tags = array(
		'strong' => [],
		'a'		 => [
			'href'   => [],
			'target' => []
		]
	);

	echo wp_kses($text, $allowed_tags);
}
add_filter('admin_footer_text', 'jwppp_footer_text');


//UPDATE MESSAGE
function jwppp_update_message( $plugin_data, $response) {
	$key = get_option('jwppp-premium-key');
	$message = null;

	if(!$key) {

		$message = 'A <b>Premium Key</b> is required for keeping this plugin up to date. Please, add yours in the <a href="' . esc_url(admin_url()) . 'admin.php/?page=jw-player-for-wp">options page</a> or click <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">here</a> for prices and details.';
	
	} else {
	
		$decoded_key = explode('|', base64_decode($key));
	    $bought_date = date( 'd-m-Y', strtotime($decoded_key[1]));
	    $limit = strtotime($bought_date . ' + 365 day');
	    $now = strtotime('today');

	    if($limit < $now) { 
	        $message = 'It seems like your <strong>Premium Key</strong> is expired. Please, click <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">here</a> for prices and details.';
	    } elseif($decoded_key[2] !== '241') {
	    	$message = 'It seems like your <strong>Premium Key</strong> is not valid. Please, click <a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">here</a> for prices and details.';
	    }

	}

	$allowed_tags = array(
		'strong' => [],
		'a'		 => [
			'href'   => [],
			'target' => []
		]
	);

	echo ($message) ? '<br><span class="jwppp-alert">' . wp_kses($message, $allowed_tags) . '</span>' : '';

}
add_action('in_plugin_update_message-' . basename(dirname(__DIR__)) . '/jw-player-7-for-wp-premium.php', 'jwppp_update_message', 10, 2);