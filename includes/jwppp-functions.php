<?php
/**
 * Plugin functions
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @version 1.6.0
 */

/*Files required*/
require(plugin_dir_path(__FILE__) . 'jwppp-ajax-add-video-callback.php');
require(plugin_dir_path(__FILE__) . 'jwppp-ajax-remove-video-callback.php');
require(plugin_dir_path(__FILE__) . 'jwppp-video-tools.php');
require(plugin_dir_path(__FILE__) . 'jwppp-save-single-video-data.php');
require(plugin_dir_path(__FILE__) . 'jwppp-sh-player-options.php');
require(plugin_dir_path(__FILE__) . 'jwppp-ads-code-block.php');
require(plugin_dir_path(__FILE__) . 'jwppp-player-code.php');
require(plugin_dir_path(__DIR__)  . 'classes/jwppp-dashboard-api.php');
require(plugin_dir_path(__DIR__)  . 'botr/api.php');

require_once(plugin_dir_path(__DIR__) .'libraries/JWT.php');
use \Firebase\JWT\JWT;

/**
 * Add meta box
 */
function jwppp_add_meta_box() {
	$jwppp_get_types = get_post_types();
	$screens = array();
	foreach($jwppp_get_types as $type) {
		if(sanitize_text_field(get_option('jwppp-type-' . $type) === '1')) {
			array_push($screens, $type);
		}
	}
	foreach($screens as $screen) {
		add_meta_box('jwppp-box', __( 'JW Player for Wordpress - VIP', 'jwppp' ), 'jwppp_meta_box_callback', $screen);
	}
}
add_action('add_meta_boxes', 'jwppp_add_meta_box');


/**
 * Get all videos of a single post
 * @param  int $post_id
 * @return array  post/ page videos with meta_key as key and url as value
 */
function jwppp_get_post_videos($post_id) {
	global $wpdb;
	$query = "
		SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE \"_jwppp-video-url-%\"
	";
	$videos = $wpdb->get_results($query, ARRAY_A); //db call ok; no-cache ok
	if(count($videos) >= 1 && !get_post_meta($post_id, '_jwppp-video-url-1', true)) {		
		array_unshift($videos, array('meta_key' => '_jwppp-video-url-1', 'meta_value' => 1));
	}
	return $videos;
}


/**
 * Get videos ids string
 * @param  int $post_id
 * @return string the vido ids of the post/ page
 */
function jwppp_videos_string($post_id) {
	$ids = array();
	$videos = jwppp_get_post_videos($post_id);
	if($videos) {
		for ($i=1; $i <= count($videos); $i++) { 
			$ids[] = $i;
		}
	}
	$string = implode(',', $ids);
	return $string;
}


/**
 * Single video box with all his option
 * @param  int $post_id 		
 * @param  int $number  the number of video in the post/ page
 */
function jwppp_single_video_box($post_id, $number) {
	
	/*Delete video if url is equal to 1, it means empty*/
	if(get_post_meta( $post_id, '_jwppp-video-url-' . $number, true ) === '1') {
		delete_post_meta($post_id, '_jwppp-video-url-' . $number);
		return;
	}

	/*How to add the playlist*/
	$plist_hide = true;
	if($number === '1' && get_option('jwppp-position') == 'custom' && count(jwppp_get_post_videos($post_id)) > 1) {
		$plist_hide = false;
	}

	/*Is it a dashboard player?*/
	$dashboard_player = is_dashboard_player();

	/*Available only with self-hosted players*/
	if($number === 1 && !$dashboard_player) {
		$output  = '<div class="playlist-how-to" style="position:relative;background:#2FBFB0;color:#fff;padding:0.5rem 1rem;';
		$output .= ($plist_hide) ? 'display:none;">' : 'display:block">';
		$output .= 'Add a playlist of your videos using this code: <code style="display:inline-block;color:#fff;background:none;">[jwp-video n="' . esc_html(jwppp_videos_string($post_id)) . '"]</code>';
		if(get_option('jwppp-position') !== 'custom') {
			$output .= '<a class="attention-mark" title="' . __('You need to set the VIDEO PLAYER POSITION option to CUSTOM in order to use this shortcode.', 'jwppp') . '"><img class="attention-mark" src="' . plugin_dir_url(__DIR__) . 'images/attention-mark.png" /></a></th>';			
		}
		$output .= '</div>';
	
		$allowed_tags = array(
			'div' => array(
				'class' => [],
				'style' => []
			),
			'code' => array(
				'style' => []
			)
		);
	
		echo wp_kses($output, $allowed_tags);
	}

	require(plugin_dir_path(__FILE__) . 'jwppp-single-video-box.php');
}


