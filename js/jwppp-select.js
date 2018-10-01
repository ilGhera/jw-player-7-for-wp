/**
 * Used with player and media contents from the cloud
 * @author ilGhera
 * @package jw-player-7-for-wp/js
 * @version 1.6.0
 *
 *
 * Fired when a media content in the list is clicked
 */
var jwppp_select_content = function(){
	jQuery(function($){
		$(document).on('click', 'ul.jwppp-video-list li', function(){
		
			var number = $(this).closest('ul').data('number');
			var reset = false;
			
			if($(this).hasClass('reset')) {
				var media_id = null;
				var video_title = null;
				$('.jwppp-video-details-' + number).html('');
				var reset = true;
			} else {
				var media_id = $(this).data('mediaid');
				var video_title = $('span', this).text();

			}
			
			$('#_jwppp-video-url-' + number + '.choose').attr('value', media_id);
			$('#_jwppp-video-url-' + number + '.jwppp-url').attr('value', media_id);
			$('#_jwppp-video-title-' + number).attr('value', video_title);
			$('#_jwppp-video-title-' + number + '.jwppp-title').attr('value', video_title);

			/*Video details*/
			if(!reset) {
				var description = $(this).data('description');

				if($(this).hasClass('playlist-element')) {
					var items = $(this).data('videos') ? $(this).data('videos') : '0';

					$('.jwppp-video-details-' + number).html(
						(video_title ? '<span>Title</span>: ' + video_title + '<br>' : '') +
						(description ? '<span>Description</span>: ' + description + '<br>' : '') +
						'<span>Items</span>: ' + items + '<br>'
					);

				} else {					
					var duration = null; 
					if($(this).data('duration') > 0) {
						duration = new Date($(this).data('duration') * 1000).toISOString().substr(11, 8);
					}
					
					var tags = $(this).data('tags');

					$('.jwppp-video-details-' + number).html(
						(video_title ? '<span>Title</span>: ' + video_title + '<br>' : '') +
						(description ? '<span>Description</span>: ' + description + '<br>' : '') +
						(duration ? '<span>Duration</span>: ' + duration + '<br>' : '') +
						(tags ? '<span>Tags</span>: ' + tags + '<br>' : '')
					);
				}
			}

			/*Image preview*/
			var width = $(document).width();

            if(media_id && width > 1112) {
            	var image_url = null;
            	if($(this).hasClass('playlist-element')) {
	            	
	            	image_url = jwppp_select.pluginUrl + '/images/playlist4.png';

	            	$('.playlist-carousel-container.' + number).css({
	            		'display': 'inline-block'
	            	})

            	} else {
	            
	            	image_url = 'https://cdn.jwplayer.com/thumbs/' + media_id + '-720.jpg'
					$('.playlist-carousel-container.' + number).hide();
					$('#_jwppp-playlist-carousel-' + number).removeAttr('checked');

            	}

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
jwppp_select_content();


/**
 * Delay used after keyup on searching media contents
 */
var delay = (function(){
	var timer = 0;
	return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	};
})();


/**
 * Search contents in the dashboard based on the text typed by the user		
 * @param  {id} number the video number of the post/ page
 * @return {mixed}     the videos and playlists returned by the api call
 */
var jwppp_search_content = function(number){
	jQuery(function($){

		$(document).on('focusin', '.jwppp-search-content', function(){
			var number = $(this).data('number'); 
			$('#_jwppp-video-list-' + number).slideDown();
		})

		$(document).on('focusout', '.jwppp-search-content', function(){
			var number = $(this).data('number'); 
			$('#_jwppp-video-list-' + number).delay(500).slideUp();
		})

		$(document).on('keyup', '.jwppp-search-content', function(){
			var number = $(this).data('number'); 
			var list_container = $('ul#_jwppp-video-list-' + number + ' span.jwppp-list-container');
			$(list_container).html('<li class="jwppp-loading"><img src="' + jwppp_select.pluginUrl + '/images/loading.gif"></li>');

			var value = $(this).val().trim();
			var data = {
				'action': 'search-content',
				'value': value
			}

			delay(function() {

				$.post(ajaxurl, data, function(response){

					var baseUrl = "https://cdn.jwplayer.com/thumbs/";
					var contents = JSON.parse(response);
					var videos = contents.videos;
					var playlists = contents.playlists;

					/*Single videos*/
					if(videos.length > 0) {
						$(list_container).html('<li class="results reset">Videos found</li>');
						for (var i = 0; i < videos.length; i++) {
							// console.log(videos[i]);
							$(list_container).append(
								'<li ' +
								'data-mediaid="' + (videos[i].key ? videos[i].key : '-') + '" ' + 
								'data-description="' + (videos[i].description ? videos[i].description : '-') + '" ' + 
								'data-duration="' + (videos[i].duration ? videos[i].duration : '-') + '" ' + 
								'data-tags="' + (videos[i].tags ? videos[i].tags : '-') + '" ' + 
								'>' + 
								'<img class="video-img" src="' + baseUrl + videos[i].key + '-60.jpg">' + 
								'<span>' + videos[i].title + '</span>' +
								'</li>'
							);
						}						
					} 

					/*Playlists*/
					if(playlists.length > 0) {
						var pl_reset = $('ul#_jwppp-video-list-' + number + ' span.jwppp-list-container:contains("Playlists found")');
						if($(pl_reset).length == 0) {
							$(list_container).append('<li class="results reset">Playlists found</li>');						
						}
						$('.jwppp-loading').remove();
						$('.playlist-element').remove();
						for (var i = 0; i < playlists.length; i++) {
							$(list_container).append(					
								'<li class="playlist-element" ' +
								'data-mediaid="' + (playlists[i].key ? playlists[i].key : '-') + '" ' + 
								'data-description="' + (playlists[i].description ? playlists[i].description : '-') + '" ' + 
								// 'data-duration="' + (playlists[i].duration ? playlists[i].duration : '-') + '" ' + 
								'data-videos="' + (playlists[i].videos.total ? playlists[i].videos.total : '-') + '" ' + 
								'data-tags="' + (playlists[i].tags ? playlists[i].tags : '-') + '" ' + 
								'>' + 
								'<img class="video-img" src="' + jwppp_select.pluginUrl + '/images/playlist4.png">' + 
								'<span>' + playlists[i].title + '</span>' +
								'</li>'
							);
						}						
					} 

					/*No results*/
					if(videos.length == 0 && playlists.length == 0) {
						$(list_container).html('<li class="jwppp-no-results">Sorry, no videos found.</li>');					
					}
				})

			}, 1000)
		})
	})
}
jwppp_search_content();