<!-- START - RELATED VIDEOS -->
<div name="jwppp-related" id="jwppp-related" class="jwppp-admin" style="display: none;">

	<?php //GET INFO FROM DATABASE

	//SHOW RELATED?
	$jwppp_show_related = sanitize_text_field(get_option('jwppp-show-related'));
	if( isset($_POST['set']) ) {
		$jwppp_show_related = isset($_POST['jwppp-show-related']) ? $_POST['jwppp-show-related'] : 0;
		update_option('jwppp-show-related', $jwppp_show_related);
	}
	

	//FORM RELATED VIDEOS
	echo '<form id="post-image" name="post-image" method="post" action="">';
	echo '<table class="form-table">';

	//SHOW RELATED?
	echo '<tr>';
	echo '<th scope="row">' . __('Active Related Videos option', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-show-related" name="jwppp-show-related" value="1"';
	echo ($jwppp_show_related == 1) ? ' checked="checked"' : '';
	echo '/>';
	echo '<p class="description">' . __('Show Related Videos overlay as default option.', 'jwppp') . '</p>';
	echo '</td>';
	echo '</tr>';

	//HEADING
	echo '<tr class="related-options">';
	echo '<th scope="row">' . __('Next Up tooltip', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" id="jwppp-related-heading" name="jwppp-related-heading" disabled="disabled"';
	echo 'placeholder="' . __('Related Videos', 'jwppp') . '" value="More videos" />';
	echo '<p class="description">' . __('Title of the Next Up tooltip in Related mode, default is <strong>Related</strong>.', 'jwppp') . '</p>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</td>';
	echo '</tr>';

	//THUMBNAIL
	echo '<tr class="related-options">';
	echo '<th scope="row">' . __('Related image', 'jwppp') . '</th>';
	echo '<td>';
	echo '<select id="thumbnail" name="thumbnail" disabled="disabled" />';

	echo '<option id="featured-image" value="featured-image"';
	echo 'selected="selected">';
	echo __('Featured image', 'jwppp') . '</option>';;

	echo '</select>';
	echo '<p class="description">' . __('Select how get images for related contents.', 'jwppp') . '<br>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</td>';
	echo '</tr>';

	//TAXONOMY SELECT
	echo '<tr class="related-options">';
	echo '<th scope="row">Related taxonomy</th>';
	echo '<td>';
	echo '<select id="jwppp-taxonomy-select" name="jwppp-taxonomy-select" disabled="disabled"/>';
	echo '<option name="categories" value=""';
	echo ' selected="selected"';
	echo '>' . __('Categories', 'jwppp') . '</option>';

	echo '</select>';
	echo '<p class="description">' . __('Use a taxonomy to get more specific related videos. It will be add to all post types you choosed.', 'jwppp') . '<br>';
	echo __('You can even use <strong>Video categories</strong> provided by this plugin.', 'jwppp') . '<br>';
	echo '<a href="https://www.ilghera.com/product/jw-player-7-for-wordpress-premium/" target="_blank">Upgrade</a></p>';
	echo '</td>';

	echo '</table>';
	echo '<input class="button button-primary" type="submit" id="submit" disabled="disabled" value="' . __('Save chages', 'jwppp') . '">';

	echo '</form>'; ?>

</div>
<!-- END - RELATED VIDEOS -->