/**
 * Output the jwppp meta box with all videos
 * @param  object $post the post
 */
function jwppp_meta_box_callback($post) {

	$jwppp_videos = jwppp_get_post_videos($post->ID);
		
	if(!empty($jwppp_videos)) {
		for ($i=1; $i <= count($jwppp_videos) ; $i++) { 
			jwppp_single_video_box($post->ID, $i);
		}
	} else {
		jwppp_single_video_box($post->ID, 1);
	}
}


/**
 * Ajax - Add video
 */
function jwppp_ajax_add_video() { 
	if(get_the_ID()) { 
	?>
		<script type="text/javascript" >
		jQuery(document).ready(function($) {
			$('.jwppp-add').on('click', function() {
				var number = parseInt($('.order:visible').last().html())+1;
				var data = {
					'action': 'jwppp_ajax_add',
					'number': number,
					'post_id': <?php echo get_the_ID(); ?>
				};

				$.post(ajaxurl, data, function(response) {
					$('#jwppp-box .inside').append(response);

					$('.jwppp-remove').bind('click', function() {
						var data = {
							'action': 'jwppp_ajax_remove',
							'number': $(this).attr('data-numb'),
							'post_id': <?php echo get_the_ID(); ?>
						};

						$.post(ajaxurl, data, function(response) {
							var element = '.jwppp-' + response;
							$(element).remove();

							/*Change playlist-how-to*/
							var tot = $('.jwppp-input-wrap:visible').length;
							if(tot==1) {
								$('.playlist-how-to').hide('slow');			
							} else {
								var string = [];
								$('.order:visible').each(function(i, el) {
									string.push($(el).html());	
								})
								$('.playlist-how-to code').html('[jwp-video n="' + string + '"]');
							}

						});

					});

					/*Change playlist-how-to*/
					$('.playlist-how-to').show('slow');
					var tot = $('.jwppp-input-wrap:visible').length;
					var string = [];
					$('.order:visible').each(function(i, el) {
						string.push($(el).html());	
					})
					$('.playlist-how-to code').html('[jwp-video n="' + string + '"]');

				});
			});
		});
		</script> 
	<?php
	}
}
add_action( 'admin_footer', 'jwppp_ajax_add_video' );
add_action( 'wp_ajax_jwppp_ajax_add', 'jwppp_ajax_add_video_callback' );


/**
 * Ajax - Remove video
 */
function jwppp_ajax_remove_video() { 
	if(get_the_ID()) { 
	?>
		<script type="text/javascript" >
		jQuery(document).ready(function($) {
			$('.jwppp-remove').bind('click', function() {
				var data = {
					'action': 'jwppp_ajax_remove',
					'number': $(this).attr('data-numb'),
					'post_id': <?php echo get_the_ID(); ?>
				};

				$.post(ajaxurl, data, function(response) {
					var element = '.jwppp-' + response;
					$(element).remove();
				
				/*Change playlist-how-to*/
				var tot = $('.jwppp-input-wrap:visible').length;
				if(tot==1) {
					$('.playlist-how-to').hide('slow');			
				} else {
					var string = [];
					$('.order:visible').each(function(i, el) {
						string.push($(el).html());	
					})
					$('.playlist-how-to code').html('[jwp-video n="' + string + '"]');
				}
				});
			});
		});
		</script> 
	<?php
	}
}
add_action( 'admin_footer', 'jwppp_ajax_remove_video' );
add_action( 'wp_ajax_jwppp_ajax_remove', 'jwppp_ajax_remove_video_callback' );


