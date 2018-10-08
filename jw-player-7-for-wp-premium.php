<?php
/**
 * Plugin Name: JW Player for Wordpress - Premium
 * Plugin URI: https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/
 * Description:  The complete solution for using JW Player into Wordpress.
 * It works with the latest version of the famous video player and it gives you full control of all the options available.
 * Player customization, social sharing and advertising are just an example.
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

	/*Database update if required*/
	global $wpdb;


	/*Add database version*/
	update_option('jwppp-database-version', '1.4.0');


	/*Internationalization*/
	load_plugin_textdomain('jwppp', false, basename( dirname( __FILE__ ) ) . '/languages' );

	/*Files required*/
	include( plugin_dir_path( __FILE__ ) . 'admin/jwppp-admin.php');
	include( plugin_dir_path( __FILE__ ) . 'includes/jwppp-functions.php');
}
add_action( 'plugins_loaded', 'jwppp_premium_load', 1 );