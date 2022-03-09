/**
 * Main admin js file
 * @author ilGhera
 * @package jw-player-for-vip/js
 * @since 2.1.0
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


    /**
     * The list of ad partners already used by the publisher
     *
     * @return array
     */
    var jwpppUsedPartners = function() {

        var usedPartners = [];
        $('.ads-options.ad-partners ul li .jwppp-ad-partner').each(function(){
            
            usedPartners.push( $(this).val() );

        })

        return usedPartners;

    }


    /**
     * Bidding options to display based on the partner selected
     *
     * @param int el the target val to check.
     * @param int n  the number of partner in page.
     *
     * @return void
     */
    var jwpppPartnerFields = function( el, n ) {

        if ( 'Rubicon' == el ) {
            $( '.ads-options.bidding.partner-id-' + n ).hide();
            $( '.ads-options.bidding.site-id-' + n ).show('show');
            $( '.ads-options.bidding.zone-id-' + n ).show('show');
        } else {
            $( '.ads-options.bidding.partner-id-' + n ).show('slow');
            $( '.ads-options.bidding.site-id-' + n ).hide();
            $( '.ads-options.bidding.zone-id-' + n ).hide();
        }

        if ( 'AppNexus' == el ) {
            $( '.ads-options.bidding.inv-code-' + n ).show('slow');
            $( '.ads-options.bidding.member-id-' + n ).show('slow');
            $( '.ads-options.bidding.publisher-id-' + n ).show('slow');
        } else {
            $( '.ads-options.bidding.inv-code-' + n ).hide();
            $( '.ads-options.bidding.member-id-' + n ).hide();
            $( '.ads-options.bidding.publisher-id-' + n ).hide();
        }

        if ( 'OpenX' == el ) {
            $( '.ads-options.bidding.del-domain-' + n ).show('slow');
        } else {
            $( '.ads-options.bidding.del-domain-' + n ).hide();
        }

    }


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
	if ( ! $( '.jwppp-active-share .tzCheckBox' ).hasClass( 'checked' ) ) {
		$( '.share-options' ).hide();
	}

	$( '.jwppp-active-share .tzCheckBox' ).on( 'click', function() {

		if ( $( this ).hasClass( 'checked' ) ) {
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
	if ( ! $( '.jwppp-active-ads .tzCheckBox' ).hasClass( 'checked' ) ) {
		$( '.ads-options' ).hide();
	} else {
		if ( $( '.ads-var-block .tzCheckBox' ).hasClass( 'checked' ) ) {
			$( '.ads-options' ).hide();
			$( '.ads-options.ads-var-block' ).show( 'slow' );
		}
	}

	$( '.jwppp-active-ads .tzCheckBox' ).on( 'click', function() {
		if ( $(this).hasClass( 'checked' ) ) {

			/*ADS variable block*/
			if ( $( '.ads-var-block .tzCheckBox' ).hasClass( 'checked' ) ) {
				$( '.ads-options' ).hide();
				$( '.ads-options.ads-var-block' ).show( 'slow' );
			} else {
				$( '.ads-options' ).show( 'slow' );
				$( '.ads-options.ads-var-block' ).hide();
				$( '.ads-options.ads-var-block.activation' ).show( 'slow' );

				if ( ! $( '.jwppp-active-bidding .tzCheckBox' ).hasClass( 'checked' ) ) {
					$( '.ads-options.bidding' ).hide();
				} else {
					if ( 'jwp' !== $( '#jwppp-mediation' ).val() && 'jwpdfp' !== $( '#jwppp-mediation' ).val() ) {
						$( '.ads-options.bidding.floor-price' ).hide();
					}
				}

			}

			/*Bidding*/
			if ( $( '.jwppp-active-bidding .tzCheckBox' ).hasClass( 'checked' ) ) {
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
	$( '.ads-var-block .tzCheckBox' ).on( 'click', function() {
		if ( $( this ).hasClass( 'checked' ) ) {
			$( '.ads-options' ).hide();
			$( '.ads-options.ads-var-block' ).show( 'slow' );
		} else {
			$( '.ads-options' ).show( 'slow' );
			$( '.ads-options.ads-var-block' ).hide();
			$( '.ads-options.ads-var-block.activation' ).show( 'slow' );

			if ( ! $( '.jwppp-active-bidding .tzCheckBox' ).hasClass( 'checked' ) ) {
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
        if ( ! $(this).hasClass('partner') ) {
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
        }
	});

	$( document ).on( 'click', '.remove-tag-hover', function() {
        if ( ! $(this).hasClass('partner') ) {
            number = $( 'li #jwppp-ads-tag' ).length;
            $( this ).closest( 'li' ).remove();
            $( '.hidden-total-tags' ).val( number );
        }

	});

	/*Bidding*/
	if ( ! $( '.jwppp-active-bidding .tzCheckBox' ).hasClass( 'checked' ) ) {

		$( '.ads-options.bidding' ).hide();

	} else {

        $('.ads-options.ad-partners ul li').each(function(){

            var number  = $(this).attr('data-number');
            var partner = $('.jwppp-ad-partner', this ).val();

            jwpppPartnerFields( partner, number );

            $('.jwppp-ad-partner', this).on('change', function(){

                jwpppPartnerFields( $(this).val(), number );

            })

        })

        /*Add a partner*/
        $( document ).on( 'click', '.add-tag-hover.partner', function() {
            var number = $( 'li.single-partner' ).length + 1;
            var data = {
                'action': 'add_ad_partner',
                'hidden-nonce-add-partner': addPartner.nonce,
                'number': number,
                'used-partners': jwpppUsedPartners
            };
            $.post( ajaxurl, data, function( response ) {
                
                $( '.ads-options.ad-partners ul' ).append( response );
                $( '.hidden-total-partners' ).val( number );
                
                jwpppPartnerFields( null, number );

                $('#jwppp-ad-partner-' + number).on('change', function(){

                    jwpppPartnerFields( $(this).val(), number );

                })
            });
        });

        $( document ).on( 'click', '.remove-tag-hover.partner', function() {
            var number;
            $( this ).closest( 'li' ).remove();
            number = $( 'li.single-partner' ).length;
            $( '.hidden-total-partners' ).val( number );

        });
        
    }     

	$( '.jwppp-active-bidding .tzCheckBox' ).on( 'click', function() {
		if ( $( this ).hasClass( 'checked' ) ) {
			$( '.ads-options.bidding' ).show( 'slow' );

            $('.ads-options.ad-partners ul li').each(function(){

                var number  = $(this).attr('data-number');
                var partner = $('.jwppp-ad-partner', this ).val();

                jwpppPartnerFields( partner, number );

            })


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

	if ( 'dfp' !== $( '#jwppp-mediation' ).val() ) {
		$( '.ads-options.bidding.range-price' ).hide();
	}

	$( '#jwppp-mediation' ).on( 'change', function() {
		if ( $( '.jwp' ).prop( 'selected' ) || $( '.jwpdfp' ).prop( 'selected' ) ) {
			$( '.ads-options.bidding.floor-price' ).show( 'slow' );
		} else {
			$( '.ads-options.bidding.floor-price' ).hide();
		}

		if ( $( '.dfp' ).prop( 'selected' ) ) {
			$( '.ads-options.bidding.range-price' ).show( 'slow' );
		} else {
			$( '.ads-options.bidding.range-price' ).hide();
		}

	});

	/*GDPR*/
	if ( ! $( '.bidding.gdpr .tzCheckBox' ).hasClass( 'checked' ) ) {
		$( '.ads-options.gdpr .gdpr' ).hide();
    }
    
	$( '.bidding.gdpr .tzCheckBox' ).on( 'click', function() {
		if ( $( this ).hasClass( 'checked' ) ) {
            $( '.ads-options .gdpr' ).show('slow');
        } else {
            $( '.ads-options .gdpr' ).hide();
        }
    })

	/*CCPA*/
	if ( ! $( '.bidding.ccpa .tzCheckBox' ).hasClass( 'checked' ) ) {
		$( '.ads-options.ccpa .ccpa' ).hide();
    }
    
	$( '.bidding.ccpa .tzCheckBox' ).on( 'click', function() {
		if ( $( this ).hasClass( 'checked' ) ) {
            $( '.ads-options .ccpa' ).show('slow');
        } else {
            $( '.ads-options .ccpa' ).hide();
        }
    })

	/*Color field for subtitles*/
	$( '.jwppp-color-field' ).wpColorPicker();

});
