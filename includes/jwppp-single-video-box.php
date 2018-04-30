<?php
/*
*
* SINGLE VIDEO BOX FOR JW PLAYER FOR WORDPRESS PREMIUM
*
*/

$output .= '<table class="widefat jwppp-' . $number . '" style="margin: 0.4rem 0;">';
$output .= '<tbody class="ui-sortable">';

$output .= '<tr class="row">';
$output .= '<td class="order">' . $number . '</td>';
$output .= '<td class="jwppp-input-wrap" style="width: 100%;">';
wp_nonce_field( 'jwppp_save_single_video_data', 'jwppp-meta-box-nonce-' . $number );

$video_url 					   = get_post_meta($post_id, '_jwppp-video-url-' . $number, true );
$sources_number			       = get_post_meta($post_id, '_jwppp-sources-number-' . $number, true);
$main_source_label 			   = get_post_meta($post_id, '_jwppp-' . $number . '-main-source-label', true );
$video_title 				   = get_post_meta($post_id, '_jwppp-video-title-' . $number, true);
$video_description 			   = get_post_meta($post_id, '_jwppp-video-description-' . $number, true);
$video_image 				   = get_post_meta($post_id, '_jwppp-video-image-' . $number, true);
$add_chapters				   = get_post_meta($post_id, '_jwppp-add-chapters-' . $number, true);
$jwppp_chapters_subtitles      = get_post_meta($post_id, '_jwppp-chapters-subtitles-' . $number, true);
$jwppp_subtitles_method        = get_post_meta($post_id, '_jwppp-subtitles-method-' . $number, true);
$jwppp_activate_media_type     = get_post_meta($post_id, '_jwppp-activate-media-type-' . $number, true);
$jwppp_subtitles_load_default  = get_post_meta($post_id, '_jwppp-subtitles-load-default-' . $number, true);
$jwppp_subtitles_write_default = get_post_meta($post_id, '_jwppp-subtitles-write-default-' . $number, true);
$jwppp_media_type 			   = get_post_meta($post_id, '_jwppp-media-type-' . $number, true);
$jwppp_autoplay 			   = get_post_meta($post_id, '_jwppp-autoplay-' . $number, true);
$jwppp_mute 				   = get_post_meta($post_id, '_jwppp-mute-' . $number, true);
$jwppp_repeat 				   = get_post_meta($post_id, '_jwppp-repeat-' . $number, true);
$jwppp_share_video 			   = sanitize_text_field(get_option('jwppp-active-share'));
$jwppp_embed_video			   = sanitize_text_field(get_option('jwppp-embed-video'));
$jwppp_single_embed 		   = (isset($_POST['_jwppp-single-embed-' . $number])) ? $_POST['_jwppp-single-embed-' . $number] : get_post_meta($post_id, '_jwppp-single-embed-' . $number, true);
$jwppp_download_video 		   = get_post_meta($post_id, '_jwppp-download-video-' . $number, true);

$output .= '<label for="_jwppp-video-url-' . $number . '">';
$output .= '<strong>' . __( 'Media URL', 'jwppp' ) . '</strong>';
$output .= '<a class="question-mark" href="http://www.ilghera.com/support/topic/media-formats-supported/" title="More informations" target="_blank"><img class="question-mark" src="' . plugins_url('jw-player-7-for-wp-premium') . '/images/question-mark.png" /></a></th>';
$output .= '</label> ';
$output .= '<p>';
$output .= '<input type="text" id="_jwppp-video-url-' . $number . '" name="_jwppp-video-url-' . $number . '" style="margin-right:1rem;" placeholder="' . __('Video (YouTube or self-hosted), Audio or Playlist', 'jwppp') . '" ';
$output .= ($video_url != 1) ? 'value="' . esc_attr( $video_url ) . '" ' : 'value="" ';
$output .= 'size="60" />';
$output .= '<input type="text" name="_jwppp-' . $number . '-main-source-label" id ="_jwppp-' . $number . '-main-source-label" class="source-label-' . $number . '" style="margin-right:1rem;';
$output .= '" value="' . $main_source_label . '" placeholder="' . __('Label (HD, 720p, 360p)', 'jwppp') . '" size="30" />';
$output .= '</p>';

