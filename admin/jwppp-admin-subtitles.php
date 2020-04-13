<?php
/**
 * Subtitles options
 * @author ilGhera
 * @package jw-player-7-for-wp/admin
 * @since 2.0.0
 */
?>
<div name="jwppp-subtitles" id="jwppp-subtitles" class="jwppp-admin" style="display: none;">
	<?php

	settings_errors();

	echo '<form id="jwppp-subtitles" name="jwppp-subtitles" method="post" action="">';
	echo '<table class="form-table">';
	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Text Color', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="text" class="jwppp-color-field" name="jwppp-subtitles-color" disabled="disabled">';
	echo '<p class="description">' . esc_html( __( 'Color of subtitles text.', 'jwppp' ) ) . '</p>';
	go_premium();
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Font Size', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="number" class="jwppp-subtitles-font-size" min="8" max="30" step="1" name="jwppp-subtitles-font-size" disabled="disabled">';
	echo '<p class="description">' . esc_html( __( 'Font size of subtitles.', 'jwppp' ) ) . '</p>';
	go_premium();
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Font Family', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="text" class="jwppp-subtitles-font-family" name="jwppp-subtitles-font-family" disabled="disabled">';
	echo '<p class="description">' . esc_html( __( 'Font family of subtitles.', 'jwppp' ) ) . '</p>';
	go_premium();
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Font Opacity', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="number" class="jwppp-subtitles-opacity" min="0" max="100" step="10" name="jwppp-subtitles-opacity" disabled="disabled">';
	echo '<p class="description">' . esc_html( __( 'Opacity of subtitles.', 'jwppp' ) ) . '</p>';
	go_premium();
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Background Color', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="text" class="jwppp-color-field" name="jwppp-subtitles-back-color" disabled="disabled">';
	echo '<p class="description">' . esc_html( __( 'Background color of subtitles.', 'jwppp' ) ) . '</p>';
	go_premium();
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Background Opacity', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="number" class="jwppp-subtitles-back-opacity" min="0" max="100" step="10" name="jwppp-subtitles-back-opacity" disabled="disabled">';
	echo '<p class="description">' . esc_html( __( 'Opacity of subtitles.', 'jwppp' ) ) . '</p>';
	go_premium();
	echo '</td>';
	echo '</tr>';

	echo '</table>';

	echo '<input type="hidden" name="set" value="1" />';
	echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_attr( __( 'Save chages', 'jwppp' ) ) . '" disabled="disabled">';
	echo '</form>';
	?>
</div>
