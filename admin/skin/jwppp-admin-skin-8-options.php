<?php
/**
 * Skin options 8
 * @author ilGhera
 * @package jw-player-for-vip/admin/skin
* @version 2.0.0
 */

$jwppp_skin_color_controlbar_text = sanitize_text_field( get_option( 'jwppp-skin-color-controlbar-text' ) );
if ( isset( $_POST['jwppp-skin-color-controlbar-text'], $_POST['hidden-nonce-skin'] ) && wp_verify_nonce( $_POST['hidden-nonce-skin'], 'jwppp-nonce-skin' ) ) {
	$jwppp_skin_color_controlbar_text = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-controlbar-text'] ) );
	update_option( 'jwppp-skin-color-controlbar-text', $jwppp_skin_color_controlbar_text );
}

$jwppp_skin_color_controlbar_icons = sanitize_text_field( get_option( 'jwppp-skin-color-controlbar-icons' ) );
if ( isset( $_POST['jwppp-skin-color-controlbar-icons'] ) ) {
	$jwppp_skin_color_controlbar_icons = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-controlbar-icons'] ) );
	update_option( 'jwppp-skin-color-controlbar-icons', $jwppp_skin_color_controlbar_icons );
}

$jwppp_skin_color_controlbar_active_icons = sanitize_text_field( get_option( 'jwppp-skin-color-controlbar-active-icons' ) );
if ( isset( $_POST['jwppp-skin-color-controlbar-active-icons'] ) ) {
	$jwppp_skin_color_controlbar_active_icons = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-controlbar-active-icons'] ) );
	update_option( 'jwppp-skin-color-controlbar-active-icons', $jwppp_skin_color_controlbar_active_icons );
}

$jwppp_skin_color_controlbar_background = sanitize_text_field( get_option( 'jwppp-skin-color-controlbar-background' ) );
if ( isset( $_POST['jwppp-skin-color-controlbar-background'] ) ) {
	$jwppp_skin_color_controlbar_background = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-controlbar-background'] ) );
	update_option( 'jwppp-skin-color-controlbar-background', $jwppp_skin_color_controlbar_background );
}

$jwppp_skin_color_timeslider_progress = sanitize_text_field( get_option( 'jwppp-skin-color-timeslider-progress' ) );
if ( isset( $_POST['jwppp-skin-color-timeslider-progress'] ) ) {
	$jwppp_skin_color_timeslider_progress = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-timeslider-progress'] ) );
	update_option( 'jwppp-skin-color-timeslider-progress', $jwppp_skin_color_timeslider_progress );
}

$jwppp_skin_color_timeslider_rail = sanitize_text_field( get_option( 'jwppp-skin-color-timeslider-rail' ) );
if ( isset( $_POST['jwppp-skin-color-timeslider-rail'] ) ) {
	$jwppp_skin_color_timeslider_rail = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-timeslider-rail'] ) );
	update_option( 'jwppp-skin-color-timeslider-rail', $jwppp_skin_color_timeslider_rail );
}

$jwppp_skin_color_menus_text = sanitize_text_field( get_option( 'jwppp-skin-color-menus-text' ) );
if ( isset( $_POST['jwppp-skin-color-menus-text'] ) ) {
	$jwppp_skin_color_menus_text = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-menus-text'] ) );
	update_option( 'jwppp-skin-color-menus-text', $jwppp_skin_color_menus_text );
}

$jwppp_skin_color_menus_active_text = sanitize_text_field( get_option( 'jwppp-skin-color-menus-active-text' ) );
if ( isset( $_POST['jwppp-skin-color-menus-active-text'] ) ) {
	$jwppp_skin_color_menus_active_text = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-menus-active-text'] ) );
	update_option( 'jwppp-skin-color-menus-active-text', $jwppp_skin_color_menus_active_text );
}

$jwppp_skin_color_menus_background = sanitize_text_field( get_option( 'jwppp-skin-color-menus-background' ) );
if ( isset( $_POST['jwppp-skin-color-menus-background'] ) ) {
	$jwppp_skin_color_menus_background = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-menus-background'] ) );
	update_option( 'jwppp-skin-color-menus-background', $jwppp_skin_color_menus_background );
}

$jwppp_skin_color_tooltips_text = sanitize_text_field( get_option( 'jwppp-skin-color-tooltips-text' ) );
if ( isset( $_POST['jwppp-skin-color-tooltips-text'] ) ) {
	$jwppp_skin_color_tooltips_text = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-tooltips-text'] ) );
	update_option( 'jwppp-skin-color-tooltips-text', $jwppp_skin_color_tooltips_text );
}

$jwppp_skin_color_tooltips_background = sanitize_text_field( get_option( 'jwppp-skin-color-tooltips-background' ) );
if ( isset( $_POST['jwppp-skin-color-tooltips-background'] ) ) {
	$jwppp_skin_color_tooltips_background = sanitize_text_field( wp_unslash( $_POST['jwppp-skin-color-tooltips-background'] ) );
	update_option( 'jwppp-skin-color-tooltips-background', $jwppp_skin_color_tooltips_background );
}
