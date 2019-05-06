<?php
/**
 * Skin options 8 - Admin form
 * @author ilGhera
 * @package jw-player-for-vip/admin/skin
* @version 2.0.0
 */

echo '<h2 style="margin: 1.5rem 0;">JW Player 8 Skin Customization</h2>';
echo '<form id="jwppp-skin" name="jwppp-skin" method="post" action="">';
echo '<table class="form-table">';

/*Get, save and update options*/
require( JWPPP_ADMIN . 'skin/jwppp-admin-skin-8-options.php' );

/*Skin colors*/
echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Constrolbar Text', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-text" value="' . esc_attr( $jwppp_skin_color_controlbar_text ) . '">';
echo '<p class="description">' . esc_html( __( 'Color of plain text in the control bar, such as the time.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Constrolbar Icons', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-icons" value="' . esc_attr( $jwppp_skin_color_controlbar_icons ) . '">';
echo '<p class="description">' . esc_html( __( 'Default, inactive color of all icons in the control bar.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Constrolbar Active Icons', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-active-icons" value="' . esc_attr( $jwppp_skin_color_controlbar_active_icons ) . '">';
echo '<p class="description">' . esc_html( __( 'Color of hovered or selected icons in the control bar.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Constrolbar Background', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-controlbar-background" value="' . esc_attr( $jwppp_skin_color_controlbar_background ) . '">';
echo '<p class="description">' . esc_html( __( 'Background color of the control bar and the volume slider. Default background is transparent..', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Timeslider Progress', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-timeslider-progress" value="' . esc_attr( $jwppp_skin_color_timeslider_progress ) . '">';
echo '<p class="description">' . esc_html( __( 'Color of the progress bar in the time slider.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Timeslider Rail', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-timeslider-rail" value="' . esc_attr( $jwppp_skin_color_timeslider_rail ) . '">';
echo '<p class="description">' . esc_html( __( 'Base color of the timeslider, known as the rail.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Menus Text', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-text" value="' . esc_attr( $jwppp_skin_color_menus_text ) . '">';
echo '<p class="description">' . esc_html( __( 'Color of inactive, default text in menus and the Next Up overlay.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Menus Active Text', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-active-text" value="' . esc_attr( $jwppp_skin_color_menus_active_text ) . '">';
echo '<p class="description">' . esc_html( __( 'Color of hovered or selected text in menus. ', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Menus Background', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-menus-background" value="' . esc_attr( $jwppp_skin_color_menus_background ) . '">';
echo '<p class="description">' . esc_html( __( 'Background color of menus and the Next Up overlay.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Tooltips Text', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-tooltips-text" value="' . esc_attr( $jwppp_skin_color_tooltips_text ) . '">';
echo '<p class="description">' . esc_html( __( 'Color of tooltips text.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Tooltips Background', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-tooltips-background" value="' . esc_attr( $jwppp_skin_color_tooltips_background ) . '">';
echo '<p class="description">' . esc_html( __( 'Background color of tooltips.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '</table>';

/*Add nonce to the form*/
wp_nonce_field( 'jwppp-nonce-skin', 'hidden-nonce-skin' );

echo '<input type="hidden" name="set" value="1" />';
echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_attr( __( 'Save chages', 'jwppp' ) ) . '">';
echo '</form>';
