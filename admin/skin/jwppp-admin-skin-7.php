<!-- START SKIN -->
<?php
echo '<h2 style="margin: 1.5rem 0;">JW Player 7 Skin Customization</h2>';
echo '<form id="jwppp-skin" name="jwppp-skin" method="post" action="">';
echo '<table class="form-table">';

require('jwppp-admin-skin-7-options.php');

/**
 * Skin selection (JWP7)
 */
echo '<tr>';
echo '<th scope="row">' . __('Skin', 'jwppp') . '</th>';
echo '<td>';
echo '<select id="jwppp-skin" name="jwppp-skin">';
echo '<option name="none" value="none" ';
echo ($jwppp_skin == 'none') ? 'selected="selected"' : '';
echo '>--</option>';
echo '<option name="seven" value="seven" ';
echo ($jwppp_skin == 'seven') ? 'selected="selected"' : '';
echo '>Seven</option>';
echo '<option name="six" value="six" ';
echo ($jwppp_skin == 'six') ? 'selected="selected"' : '';
echo '>Six</option>';
echo '<option name="five" value="five" ';
echo ($jwppp_skin == 'five') ? 'selected="selected"' : '';
echo '>Five</option>';
echo '<option name="glow" value="glow" ';
echo ($jwppp_skin == 'glow') ? 'selected="selected"' : '';
echo '>Glow</option>';
echo '<option name="beelden" value="beelden" ';
echo ($jwppp_skin == 'beelden') ? 'selected="selected"' : '';
echo '>Beelden</option>';
echo '<option name="vapor" value="vapor" ';
echo ($jwppp_skin == 'vapor') ? 'selected="selected"' : '';
echo '>Vapor</option>';
echo '<option name="bekle" value="bekle" ';
echo ($jwppp_skin == 'bekle') ? 'selected="selected"' : '';
echo '>Bekle</option>';
echo '<option name="roundster" value="roundster" ';
echo ($jwppp_skin == 'roundster') ? 'selected="selected"' : '';
echo '>Roundster</option>';
echo '<option name="stormtrooper" value="stormtrooper" ';
echo ($jwppp_skin == 'stormtrooper') ? 'selected="selected"' : '';
echo '>Stormtrooper</option>';	
echo '<option name="custom-skin" value="custom-skin" ';
echo ($jwppp_skin == 'custom-skin') ? 'selected="selected"' : '';
echo '>Add a custom skin...</option>';
echo '</select>';
echo '<p class="description">Select a skin or add a new one for customizing your player.</p>';
echo '</td>';
echo '</tr>';


//CUSTOM SKIN FIELDS
echo '<tr class="custom-skin-url">';
echo '<th scope="row">' . __('Skin URL', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="regular-text" name="custom-skin-url" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The url of the style.css file of your custom skin.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';	
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr class="custom-skin-name">';
echo '<th scope="row">' . __('Skin name', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="regular-text" name="custom-skin-name" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The name of your custom skin.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';	
echo '</p>';
echo '</td>';
echo '</tr>';


//SKIN COLORS
echo '<tr>';
echo '<th scope="row">' . __('Active', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-active" value="" disabled="disabled">';
echo '<p class="description">';
echo __('    Active skin elements. This includes active and highlighted labels, as well scrubber time that has elapsed.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';	
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Inactive', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-inactive" value="" disabled="disabled">';
echo '<p class="description">';
echo __('    Skin elements that are not active. This includes scrubber time that has not yet elapsed.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';	
echo '</p>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<th scope="row">' . __('Background', 'jwppp') . '</th>';
echo '<td>';
echo '<input type="text" class="jwppp-color-field" name="jwppp-skin-color-background" value="" disabled="disabled">';
echo '<p class="description">';
echo __('The color of hovered or selected icons in the control bar.', 'jwppp');
echo '<br><a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a>';	
echo '</p>';
echo '</td>';
echo '</tr>';

echo '</table>';
echo '<input class="button button-primary" type="submit" id="submit" value="' . __('Save chages', 'jwppp') . '">';
echo '</form>';

?>
<!-- </div> -->
<!-- END - SKIN -->

