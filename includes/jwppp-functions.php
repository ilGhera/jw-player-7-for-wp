<?php
/**
* JW PLAYER FOR WORDPRESS - PREMIUM
*/

require(plugin_dir_path(__FILE__) . 'jwppp-ajax-add-video-callback.php');
require(plugin_dir_path(__FILE__) . 'jwppp-ajax-remove-video-callback.php');
require(plugin_dir_path(__FILE__) . 'jwppp-sh-video-tools.php');
require(plugin_dir_path(__DIR__) . 'botr/api.php');


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
		add_meta_box('jwppp-box', __( 'JW Player for Wordpress - Premium', 'jwppp' ), 'jwppp_meta_box_callback', $screen);
	}
}
add_action('add_meta_boxes', 'jwppp_add_meta_box');


//GET ALL VIDEOS OF A SINGLE POST
function jwppp_get_post_videos($post_id) {
	global $wpdb;
	$query = "
		SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE \"_jwppp-video-url-%\"
	";
	$videos = $wpdb->get_results($query, ARRAY_A);
	return $videos;
}


//GET VIDEOS IDS STRING
function jwppp_videos_string($post_id) {
	$ids = array();
	$videos = jwppp_get_post_videos($post_id);
	foreach ($videos as $video) {
		$video_id = explode('_jwppp-video-url-', $video['meta_key']);
		$ids[] = $video_id[1];
	}
	$string = implode(',', $ids);
	return $string;
}


