<?php
/*
*
* SINGLE VIDEO BOX FOR JW PLAYER FOR WORDPRESS PREMIUM
*
*/


$dashboard_player = is_dashboard_player();
$output = null;
$output .= '<table class="widefat jwppp-' . esc_attr($number) . '" style="margin: 0.4rem 0;">';
$output .= '<tbody class="ui-sortable">';

$output .= '<tr class="row">';
$output .= '<td class="order">' . esc_attr($number) . '</td>';
$output .= '<td class="jwppp-input-wrap" style="width: 100%; padding-bottom: 1rem;">';
wp_nonce_field( 'jwppp_save_single_video_data', 'jwppp-meta-box-nonce-' . $number );

/*Single video details*/
$video_url = get_post_meta($post_id, '_jwppp-video-url-' . $number, true );

/*Is the video self hosted?*/
$sh_video = strrpos($video_url, 'http') === 0 ? true : false;

$sources_number = get_post_meta($post_id, '_jwppp-sources-number-' . $number, true);
$main_source_label = get_post_meta($post_id, '_jwppp-' . $number . '-main-source-label', true );

if($dashboard_player) {

	$output .= '<ul class="jwppp-video-toggles ' . $number . '">';
		$output .= '<li data-video-type="choose"' . (!$sh_video ? ' class="active"' : '') . '>' . esc_html(__('Choose', 'jwppp')) . '</li>';
		$output .= '<li data-video-type="add-url"' . ($sh_video ? ' class="active"' : '') . '>' . esc_html(__('Add url', 'jwppp')) . '</li>';
		$output .= '<div class="clear"></div>';
	$output .= '</ul>';

	/*Select*/
	$output .= '<div class="jwppp-toggle-content ' . esc_attr($number) . ' choose' . (!$sh_video ? ' active' : '') . '">';
		$output .= '<p>';
			$output .= '<select id="_jwppp-video-url-' . esc_attr($number) . '" name="_jwppp-video-url-' . esc_attr($number) . '" class="select2" style="margin-right:1rem;">';

				$output .= '<option name="" value="">' . esc_html('Select a video', 'jwppp') . '</option>';

				$videos = get_videos_from_dashboard();

				if($videos) {
					foreach ($videos as $video) {
						$output .= '<option name="' . $video->mediaid . '" data-mediaid="' . $video->mediaid . '" value="' . $video->mediaid . '"' . ($video_url === $video->mediaid ? ' selected="selecterd"' : '') . '><img src="' . $video->image . '">' . $video->title . '</option>';
					}
				}

			$output .= '</select>';
		$output .= '</p>';		
	$output .= '</div>';	

} 

/*Input*/
$output .= $dashboard_player ? '<div class="jwppp-toggle-content ' . esc_attr($number) . ' add-url' . ($sh_video ? ' active' : '') . '">' : '';

	if(!$dashboard_player) {
		$output .= '<label for="_jwppp-video-url-' . esc_attr($number) . '">';
		$output .= '<strong>' . esc_html(__( 'Media URL', 'jwppp' )) . '</strong>';
		$output .= '<a class="question-mark" href="http://www.ilghera.com/support/topic/media-formats-supported/" title="More informations" target="_blank"><img class="question-mark" src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/question-mark.png" /></a></th>';
		$output .= '</label> ';
	}

	$output .= '<p>';
		$output .= '<input type="text" id="_jwppp-video-url-' . esc_attr($number) . '" name="_jwppp-video-url-' . esc_attr($number) . '" style="margin-right:1rem;" placeholder="' . esc_html(__('Add here your media url', 'jwppp')) . '" ';
		$output .= ($video_url !== '1') ? 'value="' . esc_attr( $video_url ) . '" ' : 'value="" ';
		$output .= 'size="60" />';

		// if($sh_video) {
			$output .= '<input type="text" name="_jwppp-' . esc_attr($number) . '-main-source-label" id ="_jwppp-' . esc_attr($number) . '-main-source-label" class="source-label-' . esc_attr($number) . '" style="margin-right:1rem; display: none;';
			$output .= '" value="' . esc_html($main_source_label) . '" placeholder="' . esc_html(__('Label (HD, 720p, 360p)', 'jwppp')) . '" size="30" />';
		// }
	$output .= '</p>';
$output .= $dashboard_player ? '</div>' : '';

if(get_option('jwppp-position') === 'custom') {
	$output .= '<code style="display:inline-block;margin:0.1rem 0.5rem 0 0;color:#888;">[jwp-video n="' . esc_attr($number) . '"]</code>';
}

