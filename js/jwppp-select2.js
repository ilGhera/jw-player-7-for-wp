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
				var baseUrl = "https://assets-jpcust.jwpsrv.com/thumbs";
				var $video = $(
				'<span><img style="width: 60px;" src="' + baseUrl + '/' + video.id + '-720.jpg" class="video-img" /><span class="option-text"> ' + video.text + '</span></span>'
				);
			}
			return $video;
		};


	    $('.select2').select2({
	    	theme: 'classic',
	    	width: '16rem',
	    	templateResult: formatVideo,
			// dropdownCssClass: 'select2-dropdown',
			language: {
				noResults: function() {
					return 'No videos have been found';
				}

			},
	    });		
	})
}
jwppp_select2();