$output .= '<a class="button more-options-' . $number . '">' . __('More options', 'jwppp') . '</a>';
if(get_option('jwppp-position') == 'custom') {
	$output .= '<code style="display:inline-block;margin:0.1rem 0.5rem 0;color:#888;">[jwp-video n="' . $number . '"]</code>';
}

?>

<!-- SHOW MORE OPTIONS ONLY IF WE HAVE A SINGLE VIDEO URL -->
<script>
(function($) {
	$(document).ready(function() {
		var number = <?php echo $number; ?>;
		var $url = $('#_jwppp-video-url-' + number).val();
		var $ext = $url.split('.').pop();
		var $arr = ['xml', 'feed', 'php', 'rss'];

		//CHANGE PLAYLIST-HOW-TO
		var tot = $('.jwppp-input-wrap:visible').length;
		if(tot > 1) {
			$('.playlist-how-to').show('slow');
			
			var string = [];
			$('.order:visible').each(function(i, el) {
				string.push($(el).html());	
			})
			$('.playlist-how-to code').html('[jwp-video n="' + string + '"]');
		} else {
			$('.playlist-how-to').hide();
		}

		$('.jwppp-more-options-' + number).hide();

		if($.inArray($ext, $arr)>-1) {
			$('.more-options-' + number).hide();
		};

		$('#_jwppp-video-url-' + number).on('change',function() {
			var $url = $('#_jwppp-video-url-' + number).val();
			var $ext = $url.split('.').pop();
			var $arr = ['xml', 'feed', 'php', 'rss'];
			if($.inArray($ext, $arr)>-1) {
				$('.more-options-' + number).hide();
				$('.jwppp-more-options-' + number).hide();
			} else {
				$('.more-options-' + number).show();	
			}
		});

		//SHOW SOURCES LABELS IF THEY ARE MORE THAN TWO
		if($('#_jwppp-sources-number-' + number).val() > 1) {
			$('.source-label-' + number).show('slow');
		} else {
			$('.source-label-' + number).hide();
		}

		$('.more-options-' + number).click(function() {
			$('.jwppp-more-options-' + number).toggle('fast');
			// $('.more-options').text('Less options');
			$(this).text(function(i, text) {
				return text == 'More options' ? 'Less options' : 'More options';
			});
		});
		
		//MEDIA TYPE
		if($('#_jwppp-activate-media-type-' + number).prop('checked') == false) {
			$('#_jwppp-media-type-' + number).hide();
		} else {
			$('#_jwppp-media-type-' + number).show();
		}
		$('#_jwppp-activate-media-type-' + number).on('change', function(){
			if($(this).prop('checked') == true) {
				$('#_jwppp-media-type-' + number).show();
			} else {
				$('#_jwppp-media-type-' + number).hide();
			}
		})

		//CHAPTERS
		if($('#_jwppp-add-chapters-' + number).prop('checked') == false) {

			$('#_jwppp-chapters-subtitles-' + number).hide();
			$('#_jwppp-chapters-number-' + number).hide();			
			$('#_jwppp-subtitles-method-' + number).hide();
			$('li#video-' + number + '-chapter').hide();
			$('li#video-' + number + '-subtitle').hide();			

		} else {

			$('#_jwppp-chapters-subtitles-' + number).show();
			$('#_jwppp-chapters-number-' + number).show();
			$('#_jwppp-subtitles-method-' + number).hide();
			$('label[for="_jwppp-subtitles-write-default-' + number + '"]').hide();
			$('label[for="_jwppp-subtitles-load-default-' + number + '"]').hide();

			//IF SUBTITLES ARE ACTIVATED, SELECT MANUAL/ LOAD IS SHOWN
			if($('#_jwppp-chapters-subtitles-' + number).val() == 'subtitles') {
				$('#_jwppp-subtitles-method-' + number).show();
				$('label[for="_jwppp-subtitles-write-default-' + number + '"]').show();
				$('label[for="_jwppp-subtitles-load-default-' + number + '"]').show();
			}

			//IF SUBTITLE METHOD IS SET TO "LOAD", THE ELEMENTS CHANGE
			var sub_method = $('#_jwppp-subtitles-method-' + number).val();
			if(sub_method == 'load') {
				$('.load-subtitles-' + number).show();
				$('.chapters-subtitles-' + number).hide();
			} else {
				$('.load-subtitles-' + number).hide();
				$('.chapters-subtitles-' + number).show();
			}

			var n_chapters = $('#_jwppp-chapters-number-' + number).val();
			$('li#video-' + number + '-chapter').hide();
			$('li#video-' + number + '-chapter').each(function(i,el) {
				var num = $(el).data('number');
				if(num <= n_chapters) {
					$(el).show();
				}
			})

			$('li#video-' + number + '-subtitle').hide();
			$('li#video-' + number + '-subtitle').each(function(i,el) {
				var numb = $(el).data('number');
				if(numb <= n_chapters) {
					$(el).show();
				}
			})
		}

		//HIDE/ SHOW CONTENTS BASED ON THE MAIN FLAG
		$('#_jwppp-add-chapters-' + number).on('change',function() {
			if($('#_jwppp-add-chapters-' + number).prop('checked')) {
				$('span.add-chapters.' + number).text('<?php echo __('Add', 'jwppp'); ?>');
				$('#_jwppp-chapters-subtitles-' + number).show();
				$('#_jwppp-chapters-number-' + number).show();
				

				//IF SUBTITLES ARE ACTIVATED, SELECT MANUAL/ LOAD IS SHOWN
				if($('#_jwppp-chapters-subtitles-' + number).val() == 'subtitles') {
					$('#_jwppp-subtitles-method-' + number).show();
				}

				var sub_method = $('#_jwppp-subtitles-method-' + number).val();
				if(sub_method == 'load') {
					$('.load-subtitles-' + number).show();
					$('.chapters-subtitles-' + number).hide();
				} else {
					$('.load-subtitles-' + number).hide();
					$('.chapters-subtitles-' + number).show();
				}

				var n_chapters = $('#_jwppp-chapters-number-' + number).val();
				$('li#video-' + number + '-chapter').each(function(i,el) {
					var num = $(el).data('number');
					if(num <= n_chapters) {
						$(el).show();
					}
				})

				// $('li#video-' + number + '-subtitle').hide();
				$('li#video-' + number + '-subtitle').each(function(i,el) {
					var numb = $(el).data('number');
					if(numb <= n_chapters) {
						$(el).show();
					}
				})

			} else {
				$('span.add-chapters.' + number).text('<?php echo __('Add Chapters, Subtitles or Preview Thumbnails', 'jwppp'); ?>');
				$('#_jwppp-chapters-subtitles-' + number).hide();
				$('#_jwppp-chapters-number-' + number).hide();
				$('li#video-' + number + '-chapter').hide();
				$('#_jwppp-subtitles-method-' + number).hide();
				$('.load-subtitles-' + number).hide();

			}
		});


		//SET DIFFERENT PLACEHOLDER FOR DIFFERENTS ELEMENT TYPES 
		function placeholder() {
			var selector = $('#_jwppp-chapters-subtitles-' + number);
			if($(selector).val() == 'thumbnails') {
				var placeholder = '<?php echo __("Thumbnail url", "jwppp"); ?>';					
			} else if($(selector).val() == 'subtitles') {
				var placeholder = '<?php echo __("Subtitle", "jwppp"); ?>';
			} else {
				var placeholder = '<?php echo __("Chapter title", "jwppp"); ?>';
			}
			$('ul.chapters-subtitles-' + number + ' li input[type=text]').attr('placeholder', placeholder);
		}


		//CHANGE CONTENTS BASED ON THE TOOL SELECTED
		$('#_jwppp-chapters-subtitles-' + number).on('change', function(){
			
			placeholder();

			if($(this).val() == 'subtitles') {
				$('#_jwppp-subtitles-method-' + number).show();
				$('label[for="_jwppp-subtitles-write-default-' + number + '"]').show();
				$('label[for="_jwppp-subtitles-load-default-' + number + '"]').show();

				var sub_method = $('#_jwppp-subtitles-method-' + number).val();
				if(sub_method == 'load') {
					$('.load-subtitles-' + number).show();
					$('.chapters-subtitles-' + number).hide();
				} else {
					$('.load-subtitles-' + number).hide();
					$('.chapters-subtitles-' + number).show();
				}
			} else {
				$('#_jwppp-subtitles-method-' + number).hide();
				$('.load-subtitles-' + number).hide();
				$('label[for="_jwppp-subtitles-write-default-' + number + '"]').hide();
				$('label[for="_jwppp-subtitles-load-default-' + number + '"]').hide();
				$('.chapters-subtitles-' + number).show();
			}
		})


		//CHANGE ELEMENT TYPE BASED ON SUBTITLES METHOS
		$('#_jwppp-subtitles-method-' + number).on('change', function(){
			if($(this).val() == 'load') {
				$('.load-subtitles-' + number).show();
				$('.chapters-subtitles-' + number).hide();
			} else {
				$('.load-subtitles-' + number).hide();
				$('.chapters-subtitles-' + number).show();
			}
		})
		
		//CHANGE THE ELEMENTS NUMBER BASE ON THE NUMBER TOOL
		$('#_jwppp-sources-number-' + number).on('change',function() {
			var n_sources 		   = $(this).val();
			var n_current_sources  = $('li#video-' + number + '-source').length;

			//SHOW LABELs IF ALTERNATIVES SOURCE EXIST
			if(n_sources > 1) {
				$('.source-label-' + number).show('slow');
			} else {
				$('.source-label-' + number).hide();
			}

			if(n_sources > n_current_sources) {
				for(n=n_current_sources+1; n == n_sources; n++) {
					var element = '<li id="video-' + number + '-source" data-number="' + n + '">' +	
								  '<input type="text" style="margin-right:1rem;" name="_jwppp-' + number + '-source-' + n + '-url" value="" placeholder="<?php echo __('Source url', 'jwppp'); ?>" size="60" />' +
	    						  '<input type="text" name="_jwppp-' + number + '-source-' + n + '-label" class="source-label-' + number + '" style="margin-right:1rem;" value="" placeholder="<?php echo __('Label (HD, 720p, 360p)', 'jwppp'); ?>" size="30" />' +
	    						  '</li>';

					$('ul.sources-' + number).append(element);
				}
			}

			$('li#video-' + number + '-source').each(function(i,el) {
				var num = $(el).data('number');
				if(num > n_sources) {
					$(el).hide();
				} else {
					$(el).show('slow');
				}
			})
		})

		//CHAPTER NUMBER
		$('#_jwppp-chapters-number-' + number).on('change',function() {

			var n_chapters 			= $(this).val();
			var n_current 			= $('li#video-' + number + '-chapter').length;
			var n_current_subtitles = $('li#video-' + number + '-subtitle').length;

			if(n_chapters > n_current) {
				for(n=n_current+1; n == n_chapters; n++) {
					var element = '<li id="video-' + number + '-chapter" data-number="' + n + '">' +
							'<input type="text" style="margin-right:1rem;" name="_jwppp-' + number + '-chapter-' + n + '-title"' +
							'placeholder=""' +
							'size="60" />    ' +
							'Start <input type="number" name="_jwppp-' + number + '-chapter-' + n + '-start" style="margin-right:1rem;" min="0" step="1" class="small-text" />    ' +
							'End <input type="number" name="_jwppp-' + number + '-chapter-' + n + '-end" style="margin-right:0.5rem;" min="1" step="1" class="small-text" />' +
							'in seconds' +
						   '</li>';
					$('ul.chapters-subtitles-' + number).append(element);
					placeholder();
				}
			}

			if(n_chapters > n_current_subtitles) {
				for(n=n_current_subtitles+1; n == n_chapters; n++) {
					var element = '<li id="video-' + number + '-subtitle" data-number="' + n + '">' +
							'<input type="text" style="margin-right:1rem;" name="_jwppp-' + number + '-subtitle-' + n + '-url"' +
							'placeholder="<?php echo  __("Subtitles file url (VTT, SRT, DFXP)", "jwppp"); ?>"' +
							'size="60" />' +
							'<input type="text" name="_jwppp-' + number + '-subtitle-' + n + '-label" style="margin-right:1rem;" value="" placeholder="<?php echo __("Label (EN, IT, FR )", "jwppp"); ?>" size="30" />';
						   '</li>';
					$('ul.load-subtitles-' + number).append(element);
					// placeholder();
				}
			}


			$('li#video-' + number + '-chapter').each(function(i,el) {
				var num = $(el).data('number');
				if(num > n_chapters) {
					$(el).hide();
				} else {
					$(el).show('slow');
				}
			})

			// $('li#video-' + number + '-subtitle').hide();
			$('li#video-' + number + '-subtitle').each(function(i,el) {
				var numb = $(el).data('number');
				if(numb > n_chapters) {
					$(el).hide();
				} else {
					$(el).show('slow');
				}
			})
		})

	});
})(jQuery);
</script>

