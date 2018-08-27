<?php
/**
 * Skin options 8 - Admin form
 * @author ilGhera
 * @package jw-player-7-for-wp/admin/skin
 * @version 1.6.0
 */

echo '<h2 style="margin: 1.5rem 0;">JW Player 8 Skin Customization</h2>';
echo '<form id="jwppp-skin" name="jwppp-skin" method="post" action="">';
echo '<table class="form-table">';

/*Get, save and update options*/
require('jwppp-admin-skin-8-options.php');

/*Skin colors*/
echo '<tr>';
echo '<th scope="row">' . esc_html(__('Constrolbar text', 'jwppp')) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-text" value="' . esc_html($jwppp_skin_color_controlbar_text) . '">';
echo '<p class="description">' . esc_html(__('The color of any plain text in the control bar, such as the time.', 'jwppp')) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html(__('Constrolbar icons', 'jwppp')) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-icons" value="' . esc_html($jwppp_skin_color_controlbar_icons) . '">';
echo '<p class="description">' . esc_html(__('The default, inactive color of all icons in the control bar.', 'jwppp')) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html(__('Constrolbar active icons', 'jwppp')) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-active-icons" value="' . esc_html($jwppp_skin_color_controlbar_active_icons) . '">';
echo '<p class="description">' . esc_html(__('The color of hovered or selected icons in the control bar.', 'jwppp')) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html(__('Constrolbar background', 'jwppp')) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-background" value="' . esc_html($jwppp_skin_color_controlbar_background) . '">';
echo '<p class="description">' . esc_html(__('The background color of the control bar and the volume slider. The default background is transparent.', 'jwppp')) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html(__('Timeslider progress', 'jwppp')) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-timeslider-progress" value="' . esc_html($jwppp_skin_color_timeslider_progress) . '">';
echo '<p class="description">' . esc_html(__('The color of the bar in the time slider filled in from the beginning of the video through the current position.', 'jwppp')) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html(__('Timeslider rail', 'jwppp')) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-timeslider-rail" value="' . esc_html($jwppp_skin_color_timeslider_rail) . '">';
echo '<p class="description">' . esc_html(__('The color of the base of the timeslider, known as the rail.', 'jwppp')) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html(__('Menus text', 'jwppp')) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-text" value="' . esc_html($jwppp_skin_color_menus_text) . '">';
echo '<p class="description">' . esc_html(__('The color of inactive, default text in menus and the Next Up overlay.', 'jwppp')) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html(__('Menus active text', 'jwppp')) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-active-text" value="' . esc_html($jwppp_skin_color_menus_active_text) . '">';
echo '<p class="description">' . esc_html(__('The color of hovered or selected text in menus. ', 'jwppp')) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html(__('Menus background', 'jwppp')) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-background" value="' . esc_html($jwppp_skin_color_menus_background) . '">';
echo '<p class="description">' . esc_html(__('The background color of menus and the Next Up overlay.', 'jwppp')) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html(__('Tooltips text', 'jwppp')) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-tooltips-text" value="' . esc_html($jwppp_skin_color_tooltips_text) . '">';
echo '<p class="description">' . esc_html(__('The text color of tooltips.', 'jwppp')) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html(__('Tooltips background', 'jwppp')) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-tooltips-background" value="' . esc_html($jwppp_skin_color_tooltips_background) . '">';
echo '<p class="description">' . esc_html(__('The background color of tooltips.', 'jwppp')) . '</p>';
echo '</td>';
echo '</tr>';

echo '</table>';
echo '<input type="hidden" name="set" value="1" />';
echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_html(__('Save chages', 'jwppp')) . '">';
echo '</form>';

?>
<!-- </div> -->
<!-- END - SKIN -->

