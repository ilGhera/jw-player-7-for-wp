/**
 * Used with player and media contents from the cloud
 * @author ilGhera
 * @package jw-player-for-vip/js
* @version 2.0.0
 *
 *
 * Fired when a media content in the list is clicked
 */
var JWPPPSelectContent = function() {
	jQuery( function( $ ) {
		$( document ).on( 'click', 'ul.jwppp-video-list li', function() {

			var postId = $( '#post_ID' ).attr( 'value' );
			var number = $( this ).closest( 'ul' ).data( 'number' );
			var reset = false;
			var mediaId;
			var videoTitle;
			var description;
			var items;
			var duration;
			var tags;
			var width;
			var imageUrl;
			var articleMatching;

			if ( $( this ).hasClass( 'reset' ) ) {

				reset = true;
				mediaId = null;
				videoTitle = null;
				$( '.jwppp-video-details-' + number ).empty();

			} else {

				mediaId = $( this ).data( 'mediaid' );
				videoTitle = $( 'span', this ).text();

			}

			$( '#_jwppp-video-url-' + number + '.choose' ).attr( 'value', mediaId );
			$( '#_jwppp-video-url-' + number + '.jwppp-url' ).attr( 'value', mediaId );
			$( '#_jwppp-video-title-' + number ).attr( 'value', videoTitle );
			$( '#_jwppp-video-title-' + number + '.jwppp-title' ).attr( 'value', videoTitle );

			/*Video details*/
			if ( ! reset ) {

				description = $( this ).data( 'description' );

				$( '#_jwppp-video-description-' + number + '.choose' ).attr( 'value', description );
				$( '#_jwppp-video-description-' + number + '.jwppp-description' ).attr( 'value', description );

				if ( $( this ).hasClass( 'playlist-element' ) ) {
					items = $( this ).data( 'videos' ) ? $( this ).data( 'videos' ) : '0';

					$( '.jwppp-video-details-' + number ).empty();

					if ( videoTitle ) {
						$( '.jwppp-video-details-' + number ).append( '<span>Title</span>: ' + videoTitle + '<br>' );
					}

					if ( description ) {
						$( '.jwppp-video-details-' + number ).append( '<span>Description</span>: ' + description + '<br>' );
					}

					$( '.jwppp-video-details-' + number ).append( '<span>Items</span>: ' + items + '<br>' );


					$( '#_jwppp-playlist-items-' + number ).attr( 'value', items );

				} else {

					if ( 0 < $( this ).data( 'duration' ) ) {
						duration = new Date( $( this ).data( 'duration' ) * 1000 ).toISOString().substr( 11, 8 );
					}

					tags = $( this ).data( 'tags' );

					$( '.jwppp-video-details-' + number ).empty();

					if ( videoTitle ) {
						$( '.jwppp-video-details-' + number ).append( '<span>Title</span>: ' + videoTitle + '<br>' );
					}

					if ( description ) {
						$( '.jwppp-video-details-' + number ).append( '<span>Description</span>: ' + description + '<br>' );
					}

					if ( duration ) {
						$( '.jwppp-video-details-' + number ).append( '<span>Duration</span>: ' + duration + '<br>' );
					}

					if ( tags ) {
						$( '.jwppp-video-details-' + number ).append( '<span>Tags</span>: ' + tags + '<br>' );
					}

					$( '#_jwppp-video-duration-' + number ).attr( 'value', duration );
					$( '#_jwppp-video-tags-' + number ).attr( 'value', tags );

				}

			}

			/*Image preview*/
			width = $( document ).width();

			if ( mediaId && 1112 < width ) {

				if ( $( this ).hasClass( 'playlist-element' ) ) {

					imageUrl = jwpppSelect.pluginUrl + '/images/playlist4.png';

					$( '.playlist-carousel-container.' + number ).css({
						'display': 'inline-block'
					});

				} else {

					imageUrl = 'https://cdn.jwplayer.com/thumbs/' + mediaId + '-720.jpg';
					$( '.playlist-carousel-container.' + number ).hide();
					$( '#_jwppp-playlist-carousel-' + number ).removeAttr( 'checked' );

				}

				$( '.poster-image-preview.' + number ).remove();
				$( '.jwppp-' + number + ' .jwppp-input-wrap' ).prepend( '<img class="poster-image-preview ' + number + '" style="display: none;">' );
				$( '.jwppp-' + number + ' .jwppp-input-wrap .poster-image-preview.' + number ).attr( 'src', imageUrl );
				$( '.poster-image-preview.' + number ).fadeIn( 300 );

			} else {

				$( '.poster-image-preview.' + number ).fadeOut( 300, function() {
					$( this ).remove();
				});
			}

		});
	});
};
JWPPPSelectContent();


/**
 * Delay used after keyup on searching media contents
 */
var delay = ( function() {
	var timer = 0;
	return function( callback, ms ) {
		clearTimeout( timer );
		timer = setTimeout( callback, ms );
	};
}() );


/**
 * Search contents in the dashboard based on the text typed by the user
 * @param  {id} number the video number of the post/ page
 * @return {mixed}     the videos and playlists returned by the api call
 */