<?php
$output .= '<div class="jwppp-more-options-' . $number . '" style="margin-top:2rem;">';

$output .= '<label for="_jwppp-add-sources-' . $number . '">';
$output .= '<strong>' . __( 'More sources', 'jwppp' ) . '</strong>';
$output .= '<a class="question-mark" title="' . __('Used for quality toggling and alternate sources.', 'jwppp') . '"><img class="question-mark" src="' . plugins_url('jw-player-7-for-wp-premium') . '/images/question-mark.png" /></a></th>';
$output .= '</label> ';

if(get_post_meta($post_id, '_jwppp-sources-number-' . $number, true)) {
	$sources = get_post_meta($post_id, '_jwppp-sources-number-' .  $number, true);
} else {
	$sources = 1;
}

$output .= '<input type="number" class="small-text" style="margin-left:1.8rem; display:inline; position: relative; top:2px;" id="_jwppp-sources-number-' .  $number . '" name="_jwppp-sources-number-' .  $number . '" value="' . $sources . '">';

$output .= '</p>';

$output .= '<ul class="sources-' . $number . '">';

for($n=1; $n<$sources+1; $n++) {
	$source_url  = get_post_meta($post_id, '_jwppp-' . $number . '-source-' . $n . '-url', true);
	$source_label = get_post_meta($post_id, '_jwppp-' . $number . '-source-' . $n . '-label', true);
	$output .= '<li id="video-' . $number . '-source" data-number="' . $n . '">';	
	$output .= '<input type="text" style="margin-right:1rem;" name="_jwppp-' . $number . '-source-' . $n . '-url" id="_jwppp-' . $number . '-source-' . $n . '-url" value="' . $source_url . '" placeholder="' . __('Source url', 'jwppp') . '" size="60" />';
	$output .= '<input type="text" name="_jwppp-' . $number . '-source-' . $n . '-label" class="source-label-' . $number . '" style="margin-right:1rem;" value="' . $source_label . '" placeholder="' . __('Label (HD, 720p, 360p)', 'jwppp') . '" size="30" />';
	$output .= '</li>';
}

