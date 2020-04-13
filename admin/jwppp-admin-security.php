<?php
/**
 * Secure video URLs and player embeds
 * @author ilGhera
 * @package jw-player-7-for-wp/admin
 * @since 2.0.2
 */
?>
<div name="jwppp-security" id="jwppp-security" class="jwppp-admin" style="display: none;">
	<?php

	/*Define the allowed tags for wp_kses*/
	$allowed_tags = array(
		'u' => [],
		'strong' => [],
		'a' => [
			'href'   => [],
			'target' => [],
		],
		'br' => [],
	);

	echo '<form id="jwppp-security" name="jwppp-security" method="post" action="">';
	echo '<table class="form-table">';

	/*Secure video URLs*/
	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Secure Video URLs', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-secure-video-urls" name="jwppp-secure-video-urls" value="1" disabled="disabled" />';
	echo '<p class="description">' . wp_kses( __( 'Must match setting on JW Platform Account > Properties > (Choose Property) settings', 'jwppp' ), $allowed_tags ) . '</p>';
	go_premium();
	echo '<td>';
	echo '</tr>';

	/*Secure player embeds*/
	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Secure Player Embeds', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-secure-player-embeds" name="jwppp-secure-player-embeds" value="1" disabled="disabled" />';
	echo '<p class="description">' . wp_kses( __( 'Must match setting on JW Platform Account > Properties > (Choose Property) settings', 'jwppp' ), $allowed_tags ) . '</p>';
	go_premium();
	echo '<td>';
	echo '</tr>';

	/*Secure timeout*/
	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Set timeout', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="number" id="jwppp-secure-timeout" name="jwppp-secure-timeout" step="5" min="5" value="60" disabled="disabled" />';
	echo '<p class="description">' . wp_kses( __( 'Timeout in minutes', 'jwppp' ), $allowed_tags ) . '</p>';
	go_premium();
	echo '<td>';
	echo '</tr>';

	echo '</table>';

	echo '<input type="hidden" name="security-sent" value="1" />';
	echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_attr( __( 'Save options', 'jwppp' ) ) . '" disabled="disabled" />';
	echo '</form>';
	?>
</div>