var JWPPPSearchContent = function( number ) {
	jQuery( function( $ ) {

		var postId = $( '#post_ID' ).attr( 'value' );

		$( document ).on( 'focusin', '.jwppp-search-content', function() {

			var number = $( this ).data( 'number' );
			var listContainer = $( 'ul#_jwppp-video-list-' + number + ' span.jwppp-list-container' );
			var nonce = $( '#hidden-meta-box-nonce-' + number ).val();
			var data;

			if ( ! $( this ).hasClass( 'loaded' ) ) {

				$( this ).addClass( 'loaded' );
				$( listContainer ).html( '<li class="jwppp-loading"><img /></li>' );
				$( '.jwppp-loading img', listContainer ).attr( 'src', jwpppSelect.pluginUrl + '/images/loading.gif' );

				data = {
					'action': 'init-api',
					'post_id': postId,
					'number': number
				};

				data['hidden-meta-box-nonce-' + number] = nonce;

				$.post( ajaxurl, data, function( response ) {
					$( listContainer ).html( response );
				});

			}


			$( '#_jwppp-video-list-' + number ).slideDown();
		});

		$( document ).on( 'focusout', '.jwppp-search-content', function() {
			var number = $( this ).data( 'number' );
			$( '#_jwppp-video-list-' + number ).delay( 500 ).slideUp();
		});

		$( document ).on( 'keyup', '.jwppp-search-content', function() {

			var number = $( this ).data( 'number' );
			var listContainer = $( 'ul#_jwppp-video-list-' + number + ' span.jwppp-list-container' );
			var nonce = $( '#hidden-meta-box-nonce-' + number ).val();
			var value;
			var data;

			$( listContainer ).html( '<li class="jwppp-loading"><img /></li>' );
			$( '.jwppp-loading img', listContainer ).attr( 'src', jwpppSelect.pluginUrl + '/images/loading.gif' );

			value = $( this ).val().trim();
			data = {
				'action': 'search-content',
				'number': number,
				'value': value
			};

			data['hidden-meta-box-nonce-' + number] = nonce;

			delay( function() {

				$.post( ajaxurl, data, function( response ) {

					var baseUrl = 'https://cdn.jwplayer.com/thumbs/';
					var contents = JSON.parse( response );
					var videos = contents.videos;
					var playlists = contents.playlists;
					var i;
					var n;
					var plReset;

					/*Single videos*/
					if ( 0 < videos.length ) {
						$( listContainer ).html( '<li class="results reset">Videos found</li>' );
						for ( i = 0; i < videos.length; i++ ) {
							$( listContainer ).append( '<li class="' + i + '"></li>' );
							$( 'li.' + i, listContainer ).data( 'mediaid', ( videos[i].key ? videos[i].key : '-' ) );
							$( 'li.' + i, listContainer ).data( 'description', ( videos[i].description ? videos[i].description : '-' ) );
							$( 'li.' + i, listContainer ).data( 'duration', ( videos[i].duration ? videos[i].duration : '-' ) );
							$( 'li.' + i, listContainer ).data( 'tags', ( videos[i].tags ? videos[i].tags : '-' ) );

							/*Preview image*/
							$( 'li.' + i, listContainer ).append( '<img class="video-img" />' );
							$( 'li.' + i + ' .video-img', listContainer ).attr( 'src', baseUrl + videos[i].key + '-60.jpg' );

							$( 'li.' + i, listContainer ).append( '<span>' + videos[i].title + '</span>' );

						}
					}

					/*Playlists*/
					if ( 0 < playlists.length ) {
						plReset = $( 'ul#_jwppp-video-list-' + number + ' span.jwppp-list-container:contains("Playlists found")' );
						if ( 0 == $( plReset ).length ) {
							$( listContainer ).append( '<li class="results reset">Playlists found</li>' );
						}
						$( '.jwppp-loading' ).remove();
						$( '.playlist-element' ).remove();
						for ( n = 0; n < playlists.length; n++ ) {
							$( listContainer ).append( '<li class="playlist-element ' + n + '"></li>' );

							/*Check if the playlist is type = article_matching*/
							articleMatching = 'article_matching' === playlists[n].type ? '?search=__CONTEXTUAL__' : '';

							$( 'li.playlist-element.' + n, listContainer ).data( 'mediaid', ( playlists[n].key ? playlists[n].key : '-' ) + articleMatching );
							$( 'li.playlist-element.' + n, listContainer ).data( 'description', ( playlists[n].description ? playlists[n].description : '-' ) );
							$( 'li.playlist-element.' + n, listContainer ).data( 'videos', ( playlists[n].videos.total ? playlists[n].videos.total : '-' ) );
							$( 'li.playlist-element.' + n, listContainer ).data( 'tags', ( playlists[n].tags ? playlists[n].tags : '-' ) );

							/*Preview image*/
							$( 'li.playlist-element.' + n, listContainer ).append( '<img class="video-img" />' );
							$( 'li.playlist-element.' + n + ' .video-img', listContainer ).attr( 'src', jwpppSelect.pluginUrl + '/images/playlist4.png' );

							$( 'li.playlist-element.' + n, listContainer ).append( '<span>' + playlists[n].title + '</span>' );

						}
					}

					/*No results*/
					if ( 0 == videos.length && 0 == playlists.length ) {
						$( listContainer ).html( '<li class="jwppp-no-results">Sorry, no videos found.</li>' );
					}
				});

			}, 1000 );
		});
	});
};
JWPPPSearchContent();
