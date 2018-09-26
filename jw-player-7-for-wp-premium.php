<?php
/**
 * Plugin Name: JW Player for Wordpress - Premium
 * Plugin URI: https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/
 * Description:  The complete solution for using JW Player into Wordpress.
 * It works with the latest version of the famous video player and it gives you full control of all the options available.
 * Player customization, related videos, social sharing and advertising are just an example.
 * Author: ilGhera
 * Version: 1.6.0
 * Author URI: https://www.ilghera.com 
 * Requires at least: 4.0
 * Tested up to: 4.9
 * Text Domain: jwppp
 */


/*No direct access*/
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * Fired on the activation.
 */
function jwppp_premium_load() {

	if ( !function_exists( 'is_plugin_active' ) ) {
    	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
 	}

 	/*Deactivation of the free version*/
	if( is_plugin_active('jw-player-7-for-wp/jw-player-7-for-wp.php') || function_exists('jwppp_load') ) {
		deactivate_plugins('jw-player-7-for-wp/jw-player-7-for-wp.php');
	    remove_action( 'plugins_loaded', 'jwppp_load' );
	    wp_redirect(admin_url('plugins.php?plugin_status=all&paged=1&s'));

	}

	/*Deactivation of the specific plugin fo related videos*/
	if( is_plugin_active('related-videos-for-jw-player/related-videos-for-jwplayer.php') ) {
		deactivate_plugins('related-videos-for-jw-player/related-videos-for-jwplayer.php');
 	}

	/*Database update if required*/
	global $wpdb;

	if(get_option('jwppp-database-version') < '1.1.1') {

		$wpdb->query( //db call ok; no-cache ok
			"
			UPDATE $wpdb->postmeta
			SET meta_key = CASE meta_key
			WHEN '_jwppp-video-url' THEN '_jwppp-video-url-1'
			WHEN '_jwppp-video-image' THEN '_jwppp-video-image-1'
			WHEN '_jwppp-video-title' THEN '_jwppp-video-title-1'
			WHEN '_jwppp-video-description' THEN '_jwppp-video-description-1'
			WHEN '_jwppp-single-embed' THEN '_jwppp-single-embed-1'
			WHEN '_jwppp-add-chapters' THEN '_jwppp-add-chapters-1'
			WHEN '_jwppp-chapters-number' THEN '_jwppp-chapters-number-1'
			ELSE meta_key
			END
			"
		);

		$wpdb->query( //db call ok; no-cache ok
			"
			UPDATE $wpdb->postmeta SET
			meta_key = REPLACE(meta_key, '_jwppp-chapter-', '_jwppp-1-chapter-')
			"
		);
	}

	if(get_option('jwppp-database-version') < '1.4.0') {

		$results = $wpdb->get_results( //db call ok; no-cache ok
			"
			SELECT * FROM $wpdb->postmeta WHERE meta_key LIKE '%_jwppp-video-mobile-url-%' AND meta_value <> ''
			", 
			ARRAY_A
		);

		if($results) {
			foreach($results as $result) {
				$get_n = explode('_jwppp-video-mobile-url-', $result['meta_key']);
				add_post_meta($result['post_id'], '_jwppp-sources-number-' . $get_n[1], true);
				add_post_meta($result['post_id'], '_jwppp-' . $get_n[1] . '-source-1-url', $result['meta_value'] );
			}			
		}
		
		/*Database update*/
		update_option('jwppp-database-version', '1.4.0');
	
	}

	/*Internationalization*/
	load_plugin_textdomain('jwppp', false, basename( dirname( __FILE__ ) ) . '/languages' );

	/*Files required*/
	include( plugin_dir_path( __FILE__ ) . 'admin/jwppp-admin.php');
	include( plugin_dir_path( __FILE__ ) . 'includes/jwppp-functions.php');
	include( plugin_dir_path( __FILE__ ) . 'includes/jwppp-related-videos.php');
}
add_action( 'plugins_loaded', 'jwppp_premium_load', 1 );	


/*Update checher*/
require( plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php');
$jwpppUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://www.ilghera.com/wp-update-server-2/?action=get_metadata&slug=jw-player-7-for-wp-premium',
    __FILE__,
    'jw-player-7-for-wp-premium'
);

$jwpppUpdateChecker->addQueryArgFilter('jwppp_secure_update_check');
function jwppp_secure_update_check($queryArgs) {
    $key = base64_encode( get_option('jwppp-premium-key') );

    if($key) {
        $queryArgs['premium-key'] = $key;
    }
    return $queryArgs;
}