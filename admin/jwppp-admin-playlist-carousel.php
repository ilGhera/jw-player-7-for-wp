<?php
/**
 * Playlist carousel options
 * @author ilGhera
 * @package jw-player-7-for-wp/admin
 * @since 2.0.0
 */
?>
<div name="jwppp-playlist-carousel" id="jwppp-playlist-carousel" class="jwppp-admin" style="display: none;">
	<?php
	echo '<form id="jwppp-playlist-carousel" name="jwppp-playlist-carousel" method="post" action="">';
		echo '<table class="form-table">';
			echo '<tr>';
				echo '<th scope="row">' . esc_html( __( 'Title', 'jwppp' ) ) . '</th>';
				echo '<td>';
					echo '<input type="text" class="regular-text" id="jwppp-playlist-carousel-title" name="jwppp-playlist-carousel-title" placeholder="' . esc_attr( __( 'More videos', 'jwppp' ) ) . '" disabled="disabled" />';
					echo '<p class="description">' . esc_html( __( 'Add a title for the playlist widget', 'jwppp' ) ) . '</p>';
					go_premium();
					echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<th scope="row">' . esc_html( __( 'Text color', 'jwppp' ) ) . '</th>';
				echo '<td>';
					echo '<input type="text" class="jwppp-color-field" name="jwppp-playlist-carousel-text-color" disabled="disabled">';
					echo '<p class="description">' . esc_html( __( 'Set the text color for the playlist widget', 'jwppp' ) ) . '</p>';
					go_premium();
				echo '</td>';
			echo '</tr>';

			echo '<tr>';
				echo '<th scope="row">' . esc_html( __( 'Background color', 'jwppp' ) ) . '</th>';
				echo '<td>';
					echo '<input type="text" class="jwppp-color-field" name="jwppp-playlist-carousel-background-color" disabled="disabled">';
					echo '<p class="description">' . esc_html( __( 'Set the background color for the playlist widget', 'jwppp' ) ) . '</p>';
					go_premium();
				echo '</td>';
			echo '</tr>';

			echo '<tr>';
				echo '<th scope="row">' . esc_html( __( 'Icon color', 'jwppp' ) ) . '</th>';
				echo '<td>';
					echo '<input type="text" class="jwppp-color-field" name="jwppp-playlist-carousel-icon-color" disabled="disabled">';
					echo '<p class="description">' . esc_html( __( 'Set the icon color for the playlist widget', 'jwppp' ) ) . '</p>';
					go_premium();
				echo '</td>';
			echo '</tr>';
		echo '</table>';

		echo '<input type="hidden" name="jwppp-playlist-carousel-hidden" value="1" />';
		echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_attr( __( 'Save chages', 'jwppp' ) ) . '" disabled="disabled">';
	echo '</form>';
	?>
</div>