/**
 * Delete all video metas from the db
 * @param  int $post_id
 * @param  int $number  the number of video in the post/ page
 */
function jwppp_db_delete_video($post_id, $number) {

	delete_post_meta( $post_id, '_jwppp-video-url-' . $number);
	delete_post_meta( $post_id, '_jwppp-video-mobile-url-' . $number);
	delete_post_meta( $post_id, '_jwppp-video-image-' . $number);
	delete_post_meta( $post_id, '_jwppp-video-title-' . $number);
	delete_post_meta( $post_id, '_jwppp-video-description-' . $number);
	delete_post_meta( $post_id, '_jwppp-autoplay-' . $number);
	delete_post_meta( $post_id, '_jwppp-single-embed-' . $number);
	delete_post_meta( $post_id, '_jwppp-activate-media-type-' . $number);
	delete_post_meta( $post_id, '_jwppp-media-type-' . $number);
	delete_post_meta( $post_id, '_jwppp-choose-player-' . $number);
	delete_post_meta( $post_id, '_jwppp-playlist-carousel-' . $number);
	delete_post_meta( $post_id, '_jwppp-mute-' . $number);
	delete_post_meta( $post_id, '_jwppp-repeat-' . $number);
	delete_post_meta( $post_id, '_jwppp-download-video-' . $number);
	delete_post_meta( $post_id, '_jwppp-ads-tag-' . $number);
	delete_post_meta( $post_id, '_jwppp-add-chapters-' . $number);
	delete_post_meta( $post_id, '_jwppp-chapters-subtitles-' . $number);
	delete_post_meta( $post_id, '_jwppp-subtitles-method-' . $number);

	/*Delete all sources and labels*/
	$sources = get_post_meta( $post_id, '_jwppp-sources-number-' . $number, true);
	if($sources) {
		for ($i=0; $i <= ($sources + 1); $i++) { 
			delete_post_meta( $post_id, '_jwppp-' . $number . '-main-source-label');
			delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-url');
			delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-label');
		}
		delete_post_meta( $post_id, '_jwppp-sources-number-' . $number);
	}

	/*Delete all chapters*/
	$chapters = get_post_meta( $post_id, '_jwppp-chapters-number-' . $number);
	if($chapters) {
		for($n=0; $n <= ((int)$chapters + 1); $n++) {
			delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-title');
			delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-start');
			delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-end');
		}
		delete_post_meta( $post_id, '_jwppp-chapters-number-' . $number);		
	}
}


/**
 * Add scripts and style in the page head
 * Licence key, JW Player library, custom skin and playlist carousel
 */
function jwppp_add_header_code() {

	$get_library = sanitize_text_field(get_option('jwppp-library'));

	/*Is it a dashboard player?*/
	$dashboard_player = is_dashboard_player();

	/*Default dashboard player informations*/
	if($dashboard_player) {
		
		$library_parts = explode('https://content.jwplatform.com/libraries/', $get_library);
		$player_parts = explode('.js', $library_parts[1]);		

		/*Check if the security option is activated*/
		$security_embeds = sanitize_text_field(get_option('jwppp-secure-player-embeds'));

		$library = $security_embeds ? jwppp_get_signed_embed($player_parts[0]) : $get_library;

		/*JW Widget for Playlist Carousel*/
		wp_enqueue_style('jwppp-widget-style', plugin_dir_url(__DIR__) . 'jw-widget/css/jw-widget-min.css');
		wp_enqueue_script('jwppp-widget', plugin_dir_url(__DIR__) . 'jw-widget/js/jw-widget-min.js');
		
	
	} else {

		$library = $get_library;

		$licence = sanitize_text_field(get_option('jwppp-licence'));
		$skin 	 = sanitize_text_field(get_option('jwppp-skin'));

		if($licence !== null) {
			wp_register_script('jwppp-licence', plugin_dir_url(__DIR__) . 'js/jwppp-licence.js');
			
			/*Useful for passing data*/
			$data = array(
				'licence' => sanitize_text_field(get_option('jwppp-licence'))
			);
			wp_localize_script('jwppp-licence', 'data', $data);
			wp_enqueue_script('jwppp-licence');
		}

		if($skin === 'custom-skin') {
			$skin_url = sanitize_text_field(get_option('jwppp-custom-skin-url'));
			if($skin_url) {
				echo '<link rel="stylesheet" type="text/css" href="' . esc_url($skin_url) . '"> </link>';
			}
		}


	}

	if($library !== null) {
		wp_enqueue_script('jwppp-library', $library);
	}
	
}
add_action('wp_enqueue_scripts', 'jwppp_add_header_code');


