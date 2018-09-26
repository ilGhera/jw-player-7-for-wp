<?php  
/**
 * Related videos (posts) options
 * @author ilGhera
 * @package jw-player-7-for-wp/admin
 * @version 1.6.0
 */
?>
<div name="jwppp-related" id="jwppp-related" class="jwppp-admin" style="display: none;">
	<?php

	/*Show related?*/
	$jwppp_show_related = sanitize_text_field(get_option('jwppp-show-related'));
	if(isset($_POST['set']))  {
		$jwppp_show_related = isset($_POST['jwppp-show-related']) ? sanitize_text_field($_POST['jwppp-show-related']) : 0;
		update_option('jwppp-show-related', $jwppp_show_related);
	}

	/*Heading*/
	$jwppp_related_heading = sanitize_text_field(get_option('jwppp-related-heading'));
	if(isset($_POST['jwppp-related-heading'])) {
		$jwppp_related_heading = sanitize_text_field($_POST['jwppp-related-heading']);
		update_option('jwppp-related-heading', $jwppp_related_heading);
	}

	/*Thumbnail*/
	$set = sanitize_text_field(get_option('jwppp-image'));
	$field_set = sanitize_text_field(get_option('jwppp-field'));

	if(isset($_POST['thumbnail'])) {
		$set = sanitize_text_field($_POST['thumbnail']);
		update_option('jwppp-image', $set);
		if($set === 'custom-field') {
			$field_set = sanitize_text_field($_POST['field']);
			update_option('jwppp-field', $field_set );
		}
	}

	/*Taxonomy select*/
	$jwppp_taxonomy_select = sanitize_text_field(get_option('jwppp-taxonomy-select'));
	if(isset($_POST['jwppp-taxonomy-select'])) {
		$jwppp_taxonomy_select = sanitize_text_field($_POST['jwppp-taxonomy-select']);
		update_option('jwppp-taxonomy-select', $jwppp_taxonomy_select);

		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}

	/*Define the allowed tags for wp_kses*/
	$allowed_tags = array(
		'u' => [],
		'strong' => [],
		'a' => [
			'href'   => [],
			'target' => []
		]
	);

	/*Form post-image*/
	echo '<form id="post-image" name="post-image" method="post" action="">';
	echo '<table class="form-table">';

	/*Show related?*/
	echo '<tr>';
	echo '<th scope="row">' . __('Active Related Posts option', 'jwppp') . '</th>';
	echo '<td>';
	echo '<input type="checkbox" id="jwppp-show-related" name="jwppp-show-related" value="1"';
	echo ($jwppp_show_related === '1') ? ' checked="checked"' : '';
	echo '/>';
	echo '<p class="description">' . esc_html(__('Show Related Video Posts overlay as default option.', 'jwppp')) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Heading*/
	echo '<tr class="related-options">';
	echo '<th scope="row">' . esc_html(__('Next Up tooltip', 'jwppp')) . '</th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" id="jwppp-related-heading" name="jwppp-related-heading" ';
	echo 'placeholder="' . esc_html(__('Related Videos', 'jwppp')) . '" value="' . esc_html($jwppp_related_heading) . '" />';
	echo '<p class="description">' . wp_kses(__('Title of the Next Up tooltip in Related mode, default is <strong>Related</strong>.', 'jwppp'), $allowed_tags) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Thumbnail*/
	echo '<tr class="related-options">';
	echo '<th scope="row">' . esc_html(__('Related image', 'jwppp')) . '</th>';
	echo '<td>';
	echo '<select id="thumbnail" name="thumbnail"/>';

	echo '<option id="featured-image" value="featured-image"';
	echo ($set === 'featured-image') ? 'selected="selected">' : '>';
	echo esc_html(__('Featured image', 'jwppp')) . '</option>';

	echo '<option id="custom-field" value="custom-field"';
	echo ($set === 'custom-field') ? 'selected="selected">' : '>';
	echo esc_html(__('Custom field', 'jwppp')) . '</option>';

	echo '</select>';
	echo '<p class="description">' . esc_html(__('Select how get images for related contents.', 'jwppp')) . '</p>';
	echo '</td>';
	echo '</tr>';

	echo '<tr class="related-options cf-row">';
	echo '<th scope="row">' . esc_html(__('Custom field name', 'jwppp')) . '</th>';
	echo '<td>';
	echo '<input type="text" class="regular-text" ';
	echo 'id="field" name="field" placeholder="' . esc_html(__('Custom field name', 'jwppp')) . '" value="' . esc_html($field_set) . '" />';
	echo '<p class="description">' . esc_html(__('Add the name of the custom field you want to use.', 'jwppp')) . '</p>';
	echo '</td>';
	echo '</tr>';

	/*Taxonomy select*/
	echo '<tr class="related-options">';
	echo '<th scope="row">Related taxonomy</th>';
	echo '<td>';
	echo '<select id="jwppp-taxonomy-select" name="jwppp-taxonomy-select" />';
	echo '<option name="null" value=""';
	echo (!$jwppp_taxonomy_select) ? ' selected="selected"' : '';
	echo '>--</option>';

	$args = array('public' => true, 'hierarchical' => true);
	$taxes = get_taxonomies($args, 'objects');
	foreach($taxes as $taxonomy) {
		if($taxonomy->name !== 'video-categories') {
			echo '<option id="' . esc_html($taxonomy->name) . '" name="' . esc_html($taxonomy->name) . '" value="' . esc_html($taxonomy->name) . '"';
			echo ($jwppp_taxonomy_select === $taxonomy->name) ? ' selected="selected"' : '';
			echo '>' . esc_html($taxonomy->labels->name) . '</option>';
		}
	}

	$video_cat = esc_html(__('Video categories', 'jwppp'));
	echo '<option id="video-categories" name="video-categories" value="video-categories"';
	echo ($jwppp_taxonomy_select === 'video-categories') ? ' selected="selected"' : '';
	echo '>' . esc_html($video_cat) . '</option>';

	echo '</select>';
	echo '<p class="description">' . esc_html(__('Use a taxonomy to get more specific related video posts. It will be add to all post types you choosed.', 'jwppp')) . '<br>';
	echo wp_kses(__('You can even use <strong>Video categories</strong> provided by this plugin.', 'jwppp'), $allowed_tags) . '</p>';
	echo '</td>';

	echo '</table>';
	echo '<input type="hidden" name="set" value="1" />';
	echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_html(__('Save chages', 'jwppp')) . '">';

	echo '</form>'; 
	?>
</div>
