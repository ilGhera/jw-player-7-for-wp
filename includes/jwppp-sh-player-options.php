<?php
/**
 * Self hosted player options
 * @author ilGhera
 * @package jw-player-for-vip/includes
 * @version 1.6.0
 * @param  string $ar     aspect ratio
 * @param  string $width  the player width
 * @param  string $height the player height
 * @return string         the block code
 */
function jwppp_sh_player_option( $ar = '', $width = '', $height = '' ) {

	/*Get the options*/
	$jwppp_method_dimensions = sanitize_text_field( get_option( 'jwppp-method-dimensions' ) );
	$jwppp_player_width = sanitize_text_field( get_option( 'jwppp-player-width' ) );
	$jwppp_player_height = sanitize_text_field( get_option( 'jwppp-player-height' ) );
	$jwppp_responsive_width = sanitize_text_field( get_option( 'jwppp-responsive-width' ) );
	$jwppp_aspectratio = sanitize_text_field( get_option( 'jwppp-aspectratio' ) );

	$player_version = sanitize_text_field( get_option( 'jwppp-player-version' ) );

	/*Skin customization - jwp7*/
	$jwppp_skin = sanitize_text_field( get_option( 'jwppp-skin' ) );

	/*Is it a custom skin?*/
	if ( 'custom-skin' === $jwppp_skin ) {
		$jwppp_skin_name = sanitize_text_field( get_option( 'jwppp-custom-skin-name' ) );
	} else {
		$jwppp_skin_name = $jwppp_skin;
	}

	$jwppp_skin_color_active = sanitize_text_field( get_option( 'jwppp-skin-color-active' ) );
	$jwppp_skin_color_inactive = sanitize_text_field( get_option( 'jwppp-skin-color-inactive' ) );
	$jwppp_skin_color_background = sanitize_text_field( get_option( 'jwppp-skin-color-background' ) );

	/*Skin customization - jwp8*/
	$jwppp_skin_color_controlbar_text = sanitize_text_field( get_option( 'jwppp-skin-color-controlbar-text' ) );
	$jwppp_skin_color_controlbar_icons = sanitize_text_field( get_option( 'jwppp-skin-color-controlbar-icons' ) );
	$jwppp_skin_color_controlbar_active_icons = sanitize_text_field( get_option( 'jwppp-skin-color-controlbar-active-icons' ) );
	$jwppp_skin_color_controlbar_background = sanitize_text_field( get_option( 'jwppp-skin-color-controlbar-background' ) );
	$jwppp_skin_color_timeslider_progress = sanitize_text_field( get_option( 'jwppp-skin-color-timeslider-progress' ) );
	$jwppp_skin_color_timeslider_rail = sanitize_text_field( get_option( 'jwppp-skin-color-timeslider-rail' ) );
	$jwppp_skin_color_menus_text = sanitize_text_field( get_option( 'jwppp-skin-color-menus-text' ) );
	$jwppp_skin_color_menus_active_text = sanitize_text_field( get_option( 'jwppp-skin-color-menus-active-text' ) );
	$jwppp_skin_color_menus_background = sanitize_text_field( get_option( 'jwppp-skin-color-menus-background' ) );
	$jwppp_skin_color_tooltips_text = sanitize_text_field( get_option( 'jwppp-skin-color-tooltips-text' ) );
	$jwppp_skin_color_tooltips_background = sanitize_text_field( get_option( 'jwppp-skin-color-tooltips-background' ) );

	$jwppp_logo = sanitize_text_field( get_option( 'jwppp-logo' ) );
	$jwppp_logo_vertical = sanitize_text_field( get_option( 'jwppp-logo-vertical' ) );
	$jwppp_logo_horizontal = sanitize_text_field( get_option( 'jwppp-logo-horizontal' ) );
	$jwppp_logo_link = sanitize_text_field( get_option( 'jwppp-logo-link' ) );
	$active_share = sanitize_text_field( get_option( 'jwppp-active-share' ) );
	$jwppp_embed_video = sanitize_text_field( get_option( 'jwppp-embed-video' ) );
	$jwppp_next_up = sanitize_text_field( get_option( 'jwppp-next-up' ) );
	$jwppp_playlist_tooltip = sanitize_text_field( get_option( 'jwppp-playlist-tooltip' ) );

	/*New subtitles options*/
	$jwppp_sub_color = sanitize_text_field( get_option( 'jwppp-subtitles-color' ) );
	$jwppp_sub_font_size = sanitize_text_field( get_option( 'jwppp-subtitles-font-size' ) );
	$jwppp_sub_font_family = sanitize_text_field( get_option( 'jwppp-subtitles-font-family' ) );
	$jwppp_sub_opacity = sanitize_text_field( get_option( 'jwppp-subtitles-opacity' ) );
	$jwppp_sub_back_color = sanitize_text_field( get_option( 'jwppp-subtitles-back-color' ) );
	$jwppp_sub_back_opacity = sanitize_text_field( get_option( 'jwppp-subtitles-back-opacity' ) );

	/*Player code*/
	// $output = null;

	/*Player dimensions*/
	if ( $width && $height ) {

		echo "width: '" . esc_html( $width ) . "',\n";
		echo "height: '" . esc_html( $height ) . "',\n";

	} else {

		if ( 'fixed' === $jwppp_method_dimensions ) {
			echo "width: '";
			echo ( null !== $jwppp_player_width ) ? esc_html( $jwppp_player_width ) : '640';
			echo "',\n";
			echo "height: '";
			echo ( null !== $jwppp_player_height ) ? esc_html( $jwppp_player_height ) : '360';
			echo "',\n";

		} else {
			echo "width: '";
			echo ( null !== $jwppp_responsive_width ) ? esc_html( $jwppp_responsive_width ) . '%' : '100%';
			echo "',\n";
			echo "aspectratio: '";
			if ( $ar ) {
				echo esc_html( $ar );
			} elseif ( $jwppp_aspectratio ) {
				echo esc_html( $jwppp_aspectratio );
			} else {
				echo '16:9';
			}
			echo "',\n";
		}
	}

	/*Skin*/
	echo "skin: {\n";
	if ( '7' === $player_version ) {

		echo 'none' !== $jwppp_skin_name ? "name: '" . esc_html( $jwppp_skin_name ) . "',\n" : '';
		echo $jwppp_skin_color_active ? "active: '" . esc_html( $jwppp_skin_color_active ) . "',\n" : '';
		echo $jwppp_skin_color_inactive ? "inactive: '" . esc_html( $jwppp_skin_color_inactive ) . "',\n" : '';
		echo $jwppp_skin_color_background ? "background: '" . esc_html( $jwppp_skin_color_background ) . "',\n" : '';

	} elseif ( '8' === $player_version ) {

		echo "controlbar: {\n";
			echo $jwppp_skin_color_controlbar_text ? "text: '" . esc_html( $jwppp_skin_color_controlbar_text ) . "',\n" : '';
			echo $jwppp_skin_color_controlbar_icons ? "icons: '" . esc_html( $jwppp_skin_color_controlbar_icons ) . "',\n" : '';
			echo $jwppp_skin_color_controlbar_active_icons ? "iconsActive: '" . esc_html( $jwppp_skin_color_controlbar_active_icons ) . "',\n" : '';
			echo $jwppp_skin_color_controlbar_background ? "background: '" . esc_html( $jwppp_skin_color_controlbar_background ) . "',\n" : '';
		echo "},\n";

		echo "timeslider: {\n";
			echo $jwppp_skin_color_timeslider_progress ? "progress: '" . esc_html( $jwppp_skin_color_timeslider_progress ) . "',\n" : '';
			echo $jwppp_skin_color_timeslider_rail ? "rail: '" . esc_html( $jwppp_skin_color_timeslider_rail ) . "',\n" : '';
		echo "},\n";

		echo "menus: {\n";
			echo $jwppp_skin_color_menus_text ? "text: '" . esc_html( $jwppp_skin_color_menus_text ) . "',\n" : '';
			echo $jwppp_skin_color_menus_active_text ? "textActive: '" . esc_html( $jwppp_skin_color_menus_active_text ) . "',\n" : '';
			echo $jwppp_skin_color_menus_background ? "background: '" . esc_html( $jwppp_skin_color_menus_background ) . "',\n" : '';
		echo "},\n";

		echo "tooltips: {\n";
			echo $jwppp_skin_color_tooltips_text ? "text: '" . esc_html( $jwppp_skin_color_tooltips_text ) . "',\n" : '';
			echo $jwppp_skin_color_tooltips_background ? "background: '" . esc_html( $jwppp_skin_color_tooltips_background ) . "',\n" : '';
		echo "}\n";

	}
	echo "},\n";

	/*Logo*/
	if ( null !== $jwppp_logo ) {
		echo "logo: {\n";
		echo "file: '" . esc_url( $jwppp_logo ) . "',\n";
		echo "position: '" . esc_html( $jwppp_logo_vertical ) . '-' . esc_html( $jwppp_logo_horizontal ) . "',\n";
		if ( null !== $jwppp_logo_link ) {
			echo "link: '" . esc_html( $jwppp_logo_link ) . "'\n";
		}
		echo "},\n";
	}

	/*Subtitles style*/
	if ( jwppp_caption_style() ) {
		echo "captions: {\n";
		echo $jwppp_sub_color ? "color: '" . esc_html( ( $jwppp_sub_color ) ) . "',\n" : '';
		echo $jwppp_sub_font_size ? "fontSize: '" . esc_html( ( $jwppp_sub_font_size ) ) . "',\n" : '';
		echo $jwppp_sub_font_family ? "fontFamily: '" . esc_html( ( $jwppp_sub_font_family ) ) . "',\n" : '';
		echo $jwppp_sub_opacity ? "fontOpacity: '" . esc_html( ( $jwppp_sub_opacity ) ) . "',\n" : '';
		echo $jwppp_sub_back_color ? "backgroundColor: '" . esc_html( ( $jwppp_sub_back_color ) ) . "',\n" : '';
		echo $jwppp_sub_back_opacity ? "backgroundOpacity: '" . esc_html( ( $jwppp_sub_back_opacity ) ) . "',\n" : '';
		echo "},\n";
	}

	/*Localization*/
	echo "localization: {\n";
	if ( $jwppp_next_up ) {
		echo "nextUp: '" . esc_html( $jwppp_next_up ) . "',\n";
	}
	if ( $jwppp_playlist_tooltip ) {
		echo "playlist: '" . esc_html( $jwppp_playlist_tooltip ) . "',\n";
	}
	echo "},\n";

	// return $output;

}
