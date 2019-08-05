<?php
/**
 * Plugin Name: JW Player for Wordpress - VIP
 * Plugin URI: https://wpvip.com/plugins/jwplayer/
 * Description:  The complete solution for using JW Player into Wordpress.
 * It works with the latest version of the famous video player and it gives you full control of all the options available.
 * Player customization, social sharing and advertising are just an example.
 * Author: JW Player
 * Version: 2.0.1
 * Author URI: https://www.jwplayer.com/
 * Requires at least: 4.0
 * Tested up to: 5
 * Text Domain: jwppp
 */


/*No direct access*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*Define the plugin version*/
define( 'JWPPP_VERSION', '2.0.1' );

/**
 * Fired on the activation.
 */
function jwppp_vip_load() {

	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}

	/*Add database version*/
	update_option( 'jwppp-database-version', JWPPP_VERSION );

	/*Internationalization*/
	load_plugin_textdomain( 'jwppp', false, basename( dirname( __FILE__ ) ) . '/languages' );

	/*Constants definition*/
	define( 'JWPPP_DIR', plugin_dir_path( __FILE__ ) );
	define( 'JWPPP_URI', plugin_dir_url( __FILE__ ) );
	define( 'JWPPP_INCLUDES', JWPPP_DIR . 'includes/' );
	define( 'JWPPP_ADMIN', JWPPP_DIR . 'admin/' );

	/*Files required*/
	include( JWPPP_ADMIN . 'jwppp-admin.php' );
	include( JWPPP_INCLUDES . 'jwppp-functions.php' );
	include( JWPPP_INCLUDES . 'jwppp-video-chapters.php' );
	include( JWPPP_DIR . 'fb/jwppp-fb-player.php' );
	include( JWPPP_DIR . 'jw-widget/jwppp-carousel-config.php' );

}
add_action( 'setup_theme', 'jwppp_vip_load' );
