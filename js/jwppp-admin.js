/**
 * Main admin js file
 * @author ilGhera
 * @package jw-player-for-vip/js
* @version 2.0.0
 */
jQuery( document ).ready( function( $ ) {

	/**
     * Tabs menu navigation
     */
	var JWPPPpagination = function() {

		var $contents = $( '.jwppp-admin' );
		var url = window.location.href.split( '#' )[0];
		var hash = window.location.href.split( '#' )[1];

		if ( hash ) {
			$contents.hide();
			$( '#' + hash ).fadeIn( 200 );
			$( 'h2#jwppp-admin-menu a.nav-tab-active' ).removeClass( 'nav-tab-active' );
			$( 'h2#jwppp-admin-menu a' ).each( function() {
				if ( $( this ).data( 'link' ) == hash ) {
					$( this ).addClass( 'nav-tab-active' );
				}
			});

			$( 'html, body' ).animate({
				scrollTop: 0
			}, 'slow' );
		}

		$( 'h2#jwppp-admin-menu a' ).click( function() {
			var $this = $( this );

			$contents.hide();
			$( '#' + $this.data( 'link' ) ).fadeIn( 200 );
			$( 'h2#jwppp-admin-menu a.nav-tab-active' ).removeClass( 'nav-tab-active' );
			$this.addClass( 'nav-tab-active' );

			window.location = url + '#' + $this.data( 'link' );

			$( 'html, body' ).scrollTop( 0 );

		});

	};
	JWPPPpagination();


	if ( 'featured-image' == $( '#thumbnail' ).val() ) {
		$( '.cf-row' ).hide();
	}

	$( '#thumbnail' ).on( 'change', function() {

		if ( 'custom-field' == $( this ).val() ) {
			$( '.cf-row' ).show( 'slow' );
			$( '.cf-row' ).attr( 'required', 'required' );
		} else {
			$( '.cf-row' ).hide();
			$( '.cf-row' ).removeAttr( 'required' );
		}
	});


	/*Share options*/
	if ( false == $( '#jwppp-active-share' ).prop( 'checked' ) ) {
		$( '.share-options' ).hide();
	}

	$( '#jwppp-active-share' ).on( 'change', function() {
		if ( $( '#jwppp-active-share' ).prop( 'checked' ) ) {
			$( '.share-options' ).show( 'slow' );
		} else {
			$( '.share-options' ).hide();
		}

	});


	/*Player dimensions*/
	if ( true == $( '#fixed' ).prop( 'selected' ) ) {
		$( '.more-responsive' ).hide();
	} else {
		$( '.more-fixed' ).hide();
	}

	$( '#jwppp-method-dimensions' ).on( 'change', function() {
		if ( $( '#fixed' ).prop( 'selected' ) ) {
			$( '.more-responsive' ).hide();
			$( '.more-fixed' ).show( 'slow' );
		} else {
			$( '.more-fixed' ).hide();
			$( '.more-responsive' ).show( 'slow' );
		}
	});


	/*Ads options*/
	if ( false == $( '#jwppp-active-ads' ).prop( 'checked' ) ) {
		$( '.ads-options' ).hide();
	} else {
		if ( $( '#jwppp-active-ads-var' ).prop( 'checked' ) ) {
			$( '.ads-options' ).hide();
			$( '.ads-options.ads-var-block' ).show( 'slow' );
		}
	}

	$( '#jwppp-active-ads' ).on( 'change', function() {
		if ( $( '#jwppp-active-ads' ).prop( 'checked' ) ) {

			/*ADS variable block*/
			if ( $( '#jwppp-active-ads-var' ).prop( 'checked' ) ) {
				$( '.ads-options' ).hide();
				$( '.ads-options.ads-var-block' ).show( 'slow' );
			} else {
				$( '.ads-options' ).show( 'slow' );
				$( '.ads-options.ads-var-block' ).hide();
				$( '.ads-options.ads-var-block.activation' ).show( 'slow' );

				if ( false == $( '#jwppp-active-bidding' ).prop( 'checked' ) ) {
					$( '.ads-options.bidding' ).hide();
				} else {
					if ( 'jwp' !== $( '#jwppp-mediation' ).val() && 'jwpdfp' !== $( '#jwppp-mediation' ).val() ) {
						$( '.ads-options.bidding.floor-price' ).hide();
					}
				}

			}

			/*Bidding*/
			if ( false == $( '#jwppp-active-bidding' ).prop( 'checked' ) ) {
				$( '.ads-options.bidding' ).hide();
			} else {
				if ( 'jwp' !== $( '#jwppp-mediation' ).val() && 'jwpdfp' !== $( '#jwppp-mediation' ).val() ) {
					$( '.ads-options.bidding.floor-price' ).hide();
				}
			}

		} else {
			$( '.ads-options' ).hide();
		}

	});

	/*ADS variable block*/
	$( '#jwppp-active-ads-var' ).on( 'change', function() {
		if ( $( this ).prop( 'checked' ) ) {
			$( '.ads-options' ).hide();
			$( '.ads-options.ads-var-block' ).show( 'slow' );
		} else {
			$( '.ads-options' ).show( 'slow' );
			$( '.ads-options.ads-var-block' ).hide();
			$( '.ads-options.ads-var-block.activation' ).show( 'slow' );

			if ( false == $( '#jwppp-active-bidding' ).prop( 'checked' ) ) {
				$( '.ads-options.bidding' ).hide();
			} else {
				if ( 'jwp' !== $( '#jwppp-mediation' ).val() && 'jwpdfp' !== $( '#jwppp-mediation' ).val() ) {
					$( '.ads-options.bidding.floor-price' ).hide();
				}
			}

		}
	});

	/*ADS Tags*/
	$( document ).on( 'click', '.add-tag-hover', function() {
		var number = $( 'li #jwppp-ads-tag' ).length + 1;
		var data = {
			'action': 'add_ads_tag',
			'hidden-nonce-add-tag': addTag.nonce,
			'number': number
		};
		$.post( ajaxurl, data, function( response ) {
			$( '.ads-options.tag ul' ).append( response );
			$( '.hidden-total-tags' ).val( number );
		});
	});

	$( document ).on( 'click', '.remove-tag-hover', function() {
		number = $( 'li #jwppp-ads-tag' ).length;
		$( this ).closest( 'li' ).remove();
		$( '.hidden-total-tags' ).val( number );

	});

	/*Bidding*/
	if ( false == $( '#jwppp-active-bidding' ).prop( 'checked' ) ) {
		$( '.ads-options.bidding' ).hide();
	}

	$( '#jwppp-active-bidding' ).on( 'change', function() {
		if ( $( '#jwppp-active-bidding' ).prop( 'checked' ) ) {
			$( '.ads-options.bidding' ).show( 'slow' );

			if ( 'jwp' !== $( '#jwppp-mediation' ).val() && 'jwpdfp' !== $( '#jwppp-mediation' ).val() ) {
				$( '.ads-options.bidding.floor-price' ).hide();
			}

		} else {
			$( '.ads-options.bidding' ).hide();
		}

	});

	/*Mediation*/
	if ( 'jwp' !== $( '#jwppp-mediation' ).val() && 'jwpdfp' !== $( '#jwppp-mediation' ).val() ) {
		$( '.ads-options.bidding.floor-price' ).hide();
	}

	$( '#jwppp-mediation' ).on( 'change', function() {
		if ( $( '.jwp' ).prop( 'selected' ) || $( '.jwpdfp' ).prop( 'selected' ) ) {
			$( '.ads-options.bidding.floor-price' ).show( 'slow' );
		} else {
			$( '.ads-options.bidding.floor-price' ).hide();
		}

	});


	/*Color field for subtitles*/
	$( '.jwppp-color-field' ).wpColorPicker();

});