/**
 * Get post-types chosen by the publisher for posting videos
 * @return array the post types
 */
function jwppp_get_video_post_types() {
	$types = get_post_types(array('public' => 'true'));
	$video_types = array();
	foreach($types as $type) {
		if(get_option('jwppp-type-' . $type) === '1') {
			array_push($video_types, $type);
		}
	}
	return $video_types;
}
add_action('init', 'jwppp_get_video_post_types', 0);


/**
 * Create the taxonomy "Video categories"
 */
function jwppp_create_taxonomy() {
	$labels = array(
		'name'              => __( 'Video categories', 'jwppp' ),
		'singular_name'     => __( 'Video category', 'jwppp' ),
		'search_items'      => __( 'Search video categories', 'jwppp' ),
		'all_items'         => __( 'All video categories', 'jwppp' ),
		'parent_item'       => __( 'Parent video category', 'jwppp' ),
		'parent_item_colon' => __( 'Parent video category:', 'jwppp' ),
		'edit_item'         => __( 'Edit video category', 'jwppp' ),
		'update_item'       => __( 'Update video category', 'jwppp' ),
		'add_new_item'      => __( 'Add New video category', 'jwppp' ),
		'new_item_name'     => __( 'New video category Name', 'jwppp' ),
		'menu_name'         => __( 'Video categories', 'jwppp' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'video-categories' ),
	);

	$jwppp_taxonomy_select = sanitize_text_field(get_option('jwppp-taxonomy-select'));
	if($jwppp_taxonomy_select == 'video-categories') {
		register_taxonomy( 'video-categories', jwppp_get_video_post_types(), $args );
	}
}
add_action( 'init', 'jwppp_create_taxonomy', 1 );


/**
 * Add the "Video categories" taxonomy to all chosen post types
 */
function jwppp_add_taxonomy() {
	$types = jwppp_get_video_post_types();
	$jwppp_taxonomy_select = sanitize_text_field(get_option('jwppp-taxonomy-select'));
	foreach($types as $type) {
		register_taxonomy_for_object_type($jwppp_taxonomy_select, $type);
		add_post_type_support( $type, $jwppp_taxonomy_select );
	}
}
add_action('admin_init', 'jwppp_add_taxonomy');


/**
 * Check if a source is a YouTube video
 * @param  string $jwppp_video_url a full url to check
 * @param  int $number             the video number of the current post
 * @return array                   if a YouTube video, the embed url and the preview image
 */
