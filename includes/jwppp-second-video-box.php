<?php
/**
 * Second video box
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @version 2.0.0
 */

$dashboard_player = is_dashboard_player();
$player_position = get_option( 'jwppp-position' );

echo '<!-- jwppp video number ' . esc_html( $number ) . ' -->';
echo '<table class="widefat jwppp-' . esc_attr( $number ) . '" style="margin: 0.4rem 0; width: 100%;">';
	echo '<tbody class="ui-sortable">';
		echo '<tr class="row">';
			echo '<td class="order" style="width: 2.5%;">' . esc_html( $number ) . '</td>';
			echo '<td class="jwppp-input-wrap" style="width: 95%; padding-bottom: 1rem; position: relative;">';

				/*Nonce*/
				wp_nonce_field( 'jwppp-meta-box-nonce-' . $number, 'hidden-meta-box-nonce-' . $number );

				/*A cloud palyer allows to get contents from the JW Dasboard */
				if ( $dashboard_player ) {

					echo '<ul class="jwppp-video-toggles ' . esc_attr( $number ) . '">';
						echo '<li>' . esc_html( __( 'Choose', 'jwppp' ) ) . '</li>';
						echo '<li class="active">' . esc_html( __( 'Add url', 'jwppp' ) ) . '</li>';
						echo '<div class="clear"></div>';
					echo '</ul>';

					/*Select media content*/
					echo '<div class="jwppp-toggle-content ' . esc_attr( $number ) . ' choose">';
						echo '<p>';

							echo '<input type="text" autocomplete="off" id="_jwppp-video-title-' . esc_attr( $number ) . '" class="jwppp-search-content choose" data-number="' . esc_attr( $number ) . '" placeholder="' . esc_attr( __( 'Select video/playlist or search by ID', 'jwppp' ) ) . '" style="margin-right:1rem;" disabled="disabled"><br>';
						echo '</p>';
					echo '</div>';

				}

				/*Input url, both with cloud and self-hosted players*/
				echo $dashboard_player ? '<div class="jwppp-toggle-content active">' : '';

				if ( ! $dashboard_player ) {
					echo '<label for="_jwppp-video-url-' . esc_attr( $number ) . '">';
						echo '<strong>' . esc_html( __( 'Media URL', 'jwppp' ) ) . '</strong>';
						echo '<a class="question-mark" href="https://www.ilghera.com/support/topic/media-formats-supported/" title="More informations" target="_blank"><img class="question-mark" src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/question-mark.png" /></a></th>';
					echo '</label> ';
				}

				echo '<p>';
					echo '<input type="text" id="_jwppp-video-url-' . esc_attr( $number ) . '" class="jwppp-url" name="_jwppp-video-url-' . esc_attr( $number ) . '" placeholder="' . esc_attr( __( 'Media URL', 'jwppp' ) ) . '" size="60" disabled="disabled" />';
					echo '<input type="text" name="_jwppp-' . esc_attr( $number ) . '-main-source-label" id ="_jwppp-' . esc_attr( $number ) . '-main-source-label" class="source-label-' . esc_attr( $number ) . '" style="margin-right:1rem; display: none;';
					echo '" placeholder="' . esc_attr( __( 'Label (HD, 720p, 360p)', 'jwppp' ) ) . '" size="30" disabled="disabled" />';

				echo '</p>';

				echo $dashboard_player ? '</div>' : '';

				/*Display shortcode*/
				if ( get_option( 'jwppp-position' ) === 'custom' ) {
					echo '<code style="display:inline-block;margin:0.1rem 0.5rem 0 0.2rem;color:#888;">[jwp-video n="' . esc_attr( $number ) . '"]</code>';
				}

				echo '<a class="button">' . esc_html( __( 'Show options', 'jwppp' ) ) . '</a>';

				echo '</div>';

				go_premium( __( 'Upgrade for more videos and playlists', 'jwppp' ) );

			echo '</td>';

			if ( $number < 2 ) {
				echo '<td class="add-video" style="width: 2.5%;"><a class="jwppp-add"><img src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/add-video.png" /></a></td>';
			} else {
				echo '<td class="remove-video" style="width: 2.5%;"><a class="jwppp-remove" data-numb="' . esc_attr( $number ) . '"><img src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/remove-video.png" /></a></td>';
			}

		echo '</tr>';
	echo '</tbody>';
echo '</table>';