$more_options_button = '<a class="button more-options-' . esc_attr($number) . '">' . esc_html(__('More options', 'jwppp')) . '</a>';
if(!$dashboard_player || $sh_video) {
	$output .= $more_options_button;
}
?>

<!-- BASIC SINGLE VIDEO OPTIONS -->
<script>
(function($) {
	$(document).ready(function() {
		var post_id = <?php echo $post_id; ?>;
		var number = <?php echo $number; ?>;
		var $url = $('#_jwppp-video-url-' + number).val();
		var $ext = $url.split('.').pop();
		var $arr = ['xml', 'feed', 'php', 'rss'];
		var $more_options_button = '<?php echo $more_options_button; ?>';

		/*Video toggles*/
		$(document).on('click', '.jwppp-video-toggles.' + number + ' li', function(){
			$('.jwppp-video-toggles.' + number + ' li').removeClass('active');
			$(this).addClass('active');

			var video_type = $(this).data('video-type');

			$('.jwppp-toggle-content.' + number).removeClass('active');
			$('.jwppp-toggle-content.' + number + '.' + video_type).addClass('active');

			/*Delete the input field value on toggle change*/
			$('input#_jwppp-video-url-' + number).val('');
			$('select#_jwppp-video-url-' + number).val('').trigger('change');


			console.log('Val: ' + $('select#_jwppp-video-url-' + number).val());

			/*With cloud player and self hosted sources, all the tools are shown*/
			if(video_type === 'add-url') {

				/*Add the More options button*/
				$('.jwppp-' + number + ' .jwppp-input-wrap').append($more_options_button);

				var data = {
					'action': 'self-media-source',
					'confirmation': 1,
					'post-id': post_id,
					'number': number
				}
				$.post(ajaxurl, data, function(response){
					$(response).appendTo($('.jwppp-' + number + ' .jwppp-input-wrap'));
					sh_video_script(number);
				})
			} else {
				$('.button.more-options-' + number).remove();
				$('.jwppp-more-options-' + number).remove();
			}

		})

		/*Changwe playlist-how-to*/
		var tot = $('.jwppp-input-wrap:visible').length;
		if(tot > 1) {
			$('.playlist-how-to').show('slow');
			
			var string = [];
			$('.order:visible').each(function(i, el) {
				string.push($(el).html());	
			})
			// $('.playlist-how-to code').html('[jwp-video n="' + string + '"]');
		} else {
			$('.playlist-how-to').hide();
		}

		$('.jwppp-more-options-' + number).hide();

		if($.inArray($ext, $arr)>-1) {
			$('.more-options-' + number).hide();
		};

		/*Media url change*/
		$(document).on('change','#_jwppp-video-url-' + number, function() {
			var $url = $(this).val();

			/*Save new url*/
			// var data = {
			// 	'action': 'new-media-source',
			// 	'post-id': post_id,
			// 	'number': number,
			// 	'url': $url
			// }
			// $.post(ajaxurl, data, function(response){
			// 	console.log(response);
			// })
			
			/*Getting the extension for old type playlist*/
			var $ext = $url.split('.').pop();
			var $arr = ['xml', 'feed', 'php', 'rss'];
			if($.inArray($ext, $arr)>-1) {
				$('.more-options-' + number).hide();
				$('.jwppp-more-options-' + number).hide();
			} else {
				$('.more-options-' + number).show();	
			}
		});

		/*Select value is required in input too*/
		$(document).on('change', 'select#_jwppp-video-url-' + number, function(){			
			$('input#_jwppp-video-url-' + number).val($(this).val());
		})
	});
})(jQuery);
</script>

<?php

if(!$dashboard_player || $sh_video) {

	/*Self hosted video tools*/
	$output .= sh_video_tools($post_id, $number);
	?>
	<script>
		jQuery(document).ready(function($){
			var number = <?php echo $number; ?>;
			sh_video_script(number);
		})
	</script>
	<?php
}

$output .= '</div>';
$output .= '</td>';

if($number<2) {
	$output .= '<td class="add-video"><a class="jwppp-add"><img src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/add-video.png" /></a></td>';
} else {
	$output .= '<td class="remove-video"><a class="jwppp-remove" data-numb="' . esc_attr($number) . '"><img src="' . esc_url(plugin_dir_url(__DIR__)) . 'images/remove-video.png" /></a></td>';
}
$output .= '</tr>';
$output .= '</tbody>';
$output .= '</table>';

echo $output;