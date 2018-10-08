<?php
/**
 * Plugin options page
 * @author ilGhera
 * @package jw-player-7-for-wp/admin
 * @version 1.6.0
 */

/**
 * Register plugin scripts and style
 */
function jwppp_register_js_menu() {
	wp_register_script('jwppp-admin', plugin_dir_url(__DIR__) . 'js/jwppp-admin.js', array('jquery'), '1.0', true );
	wp_enqueue_style('jwppp-admin-style', plugin_dir_url(__DIR__) . 'css/jwppp-admin-style.css');
}
add_action( 'admin_init', 'jwppp_register_js_menu' );


/**
 * Menu's script
 */
function jwppp_js_menu() {
	wp_enqueue_script('jwppp-admin');
}
add_action( 'admin_menu', 'jwppp_js_menu' );


/**
 * Enqueue different scripts
 */
function jwppp_enqueue_scripts() {
    if(is_admin()) { 

    	/*ColorPicker*/
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker', array('jquery'), '', true); 
        wp_enqueue_script('jwppp-single-video', plugin_dir_url(__DIR__) . 'js/jwppp-single-video.js');

	    wp_enqueue_script('jwppp-select', plugin_dir_url(__DIR__) . 'js/jwppp-select.js', array('jquery'));
	    wp_localize_script('jwppp-select', 'jwppp_select', array(
		    'pluginUrl' => plugin_dir_url(__DIR__)
		));
    }
}
add_action('admin_enqueue_scripts', 'jwppp_enqueue_scripts');


/**
 * ColorPicker color validation
 * @param  string $value the color choosed by the user
 * @return bool
 */
function jwppp_check_color($value) { 
    if (preg_match( '/^#[a-f0-9]{6}$/i', $value ) || $value == '') {     
        return true;
    }
    return false;
}


/**
 * Register menu page
 */
function jwppp_add_menu() {
	$jwppp_page = add_menu_page('JW Player for Wordpress - Premium', 'JW Player', 'manage_options', 'jw-player-for-wp', 'jwppp_options', 'dashicons-format-video');
	
	add_action('admin_print_scripts-' . $jwppp_page, 'jwppp_js_menu');
	
	return $jwppp_page;
}
add_action('admin_menu', 'jwppp_add_menu');


/**
 * Ajax - Check the player version for skin customization
 */
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

				        /*Custom skin*/
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


/**
 * Callback - Skin customization by player version
 */
