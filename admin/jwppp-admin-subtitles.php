<!-- START SUBTITLES -->
<div name="jwppp-subtitles" id="jwppp-subtitles" class="jwppp-admin" style="display: none;">
	<?php
		//COLOR
		$sub_color = sanitize_text_field(get_option('jwppp-subtitles-color'));
		if(isset($_POST['jwppp-subtitles-color'])) {
			if(jwppp_check_color($_POST['jwppp-subtitles-color'])) {
				$sub_color = sanitize_text_field($_POST['jwppp-subtitles-color']);
				update_option('jwppp-subtitles-color', $sub_color);										
			} else {
			    add_settings_error( 'jw-player-for-wp', 'jwppp_color_error', 'Please, insert a valid color.', 'error' );
			}
		} 
		//FONT-SIZE
		$sub_font_size = sanitize_text_field(get_option('jwppp-subtitles-font-size'));
		if(isset($_POST['jwppp-subtitles-font-size'])) {
			$sub_font_size = sanitize_text_field($_POST['jwppp-subtitles-font-size']);
			update_option('jwppp-subtitles-font-size', $sub_font_size);
		}
		//FONT-FAMILY
		$sub_font_family = sanitize_text_field(get_option('jwppp-subtitles-font-family'));
		if(isset($_POST['jwppp-subtitles-font-family'])) {
			$sub_font_family = sanitize_text_field($_POST['jwppp-subtitles-font-family']);
			update_option('jwppp-subtitles-font-family', $sub_font_family);
		}
		//OPACITY
		$sub_opacity = sanitize_text_field(get_option('jwppp-subtitles-opacity'));
		if(isset($_POST['jwppp-subtitles-opacity'])) {
			$sub_opacity = sanitize_text_field($_POST['jwppp-subtitles-opacity']);
			update_option('jwppp-subtitles-opacity', $sub_opacity);
		}
		//BACK-COLOR
		$sub_back_color = sanitize_text_field(get_option('jwppp-subtitles-back-color'));
		if(isset($_POST['jwppp-subtitles-back-color'])) {
			if(jwppp_check_color($_POST['jwppp-subtitles-back-color'])) {
				$sub_back_color = sanitize_text_field($_POST['jwppp-subtitles-back-color']);
				update_option('jwppp-subtitles-back-color', $sub_back_color);										
			} else {
			    add_settings_error( 'jw-player-for-wp', 'jwppp_color_error', 'Please, insert a valid background color.', 'error' );
			}
		} 
		//BACK-OPACITY
		$sub_back_opacity = sanitize_text_field(get_option('jwppp-subtitles-back-opacity'));
		if(isset($_POST['jwppp-subtitles-back-opacity'])) {
			$sub_back_opacity = sanitize_text_field($_POST['jwppp-subtitles-back-opacity']);
			update_option('jwppp-subtitles-back-opacity', $sub_back_opacity);
		}

	    settings_errors();


		echo '<form id="jwppp-subtitles" name="jwppp-subtitles" method="post" action="">';
		echo '<table class="form-table">';
		echo '<tr>';
		echo '<th scope="row">' . __('Text color', 'jwppp') . '</th>';
		echo '<td>';
		echo '<input type="text" class="jwppp-color-field" name="jwppp-subtitles-color" value="' . $sub_color . '">';
		echo '<p class="description">' . __('Choose the text-color for your subtitles.', 'jwppp') . '</p>';
		echo '</td>';
		echo '</tr>';

		echo '<tr>';
		echo '<th scope="row">' . __('Font size', 'jwppp') . '</th>';
		echo '<td>';
		echo '<input type="number" class="jwppp-subtitles-font-size" min="8" max="30" step="1" name="jwppp-subtitles-font-size" value="' . $sub_font_size . '">';
		echo '<p class="description">' . __('Choose the font-size for your subtitles.', 'jwppp') . '</p>';
		echo '</td>';
		echo '</tr>';

		echo '<tr>';
		echo '<th scope="row">' . __('Font family', 'jwppp') . '</th>';
		echo '<td>';
		echo '<input type="text" class="jwppp-subtitles-font-family" name="jwppp-subtitles-font-family" value="' . $sub_font_family . '">';
		echo '<p class="description">' . __('Choose the font-family for your subtitles.', 'jwppp') . '</p>';
		echo '</td>';
		echo '</tr>';

		echo '<tr>';
		echo '<th scope="row">' . __('Font opacity', 'jwppp') . '</th>';
		echo '<td>';
		echo '<input type="number" class="jwppp-subtitles-opacity" min="0" max="100" step="10" name="jwppp-subtitles-opacity" value="' . $sub_opacity . '">';
		echo '<p class="description">' . __('Add opacity to your subtitles.', 'jwppp') . '</p>';
		echo '</td>';
		echo '</tr>';

		echo '<tr>';
		echo '<th scope="row">' . __('Background color', 'jwppp') . '</th>';
		echo '<td>';
		echo '<input type="text" class="jwppp-color-field" name="jwppp-subtitles-back-color" value="' . $sub_back_color . '">';
		echo '<p class="description">' . __('Choose the background-color for your subtitles.', 'jwppp') . '</p>';
		echo '</td>';
		echo '</tr>';

		echo '<tr>';
		echo '<th scope="row">' . __('Background opacity', 'jwppp') . '</th>';
		echo '<td>';
		echo '<input type="number" class="jwppp-subtitles-back-opacity" min="0" max="100" step="10" name="jwppp-subtitles-back-opacity" value="' . $sub_back_opacity . '">';
		echo '<p class="description">' . __('Add opacity to your subtitles background.', 'jwppp') . '</p>';
		echo '</td>';
		echo '</tr>';

		echo '</table>';
		echo '<input type="hidden" name="set" value="1" />';
		echo '<input class="button button-primary" type="submit" id="submit" value="' . __('Save chages', 'jwppp') . '">';
		echo '</form>';
	?>
</div>
<!-- END - SUBTITLES -->
