/**
 * Single video script
 * @author ilGhera
 * @package jw-player-for-vip/js
* @version 2.0.0
 * @param  {int} number  the video's number in the post/ page
 * @param  {int} post_id the post/ page id
 */
var JWPPPSingleVideo = function( number, postId ) {
	jQuery( function( $ ) {

		var getUrl = $( '#_jwppp-video-url-' + number ).val();
		var url = 1 != getUrl ? getUrl : '';
		var ext = url.split( '.' ).pop();
		var arr = [ 'xml', 'feed', 'php', 'rss' ];
		var wrap = $( '.jwppp-' + number + ' .jwppp-input-wrap' );
		var tot = $( '.jwppp-input-wrap' ).length;

		var plString;

		/*Video toggles*/
		$( document ).on( 'click', '.jwppp-video-toggles.' + number + ' li', function() {

			var videoType = $( this ).data( 'video-type' );

			$( '.jwppp-video-toggles.' + number + ' li' ).removeClass( 'active' );
			$( this ).addClass( 'active' );

			$( '.jwppp-toggle-content.' + number ).removeClass( 'active' );
			$( '.jwppp-toggle-content.' + number + '.' + videoType ).addClass( 'active' );

			/*Delete the input field value on toggle change*/
			$( 'input#_jwppp-video-url-' + number ).val( '' );
			$( '#_jwppp-video-title-' + number + '.jwppp-title' ).val( '' );
			$( '#_jwppp-video-title-' + number ).val( '' );

			/*Delete preview image*/
			$( '.poster-image-preview.' + number ).remove();

			/*Video details*/
			$( '.jwppp-video-details-' + number ).html( '' );

			/*With cloud player and self hosted sources, all the tools are shown*/
			if ( 'add-url' === videoType ) {

				/*Video title*/
				$( '#_jwppp-video-title-' + number ).val( '' );

				$( '.jwppp-single-option-' + number ).show();

				/*Hide carousel option*/
				$( '.playlist-carousel-container.' + number ).hide();

			} else {

				$( '.jwppp-single-option-' + number ).hide();
				$( '.jwppp-single-option-' + number + '.cloud-option' ).show();
				$( '.playlist-carousel-container.' + number ).hide();

			}

		});

		/*Changwe playlist-how-to*/
		if ( 1 < tot ) {
			$( '.playlist-how-to' ).show( 'slow' );

			plString = [];
			$( '.order:visible' ).each( function( i, el ) {
				plString.push( $( el ).html() );
			});
		} else {
			$( '.playlist-how-to' ).hide();
		}

		$( '.jwppp-more-options-' + number ).hide();

		if ( -1 < $.inArray( ext, arr ) ) {
			$( '.more-options-' + number ).hide();
		};

		/*Media url change*/
		$( document ).on( 'change', '#_jwppp-video-url-' + number, function() {

			var url = $( this ).val();

			/*Get the extension for old type playlist*/
			var ext = url.split( '.' ).pop();
			var arr = [ 'xml', 'feed', 'php', 'rss' ];
			if ( -1 < $.inArray( ext, arr ) ) {
				$( '.more-options-' + number ).hide();
				$( '.jwppp-more-options-' + number ).hide();
			} else {
				$( '.more-options-' + number ).show();
			}
		});

		/*Video url field length animation*/
		$( document ).on( 'focus', '#_jwppp-video-url-' + number, function() {
			$( this ).animate({
				'width': '507px'
			});
			$( '.jwppp-video-details-' + number ).hide();
		});
		$( document ).on( 'focusout', '#_jwppp-video-url-' + number, function() {

			/*Not animate if more options are open*/
			if ( 'Show options' == $( '.more-options-' + number ).text() ) {
				$( this ).animate({
					'width': '256px'
				});
				setTimeout( function() {
					$( '.jwppp-video-details-' + number ).show();
				}, 300 );

			}
		});

		/*More options button*/
		$( '.more-options-' + number ).click( function() {

			var data;
			var method = $( '.jwppp-video-toggles.' + number + ' li.active' );
			var nonce = $( '#hidden-meta-box-nonce-' + number ).val();

			$( '.jwppp-more-options-' + number ).toggle( 'fast' );

			$( this ).text( function( i, text ) {
				return 'Show options' == text ? 'Hide options' : 'Show options';
			});

			/*Load players*/
			if ( ! $( this ).hasClass( 'loaded' ) ) {

				if ( ! $( wrap ).hasClass( 'medium' ) ) {
					$( this ).addClass( 'loaded' );

					data = {
						'action': 'get-players',
						'post_id': postId,
						'number': number
					};

					data['hidden-meta-box-nonce-' + number] = nonce;

					$.post( ajaxurl, data, function( response ) {
						$( '.jwppp-single-option-' + number + '.choose-player' ).html( response ).animate({
							'opacity': 1
						});

					});
				}

			}

			if ( 'add-url' == $( method ).data( 'video-type' ) || 0 == method.length ) {

				setTimeout( function() {
					var urlField = $( '#_jwppp-video-url-' + number + '.jwppp-url' );
					var nSources = $( '#_jwppp-sources-number-' + number ).val();
					var title;
					var description;

					if ( 'Hide options' == $( '.more-options-' + number ).text() ) {

						$( '.jwppp-video-details-' + number ).hide();
						$( urlField ).animate({'width': '507px'});
						if ( 2 <= nSources ) {
							$( '#_jwppp-' + number + '-main-source-label' ).show();
						}

					} else {

						$( urlField ).animate({'width': '256px'});
						$( '#_jwppp-' + number + '-main-source-label' ).hide();

						/*Self-hosted video*/
						title = $( '.jwppp-more-options-' + number + ' #_jwppp-video-title-' + number ).val();
						description = $( '#_jwppp-video-description-' + number + '.jwppp-description' ).val();

						$( '.jwppp-video-details-' + number ).empty();

						if ( title ) {
							$( '.jwppp-video-details-' + number ).append( ( '<span>Title</span>: ' + title + '<br>' ) );
						}

						if ( description ) {
							$( '.jwppp-video-details-' + number ).append( ( '<span>Description</span>: ' + description + '<br>' ) );
						}

						setTimeout( function() {
							$( '.jwppp-video-details-' + number ).show();
						}, 300 );

					}
				}, 400 );

			}

		});

		/*Media type*/
		if ( false == $( '#_jwppp-activate-media-type-' + number ).prop( 'checked' ) ) {
			$( '#_jwppp-media-type-' + number ).hide();
		} else {
			$( '#_jwppp-media-type-' + number ).show();
		}
		$( '#_jwppp-activate-media-type-' + number ).on( 'change', function() {
			if ( true == $( this ).prop( 'checked' ) ) {
				$( '#_jwppp-media-type-' + number ).show();
			} else {
				$( '#_jwppp-media-type-' + number ).hide();
			}
		});

		/*Poster image preview*/
		$( document ).on( 'change', '#_jwppp-video-image-' + number, function() {

			var small;
			var subMethod;
			var nChapters;

			if ( $( this ).val() ) {
				$( '.poster-image-preview.' + number ).remove();

				/*Small class if the player is self-hosted*/
				small = $( wrap ).hasClass( 'self' ) ? ' small' : '';

				$( '.jwppp-' + number + ' .jwppp-input-wrap' ).prepend( '<img class="poster-image-preview ' + number + small + '" style="display: none;">' );
				$( '.jwppp-' + number + ' .jwppp-input-wrap .poster-image-preview.' + number ).attr( 'src', $( this ).val() );
				$( '.poster-image-preview.' + number ).fadeIn( 300 );
			} else {
				$( '.poster-image-preview.' + number ).fadeOut( 300, function() {
					$( this ).remove();
				});
			}
		});

		/*Chapters*/
		if ( false == $( '#_jwppp-add-chapters-' + number ).prop( 'checked' ) ) {

			$( '#_jwppp-chapters-subtitles-' + number ).hide();
			$( '#_jwppp-chapters-number-' + number ).hide();
			$( '#_jwppp-subtitles-method-' + number ).hide();
			$( 'li#video-' + number + '-chapter' ).hide();
			$( 'li#video-' + number + '-subtitle' ).hide();

		} else {

			$( '#_jwppp-chapters-subtitles-' + number ).show();
			$( '#_jwppp-chapters-number-' + number ).show();
			$( '#_jwppp-subtitles-method-' + number ).hide();
			$( 'label[for="_jwppp-subtitles-write-default-' + number + '"]' ).hide();
			$( 'label[for="_jwppp-subtitles-load-default-' + number + '"]' ).hide();

			/*If subtitles are activated, manual/ load option is shown*/
			if ( 'subtitles' == $( '#_jwppp-chapters-subtitles-' + number ).val() ) {
				$( '#_jwppp-subtitles-method-' + number ).show();
				$( 'label[for="_jwppp-subtitles-write-default-' + number + '"]' ).show();
				$( 'label[for="_jwppp-subtitles-load-default-' + number + '"]' ).show();
			}

			/*If subtitle method is set to "load", the elements change*/
			subMethod = $( '#_jwppp-subtitles-method-' + number ).val();
			if ( 'load' == subMethod ) {
				$( '.load-subtitles-' + number ).show();
				$( '.chapters-subtitles-' + number ).hide();
			} else {
				$( '.load-subtitles-' + number ).hide();
				$( '.chapters-subtitles-' + number ).show();
			}

			nChapters = $( '#_jwppp-chapters-number-' + number ).val();
			$( 'li#video-' + number + '-chapter' ).hide();
			$( 'li#video-' + number + '-chapter' ).each( function( i, el ) {
				var num = $( el ).data( 'number' );
				if ( num <= nChapters ) {
					$( el ).show();
				}
			});

			$( 'li#video-' + number + '-subtitle' ).hide();
			$( 'li#video-' + number + '-subtitle' ).each( function( i, el ) {
				var numb = $( el ).data( 'number' );
				if ( numb <= nChapters ) {
					$( el ).show();
				}
			});
		}

		/*Hide/ show contents based on the main flag*/
		$( '#_jwppp-add-chapters-' + number ).on( 'change', function() {

			var subMethod;
			var nChapters;

			if ( $( '#_jwppp-add-chapters-' + number ).prop( 'checked' ) ) {
				$( 'span.add-chapters.' + number ).text( 'Add' );
				$( '#_jwppp-chapters-subtitles-' + number ).show();
				$( '#_jwppp-chapters-number-' + number ).show();


				/*If subtitles are activated, manual/ load option is shown*/
				if ( 'subtitles' == $( '#_jwppp-chapters-subtitles-' + number ).val() ) {
					$( '#_jwppp-subtitles-method-' + number ).show();
				}

				subMethod = $( '#_jwppp-subtitles-method-' + number ).val();
				if ( 'load' == subMethod ) {
					$( '.load-subtitles-' + number ).show();
					$( '.chapters-subtitles-' + number ).hide();
				} else {
					$( '.load-subtitles-' + number ).hide();
					$( '.chapters-subtitles-' + number ).show();
				}

				nChapters = $( '#_jwppp-chapters-number-' + number ).val();
				$( 'li#video-' + number + '-chapter' ).each( function( i, el ) {
					var num = $( el ).data( 'number' );
					if ( num <= nChapters ) {
						$( el ).show();
					}
				});

				$( 'li#video-' + number + '-subtitle' ).each( function( i, el ) {
					var numb = $( el ).data( 'number' );
					if ( numb <= nChapters ) {
						$( el ).show();
					}
				});

			} else {
				$( 'span.add-chapters.' + number ).text( 'Add Chapters, Subtitles or Preview Thumbnails' );
				$( '#_jwppp-chapters-subtitles-' + number ).hide();
				$( '#_jwppp-chapters-number-' + number ).hide();
				$( 'li#video-' + number + '-chapter' ).hide();
				$( '#_jwppp-subtitles-method-' + number ).hide();
				$( '.load-subtitles-' + number ).hide();

			}
		});

		/*Set different placeholder for differents element types*/
		function placeholder() {
			var selector = $( '#_jwppp-chapters-subtitles-' + number );
			var placeholder;

			if ( 'thumbnails' == $( selector ).val() ) {
				placeholder = 'Thumbnail url';
			} else if ( 'subtitles' == $( selector ).val() ) {
				placeholder = 'Subtitle';
			} else {
				placeholder = 'Chapter title';
			}
			$( 'ul.chapters-subtitles-' + number + ' li input[type=text]' ).attr( 'placeholder', placeholder );
		}

		/*Change contents based on the tool selected*/
		$( '#_jwppp-chapters-subtitles-' + number ).on( 'change', function() {

			var subMethod;

			placeholder();

			if ( 'subtitles' == $( this ).val() ) {
				$( '#_jwppp-subtitles-method-' + number ).show();
				$( 'label[for="_jwppp-subtitles-write-default-' + number + '"]' ).show();
				$( 'label[for="_jwppp-subtitles-load-default-' + number + '"]' ).show();

				subMethod = $( '#_jwppp-subtitles-method-' + number ).val();
				if ( 'load' == subMethod ) {
					$( '.load-subtitles-' + number ).show();
					$( '.chapters-subtitles-' + number ).hide();
				} else {
					$( '.load-subtitles-' + number ).hide();
					$( '.chapters-subtitles-' + number ).show();
				}
			} else {
				$( '#_jwppp-subtitles-method-' + number ).hide();
				$( '.load-subtitles-' + number ).hide();
				$( 'label[for="_jwppp-subtitles-write-default-' + number + '"]' ).hide();
				$( 'label[for="_jwppp-subtitles-load-default-' + number + '"]' ).hide();
				$( '.chapters-subtitles-' + number ).show();
			}
		});

		/*Change element type based on subtitles methos*/
		$( '#_jwppp-subtitles-method-' + number ).on( 'change', function() {
			if ( 'load' == $( this ).val() ) {
				$( '.load-subtitles-' + number ).show();
				$( '.chapters-subtitles-' + number ).hide();
			} else {
				$( '.load-subtitles-' + number ).hide();
				$( '.chapters-subtitles-' + number ).show();
			}
		});

		/*Change the elements number base on the number tool*/
		$( '#_jwppp-sources-number-' + number ).on( 'change', function() {
			var nSources          = $( this ).val();
			var nCurrentSources  = $( 'li#video-' + number + '-source' ).length;
			var element;

			/*Show labels if alternatives source exist*/
			if ( 1 < nSources ) {
				$( '.source-label-' + number ).show( 'slow' );
			} else {
				$( '.source-label-' + number ).hide();
			}

			if ( nSources > nCurrentSources ) {
				for ( n = nCurrentSources + 1; n == nSources; n++ ) {
					$( 'ul.sources-' + number ).append( '<li id="video-' + number + '-source" class="video-' + number + '-source-' + n + '" data-number="' + n + '"></li>' );

					$( 'ul.sources-' + number + ' li.video-' + number + '-source-' + n ).append( '<input type="text" style="margin-right:1rem;" name="_jwppp-' + number + '-source-' + n + '-url" value="" placeholder="Source url" size="60" />' );
					$( 'ul.sources-' + number + ' li.video-' + number + '-source-' + n ).append( '<input type="text" name="_jwppp-' + number + '-source-' + n + '-label" class="source-label-' + number + '" style="margin-right:1rem;" value="" placeholder="Label (HD, 720p, 360p)" size="30" />' );

				}
			}

			$( 'li#video-' + number + '-source' ).each( function( i, el ) {
				var num = $( el ).data( 'number' );
				if ( num > nSources ) {
					$( el ).hide();
				} else {
					$( el ).show( 'slow' );
				}
			});
		});

		/*Chapter number*/
		$( '#_jwppp-chapters-number-' + number ).on( 'change', function() {

			var nChapters          = $( this ).val();
			var nCurrent           = $( 'li#video-' + number + '-chapter' ).length;
			var nCurrentSubtitles = $( 'li#video-' + number + '-subtitle' ).length;
			var element;

			if ( nChapters > nCurrent ) {
				for ( n = nCurrent + 1; n == nChapters; n++ ) {

					$( 'ul.chapters-subtitles-' + number ).append( '<li id="video-' + number + '-chapter" class="video-' + number + '-chapter-' + n + '" data-number="' + n + '">' );

					$( 'ul.chapters-subtitles-' + number + ' li.video-' + number + '-chapter-' + n ).append( '<input type="text" style="margin-right:1rem;" name="_jwppp-' + number + '-chapter-' + n + '-title"' + 'placeholder=""' + 'size="60" />    ' );
					$( 'ul.chapters-subtitles-' + number + ' li.video-' + number + '-chapter-' + n ).append( 'Start <input type="number" name="_jwppp-' + number + '-chapter-' + n + '-start" style="margin-right:1rem;" min="0" step="1" class="small-text" />    ' );
					$( 'ul.chapters-subtitles-' + number + ' li.video-' + number + '-chapter-' + n ).append( 'End <input type="number" name="_jwppp-' + number + '-chapter-' + n + '-end" style="margin-right:0.5rem;" min="1" step="1" class="small-text" />' );
					$( 'ul.chapters-subtitles-' + number + ' li.video-' + number + '-chapter-' + n ).append( 'in seconds' );

					placeholder();
				}
			}

			if ( nChapters > nCurrentSubtitles ) {
				for ( n = nCurrentSubtitles + 1; n == nChapters; n++ ) {
					$( 'ul.load-subtitles-' + number ).append( '<li id="video-' + number + '-subtitle" class="video-' + number + '-subtitle-' + n + '" data-number="' + n + '">' );

					$( 'ul.load-subtitles-' + number + ' li.video-' + number + '-subtitle-' + n ).append( '<input type="text" style="margin-right:1rem;" name="_jwppp-' + number + '-subtitle-' + n + '-url" placeholder="Subtitles file url (VTT, SRT, DFXP)" size="60" />' );
					$( 'ul.load-subtitles-' + number + ' li.video-' + number + '-subtitle-' + n ).append( '<input type="text" name="_jwppp-' + number + '-subtitle-' + n + '-label" style="margin-right:1rem;" value="" placeholder="Label (EN, IT, FR )" size="30" />' );
				}
			}


			$( 'li#video-' + number + '-chapter' ).each( function( i, el ) {
				var num = $( el ).data( 'number' );
				if ( num > nChapters ) {
					$( el ).hide();
				} else {
					$( el ).show( 'slow' );
				}
			});

			// $('li#video-' + number + '-subtitle').hide();
			$( 'li#video-' + number + '-subtitle' ).each( function( i, el ) {
				var numb = $( el ).data( 'number' );
				if ( numb > nChapters ) {
					$( el ).hide();
				} else {
					$( el ).show( 'slow' );
				}
			});
		});
	});
};
