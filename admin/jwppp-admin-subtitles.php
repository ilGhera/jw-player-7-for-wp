<!-- START SUBTITLES -->
<div name="jwppp-subtitles" id="jwppp-subtitles" class="jwppp-admin" style="display: none;">
	<?php
	echo '<form id="jwppp-subtitles" name="jwppp-subtitles" method="post" action="">';
	echo '<table class="form-table">';
	echo '<tr>';
	echo '<th scope="row">' . __('Text color', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="text" class="jwppp-color-field" name="jwppp-subtitles-color" value="">';
	echo '<p class="description">' . __('Choose the text-color for your subtitles.', 'jwppp') . '</p>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . __('Font size', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="number" class="jwppp-subtitles-font-size" min="8" max="30" step="1" name="jwppp-subtitles-font-size" disabled="disabled" value="">';
	echo '<p class="description">' . __('Choose the font-size for your subtitles.', 'jwppp') . '</p>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . __('Font family', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="text" class="jwppp-subtitles-font-family" name="jwppp-subtitles-font-family" disabled="disabled" value="">';
	echo '<p class="description">' . __('Choose the font-family for your subtitles.', 'jwppp') . '</p>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . __('Font opacity', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="number" class="jwppp-subtitles-opacity" min="0" max="100" step="10" name="jwppp-subtitles-opacity" disabled="disabled" value="">';
	echo '<p class="description">' . __('Add opacity to your subtitles.', 'jwppp') . '</p>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . __('Background color', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="text" class="jwppp-color-field" name="jwppp-subtitles-back-color" disabled="disabled" value="">';
	echo '<p class="description">' . __('Choose the background-color for your subtitles.', 'jwppp') . '</p>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th scope="row">' . __('Background opacity', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="number" class="jwppp-subtitles-back-opacity" min="0" max="100" step="10" name="jwppp-subtitles-back-opacity" disabled="disabled" value="">';
	echo '<p class="description">' . __('Add opacity to your subtitles background.', 'jwppp') . '</p>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</td>';
	echo '</tr>';

	echo '</table>';
	echo '<input class="button button-primary" type="submit" id="submit" disabled="disabled" value="' . __('Save chages', 'jwppp') . '">';
	echo '</form>';
	?>
</div>
<!-- END - SUBTITLES -->