function jwppp_search_yt($jwppp_video_url='', $number='') {
	if($number) {
		$jwppp_video_url = get_post_meta(get_the_ID(), '_jwppp-video-url-' . $number, true);
	}
	$youtube1 	   = 'https://www.youtube.com/watch?v=';
	$youtube2 	   = 'https://youtu.be/';
	$youtube_embed = 'https://www.youtube.com/embed/';
	$is_yt = false;

	/*YouTube link types*/
	if(strpos($jwppp_video_url, $youtube1) !== false) {
		$jwppp_embed_url = str_replace($youtube1, $youtube_embed, $jwppp_video_url);
		$yt_parts = explode($youtube1, $jwppp_video_url);
		$yt_video_id = $yt_parts[1];
		$is_yt = true;
	} elseif(strpos($jwppp_video_url, $youtube2) !== false) {
		$jwppp_embed_url = str_replace($youtube2, $youtube_embed, $jwppp_video_url);	
		$yt_parts = explode($youtube2, $jwppp_video_url);
		$yt_video_id = $yt_parts[1];
		$is_yt = true;
	} elseif(strpos($jwppp_video_url, $youtube_embed) !== false) {
		$jwppp_embed_url = $jwppp_video_url;
		$yt_parts = explode($youtube_embed, $jwppp_video_url);
		$yt_video_id = $yt_parts[1];
		$is_yt = true;
	} else {
		$jwppp_embed_url = $jwppp_video_url;
		$yt_parts = '';
		$yt_video_id = '';
		$is_yt = false;
	}

	$yt_video_image = $yt_video_id ? 'https://img.youtube.com/vi/' . $yt_video_id . '/maxresdefault.jpg' : '';

	return array('yes' => $is_yt, 'embed-url' => $jwppp_embed_url, 'video-image' => $yt_video_image);

}


/**
 * Check if the single post/ page ad tag still exists in the option array
 * @param  array $tags the ad tags saved by the user
 * @param  string $tag  the single tag choosed for the specific video
 * @return bool
 */
function jwppp_ads_tag_exists($tags, $tag) {
	foreach($tags as $single) {
		if($single['url'] === $tag) {
			return true;
		}
	} 
	return false;
}


/**
 * Generate signed URLs 
 * @param  string $media_id the media id
 * @return string
 */
function jwppp_get_signed_url($media_id) {

	$token_secret = get_option('jwppp-api-secret');
	$resource = 'v2/media/' . $media_id;
	$timeout = get_option('jwppp-secure-timeout') ? get_option('jwppp-secure-timeout') : 60;

	$expires = ceil( (time() + ($timeout * 60) ) / 180) * 180;

	$token_body = array(
	    "resource" => $resource,
	    "exp" => $expires
	);

	$jwt = JWT::encode($token_body, $token_secret);

	return "https://cdn.jwplayer.com/$resource?token=$jwt";
}


/**
 * Generate signed embeds 
 * @param  string $player_id the player id
 * @return string
 */
function jwppp_get_signed_embed($player_id) {

	$token_secret = get_option('jwppp-api-secret');
	$path = 'libraries/' . $player_id . '.js';
	$timeout = get_option('jwppp-secure-timeout') ? get_option('jwppp-secure-timeout') : 60;

	$expires = ceil( (time() + ($timeout * 60) ) / 180) * 180;
	
	$signature = md5($path . ':' . $expires . ':' . $token_secret);
	$url = 'https://content.jwplatform.com/' . $path . '?exp=' . $expires . '&sig=' . $signature;
	
	return $url;
}


/**
 * The player shortcode
 * @param  array $var the player options available
 */
function jwppp_player_s_code($var) {
	ob_start();
	$video = shortcode_atts( array(
		'p'      => get_the_ID(),
		'n'      => '1',	
		'ar'     => '',
		'width'  => '',
		'height' => '',
		'pl_autostart' => '',
		'pl_mute'     => '',
		'pl_repeat'   => ''
		), $var );
	echo jwppp_player_code(
		$video['p'], 
		$video['n'], 
		$video['ar'], 
		$video['width'], 
		$video['height'],
		$video['pl_autostart'],
		$video['pl_mute'],
		$video['pl_repeat']
	);
	$output = ob_get_clean();
	return $output;
}
add_shortcode('jw7-video', 'jwppp_player_s_code');
add_shortcode('jwp-video', 'jwppp_player_s_code');


/**
 * Used for old JW Player shortcodes only with contents from the dashboard
 * @param  string $media_id the media id
 * @return string           the code block
 */
