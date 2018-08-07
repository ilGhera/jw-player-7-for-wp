/**
 * Select2 script
 */

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
				// var baseUrl = "https://assets-jpcust.jwpsrv.com/thumbs";
				// var baseUrl = "https://cdn.jwplayer.com/thumbs/";
				var baseUrl = "https://cdn.jwplayer.com/v2/media/";
				var $video = $(
				// '<span><img style="width: 60px; max-height: 35px;" src="' + baseUrl + '/' + video.id + '-720.jpg" class="video-img" /><span class="option-text"> ' + video.text + '</span></span>'
				'<span><img style="width: 60px; max-height: 35px;" src="' + baseUrl + video.id + '/poster.jpg" class="video-img" /><span class="option-text"> ' + video.text + '</span></span>'
				);
			}
			return $video;
		};


	    $('.select2').select2({
	    	theme: 'classic',
	    	width: '16rem',
    	    // placeholder: 'Search for a video or a playlist',
	    	templateResult: formatVideo,
			// dropdownCssClass: 'select2-dropdown',
			language: {
				noResults: function() {
					return 'No videos have been found';
				}

			},
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
jwppp_select2();
