<?php
/**
 * Plugin functions
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @since 2.1.1
 */

/*Files required*/
require( JWPPP_INCLUDES . 'jwppp-ajax-add-video-callback.php' );
require( JWPPP_INCLUDES . 'jwppp-ajax-remove-video-callback.php' );
require( JWPPP_INCLUDES . 'jwppp-video-tools.php' );
require( JWPPP_INCLUDES . 'jwppp-save-single-video-data.php' );
require( JWPPP_INCLUDES . 'jwppp-sh-player-options.php' );
require( JWPPP_INCLUDES . 'jwppp-player-code.php' );

/**
 * Add meta box
 */
function jwppp_add_meta_box() {
	$jwppp_get_types = get_post_types();
	$screens = array();
	foreach ( $jwppp_get_types as $type ) {
		if ( sanitize_text_field( get_option( 'jwppp-type-' . $type ) === '1' ) ) {
			array_push( $screens, $type );
		}
	}
	foreach ( $screens as $screen ) {
		add_meta_box( 'jwppp-box', __( 'JW Player for WordPress', 'jwppp' ), 'jwppp_meta_box_callback', $screen );
	}
}
add_action( 'add_meta_boxes', 'jwppp_add_meta_box' );


/**
 * Button premium call to action
 * @param string $text the text to use as title
 * @param bool   $box  used in single video box
 * @return mixed
 */
function go_premium( $text = null, $box = false ) {
	echo '<div class="bootstrap-iso">';
		echo '<span class="label label-warning premium' . ( $box ? ' box' : '' ) . '"><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank" title="' . esc_html( $text ) . '">Premium</a></label>';
	echo '</div>';
}


/**
 * Get all videos of a single post
 * @param  int $post_id
 * @return array  post/ page videos with meta_key as key and url as value
 */
function jwppp_get_post_videos( $post_id ) {

	$videos = get_post_meta( $post_id );

	if ( is_array( $videos ) ) {
		
		$videos = array_filter(
			$videos,
			function( $key ) {
				if ( false !== strpos( $key, '_jwppp-video-url-' ) ) {
					return $key;
				}
			},
			ARRAY_FILTER_USE_KEY
		);

		if ( count( $videos ) >= 1 && ! get_post_meta( $post_id, '_jwppp-video-url-1', true ) ) {
			array_unshift(
				$videos,
				array(
					'_jwppp-video-url-1' => 1,
				)
			);
		}

		return $videos;

	} else {

		return array( 
			'_jwppp-video-url-1' => 1,
		);

	}

}


/**
 * Get videos ids string
 * @param  int $post_id
 * @return string the vido ids of the post/ page
 */
function jwppp_videos_string( $post_id ) {
	$ids = array();
	$videos = jwppp_get_post_videos( $post_id );
	if ( $videos ) {
		for ( $i = 1; $i <= count( $videos ); $i++ ) {
			$ids[] = $i;
		}
	}
	$string = implode( ',', $ids );
	return $string;
}


/**
 * Single video box with all his option
 * @param  int $post_id
 * @param  int $number  the number of video in the post/ page
 */
function jwppp_single_video_box( $post_id, $number ) {

	/*Delete video if url is equal to 1, it means empty*/
	if ( 1 === intval( get_post_meta( $post_id, '_jwppp-video-url-' . $number, true ) ) ) {
		delete_post_meta( $post_id, '_jwppp-video-url-' . $number );
		return;
	}

	/*How to add the playlist*/
	$plist_hide = true;
	if ( 1 === intval( $number ) && 'custom' === get_option( 'jwppp-position' ) && count( jwppp_get_post_videos( $post_id ) ) > 1 ) {
		$plist_hide = false;
	}

	/*Is it a dashboard player?*/
	$dashboard_player = is_dashboard_player();

	/*Available only with self-hosted players*/
	if ( 1 === intval( $number ) ) {
		$output  = '<div class="playlist-how-to" style="position:relative;background:#2FBFB0;color:#fff;padding:0.5rem 1rem;';
		$output .= ( $plist_hide ) ? 'display:none;">' : 'display:block;">';
		$output .= 'Add a playlist of your videos using this code: <code style="display:inline-block;color:#fff;background:none;">[jwp-video n="' . jwppp_videos_string( $post_id ) . '"]</code>';
		if ( 'custom' !== get_option( 'jwppp-position' ) ) {
			$output .= '<a class="attention-mark" title="' . __( 'You need to set the VIDEO PLAYER POSITION option to CUSTOM in order to use this shortcode.', 'jwppp' ) . '"><img class="attention-mark" src="' . plugin_dir_url( __DIR__ ) . 'images/attention-mark.png" /></a></th>';
		}
		$output .= '</div>';

		$allowed_tags = array(
			'div' => array(
				'class' => [],
				'style' => [],
			),
			'code' => array(
				'style' => [],
			),
		);

		echo wp_kses( $output, $allowed_tags );
	}

	require( plugin_dir_path( __FILE__ ) . 'jwppp-single-video-box.php' );
}


