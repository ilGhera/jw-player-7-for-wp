<?php
/**
 * Plugin Name: JW Player 7 for Wordpress
 * Plugin URI: http://www.ilghera.com/product/jw-player-7-for-wordpress/
 * Description:  JW Player 7 for Wordpress gives you all what you need to publish videos on your Wordpress posts and pages, with the new JW7. Change skin, position and dimensions of your player. Allow users share and embed your contents.
 * Do you want more? Please check out the premium version.
 * Author: ilGhera
 * Version: 1.3.3
 * Author URI: http://ilghera.com 
 * Requires at least: 4.0
 * Tested up to: 4.6.1
 */


//HEY, WHAT ARE UOU DOING?
if ( !defined( 'ABSPATH' ) ) exit;

add_action( 'plugins_loaded', 'jwppp_load', 100 );	

function jwppp_load() {

	//DATABASE UPDATE
	if(get_option('jwppp-database-version') < '1.1.1') {
		global $wpdb;
		$wpdb->query(
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

		$wpdb->query(
			"
			UPDATE $wpdb->postmeta SET
			meta_key = REPLACE(meta_key, '_jwppp-chapter-', '_jwppp-1-chapter-')
			"
		);

		//UPDATE DATABASE VERSION
		update_option('jwppp-database-version', '1.1.1');
	}
	
	//INTERNATIONALIZATION
	load_plugin_textdomain('jwppp', false, basename( dirname( __FILE__ ) ) . '/languages' );

	//FILES REQUIRED
	include( plugin_dir_path( __FILE__ ) . 'includes/jwppp-admin.php');
	include( plugin_dir_path( __FILE__ ) . 'includes/jwppp-functions.php');
}