$output .= '</ul>';

$output .= '<label for="_jwppp-video-image-' . $number . '">';
$output .= '<strong>' . __( 'Video poster image', 'jwppp' ) . '</strong>';
$output .= '</label> ';
$output .= '<p><input type="text" id="_jwppp-video-image-' . $number . '" name="_jwppp-video-image-' . $number .'" placeholder="' . __('Add a different poster image for this video', 'jwppp') . '" value="' . esc_attr( $video_image ) . '" size="60" /></p>';

$output .= '<label for="_jwppp-video-title-' . $number . '">';
$output .= '<strong>' . __( 'Video title', 'jwppp' ) . '</strong>';
$output .= '</label> ';
$output .= '<p><input type="text" id="_jwppp-video-title-' . $number . '" name="_jwppp-video-title-' . $number . '" placeholder="' . __('Add a title to your video', 'jwppp') . '" value="' . esc_attr( $video_title ) . '" size="60" /></p>';

$output .= '<label for="_jwppp-video-description-' . $number . '">';
$output .= '<strong>' . __( 'Video description', 'jwppp' ) . '</strong>';
$output .= '</label> ';
$output .= '<p><input type="text" id="_jwppp-video-description-' . $number . '" name="_jwppp-video-description-' . $number . '" placeholder="' . __('Add a description to your video', 'jwppp') . '" value="' . esc_attr( $video_description ) . '" size="60" /></p>';

