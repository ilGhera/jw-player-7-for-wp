<?php
/**
* JW PLAYER FOR WORDPRESS
*/

require(plugin_dir_path(__FILE__) . 'jwppp-ajax-add-video-callback.php');

//ADD META BOX
function jwppp_add_meta_box() {

	$jwppp_get_types = get_post_types();
	// $exclude = array('attachment', 'nav_menu_item');
	$screens = array();


	foreach($jwppp_get_types as $type) {
		if(sanitize_text_field(get_option('jwppp-type-' . $type) === '1')) {
			array_push($screens, $type);
		}
	}

	foreach($screens as $screen) {
		add_meta_box('jwppp-box', __( 'JW Player for Wordpress', 'jwppp' ), 'jwppp_meta_box_callback', $screen);
	}
}
add_action( 'add_meta_boxes', 'jwppp_add_meta_box' );


//SINGLE VIDEO BOX WITH ALL HIS OPTION
function jwppp_meta_box_callback( $post ) {

	require(plugin_dir_path(__FILE__) . 'jwppp-single-video-box.php');
	
}


//SAVE ALL INFORMATIONS OF THE SINGLE VIDEO
function jwppp_save_meta_box_data( $post_id ) {

	if ( ! isset( $_POST['jwppp_meta_box_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field($_POST['jwppp_meta_box_nonce']), 'jwppp_save_meta_box_data' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['post_type'] ) && 'page' === sanitize_text_field($_POST['post_type']) ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	if ( ! isset( $_POST['_jwppp-video-url-1'] ) ) {
		return;
	} else {
		$video = sanitize_text_field($_POST['_jwppp-video-url-1']);
		update_post_meta( $post_id, '_jwppp-video-url-1', $video );
	}

	if ( ! isset( $_POST['_jwppp-1-source-1-url'] ) ) {
		return;
	} else {
		$source_url = sanitize_text_field($_POST['_jwppp-1-source-1-url']);
		update_post_meta( $post_id, '_jwppp-1-source-1-url', $source_url );
	}

	if ( ! isset( $_POST['_jwppp-video-title-1'] ) ) {
		return;
	} else {
		$title = sanitize_text_field($_POST['_jwppp-video-title-1']);
		update_post_meta( $post_id, '_jwppp-video-title-1', $title );
	}

	if ( ! isset( $_POST['_jwppp-video-description-1'] ) ) {
		return;
	} else {
		$description = sanitize_text_field($_POST['_jwppp-video-description-1']);
		update_post_meta( $post_id, '_jwppp-video-description-1', $description );

	}



	//MEDIA TYPE
	if(sanitize_text_field($_POST['activate-media-type-hidden-1']) === '1') {
		$jwppp_activate_media_type = isset($_POST['_jwppp-activate-media-type-1']) ? sanitize_text_field($_POST['_jwppp-activate-media-type-1']) : 0;
		update_post_meta( $post_id, '_jwppp-activate-media-type-1', $jwppp_activate_media_type );
	}

	if(sanitize_text_field($jwppp_activate_media_type) === '1') {
		$media_type = sanitize_text_field($_POST['_jwppp-media-type-1']);
		update_post_meta( $post_id, '_jwppp-media-type-1', $media_type );
	} else {
		delete_post_meta($post_id, '_jwppp-media-type-1');
	}

	
}
add_action( 'save_post', 'jwppp_save_meta_box_data' );


//AJAX - ADD VIDEO
function jwppp_ajax_add_video() {
	if(get_the_ID()) { 
	?>
		<script type="text/javascript" >
		jQuery(document).ready(function($) {
			$('.jwppp-add').one('click', function() {
				var data = {
					'action': 'jwppp_ajax_add',
					'number': 2, 
					'post_id': <?php echo get_the_ID(); ?>
				};

				$.post(ajaxurl, data, function(response) {
					$('#jwppp-box .inside').append(response);

					$('.jwppp-remove').on('click', function() {
						$('.jwppp-2').hide();
					});

				});

				$('.jwppp-add').css('opacity', '0.3');
			});
		});
		</script> 
		<?php
	}
}
add_action( 'admin_footer', 'jwppp_ajax_add_video' );
add_action( 'wp_ajax_jwppp_ajax_add', 'jwppp_ajax_add_video_callback' );


//REMOVE VIDEO
function jwppp_remove_video() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		$('.jwppp-remove').on('click', function() {
			$('.jwppp-2').hide();
		});
	});
	</script> 
	<?php
}
add_action( 'admin_footer', 'jwppp_remove_video' );


