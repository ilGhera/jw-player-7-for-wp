<?php
/**
 * Related posts option
 * @author ilGhera
 * @package jw-player-7-for-wp/admin
 * @version 2.0.0
 */
?>
<div name="jwppp-related" id="jwppp-related" class="jwppp-admin" style="display: none;">
    <?php
    
    /*Form post-image*/
    echo '<form id="post-image" name="post-image" method="post" action="">';
    echo '<table class="form-table">';

    /*Show related?*/
    echo '<tr>';
    echo '<th scope="row">' . esc_html( __( 'Active Related Posts option', 'jwppp' ) ) . '</th>';
    echo '<td>';
    echo '<input type="checkbox" id="jwppp-show-related" name="jwppp-show-related" value=""/>';
    echo '<p class="description">' . esc_html( __( 'Show Related Posts overlay as default option.', 'jwppp' ) ) . '</p>';
    echo '</td>';
    echo '</tr>';

    /*Heading*/
    echo '<tr class="related-options">';
    echo '<th scope="row">' . esc_html( __( 'Next Up tooltip', 'jwppp' ) ) . '</th>';
    echo '<td>';
    echo '<input type="text" class="regular-text" id="jwppp-related-heading" name="jwppp-related-heading" ';
    echo 'placeholder="' . esc_attr( __( 'Related Posts', 'jwppp' ) ) . '" disabled="disabled" />';
    echo '<p class="description">' . esc_html( __( 'Title of the Next Up tooltip in Related mode, default is Related.', 'jwppp' ) ) . '</p>';
    go_premium();
    echo '</td>';
    echo '</tr>';

    /*Thumbnail*/
    echo '<tr class="related-options">';
    echo '<th scope="row">' . esc_html( __( 'Related image', 'jwppp' ) ) . '</th>';
    echo '<td>';
    echo '<select id="thumbnail" name="thumbnail" disabled="disabled" />';

    echo '<option id="featured-image" value="featured-image" selected="selected">';
    echo esc_html( __( 'Featured image', 'jwppp' ) ) . '</option>';
    echo '</select>';
    echo '<p class="description">' . esc_html( __( 'Select how get images for related contents.', 'jwppp' ) ) . '</p>';
    go_premium( __( 'Upgrade and use custom fields to get the post image', 'jwppp' ) );
    echo '</td>';
    echo '</tr>';

    /*Taxonomy select*/
    echo '<tr class="related-options">';
    echo '<th scope="row">Related taxonomy</th>';
    echo '<td>';
    echo '<select id="jwppp-taxonomy-select" name="jwppp-taxonomy-select" disabled="disabled"/>';
    echo '<option>' . esc_attr( __( 'Categories', 'jwppp' ) ) . '</option>';
    echo '</select>';
    echo '<p class="description">' . esc_html( __( 'Select an existing taxonomy or create the new "Video categories" to get more specific related posts.', 'jwppp' ) ) . '<br>';
    go_premium();
    echo '</td>';

    echo '</table>';
    echo '<input type="hidden" name="set" value="1" />';
    echo '<input class="button button-primary" type="submit" id="submit" value="' . esc_html( __( 'Save chages', 'jwppp' ) ) . '" disabled="disabled">';

    echo '</form>'; ?>

</div>
