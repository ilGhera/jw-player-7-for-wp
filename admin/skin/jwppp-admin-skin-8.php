<?php
/**
 * Skin options 8 - Admin form
 * @author ilGhera
 * @package jw-player-7-for-wp/admin/skin
 * @since 2.0.0
 */

echo '<h2 style="margin: 1.5rem 0;">JW Player 8 Skin Customization</h2>';
echo '<form id="jwppp-skin" name="jwppp-skin" method="post" action="">';
echo '<table class="form-table">';

/*Skin colors*/
echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Constrolbar Text', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-text" disabled="disabled">';
echo '<p class="description">' . esc_html( __( 'Color of plain text in the control bar, such as the time.', 'jwppp' ) ) . '</p>';
go_premium();
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Constrolbar Icons', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-icons" disabled="disabled">';
echo '<p class="description">' . esc_html( __( 'Default, inactive color of all icons in the control bar.', 'jwppp' ) ) . '</p>';
go_premium();
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Constrolbar Active Icons', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-active-icons" disabled="disabled">';
echo '<p class="description">' . esc_html( __( 'Color of hovered or selected icons in the control bar.', 'jwppp' ) ) . '</p>';
go_premium();
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Constrolbar Background', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-background" disabled="disabled">';
echo '<p class="description">' . esc_html( __( 'Background color of the control bar and the volume slider. Default background is transparent..', 'jwppp' ) ) . '</p>';
go_premium();
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Timeslider Progress', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-timeslider-progress" disabled="disabled">';
echo '<p class="description">' . esc_html( __( 'Color of the progress bar in the time slider.', 'jwppp' ) ) . '</p>';
go_premium();
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Timeslider Rail', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-timeslider-rail" disabled="disabled">';
echo '<p class="description">' . esc_html( __( 'Base color of the timeslider, known as the rail.', 'jwppp' ) ) . '</p>';
go_premium();
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Menus Text', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-text" disabled="disabled">';
echo '<p class="description">' . esc_html( __( 'Color of inactive, default text in menus and the Next Up overlay.', 'jwppp' ) ) . '</p>';
go_premium();
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Menus Active Text', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-active-text" disabled="disabled">';
echo '<p class="description">' . esc_html( __( 'Color of hovered or selected text in menus. ', 'jwppp' ) ) . '</p>';
go_premium();
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Menus Background', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-background" disabled="disabled">';
echo '<p class="description">' . esc_html( __( 'Background color of menus and the Next Up overlay.', 'jwppp' ) ) . '</p>';
go_premium();
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Tooltips Text', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-tooltips-text" disabled="disabled">';
echo '<p class="description">' . esc_html( __( 'Color of tooltips text.', 'jwppp' ) ) . '</p>';
go_premium();
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Tooltips Background', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-tooltips-background" disabled="disabled">';
echo '<p class="description">' . esc_html( __( 'Background color of tooltips.', 'jwppp' ) ) . '</p>';
go_premium();
echo '</td>';
echo '</tr>';

echo '</table>';

echo '<input type="hidden" name="set" value="1" />';
echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_attr( __( 'Save chages', 'jwppp' ) ) . '" disabled="disabled">';
echo '</form>';