$output .= '<p>';
$output .= '<label for="_jwppp-activate-media-type-' . $number . '">';
$output .= '<input type="checkbox" id="_jwppp-activate-media-type-' . $number . '" name="_jwppp-activate-media-type-' . $number . '" value="1"';
$output .= ($jwppp_activate_media_type == 1) ? ' checked="checked"' : '';
$output .= ' />';
$output .= '<strong>' . __('Force a media type', 'jwppp') . '</strong>';
$output .= '<a class="question-mark" title="' . __('Only required when a file extension is missing or not recognized', 'jwppp') . '"><img class="question-mark" src="' . plugins_url('jw-player-7-for-wp-premium') . '/images/question-mark.png" /></a></th>';
$output .= '</label>';
$output .= '<input type="hidden" name="activate-media-type-hidden-' . $number . '" value="1" />';

$output .= '<select style="position: relative; left:2rem; display:inline;" id="_jwppp-media-type-' . $number . '" name="_jwppp-media-type-' . $number . '">';
$output .= '<option name="mp4" value="mp4"';
$output .= ($jwppp_media_type == 'mp4') ? ' selected="selected"' : '';
$output .= '>mp4</option>';
$output .= '<option name="flv" value="flv"';
$output .= ($jwppp_media_type == 'flv') ? ' selected="selected"' : '';
$output .= '>flv</option>';
$output .= '<option name="mp3" value="mp3"';
$output .= ($jwppp_media_type == 'mp3') ? ' selected="selected"' : '';
$output .= '>mp3</option>';
$output .= '</select>';
$output .= '</p>';

