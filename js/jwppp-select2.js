/**
 * Select2 script
 */

//TEMP
// function get_videos_url() {
// 		var data = {
// 			'action': 'get-videos-url',
// 			'go': 1
// 		}
// 		return $.post(ajaxurl, data);
// }

// var videos_url =  function() {
// 	var output = await get_videos_url();
// 	console.log(output);
// }

// function videos_url() {
// 	get_videos_url().done(function(data){
// 		// console.log(data); //filled!
// 		return data;
// 	});	
// }

// console.log(videos_url());

var jwppp_select2 = function() {
	jQuery(function($){


		function formatVideo(video) {
			if (!video.id) {
				return video.text;
			}
			/*Different thumbs for playlists and videos*/
			var element = video['element'];
			if($(element).hasClass('playlist-element')) {
				var img = "../wp-content/plugins/jw-player-7-for-wp/images/playlist4.png";
				var $video = $(
				    '<span><img style="width: 60px;" src="' + img + '" class="video-img" /><span class="option-text"> ' + video.text + '</span></span>'
				);
			} else {
				var baseUrl = "https://cdn.jwplayer.com/thumbs/";
				var $video = $(
				'<span><img style="width: 60px; max-height: 35px;" src="' + baseUrl + video.id + '-60.jpg" class="video-img" /><span class="option-text"> ' + video.text + '</span></span>'
				);
			}
			return $video;
		};


	    $('.select2').select2({
	    	theme: 'classic',
	    	width: '16rem',
	    	templateResult: formatVideo,
			language: {
				noResults: function() {
					return 'No videos have been found';
				}
			},

			// TEMP
			minimumInputLength: 2,
		    minimumResultsForSearch: 10,
		    ajax: {

		    	

		        // url: 'https://api.github.com/orgs/select2/repos',
		        // url: 'https://api.jwplatform.com/videos/list?api_format=json&api_nonce=44498446&api_timestamp=1536060132&api_key=5W4ggqCW&api_kit=php-1.4&api_signature=77cfe1feba54f1f9e9621af5ffc0ad05a94ee5f0',
		        url: function() {
		        	// console.log(get_videos_url())
		        	return get_videos_url()
		        },



		        dataType: "json",
		        type: "GET",
		        data: function (params) {
		            var queryParameters = {
		                term: params.term
		            }
		            return queryParameters;
		        },

		     //    transport: function (params, success, failure) {
			    //   var $request = $.ajax(params);

			    //   $request.then(success);
			    //   $request.fail(failure);

			    //   console.log($request);
			    //   return $request;
			    // }



		        processResults: function (data) {
		            return {
		                results: $.map(data, function (item) {
	                    	console.log(item.name);

		                    return {
		                        text: item.tag_value,
		                        id: item.tag_id
		                    }
		                })
		            };		            
		        }

		    }
		    /*END TEMP*/

	    });	

	    /*Search field placeholder*/
	    $('.select2').on('select2:open', function() {
		    $('.select2-search--dropdown .select2-search__field').attr('placeholder', 'Search for a video or a playlist');
		});
		$('.select2').on('select2:close', function() {
		    $('.select2-search--dropdown .select2-search__field').attr('placeholder', null);
		});	
	})
}
// jwppp_select2();
// 

var select_content = function(){
	jQuery(function($){
		$(document).on('click', 'ul.jwppp-video-list li', function(){
		
			var number = $(this).closest('ul').data('number');
			var media_id = $(this).data('mediaid');
			console.log('Media id: ' + media_id);
			var video_title = $('span', this).text();
			
			$('#_jwppp-video-url-' + number + '.choose').attr('value', media_id);
			$('#_jwppp-video-url-' + number + '.jwppp-url').attr('value', media_id);
			$('#_jwppp-video-title-' + number).attr('value', video_title);
			$('#_jwppp-video-title-' + number + '.jwppp-title').attr('value', video_title);

			/*Image preview*/
			var width = $(document).width();

			//POSTER PREVIEW IMAGE
            if(media_id && width > 1112) {
            	var image_url = null;
            	// if($('option:selected', this).hasClass('playlist-element')) {
	            // 	image_url = '../wp-content/plugins/jw-player-7-for-wp/images/playlist4.png';

	            // 	$('.playlist-carousel-container.' + number).css({
	            // 		'display': 'inline-block'
	            // 	})

            	// } else {
	            
	            	image_url = 'https://cdn.jwplayer.com/thumbs/' + media_id + '-720.jpg'
					$('.playlist-carousel-container.' + number).hide();
					$('#_jwppp-playlist-carousel-' + number).removeAttr('checked');

            	// }

                $('.poster-image-preview.' + number).remove();
                $('.jwppp-' + number + ' .jwppp-input-wrap').prepend('<img class="poster-image-preview ' + number + '" src="' + image_url + '" style="display: none;">');
                $('.poster-image-preview.' + number).fadeIn(300);
            } else {
                $('.poster-image-preview.' + number).fadeOut(300, function(){
                    $(this).remove();
                });
            }

		})
	})
}
select_content();

var search_content = function(number){
	jQuery(function($){

		$(document).on('focusin', '.jwppp-search-content', function(){
			var number = $(this).data('number'); 
			$('#_jwppp-video-list-' + number).slideDown();
		})

		$(document).on('focusout', '.jwppp-search-content', function(){
			var number = $(this).data('number'); 
			$('#_jwppp-video-list-' + number).slideUp();
		})

		$(document).on('keyup', '.jwppp-search-content', function(){
			var number = $(this).data('number'); 
			var ul = $('ul#_jwppp-video-list-' + number);
			$(ul).html('<li class="jwppp-loading"><img src="' + jwpppselect2.pluginUrl + '/images/loading.gif"></li>');
			// console.log(jwpppselect2.pluginUrl + '/images/loading.gif');

			var value = $(this).val().trim();
			var data = {
				'action': 'search-content',
				'value': value
			}
			$.post(ajaxurl, data, function(response){

				var baseUrl = "https://cdn.jwplayer.com/thumbs/";
				var videos = JSON.parse(response);
				if(videos.length > 0) {
					$(ul).html(' ');
					for (var i = 0; i < videos.length; i++) {
						// console.log(videos[i]);
						$(ul).append('<li><img class="video-img" src="' + baseUrl + videos[i].key + '-60.jpg"><span>' + videos[i].title + '</span></li>');
					}						
				} else {
					$(ul).html('<li class="jwppp-no-results">Sorry, no videos found.</li>');					
				}
			})
		})
	})
}
search_content();