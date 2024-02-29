<?php
/**
 * Plugin functions
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @since 2.1.0
 */

/*Files required*/
require( JWPPP_INCLUDES . 'jwppp-ajax-add-video-callback.php' );
require( JWPPP_INCLUDES . 'jwppp-ajax-remove-video-callback.php' );
require( JWPPP_INCLUDES . 'jwppp-video-tools.php' );
require( JWPPP_INCLUDES . 'jwppp-save-single-video-data.php' );
require( JWPPP_INCLUDES . 'jwppp-sh-player-options.php' );
require( JWPPP_INCLUDES . 'jwppp-ads-code-block.php' );
require( JWPPP_INCLUDES . 'jwppp-player-code.php' );
require( JWPPP_DIR . 'classes/class-jwppp-dashboard-api.php' );

require_once( JWPPP_DIR . 'libraries/JWT.php' );
use \ilGhera\JWT\JWT;

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
	if ( 1 === get_post_meta( $post_id, '_jwppp-video-url-' . $number, true ) ) {
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
	if ( 1 === intval( $number ) && ! $dashboard_player ) {
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
 *
 * @param object $post the post.
 *
 * @return void
 */
function jwppp_meta_box_callback( $post ) {

    if ( ! is_object( $post ) ) {
        return;
    }

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


/**
 * Rebase the numbers of the post's videos
 *
 * @param int $post_id the WP post ID.
 * @param int $number  the number of the video deleted.
 *
 * @return void
 */
function jwppp_rebase_post_videos( $post_id, $number ) {

	$videos = jwppp_get_post_videos( $post_id );

    foreach ( $videos as $key => $value ) {

        $parts = explode( '_jwppp-video-url-', $key );
        $n     = isset( $parts[1] ) ? $parts[1] : null;
        $new_number = intval( $n - 1 );

        if ( is_numeric( $n ) && $n > $number ) {

            jwppp_rebase_single_video( $post_id, $n, $new_number );

        }

    }

}


/**
 * Change the single video number in the database
 *
 * @param int $number     the current number of the video.
 * @param int $new_number the new number of the video.
 *
 * @return void
 */
function jwppp_rebase_single_video( $post_id, $number, $new_number ) {

    $keys = array(
        '_jwppp-video-url-',
        '_jwppp-video-mobile-url-',
        '_jwppp-video-image-',
        '_jwppp-video-title-'	,
        '_jwppp-video-description-',
        '_jwppp-autoplay-',
        '_jwppp-single-embed-',
        '_jwppp-activate-media-type-',
        '_jwppp-media-type-',
        '_jwppp-choose-player-'	,
        '_jwppp-playlist-carousel-',
        '_jwppp-mute-',
        '_jwppp-repeat-',
        '_jwppp-ads-tag-',
        '_jwppp-add-chapters-',
        '_jwppp-chapters-subtitles-',
        '_jwppp-subtitles-method-',
        '_jwppp-playlist-items-',
        '_jwppp-video-duration-',
        '_jwppp-video-tags-',
        '_jwppp-cloud-playlist-',
    );

    foreach ( $keys as $key ) {

        /* Get value */
        $value = get_post_meta( $post_id, $key . $number, true );

        /* Add value to new meta */
        update_post_meta( $post_id, $key . $new_number, $value );

        /* Delete old meta */
        delete_post_meta( $post_id, $key . $number );

    }

	/*Update all sources and labels*/
	$sources = get_post_meta( $post_id, '_jwppp-sources-number-' . $number, true );

	if ( $sources ) {

		for ( $i = 0; $i <= ( $sources + 1 ); $i++ ) {

			$main_label = get_post_meta( $post_id, '_jwppp-' . $number . '-main-source-label', true );
			$url        = get_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-url', true );
			$label      = get_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-label', true );

			update_post_meta( $post_id, '_jwppp-' . $new_number . '-main-source-label', $main_label );
			update_post_meta( $post_id, '_jwppp-' . $new_number . '-source-' . $i . '-url', $url );
			update_post_meta( $post_id, '_jwppp-' . $new_number . '-source-' . $i . '-label', $label );

			delete_post_meta( $post_id, '_jwppp-' . $number . '-main-source-label' );
			delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-url' );
			delete_post_meta( $post_id, '_jwppp-' . $number . '-source-' . $i . '-label' );

		}

		update_post_meta( $post_id, '_jwppp-sources-number-' . $new_number, $sources );
	}

	/*Update all chapters*/
	$chapters = get_post_meta( $post_id, '_jwppp-chapters-number-' . $number );

	if ( $chapters ) {

		for ( $n = 0; $n <= ( (int) $chapters + 1 ); $n++ ) {

			$title = get_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-title', true );
			$start = get_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-start', true );
			$end   = get_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-end', true );

			update_post_meta( $post_id, '_jwppp-' . $new_number . '-chapter-' . $n . '-title', $title );
			update_post_meta( $post_id, '_jwppp-' . $new_number . '-chapter-' . $n . '-start', $start );
			update_post_meta( $post_id, '_jwppp-' . $new_number . '-chapter-' . $n . '-end', $end );

			delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-title' );
			delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-start' );
			delete_post_meta( $post_id, '_jwppp-' . $number . '-chapter-' . $n . '-end' );
		}

		update_post_meta( $post_id, '_jwppp-chapters-number-' . $new_number, $chapters );
	}

}


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
	delete_post_meta( $post_id, '_jwppp-cloud-playlist-' . $number );

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

		/*Check if the security option is activated*/
		$security_embeds = sanitize_text_field( get_option( 'jwppp-secure-player-embeds' ) );

		$library = $security_embeds ? jwppp_get_signed_embed( $player_parts[0] ) : $get_library;
		if ( null !== $library ) {
            /* Not loaded in head anymore */
			/* wp_enqueue_script( 'jwppp-library', $library ); */
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

        $loading = sprintf( '<div class="jwppp-rebase-videos loading"><img src="%simages/loading-2.gif"></div>', JWPPP_URI );

		wp_localize_script(
			'jwppp-remove-video',
			'jwpppRemoveVideo',
			array(
				'postId'      => $post_id,
				'removeNonce' => $nonce_remove_video,
                'loading'     => $loading,
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
		'show_in_rest'    	=> true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'video-categories' ),
	);

	$jwppp_taxonomy_select = sanitize_text_field( get_option( 'jwppp-taxonomy-select' ) );
	if ( 'video-categories' === $jwppp_taxonomy_select ) {
		register_taxonomy( 'video-categories', jwppp_get_video_post_types(), $args );
	}
}
add_action( 'init', 'jwppp_create_taxonomy', 1 );


/**
 * Add the "Video categories" taxonomy to all chosen post types
 */
function jwppp_add_taxonomy() {
	$types = jwppp_get_video_post_types();
	$jwppp_taxonomy_select = sanitize_text_field( get_option( 'jwppp-taxonomy-select' ) );
	foreach ( $types as $type ) {
		register_taxonomy_for_object_type( $jwppp_taxonomy_select, $type );
		add_post_type_support( $type, $jwppp_taxonomy_select );
	}
}
add_action( 'admin_init', 'jwppp_add_taxonomy' );


/**
 * The feed for related posts
 */
function jwppp_get_feed_url() {
	$id = get_the_ID();
	$taxonomy = sanitize_text_field( get_option( 'jwppp-taxonomy-select' ) );
	$terms = wp_get_post_terms( $id, $taxonomy );

	if ( isset( $terms[0]->term_id ) ) {
		$feed = get_term_link( $terms[0]->term_id, $taxonomy ); 
		if( get_option( 'permalink_structure' ) ) {
			$feed .= 'related-videos';
		} else {
			$feed .= '&feed=related-videos';
		}

		return $feed;
	}	
}


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
 * Check if the single post/ page ad tag still exists in the option array
 * @param  array $tags the ad tags saved by the user
 * @param  string $tag  the single tag choosed for the specific video
 * @return bool
 */
function jwppp_ads_tag_exists( $tags, $tag ) {
	foreach ( $tags as $single ) {
		if ( $single['url'] === $tag ) {
			return true;
		}
	}
	return false;
}


/**
 * Check if media is a playlist
 *
 * @param  int    $post_id       the post id.
 * @param  int    $video_number  the number of the video in the page.
 * @param  string $media_id      the media id.
 * @param  bool   $securitu_urls true with this option activated.
 *
 * @return bool
 */
function is_cloud_playlist( $post_id, $video_number = null, $media_id = null, $security_urls = false ) {

    $output  = false;
    $code    = $video_number ? $video_number : $media_id;
    $from_db = get_post_meta( $post_id, '_jwppp-cloud-playlist-' . $code, true );

    if ( ! $from_db ) {

        $value = 'no';

        if ( $security_urls ) {

            $url = jwppp_get_signed_url( $media_id, false, false, true );

        } else {

            $url   = 'https://cdn.jwplayer.com/v2/playlists/' . $media_id;

        }

        if ( function_exists( 'vip_safe_wp_remote_get' ) ) {

            $response = vip_safe_wp_remote_get(
                $url,
                '',
                3,
                3,
                20
            );

        } else {

            $response = wp_remote_get( // @codingStandardsIgnoreLine -- for non-VIP environments
                $url,
                array(
                    'timeout' => 3,
                )
            );

        }

        if ( ! is_wp_error( $response ) && is_array( $response ) && isset( $response['response']['code'] ) ) {

            if ( 200 === $response['response']['code'] ) {

                $value = 'yes';
                $output = true;

            }

        }

        update_post_meta( $post_id, '_jwppp-cloud-playlist-' . $code, $value );

    } elseif ( 'yes' === $from_db ) {

        $output = true;

    } 

    return $output;

}


/**
 * Generate signed URLs
 *
 * @param  string $media_id the media id.
 * @param  bool   $short return only the last part of the url.
 * @param  bool   $matching define if the media is a Article Matching plylist.
 * @param  bool   $playlist define if the media is a playlist.
 *
 * @return string
 */
function jwppp_get_signed_url( $media_id, $short = false, $matching = false, $playlist = false ) {

	$token_secret   = get_option( 'jwppp-api-secret' );
	$resource       = $playlist ? "v2/playlists/$media_id" : "v2/media/$media_id";
	$resource4token = $resource;
	$plus = '?';

	/*Different url for Article Matching playlists*/
	if ( $matching ) {
		$resource = "v2/playlists/$media_id";
		$resource4token = explode('?search', $resource)[0];
		$plus = '&';
	}

	$timeout = get_option( 'jwppp-secure-timeout' ) ? get_option( 'jwppp-secure-timeout' ) : 60;

	$expires = ceil( ( time() + ( $timeout * 60 ) ) / 180 ) * 180;

	$token_body = array(
		'resource' => $resource4token,
		'exp' 	   => $expires,
	);

	$jwt = JWT::encode( $token_body, $token_secret );

	if ( $short ) {
		return $resource . $plus . "token=$jwt";
	} else {
		return "https://cdn.jwplayer.com/" . $resource . $plus . "token=$jwt";
	}
}


/**
 * Generate signed embeds
 * @param  string $player_id the player id
 * @return string
 */
function jwppp_get_signed_embed( $player_id ) {

	$token_secret = get_option( 'jwppp-api-secret' );
	$path = 'libraries/' . $player_id . '.js';
	$timeout = get_option( 'jwppp-secure-timeout' ) ? get_option( 'jwppp-secure-timeout' ) : 60;

	$expires = ceil( ( time() + ( $timeout * 60 ) ) / 180 ) * 180;

	$signature = md5( $path . ':' . $expires . ':' . $token_secret );
	$url = 'https://content.jwplatform.com/' . $path . '?exp=' . $expires . '&sig=' . $signature;

	return $url;
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
 * @param  string $media  the media.
 * @return string         the code block
 */
function jwppp_simple_player_code( $media ) {

    /*Is the video self hosted?*/
    $sh_video = strrpos( $media, 'http' ) === 0 ? true : false;

    if ( ! $sh_video ) {
    
        $playlist = is_cloud_playlist( get_the_ID(), null, $media );
        $resource = $playlist ? 'https://cdn.jwplayer.com/v2/playlists/' . $media : 'https://cdn.jwplayer.com/v2/media/' . $media; 
        $attr     = $media;

    } else {

        $attr = md5( $media );

    }

	/*Output the player*/
	echo "<div id='jwppp-video-box-" . esc_attr( $attr ) . "' class='jwppp-video-box' data-video='" . esc_attr( $media ) . "' style=\"margin: 1rem 0;\">\n";
		echo  "<div id='jwppp-video-" . esc_attr( $attr ) . "'>";
		if ( sanitize_text_field( get_option( 'jwppp-text' ) ) !== null ) {
			echo esc_html( get_option( 'jwppp-text' ) );
		} else {
			echo esc_html( __( 'Loading the player...', 'jwppp' ) );
		}
		echo "</div>\n";

		/*Check if the security option is activated*/
		$security_urls = get_option( 'jwppp-secure-video-urls' );

		echo "<script type='text/javascript'>\n";
			echo "var playerInstance_" . trim( wp_json_encode( $attr ), '"' ) . " = jwplayer(" . wp_json_encode( 'jwppp-video-' . $attr ) . ");\n";
			echo "playerInstance_" . trim( wp_json_encode( $attr ), '"' ) . ".setup({\n";

            if ( $sh_video ) {

                echo "file: " . wp_json_encode( $media, JSON_UNESCAPED_SLASHES ) . ",\n";

            } else {

                if ( $security_urls ) {
                    echo "playlist: " . wp_json_encode( jwppp_get_signed_url( $media, false, false, $playlist ), JSON_UNESCAPED_SLASHES ) . ",\n";
                } else {
                    echo "playlist: " . wp_json_encode( $resource, JSON_UNESCAPED_SLASHES ) . ",\n";
                }

            }

            /* Poster image */
            if ( has_post_thumbnail( get_the_ID() ) && 1 === intval( get_option( 'jwppp-post-thumbnail' ) ) ) {

                echo "image: " . wp_json_encode( get_the_post_thumbnail_url(), JSON_UNESCAPED_SLASHES ) . ",\n";

            } else if ( get_option( 'jwppp-poster-image' ) ) {

                echo "image: " . wp_json_encode( get_option( 'jwppp-poster-image' ), JSON_UNESCAPED_SLASHES ) . ",\n";

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
 * @param  array $vars the media ID or the full URL.
 * @return string      the code block
 */
function jwppp_old_player_s_code( $vars ) {

    $var    = null;
    $output = null;

    if ( isset( $vars[0] ) ) {

        $var = $vars[0];

    } elseif ( isset( $vars['file'] ) ) {

        $var = $vars['file'];

    }

    if ( $var ) {

        ob_start();

        jwppp_simple_player_code( $var );

        $output = ob_get_clean();

    }

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
 * The playlist carousel html element
 * @param  int $player_id the id of the player
 * @return mixed
 */
function jwppp_playlist_carousel( $player_id ) {

	echo '<div id="jwppp-playlist-carousel-' . esc_attr( $player_id ) . '" class="jw-widget">';
		echo '<div class="jw-widget-title"></div>';
		echo '<div class="jw-widget-content"></div>';
		echo '<div class="jw-widget-arrows">';
			echo '<div class="arrow previous disabled">';
				echo '<svg class="icon" width="61.1px" height="100px" viewBox="622.7 564.5 61.1 100" fill="#fff" xml:space="preserve">';
					echo '<path d="M680.6,567.7c4.3,4.3,4.3,11.3,0,15.5l-31.2,31.2l31.2,31.2c4.3,4.3,4.3,11.3,0,15.5c-4.3,4.3-11.3,4.3-15.7,0l-39-39c-4.3-4.3-4.3-11.3,0-15.5l39.1-39C669.3,563.4,676.3,563.4,680.6,567.7z"/>';
				echo '</svg>';
			echo '</div>';
			echo '<div class="arrow next">';
				echo '<svg class="icon" width="61.3px" height="100px" viewBox="625.1 564.5 61.3 100" style="enable-background:new 625.1 564.5 61.3 100;" xml:space="preserve" fill="#fff">';
					echo '<path d="M644,567.7l39.1,39c4.3,4.3,4.3,11.3,0,15.5l-39.1,39c-4.3,4.3-11.3,4.3-15.7,0c-4.3-4.3-4.3-11.3,0-15.5l31.3-31.2l-31.3-31.2c-4.3-4.3-4.3-11.3,0-15.5C632.6,563.4,639.6,563.4,644,567.7z"/>';
				echo '</svg>';
			echo '</div>';
		echo '</div>';
	echo '</div>';

}


/**
 * Search contents in the dashboard, both single videos and playlists
 * @return string a json encoded array of the results
 */
function jwppp_search_content_callback() {

	$api = new JWPPP_Dashboard_Api();

	if ( isset( $_POST['number'], $_POST[ 'hidden-meta-box-nonce-' . $_POST['number'] ] ) ) {

		if ( wp_verify_nonce(
			$_POST[ 'hidden-meta-box-nonce-' . $_POST['number'] ],
			'jwppp-meta-box-nonce-' . sanitize_text_field( wp_unslash( $_POST['number'] ) )
		) ) {

			$term = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';

			if ( $term ) {
				$videos    = $api->search( $term );
				$playlists = $api->search( $term, true );
			} else {
				$videos    = $api->get_videos();
				$playlists = $api->get_playlists();
			}

			echo wp_json_encode(
				array(
					'videos'    => $videos,
					'playlists' => $playlists,
				)
			);

		}
	}

	exit;
}
add_action( 'wp_ajax_search-content', 'jwppp_search_content_callback' );


/**
 * Returns videos and playlists
 * Fired when the select element is clicked
 */
function jwppp_list_content_callback() {

	if ( isset( $_POST['number'], $_POST[ 'hidden-meta-box-nonce-' . $_POST['number'] ] ) ) {

		if ( wp_verify_nonce(
			$_POST[ 'hidden-meta-box-nonce-' . $_POST['number'] ],
			'jwppp-meta-box-nonce-' . sanitize_text_field( wp_unslash( $_POST['number'] ) )
		) ) {

			$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : '';
			$number = intval( $_POST['number'] );

			if ( $post_id && $number ) {

				$api = new JWPPP_Dashboard_Api();

				$video_url = get_post_meta( $post_id, '_jwppp-video-url-' . $number, true );

				if ( $api->args_check() ) {

                    $videos    = $api->get_videos();
                    $playlists = $api->get_playlists();

                    /*Videos*/
                    if ( is_array( $videos ) ) {

                        if ( isset( $videos['error'] ) ) {

                            echo '<span class="jwppp-alert api">' . esc_html( $videos['error'] ) . '</span>';

                        } else {

                            echo '<li class="reset">' . esc_html( 'select a video', 'jwppp' ) . '<span>' . esc_html( __( 'clear', 'jwppp' ) ) . '</span></li>';
                            for ( $i = 0; $i < min( 15, count( $videos ) ); $i++ ) {
                                echo '<li ';
                                    echo 'data-mediaid="' . ( isset( $videos[ $i ]->id ) ? esc_attr( $videos[ $i ]->id ) : '' ) . '" ';
                                    echo 'data-duration="' . ( isset( $videos[ $i ]->duration ) ? esc_attr( $videos[ $i ]->duration ) : '' ) . '" ';
                                    echo 'data-description="' . ( isset( $videos[ $i ]->metadata->description ) ? esc_attr( $videos[ $i ]->metadata->description ) : '' ) . '"';
                                    echo 'data-tags="' . ( isset( $videos[ $i ]->metadata->tags ) ? esc_attr( implode( ', ', $videos[ $i ]->metadata->tags ) ) : '' ) . '"';
                                    echo ( $video_url === $videos[ $i ]->id ? ' class="selected"' : '' ) . '>';
                                    echo '<img class="video-img" src="https://cdn.jwplayer.com/thumbs/' . ( isset( $videos[ $i ]->id ) ? esc_html( $videos[ $i ]->id ) : '' ) . '-60.jpg" />';
                                    echo '<span>' . ( isset( $videos[ $i ]->metadata->title ) ? esc_html( $videos[ $i ]->metadata->title ) : '' ) . '</span>';
                                echo '</li>';
                            }
                        }
                    }

                    /*Playlists*/
                    if ( is_array( $playlists ) ) {

                        if ( isset( $playlists['error'] ) ) {

                            if ( ! isset( $videos['error'] ) ) {
                                echo '<span class="jwppp-alert api">' . esc_html( $playlists['error'] ) . '</span>';
                            }
                        } else {

                            $playlist_thumb = plugin_dir_url( __DIR__ ) . 'images/playlist4.png';
                            
                            echo '<li class="reset">' . esc_html( 'Select a playlist', 'jwppp' ) . '<span>' . esc_html( __( 'Clear', 'jwppp' ) ) . '</span></li>';
                            
                            for ( $i = 0; $i < min( 15, count( $playlists ) ); $i++ ) {
                            
                                /* Get the number of videos in the playlist */
                                $items = $api->get_playlist_items( $playlists[ $i ]->id, $playlists[ $i ]->playlist_type );
                            
                                echo '<li class="playlist-element' . ( $video_url === $playlists[ $i ]->id ? ' selected' : '' ) . '" ';
                                    echo 'data-mediaid="' . ( isset( $playlists[ $i ]->id ) ? esc_attr( $playlists[ $i ]->id ) : '' ) . '"';
                                    echo 'data-description="' . ( isset( $playlists[ $i ]->metadata->description ) ? esc_attr( $playlists[ $i ]->metadata->description ) : '' ) . '"';
                                    echo 'data-videos="' . ( $items ? esc_attr( $items ) : '' ) . '"';
                                    echo '>';
                                    echo '<img class="video-img" src="' . esc_url( $playlist_thumb ) . '" />';
                                    echo '<span>' . ( isset( $playlists[ $i ]->metadata->title ) ? esc_html( $playlists[ $i ]->metadata->title ) : '' ) . '</span>';
                                echo '</li>';
                            }
                        }
                    }
                } else {

                    echo '<span class="jwppp-alert api">' . esc_html( __( 'Invalid API Credentials.', 'jwppp' ) ) . '</span>';

                }
            } else {

                echo '<span class="jwppp-alert api">' . esc_html( __( 'API Credentials are required for using this tool.', 'jwppp' ) ) . '</span>';

            }
        }
	}

	exit;
}
add_action( 'wp_ajax_init-api', 'jwppp_list_content_callback' );


/**
 * Returns the players available from the dashboard
 * Fired when the "Show options" is clicked
 */
function jwppp_get_player_callback() {

	if ( isset( $_POST['number'], $_POST[ 'hidden-meta-box-nonce-' . $_POST['number'] ] ) ) {

		if ( wp_verify_nonce(
			$_POST[ 'hidden-meta-box-nonce-' . $_POST['number'] ],
			'jwppp-meta-box-nonce-' . sanitize_text_field( wp_unslash( $_POST['number'] ) )
		) ) {

			$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : '';
			$number = intval( $_POST['number'] );

			/*Player library*/
			$library_parts = explode( 'libraries/', get_option( 'jwppp-library' ) );

			/*Choose player*/
			$choose_player = get_post_meta( $post_id, '_jwppp-choose-player-' . $number, true );

			$api = new JWPPP_Dashboard_Api();
			$players = $api->get_players();

			if ( is_array( $players ) && ! isset( $players['error'] ) ) {

				echo '<label for="_jwppp-choose-player-' . esc_attr( $number ) . '"><strong>' . esc_html( __( 'Select Player', 'jwppp' ) ) . '</strong></label>';
				echo '<p>';
					echo '<select class="jwppp-choose-player-' . esc_attr( $number ) . '" name="_jwppp-choose-player-' . esc_attr( $number ) . '">';

				foreach ( $players as $player ) {
					$selected = false;
					if ( $choose_player && $choose_player === $player->id ) {
						$selected = true;
					} elseif ( ! $choose_player && $library_parts[1] === $player->id . '.js' ) {
						$selected = true;
					}
					echo '<option name="' . esc_attr( $player->id ) . '" value="' . esc_attr( $player->id ) . '"' . ( $selected ? ' selected="selected"' : '' ) . '>' . esc_html( $player->metadata->name ) . '</option>';
				}

					echo '</select>';
				echo '</p>';

			}
		}
	}

	exit;
}
add_action( 'wp_ajax_get-players', 'jwppp_get_player_callback' );


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
    if ( $image_url && @getimagesize( $image_url ) ) {

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