$output .= '<p>';
$output .= '<label for="_jwppp-autoplay-' . $number . '">';
$output .= '<input type="checkbox" id="_jwppp-autoplay-' . $number . '" name="_jwppp-autoplay-' . $number . '" value="1"';
$output .= ($jwppp_autoplay == 1) ? ' checked="checked"' : '';
$output .= ' />';
$output .= '<strong>' . __('Autostarting on page load.', 'jwppp') . '</strong>';
$output .= '</label>';
$output .= '<input type="hidden" name="autoplay-hidden-' . $number . '" value="1" />';
$output .= '</p>';

$output .= '<p>';
$output .= '<label for="_jwppp-mute-' . $number . '">';
$output .= '<input type="checkbox" id="_jwppp-mute-' . $number . '" name="_jwppp-mute-' . $number . '" value="1"';
$output .= ($jwppp_mute == 1) ? ' checked="checked"' : '';
$output .= ' />';
$output .= '<strong>' . __('Mute the video during playback.', 'jwppp') . '</strong>';
$output .= '</label>';
$output .= '<input type="hidden" name="mute-hidden-' . $number . '" value="1" />';
$output .= '</p>';

$output .= '<p>';
$output .= '<label for="_jwppp-repeat-' . $number . '">';
$output .= '<input type="checkbox" id="_jwppp-repeat-' . $number . '" name="_jwppp-repeat-' . $number . '" value="1"';
$output .= ($jwppp_repeat == 1) ? ' checked="checked"' : '';
$output .= ' />';
$output .= '<strong>' . __('Repeat the video during playback.', 'jwppp') . '</strong>';
$output .= '</label>';
$output .= '<input type="hidden" name="repeat-hidden-' . $number . '" value="1" />';
$output .= '</p>';

if($jwppp_share_video) {
	if($jwppp_single_embed == '1') {
		$checked = 'checked="checked"';
	} elseif($jwppp_single_embed == '0') {
		$checked = '';
	} elseif(!$jwppp_single_embed) {
		$checked = ($jwppp_embed_video == 1) ? 'checked="checked"' : '';
	}

	$output .= '<p>';
	$output .= '<label for="_jwppp-single-embed-' . $number . '">';
	$output .= '<input type="checkbox" id="_jwppp-single-embed-' . $number . '" name="_jwppp-single-embed-' . $number . '" value="1"';
	$output .= ' ' . $checked;
	$output .= ' />';
	$output .= '<strong>' . __('Allow to embed this video', 'jwppp') . '</strong>';
	$output .= '</label>';
	$output .= '<input type="hidden" name="single-embed-hidden-' . $number . '" value="1" />';
	$output .= '</p>';
}