//SCRIPT AND LICENCE KEY FOR JW PLAYER
function jwppp_add_header_code() {
	$library = sanitize_text_field(get_option('jwppp-library'));
	$licence = sanitize_text_field(get_option('jwppp-licence'));

	if($library !== null) {
		wp_enqueue_script('jwppp-library', $library);
	}
	if($licence !== null) {
		wp_register_script('jwppp-licence', plugin_dir_url(__DIR__) . 'js/jwppp-licence.js');
		
		/*Useful for passing data*/
		$data = array(
			'licence' => sanitize_text_field(get_option('jwppp-licence'))
		);
		wp_localize_script('jwppp-licence', 'data', $data);
		
		wp_enqueue_script('jwppp-licence');
	}

	//ADD STYLE FOR BETTER PREVIEW IMAGE
	wp_enqueue_style('jwppp-style', plugin_dir_url(__DIR__) . 'css/jwppp-style.css');
}
add_action('wp_enqueue_scripts', 'jwppp_add_header_code');


//JW PLAYER CODE
function jwppp_video_code($p, $width = null, $height = null) {

		//GETTING THE POST/ PAGE ID
		if($p) {
			$p_id = $p;
		} else {
			$p_id = get_the_ID();
		}

		//GET THE OPTIONS
		$video_title = get_post_meta($p_id, '_jwppp-video-title-1', true);
		$video_description = get_post_meta($p_id, '_jwppp-video-description-1', true);
		$jwppp_player_width = sanitize_text_field(get_option('jwppp-player-width'));
		$jwppp_player_height = sanitize_text_field(get_option('jwppp-player-height'));
		$jwppp_skin = sanitize_text_field(get_option('jwppp-skin'));
		$active_share = sanitize_text_field(get_option('jwppp-active-share'));		
		$jwppp_embed_video = sanitize_text_field(get_option('jwppp-embed-video'));
		$jwppp_video_url = get_post_meta($p_id, '_jwppp-video-url-1', true);
		$source_url = get_post_meta($p_id, '_jwppp-1-source-1-url', true);
		$jwppp_media_type = get_post_meta($p_id, '_jwppp-media-type-1', true);
		$player_version = sanitize_text_field(get_option('jwppp-player-version'));

		$youtube1 = 'https://www.youtube.com/watch?v=';
		$youtube2 = 'https://youtu.be/';
		$youtube_embed = 'https://www.youtube.com/embed/';

		//ALL YOUTUBE LINKS
		$yt_video_id = null;
		if(strpos($jwppp_video_url, $youtube1) !== false) {
			$jwppp_embed_url = str_replace($youtube1, $youtube_embed, $jwppp_video_url);
			$yt_parts = explode($youtube1, $jwppp_video_url);
			$yt_video_id = $yt_parts[1];
		} else if(strpos($jwppp_video_url, $youtube2) !== false) {
			$jwppp_embed_url = str_replace($youtube2, $youtube_embed, $jwppp_video_url);	
			$yt_parts = explode($youtube2, $jwppp_video_url);
			$yt_video_id = $yt_parts[1];
		} elseif(strpos($jwppp_video_url, $youtube_embed) !== false) {
			$jwppp_embed_url = $jwppp_video_url;
			$yt_parts = explode($youtube_embed, $jwppp_video_url);
			$yt_video_id = $yt_parts[1];
		} else {
			$jwppp_embed_url = $jwppp_video_url;
		}

		if($yt_video_id) {
			$yt_video_image = 'https://img.youtube.com/vi/' . $yt_video_id . '/maxresdefault.jpg';
		}
		
		$this_video = $p_id . 1;

		$output = "<div id='jwppp-video-box-" . $this_video . "' style=\"margin: 1rem 0;\">\n";
		$output .= "<div id='jwppp-video-" . $this_video . "'>";
		$output .= __('Loading the player...', 'jwppp');
		$output .= "</div>"; 
		$output .= "</div>"; 
		$output .= "<script type='text/javascript'>\n";
			$output .= "var playerInstance_$this_video = jwplayer(\"jwppp-video-$this_video\");\n";
			$output .= "playerInstance_$this_video.setup({\n";
				if($source_url) {
					$output .= "sources: [\n";
					$output .= "{\n";
				}
			    $output .= "file: '" . $jwppp_video_url . "',\n"; 
				if($source_url) {
					$output .= "},\n";
					$output .= "{\n";
					$output .= "file: '" . $source_url . "'\n";
					$output .= "}\n";
					$output .= "],\n";
				}
			    
			    if(has_post_thumbnail($p_id) && get_option('jwppp-post-thumbnail') === '1') {
			    	$output .= "image: '" . get_the_post_thumbnail_url() . "',\n";
			    } else if($yt_video_id !== null) {
			    	$output .= "image: '" . $yt_video_image . "',\n";
				} else if(get_option('jwppp-poster-image')) {
				    $output .= "image: '" . get_option('jwppp-poster-image') . "',\n";
				}

				//PLAYER DIMENSIONS
				if($width && $height) {

					    $output .= "width: '" . $width . "',\n";
					    $output .= "height: '" . $height . "',\n";

				} else {

				    $output .= "width: '";
				    $output .= ($jwppp_player_width !== null) ? $jwppp_player_width : '640';
				    $output .= "',\n";
				    $output .= "height: '";
				    $output .= ($jwppp_player_height !== null) ? $jwppp_player_height : '360';
				    $output .= "',\n";	

			    }			   

			    if($player_version == 7 && $jwppp_skin !== 'none') {
			    	$output .= "skin: {\n";
			    	$output .= "name: '" . $jwppp_skin . "'\n";
			    	$output .= "},\n";
			    }

			    if($video_title) {
				    $output .= "title: '" . esc_html($video_title) . "',\n";
				}
				if($video_description) {
				    $output .= "description: '" . esc_html($video_description) . "',\n";
				}

				if($jwppp_media_type) {
			    	$output .= "type: '" . $jwppp_media_type . "',\n";
			    }

			     //GOOGLE ANALYTICS
			    $output .= "ga: {},\n";

				if($active_share === '1') {
					$output .= "sharing: {\n";
						$jwppp_share_heading = sanitize_text_field(get_option('jwppp-share-heading'));
						if($jwppp_share_heading !== null) {
							$output .= "heading: '" . $jwppp_share_heading . "',\n";
						} else {
							$output .= "heading: '" . __('Share Video', 'jwppp') . "',\n"; 
						}
						$output .= "sites: ['email','facebook','twitter','pinterest','tumblr','googleplus','reddit','linkedin'],\n";
						if($jwppp_embed_video === '1') {
							$output .= "code: '<iframe src=\"" . $jwppp_embed_url . "\"  width=\"640\"  height=\"360\"  frameborder=\"0\"  scrolling=\"auto\"></iframe>'\n";
						}
					$output .= "},\n";
				}

		  $output .= "})\n";
		$output .= "</script>\n";

		if(get_post_meta($p_id, '_jwppp-video-url-1', true)) { return $output; }
}