function jwppp_simple_player_code($media_id) {

	/*Output the player*/
	$output = "<div id='jwppp-video-box-" . esc_attr($media_id) . "' class='jwppp-video-box' data-video='" . esc_attr($media_id) . "' style=\"margin: 1rem 0;\">\n";
		$output .= "<div id='jwppp-video-" . esc_attr($media_id) . "'>";
		if(sanitize_text_field(get_option('jwppp-text')) != null) {
			$output .= sanitize_text_field(get_option('jwppp-text'));
		} else {
			$output .= esc_html(__('Loading the player...', 'jwppp'));
		}
		$output .= "</div>\n"; 

		/*Check if the security option is activated*/
		$security_urls = get_option('jwppp-secure-video-urls');

		$output .= "<script type='text/javascript'>\n";
			$output .= "var playerInstance_$media_id = jwplayer(\"jwppp-video-$media_id\");\n";
			$output .= "playerInstance_$media_id.setup({\n";

			if($security_urls) {
					$output .= "playlist: '" . jwppp_get_signed_url(esc_html($media_id)) . "',\n";		
				} else {
					$output .= "playlist: 'https://cdn.jwplayer.com/v2/media/$media_id',\n";	
				}	

			/*Is it a dashboard player?*/
			$dashboard_player = is_dashboard_player();

			/*Vars*/
			$jwppp_method_dimensions = sanitize_text_field(get_option('jwppp-method-dimensions'));
			$jwppp_player_width = sanitize_text_field(get_option('jwppp-player-width'));
			$jwppp_player_height = sanitize_text_field(get_option('jwppp-player-height'));
			$jwppp_responsive_width = sanitize_text_field(get_option('jwppp-responsive-width'));
			$jwppp_aspectratio = sanitize_text_field(get_option('jwppp-aspectratio'));

		   	/*Options available only with self-hosted player*/
		   	if(!$dashboard_player) {
				$output .= jwppp_sh_player_option();
		   	}

			$output .= "})\n";
		$output .= "</script>";
	$output .= "</div>\n"; 

	return $output;
}


/**
 * Old JW Player shortcode
 * @param  array $var the shortcode attributes available, the media id in this case
 * @return string     the code block
 */
function jwppp_old_player_s_code($var) {
	ob_start();
	echo jwppp_simple_player_code($var[0]);
	$output = ob_get_clean();
	return $output;
}
add_shortcode('jwplayer', 'jwppp_old_player_s_code');


/**
 * Execute shortcodes in widgets
 */
if(!has_filter('widget_text', 'do_shortcode')) {
	add_filter('widget_text', 'do_shortcode');
} 


/**
 * Add player to the contents
 * @param  mixed $content the post/ page content
 * @return mixed          the post/ page content + the player(s)
 */
function jwppp_add_player($content) {
	global $post;
	$type = get_post_type($post->ID);

	if(is_singular() && (sanitize_text_field(get_option('jwppp-type-' . $type)) === '1')) {
		$jwppp_videos = jwppp_get_post_videos($post->ID);
		if($jwppp_videos) {
			$video = null;
			for ($i=1; $i <= count($jwppp_videos) ; $i++) { 
				$number 	  = $i;
				$post_id 	  = get_the_ID();
				$video 	     .= jwppp_player_code(
									$post_id, 
									$number, 
									$ar='', 
									$width='', 
									$height='', 
									$pl_autostart='', 
									$pl_mute='', 
									$pl_repeat=''
								);
			}

			$position = get_option('jwppp-position');
			if($position == 'after-content') {
				$content = $content . $video;
			} elseif($position == 'before-content') {
				$content = $video . $content;
			}
		}
	} 
	return $content;
}
add_filter('the_content', 'jwppp_add_player');


/**
 * The playlist carousel html element
 * @param  int $player_id the id of the player
 * @return mixed
 */
