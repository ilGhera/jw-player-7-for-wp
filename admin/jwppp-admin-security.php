<?php
/**
 * Secure video URLs and player embeds
 * @author ilGhera
 * @package jw-player-7-for-wp/admin
 * @since 2.2.1
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

    /*API v1 Secret*/
	echo '<form id="jwppp-security" class="jwppp-settings-form" name="jwppp-security" method="post" action="">';
	echo '<table class="form-table">';

    echo '<tr>';
    echo '<th scope="row">' . esc_html( __( 'API v1 Credentials', 'jwppp' ) );
    echo '<a href="https://developer.jwplayer.com/jwplayer/docs/archived-authentication" title="Get your API v1 Secret" target="_blank"><img class="question-mark" src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/question-mark.png" /></a></th>';
    echo '<td>';
    echo '<input type="text" class="regular-text" id="jwppp-api-secret" name="jwppp-api-secret" placeholder="' . esc_attr( __( 'Add your API Secret', 'jwppp' ) ) . ' disabled="disabled" />';
    echo '<p class="description">' . esc_html( __( 'API v1 Secret', 'jwppp' ) ) . '</p>';
	echo '<td>';
	echo '</tr>';

	/*Secure video URLs*/
	echo '<tr class="jwppp-security-option">';
	echo '<th scope="row">' . esc_html( __( 'Secure Video URLs', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-secure-video-urls" name="jwppp-secure-video-urls" value="1" disabled="disabled" />';
	echo '<p class="description">' . wp_kses( __( 'Must match setting on JW Platform Account > Properties > (Choose Property) settings', 'jwppp' ), $allowed_tags ) . '</p>';
	go_premium();
	echo '<td>';
	echo '</tr>';

	/*Secure player embeds*/
	echo '<tr class="jwppp-security-option">';
	echo '<th scope="row">' . esc_html( __( 'Secure Player Embeds', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-secure-player-embeds" name="jwppp-secure-player-embeds" value="1" disabled="disabled" />';
	echo '<p class="description">' . wp_kses( __( 'Must match setting on JW Platform Account > Properties > (Choose Property) settings', 'jwppp' ), $allowed_tags ) . '</p>';
	go_premium();
	echo '<td>';
	echo '</tr>';

	/*Secure timeout*/
	echo '<tr class="jwppp-security-option">';
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
