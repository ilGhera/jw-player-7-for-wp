<?php
/**
 * Playlist carousel options
 * @author ilGhera
 * @package jw-player-7-for-wp/admin
 * @version 1.6.0
 */
?>
<div name="jwppp-playlist-carousel" id="jwppp-playlist-carousel" class="jwppp-admin" style="display: none;">
	<?php
	$jwppp_playlist_carousel_style = unserialize(base64_decode(sanitize_text_field(get_option('jwppp-playlist-carousel-style'))));

	$jwppp_playlist_carousel_title = isset($jwppp_playlist_carousel_style['title']) ? sanitize_text_field($jwppp_playlist_carousel_style['title']) : 'More Videos';
	$jwppp_playlist_carousel_text_color = isset($jwppp_playlist_carousel_style['text_color']) ? sanitize_text_field($jwppp_playlist_carousel_style['text_color']) : '#fff';
	$jwppp_playlist_carousel_background_color = isset($jwppp_playlist_carousel_style['background_color']) ? sanitize_text_field($jwppp_playlist_carousel_style['background_color']) : '#000';
	$jwppp_playlist_carousel_icon_color = isset($jwppp_playlist_carousel_style['icon_color']) ? sanitize_text_field($jwppp_playlist_carousel_style['icon_color']) : '#fff';

	if(isset($_POST['jwppp-playlist-carousel-hidden'])) {
		$jwppp_playlist_carousel_title = sanitize_text_field($_POST['jwppp-playlist-carousel-title']);
		$jwppp_playlist_carousel_text_color = sanitize_text_field($_POST['jwppp-playlist-carousel-text-color']);
		$jwppp_playlist_carousel_background_color = sanitize_text_field($_POST['jwppp-playlist-carousel-background-color']);
		$jwppp_playlist_carousel_icon_color = sanitize_text_field($_POST['jwppp-playlist-carousel-icon-color']);

		$jwppp_playlist_carousel_style = array(
			'title' => $jwppp_playlist_carousel_title,
			'text_color' => $jwppp_playlist_carousel_text_color,
			'background_color' => $jwppp_playlist_carousel_background_color,
			'icon_color' => $jwppp_playlist_carousel_icon_color
		);

		update_option('jwppp-playlist-carousel-style', base64_encode(serialize($jwppp_playlist_carousel_style)));
	}

	echo '<form id="jwppp-playlist-carousel" name="jwppp-playlist-carousel" method="post" action="">';
		echo '<table class="form-table">';
			echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Title', 'jwppp')) . '</th>';
				echo '<td>';
					echo '<input type="text" class="regular-text" id="jwppp-playlist-carousel-title" name="jwppp-playlist-carousel-title" placeholder="' . esc_html(__('More videos', 'jwppp')) . '" value="' . $jwppp_playlist_carousel_title . '" />';
					echo '<p class="description">' . esc_html(__('Add a title for the playlist widget', 'jwppp')) . '</p>';
					echo '</td>';	
			echo '</tr>';
			echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Text color', 'jwppp')) . '</th>';
				echo '<td>';
					echo '<input type="text" class="jwppp-color-field" name="jwppp-playlist-carousel-text-color" value="' . esc_html($jwppp_playlist_carousel_text_color) . '">';
					echo '<p class="description">' . esc_html(__('Set the text color for the playlist widget', 'jwppp')) . '</p>';
				echo '</td>';
			echo '</tr>';

			echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Background color', 'jwppp')) . '</th>';
				echo '<td>';
					echo '<input type="text" class="jwppp-color-field" name="jwppp-playlist-carousel-background-color" value="' . esc_html($jwppp_playlist_carousel_background_color) . '">';
					echo '<p class="description">' . esc_html(__('Set the background color for the playlist widget', 'jwppp')) . '</p>';
				echo '</td>';
			echo '</tr>';

			echo '<tr>';
				echo '<th scope="row">' . esc_html(__('Icon color', 'jwppp')) . '</th>';
				echo '<td>';
					echo '<input type="text" class="jwppp-color-field" name="jwppp-playlist-carousel-icon-color" value="' . esc_html($jwppp_playlist_carousel_icon_color) . '">';
					echo '<p class="description">' . esc_html(__('Set the icon color for the playlist widget', 'jwppp')) . '</p>';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
		echo '<input type="hidden" name="jwppp-playlist-carousel-hidden" value="1" />';
		echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_html(__('Save chages', 'jwppp')) . '">';
	echo '</form>';
	?>
</div>