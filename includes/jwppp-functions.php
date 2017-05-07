<?php
/**
* JW PLAYER 7 FOR WORDPRESS
*/

require('jwppp-ajax-add-video-callback.php');

//ADD META BOX
function jwppp_add_meta_box() {

	$jwppp_get_types = get_post_types();
	$exclude = array('attachment', 'nav_menu_item');
	$screens = array();


	foreach($jwppp_get_types as $type) {
		if(sanitize_text_field(get_option('jwppp-type-' . $type) == 1)) {
			array_push($screens, $type);
		}
	}

	foreach($screens as $screen) {
		add_meta_box('jwppp-box', __( 'JW Player 7', 'jwppp' ), 'jwppp_meta_box_callback', $screen);
	}
}
add_action( 'add_meta_boxes', 'jwppp_add_meta_box' );


//SINGLE VIDEO BOX WITH ALL HIS OPTION
function jwppp_meta_box_callback( $post ) {

	//JUST A LITTLE OF STYLE
	echo '<style>';
	echo 'a.question-mark {position:relative; left:1rem;}';
	echo 'img.question-mark {position:relative; top:0.2rem;}';
	echo '</style>';

	echo '<table class="widefat jwppp-1" style="margin: 0.4rem 0;">';
	echo '<tbody class="ui-sortable">';
	echo '<tr class="row">';
	echo '<td class="order">1</td>';
	echo '<td class="jwppp-input-wrap" style="width: 100%;">';
	wp_nonce_field( 'jwppp_save_meta_box_data', 'jwppp_meta_box_nonce' );

	$video_url = get_post_meta( $post->ID, '_jwppp-video-url-1', true );
	$video_url_mobile = get_post_meta( $post->ID, '_jwppp-video-mobile-url-1', true );
	$video_title = get_post_meta($post->ID, '_jwppp-video-title-1', true);
	$video_description = get_post_meta($post->ID, '_jwppp-video-description-1', true);
	$jwppp_embed_video = sanitize_text_field(get_option('jwppp-embed-video'));
	$jwppp_activate_media_type = get_post_meta($post->ID, '_jwppp-activate-media-type-1', true);
	$jwppp_media_type = get_post_meta($post->ID, '_jwppp-media-type-1', true);


	echo '<label for="_jwppp-video-url-1">';
	echo '<strong>' . __( 'Media URL', 'jwppp' ) . '</strong>';
	echo '<a class="question-mark" href="http://www.ilghera.com/support/topic/media-formats-supported/" title="More informations" target="_blank"><img class="question-mark" src="' . plugins_url('jw-player-7-for-wp') . '/images/question-mark.png" /></a></th>';
	echo '</label> ';
	echo '<p><input type="text" id="_jwppp-video-url-1" name="_jwppp-video-url-1" placeholder="' . __('Video (YouTube or self-hosted), Audio or Playlist (Premium)', 'jwppp') . '" value="' . esc_attr( $video_url ) . '" size="60" /></p>';

	echo '<a class="button more-options-1">' . __('More options', 'jwppp') . '</a>';
	if(get_option('jwppp-position') == 'custom') {
		echo '<code style="display:inline-block;margin:0.1rem 0.5rem 0;color:#888;">[jw7-video n="1"]</code>';
	}

	?>

	<script>
	jQuery(document).ready(function($) {
		$('.jwppp-more-options-1').hide();
		$('.more-options-1').click(function() {
			$('.jwppp-more-options-1').toggle('fast');
			// $('.more-options').text('Less options');
			$(this).text(function(i, text) {
				return text == 'More options' ? 'Less options' : 'More options';
			});
		});
		if($('#_jwppp-activate-media-type-1').prop('checked') == false) {
			$('#_jwppp-media-type-1').hide();
		} else {
			$('#_jwppp-media-type-1').show();
		}
		$('#_jwppp-activate-media-type-1').on('change', function(){
			if($(this).prop('checked') == true) {
				$('#_jwppp-media-type-1').show();
			} else {
				$('#_jwppp-media-type-1').hide();
			}
		})
	});
	</script>

	<?php
	echo '<div class="jwppp-more-options-1" style="margin-top:2rem;">';

	echo '<label for="_jwppp-video-mobile-url-1">';
	echo '<strong>' . __( 'Media URL - Mobile', 'jwppp' ) . '</strong>';
	echo '</label> ';
	echo '<p><input type="text" id="_jwppp-video-mobile-url-1" name="_jwppp-video-mobile-url-1" placeholder="' . __('Add a different media source for mobile devices', 'jwppp') . '" value="' . esc_attr( $video_url_mobile ) . '" size="60" /></p>';

	echo '<label for="_jwppp-video-image-1">';
	echo '<strong>' . __( 'Video poster image', 'jwppp' ) . ' | </strong><a href="http://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
	echo '</label> ';
	echo '<p><input type="text" id="_jwppp-video-image-1" name="_jwppp-video-image-1" placeholder="' . __('Add a different poster image for this video', 'jwppp') . '" size="60" disabled="disabled" /></p>';

	echo '<label for="_jwppp-video-title-1">';
	echo '<strong>' . __( 'Video title', 'jwppp' ) . '</strong>';
	echo '</label> ';
	echo '<p><input type="text" id="_jwppp-video-title-1" name="_jwppp-video-title-1" placeholder="' . __('Add a title to your video', 'jwppp') . '" value="' . esc_attr( $video_title ) . '" size="60" /></p>';

	echo '<label for="_jwppp-video-description-1">';
	echo '<strong>' . __( 'Video description', 'jwppp' ) . '</strong>';
	echo '</label> ';
	echo '<p><input type="text" id="_jwppp-video-description-1" name="_jwppp-video-description-1" placeholder="' . __('Add a description to your video', 'jwppp') . '" value="' . esc_attr( $video_description ) . '" size="60" /></p>';

	echo '<p>';
	echo '<label for="_jwppp-activate-media-type-1">';
	echo '<input type="checkbox" id="_jwppp-activate-media-type-1" name="_jwppp-activate-media-type-1" value="1"';
	echo ($jwppp_activate_media_type == 1) ? ' checked="checked"' : '';
	echo ' />';
	echo '<strong>' . __('Force a media type', 'jwppp') . '</strong>';
	echo '<a class="question-mark" title="' . __('Only required when a file extension is missing or not recognized', 'jwppp') . '"><img class="question-mark" src="' . plugins_url('jw-player-7-for-wp') . '/images/question-mark.png" /></a></th>';
	echo '</label>';
	echo '<input type="hidden" name="activate-media-type-hidden-1" value="1" />';
	
	echo '<select style="position: relative; left:2rem; display:inline;" id="_jwppp-media-type-1" name="_jwppp-media-type-1">';
	echo '<option name="mp4" value="mp4"';
	echo ($jwppp_media_type == 'mp4') ? ' selected="selected"' : '';
	echo '>mp4</option>';
	echo '<option name="flv" value="flv"';
	echo ($jwppp_media_type == 'flv') ? ' selected="selected"' : '';
	echo '>flv</option>';
	echo '<option name="mp3" value="mp3"';
	echo ($jwppp_media_type == 'mp3') ? ' selected="selected"' : '';
	echo '>mp3</option>';
	echo '</select>';
	echo '</p>';
	
	echo '<p>';
	echo '<label for="_jwppp-autoplay-1">';
	echo '<input type="checkbox" id="_jwppp-autoplay-1" name="_jwppp-autoplay-1" value="1" disabled="disabled"';
	echo ($jwppp_autoplay == 1) ? ' checked="checked"' : '';
	echo ' />';
	echo '<strong>' . __('Autostarting on page load.', 'jwppp') . '</strong> | <a href="http://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
	echo '</label>';
	echo '<input type="hidden" name="autoplay-hidden-1" value="1" />';
	echo '</p>';

	echo '<p>';
	echo '<label for="_jwppp-single-embed-1">';
	echo '<input type="checkbox" id="_jwppp-single-embed-1" name="_jwppp-single-embed-1" value="1" disabled="disabled"';
	echo ($jwppp_embed_video == 1) ? ' checked="checked"' : '';
	echo ' />';
	echo '<strong>' . __('Allow to embed this video', 'jwppp') . '</strong> | <a href="http://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
	echo '</label>';
	echo '<input type="hidden" name="single-embed-hidden-1" value="1" />';
	echo '</p>';

	echo '<p>';
	echo '<label for="_jwppp-add-chapters-1">';
	echo '<input type="checkbox" id="_jwppp-add-chapters-1" name="_jwppp-add-chapters-1" value="1" disabled="disabled"';
	echo ($add_chapters == 1) ? ' checked="checked"' : '';
	echo ' />';
	echo '<strong>' . __('Add Chapters, Subtitles or Preview Thumbnails', 'jwppp') . '</strong> | <a href="http://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
	echo '</label>';
	echo '<input type="hidden"function name="add-chapters-hidden-1" value="1" />';

	echo '</div>';
	echo '</td>';
	if($number<2) {
		echo '<td class="add-video"><a class="jwppp-add"><img src="' . plugins_url('jw-player-7-for-wp') . '/images/add-video.png" /></a></td>';
	} else {
		echo '<td class="remove-video"><a class="jwppp-remove" data-numb="1"><img src="' . plugins_url('jw-player-7-for-wp') . '/images/remove-video.png" /></a></td>';
	}
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	
}


