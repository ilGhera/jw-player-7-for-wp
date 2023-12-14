<?php
/**
 * The JW Player API for communicate  with the dashboard
 *
 * @author ilGhera
 * @package jw-player-7-for-wp/classes
 *
 *
 * @since 2.3.3
 */

/**
 * JWPPP_Dashboard_Api class
 *
 * @since 2.0.0
 */
class JWPPP_Dashboard_Api {

	/**
	 * The API public key
	 *
	 * @var string
	 */
	public $api_key;

	/**
	 * The API v1 secret key
	 *
	 * @var string
	 */
	public $api_secret;

	/**
	 * The API v1 secret key
	 *
	 * @var string
	 */
	public $api_secret_v2;

	/**
	 * The endpoint URL
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Check if key and secret are set
	 *
	 * @var object
	 */
	public $args_check;


	/**
	 * The constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->api_key       = get_option( 'jwppp-api-key' );
		$this->api_secret    = get_option( 'jwppp-api-secret' );
		$this->api_secret_v2 = get_option( 'jwppp-api-secret-v2' );
		$this->url           = 'https://api.jwplayer.com/v2/sites/' . $this->api_key . '/';
		$this->args_check();

	}


	/**
	 * Display an alert if the publisher still using API v1
	 *
	 * @return void
	 */
	public function api_v2_alert() {
		?>
		<div class="notice error my-acf-notice is-dismissible" >
			<p><?php esc_html_e( '<strong>JW Player for WordPress - Premium</strong> now uses the API v2 and requires to be configured.', 'jwppp' ); ?></p>
		</div>
		<?php
	}


	/**
	 * Chek if key and secret are set
	 *
	 * @return bool
	 */
	public function args_check() {

		if ( $this->api_key && $this->api_secret_v2 ) { // Temp.

			return true;

		} elseif ( is_dashboard_player() && $this->api_secret && ! $this->api_secret_v2 ) {

			if ( ! isset( $_POST['jwppp-api-secret-v2'], $_POST['hidden-nonce-options'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hidden-nonce-options'] ) ), 'jwppp-nonce-options' ) ) {

				add_action( 'admin_notices', array( $this, 'api_v2_alert' ) );

			}
		}

		return false;

	}


	/**
	 * The API call
	 *
	 * @param string $endpoint the endpoint.
	 *
	 * @return mixed
	 */
	public function call( $endpoint ) {

		global $wp_version;

        $url        = $this->url . $endpoint;
		$user_agent = 'WordPress/' . $wp_version . ' JWPlayerForWordPress/' . JWPPP_VERSION . ' PHP/' . phpversion();

		if ( function_exists( 'vip_safe_wp_remote_get' ) ) {

			$output = vip_safe_wp_remote_get(
				$url,
				'',
				3,
				3,
				20,
				array(
					'user-agent' => $user_agent,
					'headers'    => array(
						'Authorization' => 'Bearer ' . $this->api_secret_v2,
						'Content-Type'  => 'application/json',
					),
				)
			);

		} else {

			$output = wp_remote_get( // @codingStandardsIgnoreLine -- for non-VIP environments
				$url,
				array(
					'timeout'    => 3,
					'user-agent' => $user_agent,
					'headers'    => array(
						'Authorization' => 'Bearer ' . $this->api_secret_v2,
						'Content-Type'  => 'application/json',
					),
				)
			);

		}

		if ( is_array( $output ) ) {

			if ( 200 !== $output['response']['code'] ) {

				$body = json_decode( $output['body'] );

				if ( isset( $body->errors[0]->description ) ) {

					return array( 'error' => $body->errors[0]->description );

				}
			} else {

				return json_decode( $output['body'] );

			}
		}

	}


	/**
	 * Search videos and playlists
	 *
	 * @param string $term     the term to search.
	 * @param bool   $playlist search playlist with true.
	 *
	 * @return mixed
	 */
	public function search( $term, $playlist = false ) {

		if ( $playlist ) {

			$endpoint = sprintf( 'playlists/?page=1&page_length=15&q=title:"%1$s"+OR+id:"%1$s"&sort=created:dsc', $term );
			$key      = 'playlists';

		} else {

			$endpoint = sprintf( 'media/?page=1&page_length=15&q=title:"%1$s"+OR+id:"%1$s"&sort=created:dsc', $term );
			$key      = 'media';

		}

		$output = $this->call( $endpoint );

		if ( isset( $output->$key ) ) {

			return $output->$key;

		} else {

			return $output;

		}

	}


	/**
	 * Get a list of videos or a specific one if an id is provided
	 *
	 * @param string $media_id the media id.
	 *
	 * @return mixed
	 */
	public function get_videos( $media_id = null ) {

		$parameters = '?page=1&page_length=15&sort=created:dsc';

		if ( $media_id ) {

			$parameters = $media_id;

		}

		$output = $this->call( 'media/' . $parameters );

		if ( isset( $output->media ) ) {

			return $output->media;

		} else {

			return $output;

		}

	}


	/**
	 * Get a list of playlists or a specific one if an id is provided
	 *
	 * @param string $playlist_id the playlist id.
	 *
	 * @return mixed
	 */
	public function get_playlists( $playlist_id = null ) {

		$parameters = '?page=1&page_length=15&sort=created:dsc';

		if ( $playlist_id ) {
			$parameters = $playlist_id;
		}

		$output = $this->call( 'playlists/' . $parameters );

		if ( isset( $output->playlists ) ) {

			return $output->playlists;

		} else {

			return $output;

		}

	}


	/**
	 * Get the items in a playlist
	 *
	 * @param string $playlist_id   the playlist id.
	 * @param string $playlist_type the playlist type.
	 *
	 * @return mixed
	 */
	public function get_playlist_items( $playlist_id, $playlist_type ) {

		$data = $this->call( 'playlists/' . $playlist_id . '/' . $playlist_type . '_playlist' );

		if ( isset( $data->metadata->media_filter->include->values ) && is_array( $data->metadata->media_filter->include->values ) ) {

			return count( $data->metadata->media_filter->include->values );

		}

	}


	/**
	 * Get the players available
	 *
	 * @return array
	 */
	public function get_players() {

		$output = $this->call( 'players' );

		if ( isset( $output->players ) ) {

			return $output->players;

		} else {

			return $output;
		}

	}

}

new JWPPP_Dashboard_Api();

