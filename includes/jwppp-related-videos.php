<?php 
/**
 * Related videos feed
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @version 1.6.0
 * @return string   the chapters set by the publisher in a WEBVTT file
 */

/*Avoid direct access*/
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * Add new feed
 */
function jwppp_related_feed_init() {
   add_feed('related-videos', 'jwppp_related_feed');
}
add_action('init', 'jwppp_related_feed_init');


/**
 * Create the related videos feedfeed
 */
function jwppp_related_feed() {

	header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);

	$more = 1;

	echo "<rss version=\"2.0\" xmlns:media=\"https://search.yahoo.com/mrss/\">\n";
	echo "<channel>\n";	

		if(have_posts()) :
			while( have_posts()) : the_post();
				if(get_option('jwppp-image') == 'featured-image') {
					//Get the featured image url
					$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );
					$url_image = isset($thumb[0]) ? sanitize_url($thumb[0]) : '';
				} else {
					//Get the custom field value
					$key = get_option('jwppp-field');
					$url_image = get_post_meta(get_the_ID(), $key, true);
				}

				$output = "<item>\n";
					$output .= "<title>" . esc_html(get_the_title_rss()) . "</title>\n";
					$output .= "<link>" . esc_url(get_the_permalink()) . "</link>\n";
					$output .= "<media:thumbnail url=\"" . esc_url($url_image) ."\"/>\n";
				$output .= "</item>\n";

				echo $output;
			endwhile; 
		endif;
	  
	echo "</channel>\n";
	echo "</rss>";
}