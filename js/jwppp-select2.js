/**
 * Select2 script
 */
jQuery(document).ready(function($) {	

	function formatVideo(video) {
	  if (!video.id) {
	    return video.text;
	  }
	  var baseUrl = "https://assets-jpcust.jwpsrv.com/thumbs";
	  var $video = $(
	    '<span><img style="width: 60px;" src="' + baseUrl + '/' + video.id + '-720.jpg" class="video-img" /><span class="option-text"> ' + video.text + '</span></span>'
	  );
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

});