/**
 * Output the jwppp meta box with all videos
 * @param  object $post the post
 */
function jwppp_meta_box_callback( $post ) {

	$jwppp_videos = jwppp_get_post_videos( $post->ID );

	if ( ! empty( $jwppp_videos ) ) {
		for ( $i = 1; $i <= count( $jwppp_videos ); $i++ ) {
			jwppp_single_video_box( $post->ID, $i );
		}
	} else {
		jwppp_single_video_box( $post->ID, 1 );
	}
}


/*temp*/
add_action( 'wp_ajax_jwppp_ajax_add', 'jwppp_ajax_add_video_callback' );
add_action( 'wp_ajax_jwppp_ajax_remove', 'jwppp_ajax_remove_video_callback' );


/**
 * Delete all video metas from the db
 * @param  int $post_id
 * @param  int $number  the number of video in the post/ page
 */
function jwppp_db_delete_video( $post_id, $number ) {

	delete_post_meta( $post_id, '_jwppp-video-url-' . $number );
	delete_post_meta( $post_id, '_jwppp-video-mobile-url-' . $number );
	delete_post_meta( $post_id, '_jwppp-video-image-' . $number );
	delete_post_meta( $post_id, '_jwppp-video-title-' . $number );
	delete_post_meta( $post_id, '_jwppp-video-description-' . $number );
	delete_post_meta( $post_id, '_jwppp-autoplay-' . $number );
	delete_post_meta( $post_id, '_jwppp-single-embed-' . $number );
	delete_post_meta( $post_id, '_jwppp-activate-media-type-' . $number );
	delete_post_meta( $post_id, '_jwppp-media-type-' . $number );
	delete_post_meta( $post_id, '_jwppp-choose-player-' . $number );
	delete_post_meta( $post_id, '_jwppp-playlist-carousel-' . $number );
	delete_post_meta( $post_id, '_jwppp-mute-' . $number );
	delete_post_meta( $post_id, '_jwppp-repeat-' . $number );
	delete_post_meta( $post_id, '_jwppp-ads-tag-' . $number );
	delete_post_meta( $post_id, '_jwppp-add-chapters-' . $number );
	delete_post_meta( $post_id, '_jwppp-chapters-subtitles-' . $number );
	delete_post_meta( $post_id, '_jwppp-subtitles-method-' . $number );
	delete_post_meta( $post_id, '_jwppp-playlist-items-' . $number );
	delete_post_meta( $post_id, '_jwppp-video-duration-' . $number );
	delete_post_meta( $post_id, '_jwppp-video-tags-' . $number );

	/*Delete all sources and labels*/
	$sources = get_post_meta( $post_id, '_jwppp-sources-number-' . $number, true );
	if ( $sources ) {
		for ( $i = 0; $i <= ( $sources + 1 ); $i++ ) {
			delete_post_meta( $post_id, '_jwppp-' . $number . '-main-source-label' );
			delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-url' );
			delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-label' );
		}
		delete_post_meta( $post_id, '_jwppp-sources-number-' . $number );
	}

	/*Delete all chapters*/
	$chapters = get_post_meta( $post_id, '_jwppp-chapters-number-' . $number );
	if ( $chapters ) {
		for ( $n = 0; $n <= ( (int) $chapters + 1 ); $n++ ) {
			delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-title' );
			delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-start' );
			delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-end' );
		}
		delete_post_meta( $post_id, '_jwppp-chapters-number-' . $number );
	}
}


/**
 * Add scripts and style in the page head
 * Licence key, JW Player library, custom skin and playlist carousel
 */
