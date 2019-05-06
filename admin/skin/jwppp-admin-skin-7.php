<?php
/**
 * Skin options 7 - Admin form
 * @author ilGhera
 * @package jw-player-for-vip/admin/skin
* @version 2.0.0
 */

echo '<h2 style="margin: 1.5rem 0;">JW Player 7 Skin Customization</h2>';
echo '<form id="jwppp-skin" name="jwppp-skin" method="post" action="">';
echo '<table class="form-table">';

/*Get, save and update options*/
require( JWPPP_ADMIN . 'skin/jwppp-admin-skin-7-options.php' );

/*Skin selection*/
echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Skin', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<select id="jwppp-skin" name="jwppp-skin">';
echo '<option name="none" value="none" ';
echo ( 'none' === $jwppp_skin ) ? 'selected="selected"' : '';
echo '>--</option>';
echo '<option name="seven" value="seven" ';
echo ( 'seven' === $jwppp_skin ) ? 'selected="selected"' : '';
echo '>Seven</option>';
echo '<option name="six" value="six" ';
echo ( 'six' === $jwppp_skin ) ? 'selected="selected"' : '';
echo '>Six</option>';
echo '<option name="five" value="five" ';
echo ( 'five' === $jwppp_skin ) ? 'selected="selected"' : '';
echo '>Five</option>';
echo '<option name="glow" value="glow" ';
echo ( 'glow' === $jwppp_skin ) ? 'selected="selected"' : '';
echo '>Glow</option>';
echo '<option name="beelden" value="beelden" ';
echo ( 'beelden' === $jwppp_skin ) ? 'selected="selected"' : '';
echo '>Beelden</option>';
echo '<option name="vapor" value="vapor" ';
echo ( 'vapor' === $jwppp_skin ) ? 'selected="selected"' : '';
echo '>Vapor</option>';
echo '<option name="bekle" value="bekle" ';
echo ( 'bekle' === $jwppp_skin ) ? 'selected="selected"' : '';
echo '>Bekle</option>';
echo '<option name="roundster" value="roundster" ';
echo ( 'roundster' === $jwppp_skin ) ? 'selected="selected"' : '';
echo '>Roundster</option>';
echo '<option name="stormtrooper" value="stormtrooper" ';
echo ( 'stormtrooper' === $jwppp_skin ) ? 'selected="selected"' : '';
echo '>Stormtrooper</option>';
echo '<option name="custom-skin" value="custom-skin" ';
echo ( 'custom-skin' === $jwppp_skin ) ? 'selected="selected"' : '';
echo '>' . esc_html( __( 'Add a custom skin...', 'jwppp' ) ) . '</option>';
echo '</select>';
echo '<p class="description">' . esc_html( __( 'Select a skin or add a new one for customizing your player.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

/*Custom skin fields*/
echo '<tr class="custom-skin-url">';
echo '<th scope="row">' . esc_html( __( 'Skin URL', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="regular-text" name="custom-skin-url" value="' . esc_attr( $jwppp_custom_skin_url ) . '">';
echo '<p class="description">' . esc_html( __( 'The url of the style.css file of your custom skin.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr class="custom-skin-name">';
echo '<th scope="row">' . esc_html( __( 'Skin name', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="regular-text" name="custom-skin-name" value="' . esc_attr( $jwppp_custom_skin_name ) . '">';
echo '<p class="description">' . esc_html( __( 'The name of your custom skin.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

/*Skin colors*/
echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Active', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-active" value="' . esc_attr( $jwppp_skin_color_active ) . '">';
echo '<p class="description">' . esc_html( __( '    Active skin elements. This includes active and highlighted labels, as well scrubber time that has elapsed.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Inactive', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-inactive" value="' . esc_attr( $jwppp_skin_color_inactive ) . '">';
echo '<p class="description">' . esc_html( __( '    Skin elements that are not active. This includes scrubber time that has not yet elapsed.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . esc_html( __( 'Background', 'jwppp' ) ) . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-background" value="' . esc_attr( $jwppp_skin_color_background ) . '">';
echo '<p class="description">' . esc_html( __( 'The color of hovered or selected icons in the control bar.', 'jwppp' ) ) . '</p>';
echo '</td>';
echo '</tr>';

echo '</table>';

/*Add nonce to the form*/
wp_nonce_field( 'jwppp-nonce-skin', 'hidden-nonce-skin' );

echo '<input type="hidden" name="set" value="1" />';
echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_attr( __( 'Save chages', 'jwppp' ) ) . '">';
echo '</form>';