//CREATE JWPPP-VIDEO SHORTCODE
function jwppp_video_s_code($var) {
	ob_start();
	$video = shortcode_atts( array(
		'p' 	 => get_the_ID(),
		'width'  => '',
		'height' => ''
		), $var);
	echo jwppp_video_code(
		$video['p'],
		$video['width'], 
		$video['height']
	);
	$output = ob_get_clean();
	return $output;
}
add_shortcode('jw7-video', 'jwppp_video_s_code');
add_shortcode('jwp-video', 'jwppp_video_s_code');

//EXECUTE SHORTCODES IN WIDGETS
if(!has_filter('widget_text', 'do_shortcode')) {
	add_filter('widget_text', 'do_shortcode');
} 


//ADD PLAYER TO THE CONTENT
function jwppp_add_player($content) {
	global $post;
	$type = get_post_type($post->ID);
	if(is_singular() && (sanitize_text_field(get_option('jwppp-type-' . $type)) === '1') && (get_post_meta($post->ID, '_jwppp-video-url-1', true))) {
		$p = get_the_ID();
		$video = jwppp_video_code($p);
		$position = get_option('jwppp-position');
		if($position === 'after-content') {
			$content = $content . $video;
		} elseif($position === 'before-content') {
			$content = $video . $content;
		} 	
	} 
	return $content;
}
add_filter('the_content', 'jwppp_add_player');