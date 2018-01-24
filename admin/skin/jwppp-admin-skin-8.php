<!-- START SKIN -->
<?php
echo '<h2 style="margin: 1.5rem 0;">JW Player 8 Skin Customization</h2>';
echo '<form id="jwppp-skin" name="jwppp-skin" method="post" action="">';
echo '<table class="form-table">';


//SKIN COLORS
echo '<tr>';
echo '<th scope="row">' . __('Constrolbar text', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-text" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The color of any plain text in the control bar, such as the time.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Constrolbar icons', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-icons" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The default, inactive color of all icons in the control bar.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Constrolbar active icons', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-active-icons" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The color of hovered or selected icons in the control bar.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Constrolbar background', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-background" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The background color of the control bar and the volume slider. The default background is transparent.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Timeslider progress', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-timeslider-progress" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The color of the bar in the time slider filled in from the beginning of the video through the current position.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Timeslider rail', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-timeslider-rail" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The color of the base of the timeslider, known as the rail.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Menus text', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-text" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The color of inactive, default text in menus and the Next Up overlay.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Menus active text', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-active-text" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The color of hovered or selected text in menus. ', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Menus background', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-background" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The background color of menus and the Next Up overlay.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Tooltips text', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-tooltips-text" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The text color of tooltips.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Tooltips background', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-tooltips-background" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The background color of tooltips.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';
echo '</p>';
echo '</td>';
echo '</tr>';

echo '</table>';
echo '<input class="button button-primary" type="submit" id="submit" value="' . __('Save chages', 'jwppp') . '" disabled="disabled">';
echo '</form>';

?>
<!-- </div> -->
<!-- END - SKIN -->