//DOWNLOAD VIDEO
$output .= '<p>';
$output .= '<label for="_jwppp-download-video-' . $number . '">';
$output .= '<input type="checkbox" id="_jwppp-download-video-' . $number . '" name="_jwppp-download-video-' . $number . '" value="1"';
$output .= ($jwppp_download_video == 1) ? ' checked="checked"' : '';
$output .= ' />';
$output .= '<strong>' . __('Allow to download this video', 'jwppp') . '</strong>';
$output .= '<a class="question-mark" title="' . __('Only with self-hosted videos and not with playlists.', 'jwppp') . '"><img class="question-mark" src="' . plugins_url('jw-player-7-for-wp-premium') . '/images/question-mark.png" /></a></th>';
$output .= '</label>';
$output .= '<input type="hidden" name="download-video-hidden-' . $number . '" value="1" />';
$output .= '</p>';

$output .= '<p>';
$output .= '<label for="_jwppp-add-chapters-' . $number . '">';
$output .= '<input type="checkbox" id="_jwppp-add-chapters-' . $number . '" name="_jwppp-add-chapters-' . $number . '" value="1"';
$output .= ($add_chapters == 1) ? ' checked="checked"' : '';
$output .= ' />';
$output .= '<strong><span class="add-chapters ' . $number . '">';
$output .= ($add_chapters == 1) ? __('Add', 'jwppp') : __('Add Chapters, Subtitles or Preview Thumbnails', 'jwppp');
$output .= '</span></strong>';
$output .= '</label>';
$output .= '<input type="hidden"function name="add-chapters-hidden-' . $number . '" value="1" />';

$output .= '<select style="margin-left:0.5rem;" name="_jwppp-chapters-subtitles-' . $number . '" id="_jwppp-chapters-subtitles-' . $number . '">';
$output .= '<option name="chapters" id="chapters" value="chapters"';
$output .= ($jwppp_chapters_subtitles == 'chapters') ? ' selected="selected"' : '';
$output .= '>Chapters</option>';
$output .= '<option name="subtitles" id="subtitles" value="subtitles"';
$output .= ($jwppp_chapters_subtitles == 'subtitles') ? ' selected="selected"' : '';
$output .= '>Subtitles</option>';
$output .= '<option name="thumbnails" id="thumbnails" value="thumbnails"';
$output .= ($jwppp_chapters_subtitles == 'thumbnails') ? ' selected="selected"' : '';
$output .= '>Thumbnails</option>';
$output .= '</select>';

//SUBTITLES METHOD SELECTOR
$output .= '<select style="margin-left:0.3rem;" name="_jwppp-subtitles-method-' . $number . '" id="_jwppp-subtitles-method-' . $number . '">';
$output .= '<option name="manual" id="manual" value="manual"';
$output .= ($jwppp_subtitles_method == 'manual') ? ' selected="selected"' : '';
$output .= '>Write subtitles</option>';
$output .= '<option name="load" id="load" value="load"';
$output .= ($jwppp_subtitles_method == 'load') ? ' selected="selected"' : '';
$output .= '>Load subtitles</option>';
$output .= '</select>';

if(get_post_meta($post_id, '_jwppp-chapters-number-' . $number, true)) {
	$chapters = get_post_meta($post_id, '_jwppp-chapters-number-' .  $number, true);
} else {
	$chapters = 1;
}

$output .= '<input type="number" class="small-text" style="margin-left:0.3rem; display:inline; position: relative; top:2px;" id="_jwppp-chapters-number-' .  $number . '" name="_jwppp-chapters-number-' .  $number . '" value="' . $chapters . '">';

$output .= '</p>';

