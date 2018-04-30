<?php //JW PLAYER - PREMIUM PLUGIN, RELATED FEED GENERATOR

//HEY, WHAT ARE UOU DOING?
if ( !defined( 'ABSPATH' ) ) exit;


//ADD NEW FEED
add_action('init', 'jwppp_related_feed_init');

function jwppp_related_feed_init() {

   add_feed('related-videos', 'jwppp_related_feed');

}

//CREATE FEED
function jwppp_related_feed() {


header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);

$more = 1;

echo "<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss/\">\n";

echo "<channel>\n";	

while( have_posts()) : the_post();

	if(get_option('jwppp-image') == 'featured-image') {

		//Get the featured image url
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
		$url_image = $thumb[0];

	} else {

		//Get the custom field value
		$key = get_option('jwppp-field');
		$url_image = get_post_meta(get_the_ID(), $key, true);

	}

	$output = "<item>\n";

		$output .= "<title>" . get_the_title_rss() . "</title>\n";

		$output .= "<link>" . get_the_permalink() . "</link>\n";

		$output .= "<media:thumbnail url=\"" . $url_image ."\"/>\n";

	$output .= "</item>\n";

	echo $output;

endwhile; 
  
echo "</channel>\n";

echo "</rss>";


}