//SAVE ALL INFORMATIONS OF THE SINGLE VIDEO
function jwppp_save_meta_box_data( $post_id ) {

	if ( ! isset( $_POST['jwppp_meta_box_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['jwppp_meta_box_nonce'], 'jwppp_save_meta_box_data' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
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
	}
	if ( ! isset( $_POST['_jwppp-video-mobile-url-1'] ) ) {
		return;
	}
	if ( ! isset( $_POST['_jwppp-video-title-1'] ) ) {
		return;
	}

	if ( ! isset( $_POST['_jwppp-video-description-1'] ) ) {
		return;
	}

	$video = sanitize_text_field($_POST['_jwppp-video-url-1']);
	$video_mobile = sanitize_text_field($_POST['_jwppp-video-mobile-url-1']);
	$title = sanitize_text_field($_POST['_jwppp-video-title-1']);
	$description = sanitize_text_field($_POST['_jwppp-video-description-1']);

	update_post_meta( $post_id, '_jwppp-video-url-1', $video );
	update_post_meta( $post_id, '_jwppp-video-mobile-url-1', $video_mobile );
	update_post_meta( $post_id, '_jwppp-video-title-1', $title );
	update_post_meta( $post_id, '_jwppp-video-description-1', $description );

	//MEDIA TYPE
	if($_POST['activate-media-type-hidden-1'] == 1) {
		$jwppp_activate_media_type = isset($_POST['_jwppp-activate-media-type-1']) ? $_POST['_jwppp-activate-media-type-1'] : 0;
		update_post_meta( $post_id, '_jwppp-activate-media-type-1', $jwppp_activate_media_type );
	}

	if($jwppp_activate_media_type == 1) {
		$media_type = sanitize_text_field($_POST['_jwppp-media-type-1']);
		update_post_meta( $post_id, '_jwppp-media-type-1', $media_type );
	} else {
		delete_post_meta($post_id, '_jwppp-media-type-1');
	}

	
}
add_action( 'save_post', 'jwppp_save_meta_box_data' );


//AJAX - ADD VIDEO
function jwppp_ajax_add_video() { ?>
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
	if($library != null) {
		echo "<script src=\"$library\"></script>\n";
	}
	if($licence != null) {
		echo "<script>jwplayer.key=\"$licence\";</script>\n";
	}

	//ADD STYLE FOR BETTER PREVIEW IMAGE
	echo "<style>.jw-preview { background-size: 100% auto !important;}</style>";
}
add_filter('wp_head', 'jwppp_add_header_code');


//GET ALL VIDEO POSTS
function jwppp_get_video_posts() {
	global $wpdb;
	$query = "SELECT * FROM $wpdb->postmeta WHERE meta_key = '_jwppp-video-url-1' AND meta_value <> ''";
	$posts = $wpdb->get_results($query);
	$video_posts = array();
	foreach($posts as $post) {
		array_push($video_posts, $post->post_id);
	}
	return $video_posts;
}

//JW PLAYER CODE
function jwppp_video_code($p) {

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
		$jwppp_video_mobile_url = get_post_meta($p_id, '_jwppp-video-mobile-url-1', true);
		$jwppp_media_type = get_post_meta($p_id, '_jwppp-media-type-1', true);

		$youtube1 = 'https://www.youtube.com/watch?v=';
		$youtube2 = 'https://youtu.be/';
		$youtube_embed = 'https://www.youtube.com/embed/';

		//ALL YOUTUBE LINKS
		if(strpos($jwppp_video_url, $youtube1) !== false) {
			$jwppp_embed_url = str_replace($youtube1, $youtube_embed, $jwppp_video_url);
			$yt_parts = explode($youtube1, $jwppp_video_url);
			$yt_video_id = $yt_parts[1];
		} else if(strpos($jwppp_video_url, $youtube2) !== false) {
			$jwppp_embed_url = str_replace($youtube2, $youtube_embed, $jwppp_video_url);	
			$yt_parts = explode($youtube2, $jwppp_video_url);
			$yt_video_id = $yt_parts[1];
		} else {
			$jwppp_embed_url = $jwppp_video_url;
			$yt_parts = explode($youtube_embed, $jwppp_video_url);
			$yt_video_id = $yt_parts[1];
		}

		$yt_video_image = 'https://img.youtube.com/vi/' . $yt_video_id . '/maxresdefault.jpg';
		
		$jwppp_show_related = sanitize_text_field(get_option('jwppp-show-related'));
		$jwppp_show_ads = sanitize_text_field(get_option('jwppp-active-ads'));

		$this_video = $p_id . 1;

		$output = "<div id='jwppp-video-box-" . $this_video . "' style=\"margin: 1rem 0;\">\n";
		$output .= "<div id='jwppp-video-" . $this_video . "'>";
		$output .= __('Loading the player...', 'jwppp');
		$output .= "</div>"; 
		$output .= "</div>"; 
		$output .= "<script type='text/javascript'>\n";
			$output .= "var playerInstance_$this_video = jwplayer(\"jwppp-video-$this_video\");\n";
			$output .= "playerInstance_$this_video.setup({\n";
				if($jwppp_video_mobile_url) {
					$output .= "sources: [{\n";
				}
			    $output .= "file: '" . $jwppp_video_url . "',\n"; 
				if($jwppp_video_mobile_url) {
					$output .= "},\n";
					$output .= "{ file: '" . $jwppp_video_mobile_url . "'}\n";
					$output .= "],\n";
				}
			    
			    if(has_post_thumbnail($p_id) && get_option('jwppp-post-thumbnail') == 1) {
			    	$output .= "image: '" . get_the_post_thumbnail_url() . "',\n";
			    } else if($yt_video_id != null) {
			    	$output .= "image: '" . $yt_video_image . "',\n";
				} else if(get_option('jwppp-poster-image')) {
				    $output .= "image: '" . get_option('jwppp-poster-image') . "',\n";
				}

			    $output .= "width: '";
			    $output .= ($jwppp_player_width != null) ? $jwppp_player_width : '640';
			    $output .= "',\n";
			    $output .= "height: '";
			    $output .= ($jwppp_player_height != null) ? $jwppp_player_height : '360';
			    $output .= "',\n";				   

			    if($jwppp_skin != 'none') {
			    	$output .= "skin: {\n";
			    	$output .= "name: '" . $jwppp_skin . "'\n";
			    	$output .= "},\n";
			    }

			    if($video_title) {
				    $output .= "title: '" . $video_title . "',\n";
				}
				if($video_description) {
				    $output .= "description: '" . $video_description . "',\n";
				}

				if($jwppp_media_type) {
			    	$output .= "type: '" . $jwppp_media_type . "',\n";
			    }

			     //GOOGLE ANALYTICS
			    $output .= "ga: {},\n";

				if($active_share == 1) {
					$output .= "sharing: {\n";
						$jwppp_share_heading = sanitize_text_field(get_option('jwppp-share-heading'));
						if($jwppp_share_heading != null) {
							$output .= "heading: '" . $jwppp_share_heading . "',\n";
						} else {
							$output .= "heading: '" . __('Share Video', 'jwppp') . "',\n"; 
						}
						if($jwppp_embed_video == 1) {
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
		'p' => get_the_ID(),
		), $var, 'jw7-video');
	echo jwppp_video_code($video['p']);
	$output = ob_get_clean();
	return $output;
}
add_shortcode('jw7-video', 'jwppp_video_s_code');
//EXECUTE SHORTCODES IN WIDGETS
if(!has_filter('widget_text', 'do_shortcode')) {
	add_filter('widget_text', 'do_shortcode');
} 

//ADD PLAYER TO THE CONTENT
function jwppp_add_player($content) {
	global $post;
	$type = get_post_type($post->ID);
	if(is_singular() && (sanitize_text_field(get_option('jwppp-type-' . $type)) == 1) && (get_post_meta($post->ID, '_jwppp-video-url-1', true))) {
		$p = get_the_ID();
		$video = jwppp_video_code($p);
		$position = get_option('jwppp-position');
		if($position == 'after-content') {
			$content = $content . $video;
		} elseif($position == 'before-content') {
			$content = $video . $content;
		} 	
	} 
	return $content;
}
add_filter('the_content', 'jwppp_add_player');