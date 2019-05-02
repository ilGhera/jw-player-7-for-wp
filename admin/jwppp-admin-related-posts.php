<?php
/**
 * Related posts option
 * @author ilGhera
 * @package jw-player-7-for-wp/admin
 * @version 1.6.0
 */
?>
<div name="jwppp-related" id="jwppp-related" class="jwppp-admin" style="display: none;">
    <?php

    /*Show related?*/
    $jwppp_show_related = sanitize_text_field( get_option( 'jwppp-show-related' ) );
    if (isset($_POST['set'])) {
        $jwppp_show_related = isset( $_POST['jwppp-show-related'] ) ? $_POST['jwppp-show-related'] : 0;
        update_option( 'jwppp-show-related', $jwppp_show_related );
    }

    /*Heading*/
    $jwppp_related_heading = sanitize_text_field(get_option('jwppp-related-heading'));
    if ( isset( $_POST['jwppp-related-heading'] ) ) {
        $jwppp_related_heading = sanitize_text_field( $_POST['jwppp-related-heading'] );
        update_option( 'jwppp-related-heading', $jwppp_related_heading );
    }

    /*Thumbnail*/
    $set = sanitize_text_field( get_option( 'jwppp-image' ) );
    $field_set = sanitize_text_field( get_option( 'jwppp-field' ) );

    if ( isset( $_POST['thumbnail'] ) ) {
        $set = sanitize_text_field( $_POST['thumbnail'] );
        update_option( 'jwppp-image', $set );
        if ( $set == 'custom-field' ) {
            $field_set = isset( $_POST['field'] ) ? sanitize_text_field( $_POST['field'] ) : '';
            update_option( 'jwppp-field', $field_set );
        }
    }

    /*Taxonomy select*/
    $jwppp_taxonomy_select = sanitize_text_field( get_option( 'jwppp-taxonomy-select' ) );
    if ( isset( $_POST['jwppp-taxonomy-select'] ) ) {
        $jwppp_taxonomy_select = sanitize_text_field( $_POST['jwppp-taxonomy-select'] );
        update_option( 'jwppp-taxonomy-select', $jwppp_taxonomy_select );

        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    

    /*Form post-image*/
    echo '<form id="post-image" name="post-image" method="post" action="">';
    echo '<table class="form-table">';

    /*Show related?*/
    echo '<tr>';
    echo '<th scope="row">' . esc_html( __( 'Active Related Videos option', 'jwppp' ) ) . '</th>';
    echo '<td>';
    echo '<input type="checkbox" id="jwppp-show-related" name="jwppp-show-related" value="1"';
    echo ( 1 == $jwppp_show_related ) ? ' checked="checked"' : '';
    echo '/>';
    echo '<p class="description">' . esc_html( __( 'Show Related Videos overlay as default option.', 'jwppp' ) ) . '</p>';
    echo '</td>';
    echo '</tr>';

    /*Heading*/
    echo '<tr class="related-options">';
    echo '<th scope="row">' . esc_html( __( 'Next Up tooltip', 'jwppp' ) ) . '</th>';
    echo '<td>';
    echo '<input type="text" class="regular-text" id="jwppp-related-heading" name="jwppp-related-heading" ';
    echo 'placeholder="' . esc_attr( __( 'Related Videos', 'jwppp' ) ) . '" value="' . esc_attr( $jwppp_related_heading ) . '" />';
    echo '<p class="description">' . esc_html( __( 'Title of the Next Up tooltip in Related mode, default is <strong>Related</strong>.', 'jwppp' ) ) . '</p>';
    echo '</td>';
    echo '</tr>';

    /*Thumbnail*/
    echo '<tr class="related-options">';
    echo '<th scope="row">' . esc_html( __( 'Related image', 'jwppp' ) ) . '</th>';
    echo '<td>';
    echo '<select id="thumbnail" name="thumbnail"/>';

    echo '<option id="featured-image" value="featured-image"';
    echo ($set == 'featured-image') ? 'selected="selected">' : '>';
    echo esc_html( __( 'Featured image', 'jwppp' ) ) . '</option>';

    echo '<option id="custom-field" value="custom-field"';
    echo ($set == 'custom-field') ? 'selected="selected">' : '>';
    echo esc_html( __( 'Custom field', 'jwppp' ) ) . '</option>';

    echo '</select>';
    echo '<p class="description">' . esc_html( __( 'Select how get images for related contents.', 'jwppp' ) ) . '</p>';
    echo '</td>';
    echo '</tr>';

    echo '<tr class="related-options cf-row">';
    echo '<th scope="row">' . esc_html( __('Custom field name', 'jwppp' ) ) . '</th>';
    echo '<td>';
    echo '<input type="text" class="regular-text" ';
    echo 'id="field" name="field" placeholder="' . esc_attr( __('Custom field name', 'jwppp' ) ) . '" value="' . esc_attr( $field_set ) . '" />';
    echo '<p class="description">' . esc_html( __('Add the name of the custom field you want to use.', 'jwppp' ) ) . '</p>';
    echo '</td>';
    echo '</tr>';

    /*Taxonomy select*/
    echo '<tr class="related-options">';
    echo '<th scope="row">Related taxonomy</th>';
    echo '<td>';
    echo '<select id="jwppp-taxonomy-select" name="jwppp-taxonomy-select" />';
    echo '<option name="null" value=""';
    echo ( null == $jwppp_taxonomy_select ) ? ' selected="selected"' : '';
    echo '>--</option>';

    $args = array('public' => true, 'hierarchical' => true);
    $taxes = get_taxonomies($args, 'objects');
    foreach ($taxes as $taxonomy) {
        if ( 'video-categories' !== $taxonomy->name ) {
            echo '<option id="' . esc_attr( $taxonomy->name ) . '" name="' . esc_attr( $taxonomy->name ) . '" value="' . esc_attr( $taxonomy->name ) . '"';
            echo ( $jwppp_taxonomy_select == $taxonomy->name ) ? ' selected="selected"' : '';
            echo '>' . esc_html( $taxonomy->labels->name ) . '</option>';
        }
    }

    $video_cat = __('Video categories', 'jwppp');
    echo '<option id="video-categories" name="video-categories" value="video-categories"';
    echo ( 'video-categories' === $jwppp_taxonomy_select ) ? ' selected="selected"' : '';
    echo '>' . esc_html( $video_cat ) . '</option>';

    echo '</select>';
    echo '<p class="description">' . esc_html( __( 'Use a taxonomy to get more specific related videos. It will be add to all post types you choosed.', 'jwppp' ) ) . '<br>';
    echo esc_html( __( 'You can even use <strong>Video categories</strong> provided by this plugin.', 'jwppp' ) ) . '</p>';
    echo '</td>';

    echo '</table>';
    echo '<input type="hidden" name="set" value="1" />';
    echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_html( __( 'Save chages', 'jwppp' ) ) . '">';

    echo '</form>'; ?>

</div>