//SINGLE VIDEO BOX WITH ALL HIS OPTION
function jwppp_single_video_box($post_id, $number) {
	//DELETE VIDEO IF URL==1, IT MEANS EMPTY
	if(get_post_meta( $post_id, '_jwppp-video-url-' . $number, true ) === '1') {
		delete_post_meta($post_id, '_jwppp-video-url-' . $number);
		return;
	}

	//HOW TO ADD THE PLAYLIST
	$plist_hide = true;
	if($number === '1' && get_option('jwppp-position') == 'custom' && count(jwppp_get_post_videos($post_id)) > 1) {
		$plist_hide = false;
	}

	//IS IT A DASHBOARD PLAYER?
	$dashboard_player = is_dashboard_player();

	if($number === '1' && !$dashboard_player) {
		$output  = '<div class="playlist-how-to" style="position:relative;background:#2FBFB0;color:#fff;padding:0.5rem 1rem;';
		$output .= ($plist_hide) ? 'display:none;">' : 'display:block">';
		$output .= 'Add a playlist of your videos using this code: <code style="display:inline-block;color:#fff;background:none;">[jwp-video n="' . jwppp_videos_string($post_id) . '"]</code>';
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


//OUTPUT THE JWPPP META BOX WITH ALL VIDEOS
function jwppp_meta_box_callback($post) {
	$jwppp_videos = jwppp_get_post_videos($post->ID);
		
	if(!empty($jwppp_videos)) {
		foreach($jwppp_videos as $jwppp_video) {
			$jwppp_number = explode('_jwppp-video-url-', $jwppp_video['meta_key']);
			jwppp_single_video_box($post->ID, $jwppp_number[1]);
		}
	} else {
		jwppp_single_video_box($post->ID, 1);
	}
}


//AJAX - ADD VIDEO
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
							$(element).hide();

							//CHANGE PLAYLIST-HOW-TO
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

					//CHANGE PLAYLIST-HOW-TO
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


//AJAX - REMOVE VIDEO
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
					$(element).hide();
				
				//CHANGE PLAYLIST-HOW-TO
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


//SAVE ALL INFORMATIONS OF THE SINGLE VIDEO
function jwppp_save_single_video_data( $post_id ) {

	/*Is it a Dashboard player?*/
	$dashboard_player = is_dashboard_player();

	if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
		return;
	}

	if (isset( $_POST['post_type'] ) && 'page' === sanitize_text_field($_POST['post_type'])) {
		if (!current_user_can( 'edit_page', $post_id )) {
			return;
		}
	} else {
		if (!current_user_can( 'edit_post', $post_id )) {
			return;
		}
	}

	$jwppp_videos = jwppp_get_post_videos($post_id);
	if(empty($jwppp_videos)) {
		$jwppp_videos = array(
			array(
				'meta_key' => '_jwppp-video-url-1', 
				'meta_value' => 1
			)
		);
	}

	foreach($jwppp_videos as $jwppp_video) {

		$jwppp_number = explode('_jwppp-video-url-', $jwppp_video['meta_key']);
		$number = $jwppp_number[1];

		if (!isset( $_POST['jwppp-meta-box-nonce-' . $number] )) {
			return;
		}

		if (!wp_verify_nonce( $_POST['jwppp-meta-box-nonce-' . $number], 'jwppp_save_single_video_data' )) {
			return;
		}

		if (isset( $_POST['_jwppp-video-url-' . $number] )) {
			$video = sanitize_text_field($_POST['_jwppp-video-url-' . $number]);
			if(!$video) {
				delete_post_meta($post_id, '_jwppp-video-url-' . $number);
			} else {
				update_post_meta( $post_id, '_jwppp-video-url-' . $number, $video );
			}
		}

		if (isset( $_POST['_jwppp-sources-number-' . $number] )) {

			$sources = sanitize_text_field($_POST['_jwppp-sources-number-' . $number]);

			for($i=1; $i <= $sources; $i++) {	
				$source_url = sanitize_text_field($_POST['_jwppp-' . $number . '-source-' . $i . '-url']);
				if(!$source_url) {
					delete_post_meta($post_id, '_jwppp-' . $number . '-source-' . $i . '-url');
				} else {
					update_post_meta($post_id, '_jwppp-' . $number . '-source-' . $i . '-url', $source_url);
				}

				$source_label = sanitize_text_field($_POST['_jwppp-' . $number . '-source-' . $i . '-label']);
				if(!$source_label) {
					delete_post_meta($post_id, '_jwppp-' . $number . '-source-' . $i . '-label');
				} else {
					update_post_meta($post_id, '_jwppp-' . $number . '-source-' . $i . '-label', $source_label);
				}
			}

			update_post_meta($post_id, '_jwppp-sources-number-' . $number, $sources);				
		
		} else {

			$sources = get_post_meta($post_id, '_jwppp-sources-number-' . $number, true);
			if($sources) {
				for ($i=1; $i <= $sources; $i++) { 
					delete_post_meta($post_id, '_jwppp-' . $number . '-source-' . $i . '-url');
					delete_post_meta($post_id, '_jwppp-' . $number . '-source-' . $i . '-label');
				}
			}				
			delete_post_meta($post_id, '_jwppp-sources-number-' . $number);
		}

		if (isset( $_POST['_jwppp-' . $number . '-main-source-label'] )) {
			$label = sanitize_text_field($_POST['_jwppp-' . $number . '-main-source-label']);
			if(!$label) {
				delete_post_meta($post_id, '_jwppp-' . $number . '-main-source-label');
			} else {
				update_post_meta( $post_id, '_jwppp-' . $number . '-main-source-label', $label );
			}
		} else {
			delete_post_meta($post_id, '_jwppp-' . $number . '-main-source-label');			
		}

		if (isset( $_POST['_jwppp-video-image-' . $number] )) {
			$image = sanitize_text_field($_POST['_jwppp-video-image-' . $number]);
			if(!$image) {
				delete_post_meta($post_id, '_jwppp-video-image-' . $number);
			} else {
				update_post_meta( $post_id, '_jwppp-video-image-' . $number, $image );
			}
		} else {
			delete_post_meta($post_id, '_jwppp-video-image-' . $number);
		}

		if (isset( $_POST['_jwppp-video-title-' . $number] )) {
			$title = sanitize_text_field($_POST['_jwppp-video-title-' . $number]);
			if(!$title) {
				delete_post_meta($post_id, '_jwppp-video-title-' . $number);
			} else {
				update_post_meta( $post_id, '_jwppp-video-title-' . $number, $title );
			}
		} else {
			delete_post_meta($post_id, '_jwppp-video-title-' . $number);
		}

		if (isset( $_POST['_jwppp-video-description-' . $number] )) {
			$description = sanitize_text_field($_POST['_jwppp-video-description-' . $number]);
			if(!$description) {
				delete_post_meta($post_id, '_jwppp-video-description-' . $number);
			} else {;
				update_post_meta( $post_id, '_jwppp-video-description-' . $number, $description );
			}
		} else {
			delete_post_meta($post_id, '_jwppp-video-description-' . $number);			
		}

		$jwppp_activate_media_type = null;
		if(isset($_POST['activate-media-type-hidden-' . $number])) {
			$jwppp_activate_media_type = isset($_POST['_jwppp-activate-media-type-' . $number]) ? sanitize_text_field($_POST['_jwppp-activate-media-type-' . $number]) : 0;
			update_post_meta( $post_id, '_jwppp-activate-media-type-' . $number, $jwppp_activate_media_type );
		} else {
			delete_post_meta($post_id, '_jwppp-activate-media-type-' . $number);			
		}

		if($jwppp_activate_media_type === '1') {
			$media_type = sanitize_text_field($_POST['_jwppp-media-type-' . $number]);
			update_post_meta( $post_id, '_jwppp-media-type-' . $number, $media_type );
		} else {
			delete_post_meta($post_id, '_jwppp-media-type-' . $number);
		}

		if(isset($_POST['autoplay-hidden-' . $number])) {
			$jwppp_autoplay = isset($_POST['_jwppp-autoplay-' . $number]) ? sanitize_text_field($_POST['_jwppp-autoplay-' . $number]) : 0;
			update_post_meta( $post_id, '_jwppp-autoplay-' . $number, $jwppp_autoplay );
		}

		if(isset($_POST['mute-hidden-' . $number])) {
			$jwppp_mute = isset($_POST['_jwppp-mute-' . $number]) ? sanitize_text_field($_POST['_jwppp-mute-' . $number]) : 0;
			update_post_meta( $post_id, '_jwppp-mute-' . $number, $jwppp_mute );
		}

		if(isset($_POST['repeat-hidden-' . $number])) {
			$jwppp_repeat = isset($_POST['_jwppp-repeat-' . $number]) ? sanitize_text_field($_POST['_jwppp-repeat-' . $number]) : 0;
			update_post_meta( $post_id, '_jwppp-repeat-' . $number, $jwppp_repeat );
		}

		if(isset($_POST['single-embed-hidden-' . $number])) {
			$jwppp_single_embed = isset($_POST['_jwppp-single-embed-' . $number]) ? sanitize_text_field($_POST['_jwppp-single-embed-' . $number]) : 0;
			update_post_meta( $post_id, '_jwppp-single-embed-' . $number, $jwppp_single_embed );
		}

		if(isset($_POST['download-video-hidden-' . $number])) {
			$jwppp_download_video = isset($_POST['_jwppp-download-video-' . $number]) ? sanitize_text_field($_POST['_jwppp-download-video-' . $number]) : 0;
			update_post_meta( $post_id, '_jwppp-download-video-' . $number, $jwppp_download_video );
		}
		
		$jwppp_add_chapters = null;
		if(isset($_POST['add-chapters-hidden-' . $number])) {
			$jwppp_add_chapters = isset($_POST['_jwppp-add-chapters-' . $number]) ? sanitize_text_field($_POST['_jwppp-add-chapters-' . $number]) : 0;
			$jwppp_chapters_subtitles = sanitize_text_field($_POST['_jwppp-chapters-subtitles-' . $number]);

			$jwppp_subtitles_method = ($jwppp_chapters_subtitles === 'subtitles') ? sanitize_text_field($_POST['_jwppp-subtitles-method-' . $number]) : '';

			update_post_meta( $post_id, '_jwppp-add-chapters-' . $number, $jwppp_add_chapters );
			update_post_meta( $post_id, '_jwppp-chapters-subtitles-' . $number, $jwppp_chapters_subtitles);
			update_post_meta( $post_id, '_jwppp-subtitles-method-' . $number, $jwppp_subtitles_method);
		}

		if(isset($_POST['subtitles-load-default-hidden-' . $number])) {
			$jwppp_subtitles_load_default = isset($_POST['_jwppp-subtitles-load-default-' . $number]) ? sanitize_text_field($_POST['_jwppp-subtitles-load-default-' . $number]) : 0;
			update_post_meta( $post_id, '_jwppp-subtitles-load-default-' . $number, $jwppp_subtitles_load_default );
		}

		if(isset($_POST['subtitles-write-default-hidden-' . $number])) {
			$jwppp_subtitles_write_default = isset($_POST['_jwppp-subtitles-write-default-' . $number]) ? sanitize_text_field($_POST['_jwppp-subtitles-write-default-' . $number]) : 0;
			update_post_meta( $post_id, '_jwppp-subtitles-write-default-' . $number, $jwppp_subtitles_write_default );
		}

		if(isset($_POST['playlist-carousel-hidden-' . $number])) {
			$jwppp_playlist_carousel = isset($_POST['_jwppp-playlist-carousel-' . $number]) ? sanitize_text_field($_POST['_jwppp-playlist-carousel-' . $number]) : 0;
			update_post_meta( $post_id, '_jwppp-playlist-carousel-' . $number, $jwppp_playlist_carousel );
		}

		/*Chapter and subtitles are available only with self-hosted player*/
		if(!$dashboard_player) {

			if($jwppp_add_chapters === '1') {

				$chapters = sanitize_text_field($_POST['_jwppp-chapters-number-' . $number]);
				update_post_meta($post_id, '_jwppp-chapters-number-' . $number, $chapters);

				for($i=1; $i<$chapters+1; $i++) {
					
					if($jwppp_chapters_subtitles === 'subtitles' && $jwppp_subtitles_method === 'load') {
						
						//DELETE OLD DIFFERENT ELEMENTS
						delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title');
						delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start');
						delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end');
						delete_post_meta($post_id, '_jwppp-subtitles-write-default-' . $number);


						$sub_url = sanitize_text_field($_POST['_jwppp-' . $number . '-subtitle-' . $i . '-url']);
						if(!$sub_url) {
							delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url');
						} else {
							update_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url', $sub_url);
						}

						$sub_label = sanitize_text_field($_POST['_jwppp-' . $number . '-subtitle-' . $i . '-label']);
						if(!$sub_label) {
							delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label');
						} else {
							update_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label', $sub_label);
						}


					} else {

						//DELETE OLD DIFFERENT ELEMENTS
						delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url');
						delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label');
						delete_post_meta($post_id, '_jwppp-subtitles-load-default-' . $number);

						$title = sanitize_text_field($_POST['_jwppp-' . $number . '-chapter-' . $i . '-title']);
						if(!$title) {
							delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title');
						} else {
							update_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title', $title);
						}

						$start = sanitize_text_field($_POST['_jwppp-' . $number . '-chapter-' . $i . '-start']);
						if(!$start) {
							delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start');
						} else {
							update_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start', $start);
						}

						$end = sanitize_text_field($_POST['_jwppp-' . $number . '-chapter-' . $i . '-end']);
						if(!$end) {
							delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end');
						} else {
							update_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end', $end);
						}
						
					}
					
				}

			} else {
				$chapters = sanitize_text_field($_POST['_jwppp-chapters-number-' . $number]);
				for($i=1; $i<$chapters+1; $i++) {
					delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title');
					delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start');
					delete_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end');
					delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url');
					delete_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label');
					delete_post_meta($post_id, '_jwppp-chapters-subtitles-' . $number );
					delete_post_meta($post_id, '_jwppp-subtitles-method-' . $number);
				}
				delete_post_meta($post_id, '_jwppp-chapters-number-' . $number);
			}			

		}
		
	}

}
add_action( 'save_post', 'jwppp_save_single_video_data', 10, 1);


//SCRIPT, LICENCE KEY AND CUSTOM SKIN FOR JW PLAYER
function jwppp_add_header_code() {
	$library = sanitize_text_field(get_option('jwppp-library'));
	$licence = sanitize_text_field(get_option('jwppp-licence'));
	$skin 	 = sanitize_text_field(get_option('jwppp-skin'));
	
	if($skin === 'custom-skin') {
		$skin_url = sanitize_text_field(get_option('jwppp-custom-skin-url'));
		if($skin_url) {
			echo '<link rel="stylesheet" type="text/css" href="' . esc_url($skin_url) . '"> </link>';
		}
	}



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

	//JW WIDGET
	wp_enqueue_style('jwppp-widget-style', plugin_dir_url(__DIR__) . 'jw-widget/css/jw-widget-min.css');
	wp_enqueue_script('jwppp-widget', plugin_dir_url(__DIR__) . 'jw-widget/js/jw-widget-min.js');
	
}
add_action('wp_enqueue_scripts', 'jwppp_add_header_code');


//GET VIDEO POST-TYPES
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


//CREATE "VIDEO CATEGORIES" TAXONOMY
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


//ADD "VIDEO CATEGORIES" TO ALL CHOOSED POST TYPES
function jwppp_add_taxonomy() {
	$types = jwppp_get_video_post_types();
	$jwppp_taxonomy_select = sanitize_text_field(get_option('jwppp-taxonomy-select'));
	// if($jwppp_taxonomy_select != 'video-categories') {
		foreach($types as $type) {
			register_taxonomy_for_object_type($jwppp_taxonomy_select, $type);
			add_post_type_support( $type, $jwppp_taxonomy_select );
		}
	// }
}
add_action('admin_init', 'jwppp_add_taxonomy');


//GET THE FEEED FOR RELATED VIDEOS
function jwppp_get_feed_url() {
	$id = get_the_ID();
	$taxonomy = sanitize_text_field(get_option('jwppp-taxonomy-select'));
	$terms = wp_get_post_terms($id, $taxonomy);
	
	// $feed = home_url() . '/';
	// foreach($terms as $term) {
		// $term_link = get_term_link($term->term_id);
		// $feed .= $taxonomy . '/' . $term->slug . '/';
	// }
	// $feed .= '?feed=related-videos';

	if($terms !== null) {
		$feed = get_term_link($terms[0]->term_id, $taxonomy); 
		if(get_option('permalink_structure')) {
			$feed .= '/related-videos';
		} else {
			$feed .= '&feed=related-videos';
		}

		return $feed;
	}	
}


//CHECK FOR A YOUTUBE VIDEO
function jwppp_search_yt($jwppp_video_url='', $number='') {
	if($number) {
		$jwppp_video_url = get_post_meta(get_the_ID(), '_jwppp-video-url-' . $number, true);
	}
	$youtube1 	   = 'https://www.youtube.com/watch?v=';
	$youtube2 	   = 'https://youtu.be/';
	$youtube_embed = 'https://www.youtube.com/embed/';
	$is_yt = false;
	//ALL YOUTUBE LINKS
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

	$yt_video_image = 'https://img.youtube.com/vi/' . $yt_video_id . '/maxresdefault.jpg';

	return array('yes' => $is_yt, 'embed-url' => $jwppp_embed_url, 'video-image' => $yt_video_image);

}


//JW PLAYER CODE
function jwppp_video_code($p, $n, $ar, $width, $height, $pl_autostart, $pl_mute, $pl_repeat) {

	//IS IT A DASHBOARD PLAYER?
	$dashboard_player = is_dashboard_player();

	//GET THE OPTIONS
	$jwppp_method_dimensions = sanitize_text_field(get_option('jwppp-method-dimensions'));
	$jwppp_player_width = sanitize_text_field(get_option('jwppp-player-width'));
	$jwppp_player_height = sanitize_text_field(get_option('jwppp-player-height'));
	$jwppp_responsive_width = sanitize_text_field(get_option('jwppp-responsive-width'));
	$jwppp_aspectratio = sanitize_text_field(get_option('jwppp-aspectratio'));

	$player_version = sanitize_text_field(get_option('jwppp-player-version'));

	//SKIN CUSTOMIZATION - JWP7	
	$jwppp_skin = sanitize_text_field(get_option('jwppp-skin'));
	/*Is it a custom skin?*/
	if($jwppp_skin == 'custom-skin') {
		$jwppp_skin_name = sanitize_text_field(get_option('jwppp-custom-skin-name'));
	} else {
		$jwppp_skin_name = $jwppp_skin;
	}

	$jwppp_skin_color_active = sanitize_text_field(get_option('jwppp-skin-color-active'));
	$jwppp_skin_color_inactive = sanitize_text_field(get_option('jwppp-skin-color-inactive'));
	$jwppp_skin_color_background = sanitize_text_field(get_option('jwppp-skin-color-background'));


	//SKIN CUSTOMIZATION - JWP8
	$jwppp_skin_color_controlbar_text = sanitize_text_field(get_option('jwppp-skin-color-controlbar-text'));
	$jwppp_skin_color_controlbar_icons = sanitize_text_field(get_option('jwppp-skin-color-controlbar-icons'));
	$jwppp_skin_color_controlbar_active_icons = sanitize_text_field(get_option('jwppp-skin-color-controlbar-active-icons'));
	$jwppp_skin_color_controlbar_background = sanitize_text_field(get_option('jwppp-skin-color-controlbar-background'));
	$jwppp_skin_color_timeslider_progress = sanitize_text_field(get_option('jwppp-skin-color-timeslider-progress'));
	$jwppp_skin_color_timeslider_rail = sanitize_text_field(get_option('jwppp-skin-color-timeslider-rail'));
	$jwppp_skin_color_menus_text = sanitize_text_field(get_option('jwppp-skin-color-menus-text'));
	$jwppp_skin_color_menus_active_text = sanitize_text_field(get_option('jwppp-skin-color-menus-active-text'));
	$jwppp_skin_color_menus_background = sanitize_text_field(get_option('jwppp-skin-color-menus-background'));
	$jwppp_skin_color_tooltips_text = sanitize_text_field(get_option('jwppp-skin-color-tooltips-text'));
	$jwppp_skin_color_tooltips_background = sanitize_text_field(get_option('jwppp-skin-color-tooltips-background'));


	$jwppp_logo = sanitize_text_field(get_option('jwppp-logo'));
	$jwppp_logo_vertical = sanitize_text_field(get_option('jwppp-logo-vertical'));
	$jwppp_logo_horizontal = sanitize_text_field(get_option('jwppp-logo-horizontal'));
	$jwppp_logo_link = sanitize_text_field(get_option('jwppp-logo-link'));
	$active_share = sanitize_text_field(get_option('jwppp-active-share'));	
	$jwppp_embed_video = sanitize_text_field(get_option('jwppp-embed-video'));
	$jwppp_show_related = sanitize_text_field(get_option('jwppp-show-related'));
	$jwppp_related_heading = sanitize_text_field(get_option('jwppp-related-heading'));
	$jwppp_next_up = sanitize_text_field(get_option('jwppp-next-up'));
	$jwppp_playlist_tooltip = sanitize_text_field(get_option('jwppp-playlist-tooltip'));
	$jwppp_show_ads = sanitize_text_field(get_option('jwppp-active-ads'));
	$jwppp_ads_client = sanitize_text_field(get_option('jwppp-ads-client'));
	$jwppp_ads_tag = sanitize_text_field(get_option('jwppp-ads-tag'));
	$jwppp_ads_skip = sanitize_text_field(get_option('jwppp-ads-skip'));
	$jwppp_bidding = sanitize_text_field(get_option('jwppp-active-bidding'));
	$jwppp_channel_id = sanitize_text_field(get_option('jwppp-channel-id'));
	$jwppp_mediation = sanitize_text_field(get_option('jwppp-mediation'));
	$jwppp_floor_price = sanitize_text_field(get_option('jwppp-floor-price'));

	//NEW SUBTITLES OPTIONS
	$jwppp_sub_color = sanitize_text_field(get_option('jwppp-subtitles-color'));
	$jwppp_sub_font_size = sanitize_text_field(get_option('jwppp-subtitles-font-size'));
	$jwppp_sub_font_family = sanitize_text_field(get_option('jwppp-subtitles-font-family'));
	$jwppp_sub_opacity = sanitize_text_field(get_option('jwppp-subtitles-opacity'));
	$jwppp_sub_back_color = sanitize_text_field(get_option('jwppp-subtitles-back-color'));
	$jwppp_sub_back_opacity = sanitize_text_field(get_option('jwppp-subtitles-back-opacity'));

	//GETTING THE POST/ PAGE ID
	if($p) {
		$p_id = $p;
	} else {
		$p_id = get_the_ID();
	}
	//GETTING THE NUMBER/ S OF VIDEO/ S
	$videos = explode(',', $n);
	$jwppp_new_playlist = ( count($videos)>1 ) ? true : false;

	foreach($videos as $number) {

		/*Video url or media id*/
		$jwppp_video_url = get_post_meta($p_id, '_jwppp-video-url-' . $number, true);

		/*Is the video self hosted?*/
		$sh_video = strrpos($jwppp_video_url, 'http') === 0 ? true : false;

		/*Playlist carousel*/
		$jwppp_playlist_carousel = get_post_meta($p_id, '_jwppp-playlist-carousel-' . $number, true);


		if(!$dashboard_player || $sh_video) {

			$video_image = get_post_meta($p_id, '_jwppp-video-image-' . $number, true);
			$video_title = get_post_meta($p_id, '_jwppp-video-title-' . $number, true);
			$video_description = get_post_meta($p_id, '_jwppp-video-description-' . $number, true);
			$jwppp_activate_media_type = get_post_meta($p_id, '_jwppp-activate-media-type-' . $number, true);		
			$jwppp_media_type = get_post_meta($p_id, '_jwppp-media-type-' . $number, true);		
			$jwppp_autoplay = get_post_meta($p_id, '_jwppp-autoplay-' . $number, true);
			$jwppp_mute = get_post_meta($p_id, '_jwppp-mute-' . $number, true);
			$jwppp_repeat = get_post_meta($p_id, '_jwppp-repeat-' . $number, true);
			$jwppp_single_embed = get_post_meta($p_id, '_jwppp-single-embed-' . $number, true);
			if(!$jwppp_single_embed) {
				$jwppp_single_embed = $jwppp_embed_video;
			}
			$jwppp_download_video = get_post_meta($p_id, '_jwppp-download-video-' . $number, true);
			$jwppp_add_chapters = get_post_meta($p_id, '_jwppp-add-chapters-' . $number, true);
			$jwppp_chapters_subtitles = get_post_meta($p_id, '_jwppp-chapters-subtitles-' . $number, true);
			$jwppp_chapters_number = get_post_meta($p_id, '_jwppp-chapters-number-' . $number, true);
			$jwppp_subtitles_method = get_post_meta($p_id, '_jwppp-subtitles-method-' . $number, true);
			$jwppp_subtitles_load_default = get_post_meta($p_id, '_jwppp-subtitles-load-default-' . $number, true);
			$jwppp_subtitles_write_default = get_post_meta($p_id, '_jwppp-subtitles-write-default-' . $number, true);

		}
	}
	
	//CHECK FOR PLAYLIST
	$file_info = pathinfo($jwppp_video_url);
	$jwppp_playlist = false;
	if( array_key_exists('extension', $file_info) ) {
		if( in_array( $file_info['extension'], array('xml', 'feed', 'php', 'rss') ) ) {
			$jwppp_playlist = true;
		}
	}

	$this_video = $p_id . $number;

	if($dashboard_player && !$sh_video) {
	
		/*Player informations*/
		$library_parts = explode('https://content.jwplatform.com/libraries/', get_option('jwppp-library'));
		$player_parts = explode('.js', $library_parts[1]);

		/*Video*/
		$self_content = strpos($jwppp_video_url, 'http');

		/*Output the player*/
		$output = "<div id='jwppp-video-box-" . esc_attr($this_video) . "' class='jwppp-video-box' data-video='" . esc_attr($n) . "' style=\"margin: 1rem 0;\">\n";
			$output .= "<div id='jwppp-video-" . esc_attr($this_video) . "'>";
			if(sanitize_text_field(get_option('jwppp-text')) != null) {
				$output .= sanitize_text_field(get_option('jwppp-text'));
			} else {
				$output .= esc_html(__('Loading the player...', 'jwppp'));
			}
			$output .= "</div>\n"; 
		$output .= "</div>\n"; 

		/*Playlist carousel*/
		$output .= $jwppp_playlist_carousel ? playlist_carousel() : '';

		$output .= "<script type='text/javascript'>\n";
			$output .= "var playerInstance_$this_video = jwplayer(\"jwppp-video-$this_video\");\n";
			$output .= "playerInstance_$this_video.setup({\n";
				if($self_content === 0) {
				    $output .= "file: '" . $jwppp_video_url . "',\n"; 
				} else {
					$output .= "playlist: 'https://cdn.jwplayer.com/v2/media/$jwppp_video_url'\n";						
				}
			$output .= "})\n";
		$output .= "</script>";

	} else {

		$output = "<div id='jwppp-video-box-" . esc_attr($this_video) . "' class='jwppp-video-box' data-video='" . esc_attr($n) . "' style=\"margin: 1rem 0;\">\n";
		$output .= "<div id='jwppp-video-" . esc_attr($this_video) . "'>";
		if(sanitize_text_field(get_option('jwppp-text')) != null) {
			$output .= sanitize_text_field(get_option('jwppp-text'));
		} else {
			$output .= esc_html(__('Loading the player...', 'jwppp'));
		}
		$output .= "</div>\n"; 
		$output .= "</div>\n"; 
		$output .= "<script type='text/javascript'>\n";
			$output .= "var playerInstance_$this_video = jwplayer(\"jwppp-video-$this_video\");\n";
			$output .= "playerInstance_$this_video.setup({\n";
				if($jwppp_playlist) {
				    $output .= "playlist: '" . get_post_meta($p_id, '_jwppp-video-url-' . $number, true) . "',\n"; 
				} else {
					if($jwppp_new_playlist) {
						$n=0;
						$output .= "playlist: [\n";
					}
					foreach($videos as $number) {

						$jwppp_video_url = get_post_meta($p_id, '_jwppp-video-url-' . $number, true);
						$jwppp_sources_number = get_post_meta($p_id, '_jwppp-sources-number-' . $number);
						$jwppp_source_1 = get_post_meta($p_id, '_jwppp-' . $number . '-source-1-url', true);
						$video_image = get_post_meta($p_id, '_jwppp-video-image-' . $number, true);
						$video_title = get_post_meta($p_id, '_jwppp-video-title-' . $number, true);
						$video_description = get_post_meta($p_id, '_jwppp-video-description-' . $number, true);
						$jwppp_activate_media_type = get_post_meta($p_id, '_jwppp-activate-media-type-' . $number, true);
						$jwppp_media_type = get_post_meta($p_id, '_jwppp-media-type-' . $number, true);
						$jwppp_autoplay = get_post_meta($p_id, '_jwppp-autoplay-' . $number, true);
						$jwppp_mute = get_post_meta($p_id, '_jwppp-mute-' . $number, true);
						$jwppp_repeat = get_post_meta($p_id, '_jwppp-repeat-' . $number, true);
						$jwppp_single_embed = get_post_meta($p_id, '_jwppp-single-embed-' . $number, true);
						if(!$jwppp_single_embed) {
							$jwppp_single_embed = $jwppp_embed_video;
						}
						$jwppp_download_video = get_post_meta($p_id, '_jwppp-download-video-' . $number, true);
						$jwppp_add_chapters = get_post_meta($p_id, '_jwppp-add-chapters-' . $number, true);
						$jwppp_chapters_number = get_post_meta($p_id, '_jwppp-chapters-number-' . $number, true);
						$jwppp_chapters_subtitles = get_post_meta($p_id, '_jwppp-chapters-subtitles-' . $number, true);
						$jwppp_subtitles_method = get_post_meta($p_id, '_jwppp-subtitles-method-' . $number, true);
						$jwppp_subtitles_load_default = get_post_meta($p_id, '_jwppp-subtitles-load-default-' . $number, true);
						$jwppp_subtitles_write_default = get_post_meta($p_id, '_jwppp-subtitles-write-default-' . $number, true);

						//CHECK FOR A YT VIDEO
						$youtube = jwppp_search_yt($jwppp_video_url);
						$jwppp_embed_url = $youtube['embed-url'];
						$yt_video_image  = $youtube['video-image'];

						if($jwppp_new_playlist) {
							$output .= "{\n"; 
						}

					    //MOBILE SOURCE
						if($jwppp_source_1) {
							$output .= "sources: [\n";
							$output .= "{\n";
						}
					    $output .= "file: '" . esc_url($jwppp_video_url) . "',\n"; 
					    if($jwppp_sources_number && $jwppp_sources_number[0] > 1) {
					    	$main_source_label = get_post_meta($p_id, '_jwppp-' . $number . '-main-source-label', true);
				      		$output .= ($main_source_label) ? "label: '" . esc_html($main_source_label) . "'\n" : '';
						}
						
						if($jwppp_source_1) {
							$output .= "},\n";
						}

					    if($jwppp_source_1) {
							for($i=1; $i<$jwppp_sources_number[0]+1; $i++) {	
								$source_url = get_post_meta($p_id, '_jwppp-' . $number . '-source-' . $i . '-url', true);
								$source_label = get_post_meta($p_id, '_jwppp-' . $number . '-source-' . $i . '-label', true);
								if($source_url) {
						      		$output .= "{\n";
						      		$output .= "file: '" . esc_url($source_url) . "',\n";
						      		$output .= ($source_label) ? "label: '" . esc_html($source_label) . "'\n" : '';
						      		$output .= "},\n";
								} 
							}
				      	}

				      	if($jwppp_source_1) {
				      		$output .= "],\n";
						}

				      	//VIDEO TITLE
					    if($video_title) {
						    $output .= "title: '" . esc_html($video_title) . "',\n";
						}

						//VIDEO DESCRIPTION
						if($video_description) {
						    $output .= "description: '" . esc_html($video_description) . "',\n";
						}

						//POSTER IMAGE
						if($video_image) {
					    	$output .= "image: '" . esc_url($video_image) . "',\n";
					    } else if(has_post_thumbnail($p_id) && get_option('jwppp-post-thumbnail') === '1') {
					    	$output .=  "image: '" . get_the_post_thumbnail_url() . "',\n";
					    } else if($youtube['yes']) {
					    	$output .= "image: '" . esc_url($yt_video_image) . "',\n";
						} else if(get_option('jwppp-poster-image')) {
						    $output .= "image: '" . get_option('jwppp-poster-image') . "',\n";
						}

						if($jwppp_new_playlist) {
							$output .= "mediaid: '" . esc_attr($this_video) . $n++ . "',\n";
						}

						//MEIA-TYPE
						if($jwppp_media_type) {
					    	$output .= "type: '" . esc_html($jwppp_media_type) . "',\n";
					    }

					    //AUTOPLAY
						if(!$jwppp_new_playlist && $jwppp_autoplay === '1') {
					    	$output .= "autostart: 'true',\n";
					    }

					    //MUTE
					    if(!$jwppp_new_playlist && $jwppp_mute === '1') {
					    	$output .= "mute: 'true',\n";
					    	// if($youtube['yes']) {
					    	// 	$output .= "var down_volume = 'true',\n";
					    	// }
					    }

					    //REPEAT
					    if(!$jwppp_new_playlist && $jwppp_repeat === '1') {
					    	$output .= "repeat: 'true',\n";
					    }

					    //GOOGLE ANALYTICS
					    $output .= "ga: {},\n";
					    


						//SHARING FOR SINGLE VIDEO
						if(!$jwppp_new_playlist && $active_share === '1') {
							$output .= "sharing: {\n";
								$jwppp_share_heading = sanitize_text_field(get_option('jwppp-share-heading'));
								if($jwppp_share_heading !== null) {
									$output .= "heading: '" . esc_html($jwppp_share_heading) . "',\n";
								} else {
									$output .= "heading: '" . esc_html(__('Share Video', 'jwppp')) . "',\n"; 
								}
								$output .= "sites: ['email','facebook','twitter','pinterest','tumblr','googleplus','reddit','linkedin'],\n";
								if(($jwppp_embed_video || $jwppp_single_embed === '1') && !$jwppp_playlist) {
									$output .= "code: '<iframe src=\"" . esc_url($jwppp_embed_url) . "\"  width=\"640\"  height=\"360\"  frameborder=\"0\"  scrolling=\"auto\"></iframe>'\n";
								}
							$output .= "},\n";
						}

						//ADD CHAPTERS
						if($jwppp_add_chapters === '1') {
							$output .= "tracks:[\n";

							if($jwppp_chapters_subtitles === 'subtitles' && $jwppp_subtitles_method === 'load') {
								for($i=1; $i<$jwppp_chapters_number+1; $i++) {
									$output .= "{\n";
								    $output .= "file:'" . get_post_meta($p_id, '_jwppp-' . $number . '-subtitle-' . $i . '-url', true) . "',\n";
								    $output .= "kind:'captions',\n";	
								    $output .= "label:'" . get_post_meta($p_id, '_jwppp-' . $number . '-subtitle-' . $i . '-label', true) . "',\n";	
								    if($i==1 && $jwppp_subtitles_load_default === '1') {
								    	$output .= "'default': 'true'\n";
								    }
									$output .= "},\n";
								}

							} else {
								$output .= "{\n";
							    $output .= "file:'" . esc_url(plugin_dir_url(__DIR__))  . "includes/jwppp-chapters.php?id=" . $p_id . "&number=$number',\n";
							    if($jwppp_chapters_subtitles === 'chapters') {
								    $output .= "kind:'chapters'\n";						    	
							    } else if($jwppp_chapters_subtitles === 'subtitles') {
								    $output .= "kind:'captions',\n";		
								    if($jwppp_subtitles_write_default === '1') {
								    	$output .= "'default': 'true'\n";
								    }				    	
							    } else {
							    	$output .= "kind:'thumbnails'\n";
							    }
								$output .= "}\n";
							}

			      			$output .= "],\n";

			      		}

			      		if($jwppp_new_playlist) {
				      		$output .= "},\n";
				      	}
				    }

				    if($jwppp_new_playlist) {
						$output .= "],\n";
					}
				}

				//SHARING FOR PLAYLIST
				if($jwppp_new_playlist && $active_share === '1') {
					$output .= "sharing: {\n";
						$jwppp_share_heading = sanitize_text_field(get_option('jwppp-share-heading'));
						if($jwppp_share_heading !== null) {
							$output .= "heading: '" . esc_html($jwppp_share_heading) . "',\n";
						} else {
							$output .= "heading: '" . esc_html(__('Share Video', 'jwppp')) . "',\n"; 
						}
						$output .= "sites: ['email','facebook','twitter','pinterest','tumblr','googleplus','reddit','linkedin'],\n";
					$output .= "},\n";
				}
			   

			   	/*Options available only with self-hosted player*/
			   	if(!$dashboard_player) {
	
					//PLAYER DIMENSIONS
					if($width && $height) {

						    $output .= "width: '" . esc_html($width) . "',\n";
						    $output .= "height: '" . esc_html($height) . "',\n";

					} else {
					    
					    if($jwppp_method_dimensions === 'fixed') {
						    $output .= "width: '";
						    $output .= ($jwppp_player_width !== null) ? esc_html($jwppp_player_width) : '640';
						    $output .= "',\n";
						    $output .= "height: '";
						    $output .= ($jwppp_player_height != null) ? esc_html($jwppp_player_height) : '360';
						    $output .= "',\n";
						    
						} else {
							$output .= "width: '";
							$output .= ($jwppp_responsive_width != null) ? esc_html($jwppp_responsive_width) . '%' : '100%';
							$output .= "',\n";
							$output .= "aspectratio: '";
							if($ar) {
								$output .= $ar;
							} elseif($jwppp_aspectratio) {
								$output .= esc_html($jwppp_aspectratio);
							} else {
								$output .= '16:9';
							}
							$output .= "',\n";
						}

					}

					//SKIN
			    	$output .= "skin: {\n";
				    	if($player_version === '7') {

					    	$output .= $jwppp_skin_name != 'none' ? "name: '" . esc_html($jwppp_skin_name) . "',\n" : '';
							$output .= $jwppp_skin_color_active ? "active: '" . esc_html($jwppp_skin_color_active) . "',\n" : '';
							$output .= $jwppp_skin_color_inactive ? "inactive: '" . esc_html($jwppp_skin_color_inactive) . "',\n" : '';
							$output .= $jwppp_skin_color_background ? "background: '" . esc_html($jwppp_skin_color_background) . "',\n" : '';

				    	} elseif($player_version === '8') {

					    	$output .= "controlbar: {\n";
						    	$output .= $jwppp_skin_color_controlbar_text ? "text: '" . esc_html($jwppp_skin_color_controlbar_text) . "',\n" : '';
						    	$output .= $jwppp_skin_color_controlbar_icons ? "icons: '" . esc_html($jwppp_skin_color_controlbar_icons) . "',\n" : '';
						    	$output .= $jwppp_skin_color_controlbar_active_icons ? "iconsActive: '" . esc_html($jwppp_skin_color_controlbar_active_icons) . "',\n" : '';
						    	$output .= $jwppp_skin_color_controlbar_background ? "background: '" . esc_html($jwppp_skin_color_controlbar_background) . "',\n" : '';
					    	$output .= "},\n";

					    	$output .= "timeslider: {\n";
						    	$output .= $jwppp_skin_color_timeslider_progress ? "progress: '" . esc_html($jwppp_skin_color_timeslider_progress) . "',\n" : '';
						    	$output .= $jwppp_skin_color_timeslider_rail ? "rail: '" . esc_html($jwppp_skin_color_timeslider_rail) . "',\n" : '';
					    	$output .= "},\n";

					    	$output .= "menus: {\n";
						    	$output .= $jwppp_skin_color_menus_text ? "text: '" . esc_html($jwppp_skin_color_menus_text) . "',\n" : '';
						    	$output .= $jwppp_skin_color_menus_active_text ? "textActive: '" . esc_html($jwppp_skin_color_menus_active_text) . "',\n" : '';
						    	$output .= $jwppp_skin_color_menus_background ? "background: '" . esc_html($jwppp_skin_color_menus_background) . "',\n" : '';
					    	$output .= "},\n";

					    	$output .= "tooltips: {\n";
						    	$output .= $jwppp_skin_color_tooltips_text ? "text: '" . esc_html($jwppp_skin_color_tooltips_text) . "',\n" : '';
						    	$output .= $jwppp_skin_color_tooltips_background ? "background: '" . esc_html($jwppp_skin_color_tooltips_background) . "',\n" : '';
					    	$output .= "}\n";

				    	}
			    	$output .= "},\n";

					//LOGO
				    if($jwppp_logo !== null) {
				    	$output .= "logo: {\n";
				    	$output .= "file: '" . esc_url($jwppp_logo) . "',\n";
				    	$output .= "position: '" . esc_html($jwppp_logo_vertical) . '-' . esc_html($jwppp_logo_horizontal) . "',\n";
				    	if($jwppp_logo_link !== null) {
				    		$output .= "link: '" . esc_html($jwppp_logo_link) . "'\n";
				    	}
				    	$output .= "},\n";
				    }

				    // SHORTCODE OPTIONS FOR PLAYLISTS
					if($jwppp_new_playlist) {
						//AUTOPLAY
						if($pl_autostart === '1') {
					    	$output .= "autostart: 'true',\n";
					    }

					    //MUTE
					    if($pl_mute === '1') {
					    	$output .= "mute: 'true',\n";
					    }

					    //REPEAT
					    if($pl_repeat === '1') {
					    	$output .= "repeat: 'true',\n";
					    }
					}    
					    
					//ADS
					if($jwppp_show_ads === '1') {
						$output .= "advertising: {\n";
						$output .= "client: '" . esc_html($jwppp_ads_client) . "',\n";
						$output .= "tag: '" . esc_html($jwppp_ads_tag) . "',\n";
						if($jwppp_ads_skip !== '0') {
							$output .= "skipoffset: " . esc_html($jwppp_ads_skip) . ",\n";
						}
						if($jwppp_bidding) {
							$output .= "bids: {\n";
								$output .= "settings: {\n";
									$output .= "mediationLayerAdServer: '" . esc_html($jwppp_mediation) . "',\n";
									if($jwppp_mediation === 'jwp' && $jwppp_floor_price) {
										$output .= "floorPriceCents: " . esc_html($jwppp_floor_price) * 100 . "\n";
									}
								$output .= "},\n";
								$output .= "bidders: [\n";
									$output .= "{\n";
									$output .= "name: 'SpotX',\n";
									$output .= "id: '" . esc_html($jwppp_channel_id) . "'\n";
									$output .= "}\n";
								$output .= "]\n";
								// $output .= "";

							$output .= "}\n";
						}
						$output .= "},\n";
					}

					//RELATED VIDEOS
				    if($jwppp_show_related === '1' && jwppp_get_feed_url() !== null) {
						$output .= "related: {\n";
						$output .= "file: '" . jwppp_get_feed_url() . "',\n";
						if($jwppp_related_heading !== null) {
							$output .= "heading: '" . esc_html($jwppp_related_heading) . "',\n";
						} else {
							$output .= "heading: '" . esc_html(__('Related Videos', 'jwppp')) . "',\n";
						}
						$output .= "onclick: 'link'\n";				
						$output .= "},\n";
					}

					//SUBTITLES STYLE
		      		// if($jwppp_chapters_subtitles == 'subtitles' && jwppp_caption_style()) {
		      		if( jwppp_caption_style() ) {
		      			$output .= "captions: {\n";
		      			$output .= $jwppp_sub_color ? "color: '" . esc_html(($jwppp_sub_color)) . "',\n" : "";
		      			$output .= $jwppp_sub_font_size ? "fontSize: '" . esc_html(($jwppp_sub_font_size)) . "',\n" : "";
		      			$output .= $jwppp_sub_font_family ? "fontFamily: '" . esc_html(($jwppp_sub_font_family)) . "',\n" : "";
		      			$output .= $jwppp_sub_opacity ? "fontOpacity: '" . esc_html(($jwppp_sub_opacity)) . "',\n" : "";
		      			$output .= $jwppp_sub_back_color ? "backgroundColor: '" . esc_html(($jwppp_sub_back_color)) . "',\n" : "";
		      			$output .= $jwppp_sub_back_opacity ? "backgroundOpacity: '" . esc_html(($jwppp_sub_back_opacity)) . "',\n" : "";
		      			$output .= "},\n";
		      		}

					//LOCALIZATION
				    $output .= "localization: {\n";
				    	if($jwppp_next_up) {
						    $output .= "nextUp: '" . esc_html($jwppp_next_up) . "',\n";		    		
				    	}
				    	if($jwppp_playlist_tooltip) {
						    $output .= "playlist: '" . esc_html($jwppp_playlist_tooltip) . "',\n";		    		
				    	}
					    if($jwppp_related_heading) {
						    $output .= "related: '" . esc_html($jwppp_related_heading) . "',\n";			    	
					    }
				    $output .= "},\n";

			   	}

		$output .= "});\n";

		//CHECK FOR A YOUTUBE VIDEO
		$is_yt = jwppp_search_yt('', $number);

		//DOWNLOAD BUTTON
		if($jwppp_download_video && !$jwppp_new_playlist && !$is_yt['yes']) {
			$output .= "playerInstance_$this_video.addButton(\n";
				$output .= "'" . esc_url(plugin_dir_url(__DIR__))  . "images/download-icon.svg',\n";
				$output .= "'Download Video',\n";
				$output .= "function() {\n";
					$output .= "var file = playerInstance_$this_video.getPlaylistItem()['file'];\n";
					$output .= "var file_download = '" . esc_url(plugin_dir_url(__DIR__))  . "includes/jwppp-video-download.php?file=' + file;\n";
					$output .= "window.location.href = file_download;\n";
				$output .= "},\n";
				$output .= "'download',\n";
			$output .=")\n";
		}

		if($is_yt['yes'] || $pl_mute === '1') {
			//VOLUME OFF
			$output .= "playerInstance_$this_video.on('play', function(){\n";
				$output .= "var sound_off = playerInstance_$this_video.getMute();\n";
				$output .= "if(sound_off) {\n";
					$output .= "playerInstance_$this_video.setVolume(0);\n";
				$output .= "}\n";
			$output .= "})\n";
		}
		
		$output .= "</script>\n";

	}

	if(get_post_meta($p_id, '_jwppp-video-url-' . $number, true)) { return $output; }
}


//CREATE JWPPP-VIDEO SHORTCODE
function jwppp_video_s_code($var) {
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
	echo jwppp_video_code(
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

	if(is_singular() && (sanitize_text_field(get_option('jwppp-type-' . $type)) === '1')) {
		$jwppp_videos = jwppp_get_post_videos($post->ID);
		if($jwppp_videos) {
			$video = null;
			foreach($jwppp_videos as $jwppp_video) {
				$jwppp_number = explode('_jwppp-video-url-', $jwppp_video['meta_key']);
				$number 	  = $jwppp_number[1];
				$post_id 	  = get_the_ID();
				$video 	     .= jwppp_video_code(
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
 * With cloud player and self hosted sources, all the tools are shown
 */
function self_media_source_callback() {
	$confirmation = isset($_POST['confirmation']) ? sanitize_text_field($_POST['confirmation']) : '';
	$post_id = isset($_POST['post-id']) ? sanitize_text_field($_POST['post-id']) : '';
	$number = isset($_POST['number']) ? sanitize_text_field($_POST['number']) : '';
	if($confirmation) {
		$tools = sh_video_tools($post_id, $number);
		echo $tools;
	}
	exit;
}
add_action('wp_ajax_self-media-source', 'self_media_source_callback');


function new_media_source_callback() {
	$post_id = isset($_POST['post-id']) ? sanitize_text_field($_POST['post-id']) : '';
	$number = isset($_POST['number']) ? sanitize_text_field($_POST['number']) : '';
	$media_url = isset($_POST['url']) ? sanitize_text_field($_POST['url']) : '';

	if($media_url) {
		update_post_meta($post_id, '_jwppp-video-url-' . $number, $media_url);
	}
	exit;
}
add_action('wp_ajax_new-media-source', 'new_media_source_callback');


/**
 * Get user videos from JW Dashboard (playlist)
 * @return array
 */
function get_videos_from_dashboard() {

    // $contents = file_get_contents('https://cdn.jwplayer.com/v2/playlists/uGjAm4L6?search=test');

    // if($contents) {
    // 	$videos = json_decode($contents);
    // 	return $videos->playlist;
    // }
}


function playlist_carousel() {
	
	$output = '<div id="jwppp-playlist-carousel" class="jw-widget">';
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

class jwppp_dasboard_api {

	public function __construct() {

		$this->api_key = get_option('jwppp-api-key');
		$this->api_secret = get_option('jwppp-api-secret');

		$this->api = $this->init();

	}	

	public function args_check() {
		if($this->api_key && $this->api_secret) {
			return true;
		}
		return false;
	}	

	public function init() {
		// $botr_api = new BotrAPI('XDT6MCLd', 'VpX0PLdUFYOmIx1bv93Bla7G');	//hotmail
		// $botr_api = new BotrAPI('5W4ggqCW', 'OQcKYS0wNqUz5bgWtRbG8yaD'); //info

		$botr_api = null;
		if(strlen($this->api_key) === 8 && strlen($this->api_secret) === 24) {
			$botr_api = new BotrAPI($this->api_key, $this->api_secret);			
		}
		return $botr_api;
	}

	public function get_videos() {
		if($this->api) {
			$output = $this->api->call("/videos/list"); //videos					
			if(isset($output['videos'])) {
				return $output['videos'];
			}
		}
		return null;
	}

	public function get_playlists() {
		if($this->api) {
			$output = $this->api->call("/channels/list"); //videos
			if(isset($output['channels'])) {
				return $output['channels'];
			}
		}
		return null;
	}

	public function account_validation() {
		if($this->api) {
			$output = $this->api->call("/accounts/show", array('account_key' => $this->api_key)); //videos
			if(isset($output['status'])) {
				if($output['status'] === 'ok') {
					return true;					
				}
			}
		}
		return false;
	}

	//TEMP
	public function get_playlist_details($key) {
		// return $this->api->call("/channels/show", array('channel_key' => $key)); //videos	
	
		$contents = file_get_contents('https://cdn.jwplayer.com/v2/playlists/' . $key);	
		if($contents) {
	    	$videos = json_decode($contents);
	    	return $videos->playlist;
	    }
	}

}