$output .= '<ul class="chapters-subtitles-' . $number . '">';
for($i=1; $i<$chapters+1; $i++) {
	$title = get_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-title', true);
	$start = get_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-start', true);
	$end = get_post_meta($post_id, '_jwppp-' . $number . '-chapter-' . $i . '-end', true);		
	$output .= '<li id="video-' . $number . '-chapter" data-number="' . $i . '">';
	$output .= '<input type="text" style="margin-right:1rem;" name="_jwppp-' . $number . '-chapter-' . $i . '-title" value="' . $title . '"';

	if($jwppp_chapters_subtitles == 'subtitles') {
		$output .= 'placeholder="' . __('Subtitle', 'jwppp') . '"';
	} elseif($jwppp_chapters_subtitles == 'thumbnails') {
		$output .= 'placeholder="' . __('Thumbnail url', 'jwppp') . '"';
	} else {
		$output .= 'placeholder="' . __('Chapter title', 'jwppp') . '"';
	}

	$output .= ' size="60" />';
	$output .= '    ' . __('Start', 'jwppp') . '    <input type="number" name="_jwppp-' . $number . '-chapter-' . $i . '-start" style="margin-right:1rem;" min="0" step="1" class="small-text" value="' . $start . '" />';
	$output .= '    ' . __('End', 'jwppp') . '    <input type="number" name="_jwppp-' . $number . '-chapter-' . $i . '-end" style="margin-right:0.5rem;" min="1" step="1" class="small-text" value="' . $end . '" />';
	$output .= __('in seconds', 'jwpend');

	//SUBTITLES ACTIVATED BY DEFAULT
	if($i==1) {
		$output .= '<label for="_jwppp-subtitles-write-default-' . $number . '" style="display: inline-block; margin-left: 1rem;">';
		$output .= '<input type="checkbox" id="_jwppp-subtitles-write-default-' . $number . '" name="_jwppp-subtitles-write-default-' . $number . '" value="1"';
		$output .= ($jwppp_subtitles_write_default == 1) ? ' checked="checked"' : '';
		$output .= ' />';
		$output .= __('Default', 'jwppp');
		$output .= '<a class="question-mark" title="' . __('These subtitles will be activated by default', 'jwppp') . '"><img class="question-mark" src="' . plugins_url('jw-player-7-for-wp-premium') . '/images/question-mark.png" /></a></th>';
		$output .= '</label>';
		$output .= '<input type="hidden" name="subtitles-write-default-hidden-' . $number . '" value="1" />';
	}

	$output .= '</li>';
}
$output .= '</ul>';

$output .= '<ul class="load-subtitles-' . $number . '">';

for($n=1; $n<$chapters+1; $n++) {
	$url  = get_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $n . '-url', true);
	$label = get_post_meta($post_id, '_jwppp-' . $number . '-subtitle-' . $n . '-label', true);
	$output .= '<li id="video-' . $number . '-subtitle" data-number="' . $n . '">';	
	$output .= '<input type="text" style="margin-right:1rem;" name="_jwppp-' . $number . '-subtitle-' . $n . '-url" value="' . $url . '" placeholder="' . __('Subtitles file url (VTT, SRT, DFXP)', 'jwppp') . '" size="60" />';
	$output .= '<input type="text" name="_jwppp-' . $number . '-subtitle-' . $n . '-label" style="margin-right:1rem;" value="' . $label . '" placeholder="' . __('Label (EN, IT, FR )', 'jwppp') . '" size="30" />';

	if($n==1) {
		$output .= '<label for="_jwppp-subtitles-load-default-' . $number . '">';
		$output .= '<input type="checkbox" id="_jwppp-subtitles-load-default-' . $number . '" name="_jwppp-subtitles-load-default-' . $number . '" value="1"';
		$output .= ($jwppp_subtitles_load_default == 1) ? ' checked="checked"' : '';
		$output .= ' />';
		$output .= __('Default', 'jwppp');
		$output .= '<a class="question-mark" title="' . __('These first subtitles will be activated by default', 'jwppp') . '"><img class="question-mark" src="' . plugins_url('jw-player-7-for-wp-premium') . '/images/question-mark.png" /></a></th>';
		$output .= '</label>';
		$output .= '<input type="hidden" name="subtitles-load-default-hidden-' . $number . '" value="1" />';
	}

	$output .= '</li>';
}

$output .= '</ul>';

$output .= '</div>';
$output .= '</td>';
if($number<2) {
	$output .= '<td class="add-video"><a class="jwppp-add"><img src="' . plugins_url('jw-player-7-for-wp-premium') . '/images/add-video.png" /></a></td>';
} else {
	$output .= '<td class="remove-video"><a class="jwppp-remove" data-numb="' . $number . '"><img src="' . plugins_url('jw-player-7-for-wp-premium') . '/images/remove-video.png" /></a></td>';
}
$output .= '</tr>';
$output .= '</tbody>';
$output .= '</table>';

echo $output;