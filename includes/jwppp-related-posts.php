<?php
/**
 * Related posts feed
 * @author ilGhera
 * @package jw-player-7-for-wp/includes
 * @version 2.0.0
 */

/*No direct access*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Add new feed
 */
function jwppp_related_feed_init() {

   add_feed( 'related-videos', 'jwppp_related_feed' );

}
add_action( 'init', 'jwppp_related_feed_init' );


/**
 * Create feed
 */
function jwppp_related_feed() {


	header( 'Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option( 'blog_charset' ), true );

	echo "<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss/\">\n";

		echo "<channel>\n";	

		if( have_posts() ) :
			while( have_posts() ) : the_post();

				if( get_option( 'jwppp-image' ) == 'featured-image' ) {

					/*Get the featured image url*/
					$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
					$url_image = $thumb[0];

				} else {

					/*Get the custom field value*/
					$key = get_option( 'jwppp-field' );
					$url_image = get_post_meta( get_the_ID(), $key, true );

				}

				echo "<item>\n";

					echo "<title>" . esc_html( get_the_title_rss() ) . "</title>\n";

					echo "<link>" . esc_url( get_the_permalink() ) . "</link>\n";

					echo "<media:thumbnail url=\"" . esc_url( $url_image ) ."\"/>\n";

				echo "</item>\n";

			endwhile; 
		endif;
		  
		echo "</channel>\n";

	echo "</rss>";

}