function skin_customization_by_version_callback() {
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
add_action('wp_ajax_skin-customization', 'skin_customization_by_version_callback');


/**
 * Check for caption style
 */
function jwppp_caption_style() {
	$options = array(
		'jwppp-subtitles-color',
		'jwppp-subtitles-font-size',
		'jwppp-subtitles-font-family',
		'jwppp-subtitles-opacity',
		'jwppp-subtitles-back-color',
		'jwppp-subtitles-back-opacity'
	);
	foreach($options as $option) {
		if(get_option($option)) {
			return true;
		}
	}
	return false;
}


/**
 * Check if the player comes from jwp dashboard
 * @param  string  $player a given player to check
 * @return boolean
 */
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


/**
 * Ajax - Update the options page on player library change
 */
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


/**
 * Callback - Update options page
 * Save the new player into db
 */
function jwppp_player_check_callback() {
	$player = isset($_POST['player']) ? sanitize_text_field($_POST['player']) : '';
	if($player) {
		update_option('jwppp-library', $player);
		echo 'done';
	}
	exit;
}
add_action('wp_ajax_player_check', 'jwppp_player_check_callback');


/**
 * Single ads tag used in ajax callback funztion 
 * @param  int    $n   the video number
 * @param  string $tag the ads tag
 * @return mixed  the html element with url and label
 */
function jwppp_ads_tag($n, $tag='') {
	
	$ad_url = isset($tag['url']) ? $tag['url'] : $tag;
	$ad_label = isset($tag['label']) ? sanitize_text_field($tag['label']) : '';

	$output = '<li>';
		$output .= '<input type="text" class="regular-text" id="jwppp-ads-tag" name="jwppp-ads-tag-' . esc_attr($n) . '" placeholder="' . esc_html(__('Add the url of your XML file.', 'jwppp')) . '" value="' . $ad_url . '" />';
		$output .= '<input type="text" id="jwppp-ads-tag-label" name="jwppp-ads-tag-label' . esc_attr($n) . '" placeholder="' . esc_html(__('Add a label for this tag', 'jwppp')) . '" value="' . esc_html($ad_label) . '" />';

		if($n === 1) {

			$output .= '<div class="add-tag-container">';
				$output .= '<img class="add-tag" src="' . plugin_dir_url(__DIR__) . 'images/add-tag.png">';
				$output .= '<img class="add-tag-hover" src="' . plugin_dir_url(__DIR__) . 'images/add-tag-hover.png">';
			$output .= '</div>';				

		} else {

			$output .= '<div class="remove-tag-container">';
				$output .= '<img class="remove-tag" src="' . plugin_dir_url(__DIR__) . 'images/remove-tag.png">';
				$output .= '<img class="remove-tag-hover" src="' . plugin_dir_url(__DIR__) . 'images/remove-tag-hover.png">';
			$output .= '</div>';

		}
	$output .= '</li>';

	return $output;
}


/**
 * Callback - add a new ads tag
 */
function jwppp_ads_tag_callback() {
	$n = isset($_POST['number']) ? sanitize_text_field($_POST['number']) : '';
	if($n) {
		echo jwppp_ads_tag($n);
	}
	exit;
}
add_action('wp_ajax_add_ads_tag', 'jwppp_ads_tag_callback');


/**
 * Save the ads var name in the db
 */
function jwppp_ads_var_callback() {

	$tag = isset($_POST['tag']) ? $_POST['tag'] : '';
	update_option('jwppp-ads-var', $tag);

	exit;
}
add_action('wp_ajax_ads-var-name', 'jwppp_ads_var_callback');


/**
 * Plugin options page
 */
function jwppp_options() {
	
	/*Verify if the user can access*/
	if (!current_user_can('manage_options')) {
		wp_die(esc_html( __( 'It looks like you do not have sufficient permissions to view this page.', 'jwppp' )) );
	}

	/*Is it a dashboard player?*/
	$dashboard_player = is_dashboard_player();

	/*Start page template*/
	echo '<div class="wrap">'; 
	echo '<div class="wrap-left" style="float:left; width:70%;">';

	echo '<div id="jwppp-description">';
		echo "<h1 class=\"jwppp main\">" . esc_html(__( 'JW Player for Wordpress - Premium', 'jwppp' )) . "<span style=\"font-size:60%;\"> 1.5.2</span></h1>";
	echo '</div>';

	?>
	
	<!-- Tabs menu: the options are based on which kind of player the publisher is using -->
	<h2 id="jwppp-admin-menu" class="nav-tab-wrapper">
		<a href="#" data-link="jwppp-settings" class="nav-tab nav-tab-active" onclick="return false;"><?php esc_html_e( __('Settings', 'jwppp')); ?></a>
		<?php if(!$dashboard_player) { ?>
			<a href="#" data-link="jwppp-skin" class="nav-tab" onclick="return false;"><?php esc_html_e( __('Skin', 'jwppp')); ?></a>
			<a href="#" data-link="jwppp-subtitles" class="nav-tab" onclick="return false;"><?php esc_html_e( __('Subtitles', 'jwppp')); ?></a>
			<a href="#" data-link="jwppp-social" class="nav-tab" onclick="return false;"><?php esc_html_e( __('Sharing', 'jwppp')); ?></a>    
		<?php } else { ?>
			<a href="#" data-link="jwppp-playlist-carousel" class="nav-tab" onclick="return false;"><?php esc_html_e( __('Playlist carousel', 'jwppp')); ?></a>    
		<?php } ?>		         
		<a href="#" data-link="jwppp-ads" class="nav-tab" onclick="return false;"><?php esc_html_e( __('Ads', 'jwppp')); ?></a>
	</h2>

 	<div name="jwppp-settings" id="jwppp-settings" class="jwppp-admin" style="display: block;">

 		<?php
		echo '<form id="jwppp-options" method="post" action="">';
		echo '<table class="form-table">';

		/*JW Player library url*/
		$library = sanitize_text_field(get_option('jwppp-library'));
		if( isset($_POST['jwppp-library']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
			$library = sanitize_text_field($_POST['jwppp-library']);
			update_option('jwppp-library', $library);
		}

		/*Style*/
		echo '<style>';
		echo '.question-mark {position:relative; float:right; top:2px; right:3rem;}';
		echo '</style>';

		echo '<tr>';
		echo '<th scope="row">' . esc_html(__('Player library URL', 'jwppp'));
		echo '<a href="https://www.ilghera.com/documentation/setup-the-player/" title="More informations" target="_blank"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
		echo '<td>';
		echo '<input type="text" class="regular-text" id="jwppp-library" name="jwppp-library" placeholder="https://content.jwplatform.com/libraries/jREFGDT.js" value="' . esc_url($library) . '" />';
		echo '<p class="description">' . esc_html(__('Cloud-hosted or self-hosted player library URL', 'jwppp')) . '</p>';
		echo '</td>';
		echo '</tr>';

		if(!$dashboard_player) {

			/**
			 * For self-hosted player
			 * JW Player licence key
			 */
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

			/**
			 * For cloud player
			 * Api credentials
			 */
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
			echo '<input type="text" class="regular-text" id="jwppp-api-key" name="jwppp-api-key" placeholder="' . esc_html(__('Add your API Key', 'jwppp')) . '" value="' . esc_html($api_key) . '" /><br>';
			echo '<input type="text" class="regular-text" id="jwppp-api-secret" name="jwppp-api-secret" placeholder="' . esc_html(__('Add your API Secret', 'jwppp')) . '" value="' . esc_html($api_secret) . '" />';
			echo '<p class="description">' . esc_html(__('API Key and Secret.', 'jwppp')) . '</p>';
			
			/*Api class instance*/
			$api = new jwppp_dasboard_api();

			/*Credentials validation*/
			if($api->args_check() && !$api->account_validation()) {
				echo '<span class="jwppp-alert api">' . esc_html('It seems like your API Credentials are not correct.', 'jwppp') . '</span>';
			}
				echo '</td>';
				echo '</tr>';
			}

			/*Post types selection*/
			$jwppp_get_types = get_post_types(array('public' => true));
			$exclude = array('attachment', 'nav_menu_item');

			echo '<tr>';
			echo '<th scope="row">' . esc_html(__('Post types', 'jwppp')) . '</th>';
			echo '<td>';

			foreach($jwppp_get_types as $type) {

				if(!in_array($type, $exclude)) {

					$var_type = sanitize_text_field(get_option('jwppp-type-' . $type));

				if( isset($_POST['done']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {

					$var_type = isset($_POST[$type]) ? sanitize_text_field($_POST[$type]) : 0;
					update_option('jwppp-type-' . $type, $var_type);

				}
					echo '<input type="checkbox" name="' . esc_html($type) . '" id="' . esc_html($type) . '" value="1"';
					echo ($var_type === '1') ? 'checked="checked"' : '';
					echo ' /><span class="jwppp-type">' . ucfirst(esc_html($type)) . '</span><br>';
				}

			}

			echo '<p class="description">' . esc_html(__('Content types that use video.', 'jwppp')) . '<br>';
			echo '</td>';
			echo '</tr>';

			/*Player position*/
			$position = sanitize_text_field(get_option('jwppp-position'));
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
			echo '<p class="description">';
			echo wp_kses(
				__('Position of the video embed. Custom position requires use of shortcode <b>[jwp-video]</b> provided by the plugin.', 'jwppp'),
				array(
					'b' => []
				)
			);
			echo '</p>';
			echo '</td>';
			echo '</tr>';

			if(!$dashboard_player) {

				/*The message shown to the user before the player is been embed*/
				$jwppp_text = sanitize_text_field(get_option('jwppp-text'));
				if(isset($_POST['jwppp-text']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_text = sanitize_text_field($_POST['jwppp-text']);
					update_option('jwppp-text', $jwppp_text);
				}

				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Video Loading Message', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<textarea cols="40" rows="2" id="jwppp-text" name="jwppp-text" placeholder="' . esc_html(__('Loading the player...', 'jwppp')) . '">' . esc_html($jwppp_text) . '</textarea>';
				echo '<p class="description">' . esc_html(__('Video loading message.', 'jwppp')) . '<br>';
				echo '</td>';
				echo '</tr>';


				/*Poster image*/
				$poster_image = sanitize_text_field(get_option('jwppp-poster-image'));
				if( isset($_POST['poster-image']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
					$poster_image = sanitize_text_field($_POST['poster-image']);
					update_option('jwppp-poster-image', $poster_image);
				}

				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Default poster image', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="text" class="regular-text" id="poster-image" name="poster-image" value="' . esc_url($poster_image) . '" />';
				echo '<p class="description">' . esc_html(__('Default poster image URL.', 'jwppp')) . '</p>';
				echo '<td>';
				echo '</tr>';


				/*Post thumbnail as poster image*/
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
				echo '<p class="description">' . esc_html(__('Use post thumbnail as poster image.', 'jwppp')) . '</p>';
				echo '<td>';
				echo '</tr>';


				/*Fixed dimensions or responsive?*/
				$jwppp_method_dimensions = sanitize_text_field(get_option('jwppp-method-dimensions'));
				if(isset($_POST['jwppp-method-dimensions']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_method_dimensions = sanitize_text_field($_POST['jwppp-method-dimensions']);
					update_option('jwppp-method-dimensions', $jwppp_method_dimensions);
				}

				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Player Embed Type', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<select id="jwppp-method-dimensions" name="jwppp-method-dimensions" />';
				echo '<option name="fixed" id="fixed" value="fixed" ';
				echo ($jwppp_method_dimensions === 'fixed') ? 'selected="selected"' : '';
				echo '>' . esc_html(__('Fixed', 'jwppp')) . '</option>';
				echo '<option name="responsive" id="responsive" value="responsive"';
				echo ($jwppp_method_dimensions === 'responsive') ? 'selected="selected"' : '';
				echo '>' . esc_html(__('Responsive', 'jwppp')) . '</option>';
				echo '</select>';
				echo '<p class="description">' . esc_html(__('Player embed type.', 'jwppp')) . '<br>';
				echo '</td>';
				echo '</tr>';


				/*Player fixed width*/
				$jwppp_player_width = sanitize_text_field(get_option('jwppp-player-width'));
				if( isset($_POST['jwppp-player-width']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
					$jwppp_player_width = sanitize_text_field($_POST['jwppp-player-width']);
					update_option('jwppp-player-width', $jwppp_player_width);
				}

				/*Player fixed height*/
				$jwppp_player_height = sanitize_text_field(get_option('jwppp-player-height'));
				if( isset($_POST['jwppp-player-height']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options') ) {
					$jwppp_player_height = sanitize_text_field($_POST['jwppp-player-height']);
					update_option('jwppp-player-height', $jwppp_player_height);
				}

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


				/*Player %*/
				$jwppp_responsive_width = sanitize_text_field(get_option('jwppp-responsive-width'));
				if(isset($_POST['jwppp-responsive-width']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_responsive_width = sanitize_text_field($_POST['jwppp-responsive-width']);
					update_option('jwppp-responsive-width', $jwppp_responsive_width);
				}

				echo '<tr class="more-responsive">';
				echo '<th scope="row">' . esc_html(__('Player width', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="number" min="10" step="5" class="small-text" id="jwppp-responsive-width" name="jwppp-responsive-width" value="';
				echo ($jwppp_responsive_width !== null) ? esc_html($jwppp_responsive_width) : '100';
				echo '" /> %';
				echo '<p class="description">' . esc_html(__('Player width', 'jwppp')) . '</p>';
				echo '</td>';
				echo '</tr>';


				/*Player aspect ratio*/
				$jwppp_aspectratio = sanitize_text_field(get_option('jwppp-aspectratio'));
				if(isset($_POST['jwppp-aspectratio']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_aspectratio = sanitize_text_field($_POST['jwppp-aspectratio']);
					update_option('jwppp-aspectratio', $jwppp_aspectratio);
				}

				echo '<tr class="more-responsive">';
				echo '<th scope="row">' . esc_html(__('Player Aspect Ratio', 'jwppp')) . '</th>';
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
				echo '<p class="description">' . esc_html(__('Player aspect ratio', 'jwppp')) . '</p>';
				echo '</td>';
				echo '</tr>';
			

				/*Logo*/
				$jwppp_logo = sanitize_text_field(get_option('jwppp-logo'));
				if(isset($_POST['jwppp-logo']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_logo = sanitize_text_field($_POST['jwppp-logo']);
					update_option('jwppp-logo', $jwppp_logo);
				}

				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Logo Image', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="text" class="regular-text" id="jwppp-logo" name="jwppp-logo" ';
				echo 'placeholder="' . esc_html(__('Image url', 'jwppp')) . '" value="' . esc_html($jwppp_logo) . '" />';
				echo '<p class="description">' . esc_html(__('Custom logo image URL.', 'jwppp')) . '<br>';
				echo '</td>';
				echo '</tr>';


				/*Logo position*/
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
				echo '<p class="description">' . esc_html(__('Logo position.', 'jwppp')) . '</p>';
				echo '</td>';
				echo '</tr>';


				/*Logo link*/
				$jwppp_logo_link = sanitize_text_field(get_option('jwppp-logo-link'));
				if(isset($_POST['jwppp-logo-link']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_logo_link = sanitize_text_field($_POST['jwppp-logo-link']);
					update_option('jwppp-logo-link', $jwppp_logo_link);
				}

				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Logo Link', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="text" class="regular-text" id="jwppp-logo-link" name="jwppp-logo-link" ';
				echo 'placeholder="' . esc_html(__('Link url', 'jwppp')) . '" value="' . esc_url($jwppp_logo_link) . '" />';
				echo '<p class="description">' . esc_html(__('Logo click-through URL.', 'jwppp')) . '<br>';
				echo '</td>';
				echo '</tr>';


				/*Next up*/
				$jwppp_next_up = sanitize_text_field(get_option('jwppp-next-up'));
				if(isset($_POST['jwppp-next-up']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_next_up = sanitize_text_field($_POST['jwppp-next-up']);
					update_option('jwppp-next-up', $jwppp_next_up);
				}

				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('User Prompt', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="text" class="regular-text" id="jwppp-next-up" name="jwppp-next-up" ';
				echo 'placeholder="' . esc_html(__('Next Up', 'jwppp')) . '" value="' . esc_html($jwppp_next_up) . '" />';
				echo '<p class="description">' . esc_html(__('Text for user prompt', 'jwppp')) . '</p>';
				echo '</td>';
				echo '</tr>';


				/*Playlist tooltip*/
				$jwppp_playlist_tooltip = sanitize_text_field(get_option('jwppp-playlist-tooltip'));
				if(isset($_POST['jwppp-playlist-tooltip']) && wp_verify_nonce(sanitize_text_field($_POST['hidden-nonce-options']), 'jwppp-nonce-options')) {
					$jwppp_playlist_tooltip = sanitize_text_field($_POST['jwppp-playlist-tooltip']);
					update_option('jwppp-playlist-tooltip', $jwppp_playlist_tooltip);
				}

				echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Playlist Tooltip', 'jwppp')) . '</th>';
				echo '<td>';
				echo '<input type="text" class="regular-text" id="jwppp-playlist-tooltip" name="jwppp-playlist-tooltip" ';
				echo 'placeholder="' . esc_html(__('Playlist', 'jwppp')) . '" value="' . esc_html($jwppp_playlist_tooltip) . '" />';
				echo '<p class="description">' . esc_html(__('Text for playlist tooltip.', 'jwppp')) . '</p>';
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
	
	<?php
	/*Skin customization based on the player in use*/
	if(get_option('jwppp-player-version') === '7') {
		
		require(plugin_dir_path(__FILE__) . 'skin/jwppp-admin-skin-7-options.php');

	} elseif(get_option('jwppp-player-version') === '8') {
	
		require(plugin_dir_path(__FILE__) . 'skin/jwppp-admin-skin-8-options.php');

	}
	?>

	<div name="jwppp-skin" id="jwppp-skin" class="jwppp-admin" style="display: none;">
		<div class="jwppp-alert"><?php esc_html_e(__('Skin customization options depends from the JW Player version in use.<br>Please add first the <b><i>Player library URL</i></b>', 'jwppp')); ?></div>		
	</div>
	

	<?php include(plugin_dir_path(__FILE__) . 'jwppp-admin-subtitles.php'); ?>

	<?php include(plugin_dir_path(__FILE__) . 'jwppp-admin-sharing.php'); ?>

	<?php include(plugin_dir_path(__FILE__) . 'jwppp-admin-ads.php'); ?>

	<?php include(plugin_dir_path(__FILE__) . 'jwppp-admin-playlist-carousel.php'); ?>

	</div><!-- wrap-left -->
	<div class="wrap-right" style="float:left; width:30%; text-align:center; padding-top:3rem;">
		<div class="jwppp-banner">
		<h3>Resources</h3>
			<ul>
				<li><a href="https://vip.wordpress.com/plugins/jwplayer/" target="_blank">WP VIP Plugin Info</a></li>
				<li><a href="https://support.jwplayer.com/articles/jw-player-wordpress-plugin-reference" target="_blank">Plugin Reference</a></li>
				<li><a href="https://support.jwplayer.com/submit-support-case" target="_blank">Support</li></a>
			</ul>
		</div>
	</div>
	<div class="clear"></div>

</div>

<?php

}