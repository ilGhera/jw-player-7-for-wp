<?php
/**
 * Skin options 7
 * @author ilGhera
 * @package jw-player-for-vip/admin/skin
* @version 2.0.0
 */

$jwppp_skin = sanitize_text_field( get_option( 'jwppp-skin' ) );
if ( isset( $_POST['jwppp-skin'], $_POST['hidden-nonce-skin'] ) && wp_verify_nonce( $_POST['hidden-nonce-skin'], 'jwppp-nonce-skin' ) ) {
	$jwppp_skin = sanitize_text_field( wp_unslash( $_POST['jwppp-skin'] ) );
	update_option( 'jwppp-skin', $jwppp_skin );
}

$jwppp_custom_skin_url = sanitize_text_field( get_option( 'jwppp-custom-skin-url' ) );
if ( isset( $_POST['custom-skin-url'] ) ) {
	$jwppp_custom_skin_url = sanitize_text_field( wp_unslash( $_POST['custom-skin-url'] ) );
	update_option( 'jwppp-custom-skin-url', $jwppp_custom_skin_url );
}

$jwppp_custom_skin_name = sanitize_text_field( get_option( 'jwppp-custom-skin-name' ) );
if ( isset( $_POST['custom-skin-name'] ) ) {
	$jwppp_custom_skin_name = sanitize_text_field( wp_unslash( $_POST['custom-skin-name'] ) );
	update_option( 'jwppp-custom-skin-name', $jwppp_custom_skin_name );
}

$jwppp_skin_color_active = sanitize_text_field( get_option( 'jwppp-skin-color-active' ) );
if ( isset( $_POST['jwppp-skin-color-active'] ) ) {
	$jwppp_skin_color_active = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-active'] ) );
	update_option( 'jwppp-skin-color-active', $jwppp_skin_color_active );
}

$jwppp_skin_color_inactive = sanitize_text_field( get_option( 'jwppp-skin-color-inactive' ) );
if ( isset( $_POST['jwppp-skin-color-inactive'] ) ) {
	$jwppp_skin_color_inactive = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-inactive'] ) );
	update_option( 'jwppp-skin-color-inactive', $jwppp_skin_color_inactive );
}

$jwppp_skin_color_background = sanitize_text_field( get_option( 'jwppp-skin-color-background' ) );
if ( isset( $_POST['jwppp-skin-color-background'] ) ) {
	$jwppp_skin_color_background = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-background'] ) );
	update_option( 'jwppp-skin-color-background', $jwppp_skin_color_background );
}