function jwppp_add_header_code() {

	$get_library = sanitize_text_field( get_option( 'jwppp-library' ) );

	/*Is it a dashboard player?*/
	$dashboard_player = is_dashboard_player();

	/*Default dashboard player informations*/
	if ( $dashboard_player ) {
		if ( strpos( $get_library, 'jwplatform.com' ) !== false ) {
			$library_parts = explode( 'https://content.jwplatform.com/libraries/', $get_library );
		} else {
			$library_parts = explode( 'https://cdn.jwplayer.com/libraries/', $get_library );
		}
		$player_parts = explode( '.js', $library_parts[1] );

		$library = $get_library;
		if ( null !== $library ) {
			wp_enqueue_script( 'jwppp-library', $library );
		}

		/*JW Widget for Playlist Carousel*/
		wp_enqueue_style( 'jwppp-widget-style', plugin_dir_url( __DIR__ ) . 'jw-widget/css/jw-widget-min.css' );
		wp_enqueue_script( 'jwppp-widget', plugin_dir_url( __DIR__ ) . 'jw-widget/js/jw-widget-min.js' );

	} else {

		$library = $get_library;
		if ( null !== $library ) {
			wp_enqueue_script( 'jwppp-library', $library );
		}

		$licence = sanitize_text_field( get_option( 'jwppp-licence' ) );
		$skin    = sanitize_text_field( get_option( 'jwppp-skin' ) );

		if ( null !== $licence ) {
			wp_register_script( 'jwppp-licence', plugin_dir_url( __DIR__ ) . 'js/jwppp-licence.js' );

			/*Useful for passing data*/
			$data = array(
				'licence' => sanitize_text_field( get_option( 'jwppp-licence' ) ),
			);
			wp_localize_script( 'jwppp-licence', 'data', $data );
			wp_enqueue_script( 'jwppp-licence' );
		}

		if ( 'custom-skin' === $skin ) {
			$skin_url = sanitize_text_field( get_option( 'jwppp-custom-skin-url' ) );
			if ( $skin_url ) {
				wp_enqueue_style( 'jwppp-custom-skin', $skin_url );
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'jwppp_add_header_code' );


/**
 * Enqueue scripts for backend posts and pages
 */
function jwppp_backend_scripts() {

	if ( get_the_ID() ) {

		$post_id = get_the_ID();
		$nonce_add_video = wp_create_nonce( 'jwppp-nonce-add-video' );
		$nonce_remove_video = wp_create_nonce( 'jwppp-nonce-remove-video' );

		/*Add a new video to post/ page*/
		wp_enqueue_script( 'jwppp-add-video', plugin_dir_url( __DIR__ ) . 'js/jwppp-add-video.js', array( 'jquery' ) );

		wp_localize_script(
			'jwppp-add-video',
			'jwpppAddVideo',
			array(
				'postId'    => $post_id,
				'addNonce'  => $nonce_add_video,
				'addRemove' => $nonce_remove_video,
			)
		);

		/*Remove a video in a post/ page*/
		wp_enqueue_script( 'jwppp-remove-video', plugin_dir_url( __DIR__ ) . 'js/jwppp-remove-video.js', array( 'jquery' ) );

		wp_localize_script(
			'jwppp-remove-video',
			'jwpppRemoveVideo',
			array(
				'postId'      => $post_id,
				'removeNonce' => $nonce_remove_video,
			)
		);

	}
}
add_action( 'admin_enqueue_scripts', 'jwppp_backend_scripts' );


/**
 * Get post-types chosen by the publisher for posting videos
 * @return array the post types
 */
function jwppp_get_video_post_types() {
	$types = get_post_types( array( 'public' => 'true' ) );
	$video_types = array();
	foreach ( $types as $type ) {
		if ( get_option( 'jwppp-type-' . $type ) === '1' ) {
			array_push( $video_types, $type );
		}
	}
	return $video_types;
}
add_action( 'init', 'jwppp_get_video_post_types', 0 );


/**
 * Check if a source is a YouTube video
 * @param  string $jwppp_video_url a full url to check
 * @param  int $number             the video number of the current post
 * @return array                   if a YouTube video, the embed url and the preview image
 */
function jwppp_search_yt( $jwppp_video_url = '', $number = '' ) {
	if ( $number ) {
		$jwppp_video_url = get_post_meta( get_the_ID(), '_jwppp-video-url-' . $number, true );
	}
	$youtube1      = 'https://www.youtube.com/watch?v=';
	$youtube2      = 'https://youtu.be/';
	$youtube_embed = 'https://www.youtube.com/embed/';
	$is_yt = false;

	/*YouTube link types*/
	if ( strpos( $jwppp_video_url, $youtube1 ) !== false ) {
		$jwppp_embed_url = str_replace( $youtube1, $youtube_embed, $jwppp_video_url );
		$yt_parts = explode( $youtube1, $jwppp_video_url );
		$yt_video_id = $yt_parts[1];
		$is_yt = true;
	} elseif ( strpos( $jwppp_video_url, $youtube2 ) !== false ) {
		$jwppp_embed_url = str_replace( $youtube2, $youtube_embed, $jwppp_video_url );
		$yt_parts = explode( $youtube2, $jwppp_video_url );
		$yt_video_id = $yt_parts[1];
		$is_yt = true;
	} elseif ( strpos( $jwppp_video_url, $youtube_embed ) !== false ) {
		$jwppp_embed_url = $jwppp_video_url;
		$yt_parts = explode( $youtube_embed, $jwppp_video_url );
		$yt_video_id = $yt_parts[1];
		$is_yt = true;
	} else {
		$jwppp_embed_url = $jwppp_video_url;
		$yt_parts = '';
		$yt_video_id = '';
		$is_yt = false;
	}

	$yt_video_image = $yt_video_id ? 'https://img.youtube.com/vi/' . $yt_video_id . '/maxresdefault.jpg' : '';

	return array(
		'yes' => $is_yt,
		'embed-url' => $jwppp_embed_url,
		'video-image' => $yt_video_image,
	);

}


/**
 * The player shortcode
 * @param  array $var the player options available
 */
function jwppp_player_s_code( $var ) {
	ob_start();
	$video = shortcode_atts(
		array(
			'p'      => get_the_ID(),
			'n'      => '1',
			'ar'     => '',
			'width'  => '',
			'height' => '',
			'pl_autostart' => '',
			'pl_mute'     => '',
			'pl_repeat'   => '',
		),
		$var
	);
	jwppp_player_code(
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
add_shortcode( 'jw7-video', 'jwppp_player_s_code' );
add_shortcode( 'jwp-video', 'jwppp_player_s_code' );


/**
 * Used for old JW Player shortcodes only with contents from the dashboard
 * @param  string $media_id the media id
 * @return string           the code block
 */
function jwppp_simple_player_code( $media_id ) {

	/*Output the player*/
	echo "<div id='jwppp-video-box-" . esc_attr( $media_id ) . "' class='jwppp-video-box' data-video='" . esc_attr( $media_id ) . "' style=\"margin: 1rem 0;\">\n";
		echo  "<div id='jwppp-video-" . esc_attr( $media_id ) . "'>";
		if ( sanitize_text_field( get_option( 'jwppp-text' ) ) !== null ) {
			echo esc_html( get_option( 'jwppp-text' ) );
		} else {
			echo esc_html( __( 'Loading the player...', 'jwppp' ) );
		}
		echo "</div>\n";

		/*Check if the security option is activated*/
		$security_urls = get_option( 'jwppp-secure-video-urls' );

		echo "<script type='text/javascript'>\n";
			echo "var playerInstance_" . trim( wp_json_encode( $media_id ), '"' ) . " = jwplayer(" . wp_json_encode( 'jwppp-video-' . $media_id ) . ");\n";
			echo "playerInstance_" . trim( wp_json_encode( $media_id ), '"' ) . ".setup({\n";

			if ( $security_urls ) {
					echo "playlist: " . wp_json_encode( jwppp_get_signed_url( $media_id ), JSON_UNESCAPED_SLASHES ) . ",\n";
			} else {
				echo "playlist: " . wp_json_encode( "https://cdn.jwplayer.com/v2/media/" . $media_id, JSON_UNESCAPED_SLASHES ) . ",\n";
			}

			/*Is it a dashboard player?*/
			$dashboard_player = is_dashboard_player();

			/*Options available only with self-hosted player*/
			if ( ! $dashboard_player ) {
				jwppp_sh_player_option();
			}

			echo "})\n";
		echo '</script>';
	echo "</div>\n";

}


/**
 * Old JW Player shortcode
 * @param  array $var the shortcode attributes available, the media id in this case
 * @return string     the code block
 */
function jwppp_old_player_s_code( $var ) {
	ob_start();
	jwppp_simple_player_code( $var[0] );
	$output = ob_get_clean();
	return $output;
}
add_shortcode( 'jwplayer', 'jwppp_old_player_s_code' );


/**
 * Execute shortcodes in widgets
 */
if ( ! has_filter( 'widget_text', 'do_shortcode' ) ) {
	add_filter( 'widget_text', 'do_shortcode' );
}


/**
 * Add player to the contents
 * @param  mixed $content the post/ page content
 * @return mixed          the post/ page content + the player(s)
 */
function jwppp_add_player( $content ) {
	global $post;
	$type = get_post_type( $post->ID );

	$jwppp_videos = jwppp_get_post_videos( $post->ID );

    if ( $jwppp_videos ) {
		$video = null;

		ob_start();

		for ( $i = 1; $i <= count( $jwppp_videos ); $i++ ) {
			$number       = $i;
			$post_id      = get_the_ID();
			$video       .= jwppp_player_code(
				$post_id,
				$number,
				$ar = '',
				$width = '',
				$height = '',
				$pl_autostart = '',
				$pl_mute = '',
				$pl_repeat = ''
			);
		}

		$output = ob_get_clean();

		$position = get_option( 'jwppp-position' );
		if ( 'after-content' === $position ) {
			$content = $content . $output;
		} elseif ( 'before-content' === $position ) {
			$content = $output . $content;
		}
	}
	
	return $content;
}
add_filter( 'the_content', 'jwppp_add_player' );


/**
 * Returns videos and playlists
 * Fired when the select element is clicked
 */
function jwppp_list_content_callback() {

	if ( isset( $_POST['number'], $_POST[ 'hidden-meta-box-nonce-' . $_POST['number'] ] ) ) {		
					
		echo '<span class="jwppp-alert api">' . esc_html( __( 'API Credentials are required for using this tool.', 'jwppp' ) ) . '</span>';
	
	}

	exit;
}
add_action( 'wp_ajax_init-api', 'jwppp_list_content_callback' );


/**
 * Get poster image of both self-hosted and cloud videos
 *
 * @param int   the post id.
 * @param mixed the URL or video id from the JW Player Dashboard.
 *
 * @return string the image URL
 */
function jwppp_get_poster_image( $post_id, $video ) {

    /*Is the video self hosted?*/
    $sh_video = strrpos( $video, 'http' ) === 0 ? true : false;

    /* Remote image URL */
    if ( $sh_video ) {

        $image_url = get_post_meta( $post_id, '_jwppp-video-image-1', true );

    } else {

        $image_url = 'https://cdn.jwplayer.com/thumbs/' . $video . '-720.jpg';

    }

    /* If image exists */
    if ( @getimagesize( $image_url ) ) {

        return $image_url;

    }

}


/*
 * Return true if a video is set
 *
 * @param bool $has_thumbnail true if post has thumbnail.
 * @param int $post_id        the post id.
 *
 * @return void
 */
function jwppp_check_post_thumbnail( $has_thumbnail, $post_id ) {

    if ( ! $has_thumbnail && get_option( 'jwppp-poster-image-as-thumb' ) ) {

        if( ! $post_id || get_post_meta( $post_id, '_jwppp-video-url-1', true ) ) {

            $has_thumbnail = true;

        }

    }

    return $has_thumbnail;

}
add_filter( 'has_post_thumbnail', 'jwppp_check_post_thumbnail', 20, 2 );


/*
 * Use video poster-image as thumbanail if a featured image is not set
 *
 * @param string $html the post thumbnail HTML.
 * @param int    $post_id  the post ID.
 *
 * $return string
 */
function jwppp_poster_image_as_thumbnail( $html, $post_id ) {

    if( get_option( 'jwppp-poster-image-as-thumb' ) ) {

        $video = get_post_meta( $post_id, '_jwppp-video-url-1', true );

        if ( ! $html && $video ) {

            $image_url = jwppp_get_poster_image( $post_id, $video );

            if ( $image_url ) {

                $html = sprintf( '<img src="%s">', esc_url( $image_url ) );

            }

        }

    }

    return $html;

}
add_filter( 'post_thumbnail_html', 'jwppp_poster_image_as_thumbnail', 10, 2 );


/*
 * Transform time string to ISO 8601 format
 *
 * @param string $duration the time string in 00:00:00 format.
 *
 * @return string
 *
 */
function get_duration_iso_format( $duration ) {

    if ( '00:00:00' !== $duration ) {

        $times = explode( ':', $duration );

        if ( 3 === count( $times ) ) {

            return sprintf('PT%dH%dM%dS', $times[0], $times[1], $times[2]);

        }

    }

}

