<?php
/**
 * Subtitles options
 * @author ilGhera
 * @package jw-player-for-vip/admin
* @version 2.0.0
 */
?>
<div name="jwppp-subtitles" id="jwppp-subtitles" class="jwppp-admin" style="display: none;">
	<?php

	/*Color*/
	$sub_color = sanitize_text_field( get_option( 'jwppp-subtitles-color' ) );
	if ( isset( $_POST['jwppp-subtitles-color'], $_POST['hidden-nonce-subtitles'] ) && wp_verify_nonce( $_POST['hidden-nonce-subtitles'], 'jwppp-nonce-subtitles' ) ) {
		if ( jwppp_check_color( sanitize_text_field( wp_unslash( $_POST['jwppp-subtitles-color'] ) ) ) ) {
			$sub_color = sanitize_text_field( wp_unslash( $_POST['jwppp-subtitles-color'] ) );
			update_option( 'jwppp-subtitles-color', $sub_color );
		} else {
			add_settings_error( 'jw-player-for-wp', 'jwppp_color_error', __( 'Please, insert a valid color.', 'jwppp' ), 'error' );
		}
	}

	/*Font-size*/
	$sub_font_size = sanitize_text_field( get_option( 'jwppp-subtitles-font-size' ) );
	if ( isset( $_POST['jwppp-subtitles-font-size'] ) ) {
		$sub_font_size = sanitize_text_field( wp_unslash( $_POST['jwppp-subtitles-font-size'] ) );
		update_option( 'jwppp-subtitles-font-size', $sub_font_size );
	}

	/*Font-family*/
	$sub_font_family = sanitize_text_field( get_option( 'jwppp-subtitles-font-family' ) );
	if ( isset( $_POST['jwppp-subtitles-font-family'] ) ) {
		$sub_font_family = sanitize_text_field( wp_unslash( $_POST['jwppp-subtitles-font-family'] ) );
		update_option( 'jwppp-subtitles-font-family', $sub_font_family );
	}

	/*Opacity*/
	$sub_opacity = sanitize_text_field( get_option( 'jwppp-subtitles-opacity' ) );
	if ( isset( $_POST['jwppp-subtitles-opacity'] ) ) {
		$sub_opacity = sanitize_text_field( wp_unslash( $_POST['jwppp-subtitles-opacity'] ) );
		update_option( 'jwppp-subtitles-opacity', $sub_opacity );
	}

	/*Back-color*/
	$sub_back_color = sanitize_text_field( get_option( 'jwppp-subtitles-back-color' ) );
	if ( isset( $_POST['jwppp-subtitles-back-color'] ) ) {
		if ( jwppp_check_color( sanitize_text_field( wp_unslash( $_POST['jwppp-subtitles-back-color'] ) ) ) ) {
			$sub_back_color = sanitize_text_field( wp_unslash( $_POST['jwppp-subtitles-back-color'] ) );
			update_option( 'jwppp-subtitles-back-color', $sub_back_color );
		} else {
			add_settings_error( 'jw-player-for-wp', 'jwppp_color_error', __( 'Please, insert a valid background color.', 'jwppp' ), 'error' );
		}
	}

	/*Back-opacity*/
	$sub_back_opacity = sanitize_text_field( get_option( 'jwppp-subtitles-back-opacity' ) );
	if ( isset( $_POST['jwppp-subtitles-back-opacity'] ) ) {
		$sub_back_opacity = sanitize_text_field( wp_unslash( $_POST['jwppp-subtitles-back-opacity'] ) );
		update_option( 'jwppp-subtitles-back-opacity', $sub_back_opacity );
	}

	settings_errors();

	echo '<form id="jwppp-subtitles" name="jwppp-subtitles" method="post" action="">';
	echo '<table class="form-table">';
	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Text Color', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="text" class="jwppp-color-field" name="jwppp-subtitles-color" value="' . esc_attr( $sub_color ) . '">';
	echo '<p class="description">' . esc_html( __( 'Color of subtitles text.', 'jwppp' ) ) . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Font Size', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="number" class="jwppp-subtitles-font-size" min="8" max="30" step="1" name="jwppp-subtitles-font-size" value="' . esc_attr( $sub_font_size ) . '">';
	echo '<p class="description">' . esc_html( __( 'Font size of subtitles.', 'jwppp' ) ) . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Font Family', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="text" class="jwppp-subtitles-font-family" name="jwppp-subtitles-font-family" value="' . esc_attr( $sub_font_family ) . '">';
	echo '<p class="description">' . esc_html( __( 'Font family of subtitles.', 'jwppp' ) ) . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Font Opacity', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="number" class="jwppp-subtitles-opacity" min="0" max="100" step="10" name="jwppp-subtitles-opacity" value="' . esc_attr( $sub_opacity ) . '">';
	echo '<p class="description">' . esc_html( __( 'Opacity of subtitles.', 'jwppp' ) ) . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Background Color', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="text" class="jwppp-color-field" name="jwppp-subtitles-back-color" value="' . esc_attr( $sub_back_color ) . '">';
	echo '<p class="description">' . esc_html( __( 'Background color of subtitles.', 'jwppp' ) ) . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Background Opacity', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="number" class="jwppp-subtitles-back-opacity" min="0" max="100" step="10" name="jwppp-subtitles-back-opacity" value="' . esc_attr( $sub_back_opacity ) . '">';
	echo '<p class="description">' . esc_html( __( 'Opacity of subtitles.', 'jwppp' ) ) . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '</table>';

	/*Add nonce to the form*/
	wp_nonce_field( 'jwppp-nonce-subtitles', 'hidden-nonce-subtitles' );

	echo '<input type="hidden" name="set" value="1" />';
	echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_attr( __( 'Save chages', 'jwppp' ) ) . '">';
	echo '</form>';
	?>
</div>