function jwppp_playlist_carousel($player_id) {
	
	$output = '<div id="jwppp-playlist-carousel-' . esc_html($player_id) . '" class="jw-widget">';
		$output .= '<div class="jw-widget-title"></div>';
		$output .= '<div class="jw-widget-content"></div>';
		$output .= '<div class="jw-widget-arrows">';
			$output .= '<div class="arrow previous disabled">';
				$output .= '<svg class="icon" width="61.1px" height="100px" viewBox="622.7 564.5 61.1 100" fill="#fff" xml:space="preserve">';
					$output .= '<path d="M680.6,567.7c4.3,4.3,4.3,11.3,0,15.5l-31.2,31.2l31.2,31.2c4.3,4.3,4.3,11.3,0,15.5c-4.3,4.3-11.3,4.3-15.7,0l-39-39c-4.3-4.3-4.3-11.3,0-15.5l39.1-39C669.3,563.4,676.3,563.4,680.6,567.7z"/>';
				$output .= '</svg>';
			$output .= '</div>';
			$output .= '<div class="arrow next">';
				$output .= '<svg class="icon" width="61.3px" height="100px" viewBox="625.1 564.5 61.3 100" style="enable-background:new 625.1 564.5 61.3 100;" xml:space="preserve" fill="#fff">';
					$output .= '<path d="M644,567.7l39.1,39c4.3,4.3,4.3,11.3,0,15.5l-39.1,39c-4.3,4.3-11.3,4.3-15.7,0c-4.3-4.3-4.3-11.3,0-15.5l31.3-31.2l-31.3-31.2c-4.3-4.3-4.3-11.3,0-15.5C632.6,563.4,639.6,563.4,644,567.7z"/>';
				$output .= '</svg>';
			$output .= '</div>';
		$output .= '</div>';
	$output .= '</div>';

	return $output;
}


/**
 * Search contents in the dashboard, both single videos and playlists
 * @return string a json encoded array of the results
 */
function jwppp_get_videos_callback() {

	$api = new jwppp_dasboard_api();

	if(isset($_POST['value'])) {
		$term = sanitize_text_field($_POST['value']);
		if($term){
			$videos = $api->search($term);
			$playlists = $api->search($term, true);
		} else {
			$videos = $api->get_videos();
			$playlists = $api->get_playlists();			
		}
	}

	echo json_encode(array('videos' => $videos, 'playlists' => $playlists));
	exit;
}
add_action('wp_ajax_search-content', 'jwppp_get_videos_callback');


/**
 * Callback - Save video details, used in single video meta box 
 * @return string a json encoded array of the results
 */
function jwppp_save_current_video_details() {

	$media_id = isset($_POST['media_id']) ? sanitize_text_field($_POST['media_id']) : '';

	if(isset($_POST['media_id']) && $_POST['media_id'] !== '') {
		
		$media_id = sanitize_text_field($_POST['media_id']);
		$post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
		$sh_video = strrpos($media_id, 'http') === 0 ? true : false;

		$number = isset($_POST['number']) ? sanitize_text_field($_POST['number']) : '';
		$media_id = isset($_POST['media_id']) ? sanitize_text_field($_POST['media_id']) : '';
		$media_details = isset($_POST['media_details']) ? sanitize_text_field($_POST['media_details']) : '';
		// $media_details = isset($_POST['media_details']) ? json_decode(stripslashes($_POST['media_details']) : '';

		if($media_details) {
			update_post_meta($post_id, '_jwppp-media-details', $media_details);
		}
	}

	exit;
}
add_action('wp_ajax_save-video-details', 'jwppp_save_current_video_details');

/**
 * Get details about the current media, used in single video meta box 
 * @return string a json encoded array of the results
 */
// function jwppp_get_current_video_details() {

// 	if(isset($_POST['media_id']) && $_POST['media_id'] !== '') {
// 		$media_id = sanitize_text_field($_POST['media_id']);
// 		$sh_video = strrpos($media_id, 'http') === 0 ? true : false;

// 		if(!$sh_video){
// 			$api = new jwppp_dasboard_api();
// 			$videos = $api->get_videos($media_id);
// 			if(isset($videos[0])){
// 				echo json_encode($videos[0]);				
// 			} else {
// 				$playlists = $api->get_playlists($media_id);
// 				if(isset($playlists[0])) {
// 					echo json_encode($playlists[0]);				
// 				}
// 			}
// 		}
// 	}

// 	exit;
// }
// add_action('wp_ajax_current-video-details', 'jwppp_get_current_video_details');