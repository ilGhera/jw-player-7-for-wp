<!-- START - RELATED VIDEOS -->
<div name="jwppp-related" id="jwppp-related" class="jwppp-admin" style="display: none;">

	<?php //GET INFO FROM DATABASE

	//SHOW RELATED?
	$jwppp_show_related = sanitize_text_field(get_option('jwppp-show-related'));
	if( isset($_POST['set']) )  {
		$jwppp_show_related = isset($_POST['jwppp-show-related']) ? $_POST['jwppp-show-related'] : 0;
		update_option('jwppp-show-related', $jwppp_show_related);
	}

	//HEADING
	$jwppp_related_heading = sanitize_text_field(get_option('jwppp-related-heading'));
	if(isset($_POST['jwppp-related-heading'])) {
		$jwppp_related_heading = sanitize_text_field($_POST['jwppp-related-heading']);
		update_option('jwppp-related-heading', $jwppp_related_heading);
	}

	//THUMBNAIL
	$set = sanitize_text_field(get_option('jwppp-image'));
	$field_set = sanitize_text_field(get_option('jwppp-field'));

	if( isset($_POST['thumbnail']) ) {
		$set = sanitize_text_field($_POST['thumbnail']);
		update_option('jwppp-image', $set);
		if($set == 'custom-field') {
			$field_set = $_POST['field'];
			update_option('jwppp-field', $field_set );
		}
	}

	//TAXONOMY SELECT
	$jwppp_taxonomy_select = sanitize_text_field(get_option('jwppp-taxonomy-select'));
	if(isset($_POST['jwppp-taxonomy-select'])) {
		$jwppp_taxonomy_select = sanitize_text_field($_POST['jwppp-taxonomy-select']);
		update_option('jwppp-taxonomy-select', $jwppp_taxonomy_select);

		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}

	

	//FORM POST-IMAGE
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
	echo '<input type="text" class="regular-text" id="jwppp-related-heading" name="jwppp-related-heading" ';
	echo 'placeholder="' . __('Related Videos', 'jwppp') . '" value="' . $jwppp_related_heading . '" />';
	echo '<p class="description">' . __('Title of the Next Up tooltip in Related mode, default is <strong>Related</strong>.', 'jwppp') . '</p>';
	echo '</td>';
	echo '</tr>';

	//THUMBNAIL
	echo '<tr class="related-options">';
	echo '<th scope="row">' . __('Related image', 'jwppp') . '</th>';
	echo '<td>';
	echo '<select id="thumbnail" name="thumbnail"/>';

	echo '<option id="featured-image" value="featured-image"';
	echo ($set == 'featured-image') ? 'selected="selected">' : '>';
	echo __('Featured image', 'jwppp') . '</option>';

	echo '<option id="custom-field" value="custom-field"';
	echo ($set == 'custom-field') ? 'selected="selected">' : '>';
	echo __('Custom field', 'jwppp') . '</option>';

	echo '</select>';
	echo '<p class="description">' . __('Select how get images for related contents.', 'jwppp') . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr class="related-options cf-row">';
	echo '<th scope="row">' . __('Custom field name', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" ';
	echo 'id="field" name="field" placeholder="' . __('Custom field name', 'jwppp') . '" value="' . $field_set . '" />';
	echo '<p class="description">' . __('Add the name of the custom field you want to use.', 'jwppp') . '</p>';
	echo '</td>';
	echo '</tr>';

	//TAXONOMY SELECT
	echo '<tr class="related-options">';
	echo '<th scope="row">Related taxonomy</th>';
	echo '<td>';
	echo '<select id="jwppp-taxonomy-select" name="jwppp-taxonomy-select" />';
	echo '<option name="null" value=""';
	echo ($jwppp_taxonomy_select == null) ? ' selected="selected"' : '';
	echo '>--</option>';

	$args = array('public' => true, 'hierarchical' => true);
	$taxes = get_taxonomies($args, 'objects');
	foreach($taxes as $taxonomy) {
		if($taxonomy->name != 'video-categories') {
			echo '<option id="' . $taxonomy->name . '" name="' . $taxonomy->name . '" value="' . $taxonomy->name . '"';
			echo ($jwppp_taxonomy_select == $taxonomy->name) ? ' selected="selected"' : '';
			echo '>' . $taxonomy->labels->name . '</option>';
		}
	}

	$video_cat = __('Video categories', 'jwppp');
	echo '<option id="video-categories" name="video-categories" value="video-categories"';
	echo ($jwppp_taxonomy_select == 'video-categories') ? ' selected="selected"' : '';
	echo '>' . $video_cat . '</option>';

	echo '</select>';
	echo '<p class="description">' . __('Use a taxonomy to get more specific related videos. It will be add to all post types you choosed.', 'jwppp') . '<br>';
	echo __('You can even use <strong>Video categories</strong> provided by this plugin.', 'jwppp') . '</p>';
	echo '</td>';

	echo '</table>';
	echo '<input type="hidden" name="set" value="1" />';
	echo '<input class="button button-primary" type="submit" id="submit" value="' . __('Save chages', 'jwppp') . '">';

	echo '</form>'; ?>

</div>
<!-- END - RELATED VIDEOS -->

