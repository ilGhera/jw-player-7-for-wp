<?php
/**
 * Share options
 * @author ilGhera
 * @package jw-player-for-vip/admin
* @version 2.0.0
 */
?>
<div name="jwppp-social" id="jwppp-social" class="jwppp-admin" style="display: none;">
	<?php

	/*Active share?*/
	$active_share = sanitize_text_field( get_option( 'jwppp-active-share' ) );
	if ( isset( $_POST['share-sent'], $_POST['hidden-nonce-share'] ) && wp_verify_nonce( $_POST['hidden-nonce-share'], 'jwppp-nonce-share' ) ) {
		$active_share = isset( $_POST['jwppp-active-share'] ) ? sanitize_text_field( wp_unslash( $_POST['jwppp-active-share'] ) ) : 0;
		update_option( 'jwppp-active-share', $active_share );
	}
	/*Heading*/
	$share_heading = get_option( 'jwppp-share-heading' );
	if ( isset( $_POST['share-heading'] ) ) {
		$share_heading = sanitize_text_field( wp_unslash( $_POST['share-heading'] ) );
		update_option( 'jwppp-share-heading', $share_heading );
	}
	/*Embed?*/
	$jwppp_embed_video = sanitize_text_field( get_option( 'jwppp-embed-video' ) );
	if ( isset( $_POST['share-sent'] ) ) {
		$jwppp_embed_video = isset( $_POST['jwppp-embed-video'] ) ? sanitize_text_field( wp_unslash( $_POST['jwppp-embed-video'] ) ) : 0;
		update_option( 'jwppp-embed-video', $jwppp_embed_video );
	}

	/*Define the allowed tags for wp_kses*/
	$allowed_tags = array(
		'u' => [],
		'strong' => [],
		'a' => [
			'href'   => [],
			'target' => [],
		],
	);

	echo '<form id="jwppp-sharing" name="jwppp-sharing" method="post" action="">';
	echo '<table class="form-table">';

	/*Active share?*/
	echo '<tr>';
	echo '<th scope="row">' . esc_html( __( 'Activate Sharing Option', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-active-share" name="jwppp-active-share" value="1"';
	echo ( '1' === $active_share ) ? ' checked="checked"' : '';
	echo ' />';
	echo '<p class="description">' . esc_html( __( 'Activate sharing option -- can be changed on single video.', 'jwppp' ) ) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Heading*/
	echo '<tr class="share-options">';
	echo '<th scope="row">' . esc_html( __( 'Heading', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" id="share-heading" name="share-heading" placeholder="' . esc_attr( __( 'Share Video', 'jwppp' ) ) . '" value="' . esc_attr( $share_heading ) . '" />';
	echo '<p class="description">' . wp_kses( __( 'Add a custom heading, default is <strong>Share Video</strong>', 'jwppp' ), $allowed_tags ) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Embed?*/
	echo '<tr class="share-options">';
	echo '<th scope="row">' . esc_html( __( 'Active embed option', 'jwppp' ) ) . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-embed-video" name="jwppp-embed-video" value="1"';
	echo ( '1' === $jwppp_embed_video ) ? ' checked="checked"' : '';
	echo ' />';
	echo '<p class="description">' . wp_kses( __( 'Active <strong>embed video</strong> as default option. You\'ll be able to change it on single video.', 'jwppp' ), $allowed_tags ) . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '</table>';

	/*Add nonce to the form*/
	wp_nonce_field( 'jwppp-nonce-share', 'hidden-nonce-share' );

	echo '<input type="hidden" name="share-sent" value="1" />';
	echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_attr( __( 'Save options', 'jwppp' ) ) . '" />';
	echo '</form>';
	?>
</div>
