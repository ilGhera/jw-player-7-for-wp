/**
 * Single video script
 * @author ilGhera
 * @package jw-player-7-for-wp/js
 * @version 1.6.0
 * @param  {int} number  the video's number in the post/ page
 * @param  {int} post_id the post/ page id
 */
var jwppp_single_video = function(number, post_id) {
    jQuery(function($){

        var $get_url = $('#_jwppp-video-url-' + number).val(); 
        var $url = $get_url != 1 ? $get_url : '';
        var $ext = $url.split('.').pop();
        var $arr = ['xml', 'feed', 'php', 'rss'];
        var wrap = $('.jwppp-' + number + ' .jwppp-input-wrap');

        var data = {
            'action': 'current-video-details',
            'media_id': $url
        }

        /*Ajax - get video informations*/
        $.post(ajaxurl, data, function(response){            

            /*Video details*/
            var title = $('#_jwppp-video-title-' + number).val();

            if(response) {
    
                /*Video from the dasboard*/
            
                var info = JSON.parse(response);

                if(info.videos) {

                    /*It is a playlist*/
                    $('.jwppp-video-details-' + number).html(
                        (title ? '<span>Title</span>: ' + title + '<br>' : '') +
                        (info.description ? '<span>Description</span>: ' + info.description + '<br>' : '') +
                        '<span>Items</span>: ' + info.videos.total + '<br>'
                    );

                    /*Display the playlist carousel option*/
                    $('.playlist-carousel-container.' + number).css({
                        'display': 'inline-block'
                    })          

                } else {

                    /*It is a single video*/
                    var duration = null; 
                    if(info.duration > 0) {
                        duration = new Date(info.duration * 1000).toISOString().substr(11, 8);
                    }

                    $('.jwppp-video-details-' + number).html(
                        (title ? '<span>Title</span>: ' + title + '<br>' : '') +
                        (info.description ? '<span>Description</span>: ' + info.description + '<br>' : '') +
                        (duration ? '<span>Duration</span>: ' + duration + '<br>' : '') +
                        (info.tags ? '<span>Tags</span>: ' + info.tags + '<br>' : '')
                    );
                } 

            } else {

                /*Self-hosted video*/
                var description = $('#_jwppp-video-description-' + number).val();

                $('.jwppp-video-details-' + number).html(
                    (title ? '<span>Title</span>: ' + title + '<br>' : '') +
                    (description ? '<span>Description</span>: ' + description + '<br>' : '')
                );

            }

        })

        /*Video toggles*/
        $(document).on('click', '.jwppp-video-toggles.' + number + ' li', function(){
            $('.jwppp-video-toggles.' + number + ' li').removeClass('active');
            $(this).addClass('active');

            var video_type = $(this).data('video-type');

            $('.jwppp-toggle-content.' + number).removeClass('active');
            $('.jwppp-toggle-content.' + number + '.' + video_type).addClass('active');

            /*Delete the input field value on toggle change*/
            $('input#_jwppp-video-url-' + number).val('');
            $('#_jwppp-video-title-' + number + '.jwppp-title').val('');
            $('#_jwppp-video-title-' + number).val('');

            /*Delete preview image*/
            $('.poster-image-preview.' + number).remove();

            /*Video details*/
            $('.jwppp-video-details-' + number).html('');

            /*With cloud player and self hosted sources, all the tools are shown*/
            if(video_type === 'add-url') {

                /*Video title*/
                $('#_jwppp-video-title-' + number).val('');

                $('.jwppp-single-option-' + number).show();

                /*Hide carousel option*/
                $('.playlist-carousel-container.' + number).hide();

            } else {

                $('.jwppp-single-option-' + number).hide();
                $('.jwppp-single-option-' + number + '.cloud-option').show();
                $('.playlist-carousel-container.' + number).hide();
 
            }

        })

        /*Changwe playlist-how-to*/
        var tot = $('.jwppp-input-wrap:visible').length;
        if(tot > 1) {
            $('.playlist-how-to').show('slow');
            
            var string = [];
            $('.order:visible').each(function(i, el) {
                string.push($(el).html());  
            })
        } else {
            $('.playlist-how-to').hide();
        }

        $('.jwppp-more-options-' + number).hide();

        if($.inArray($ext, $arr)>-1) {
            $('.more-options-' + number).hide();
        };

        /*Media url change*/
        $(document).on('change','#_jwppp-video-url-' + number, function() {

            var $url = $(this).val();
            
            /*Get the extension for old type playlist*/
            var $ext = $url.split('.').pop();
            var $arr = ['xml', 'feed', 'php', 'rss'];
            if($.inArray($ext, $arr)>-1) {
                $('.more-options-' + number).hide();
                $('.jwppp-more-options-' + number).hide();
            } else {
                $('.more-options-' + number).show();    
            }
        });

        /*Video url field length animation*/ 
        $(document).on('focus', '#_jwppp-video-url-' + number, function(){
            $(this).animate({
                'width': '507px'
            })
            $('.jwppp-video-details-' + number).hide();
        })
        $(document).on('focusout', '#_jwppp-video-url-' + number, function(){
    
            /*Not animate if more options are open*/
            if($('.more-options-' + number).text() == 'Show options') {
                $(this).animate({
                    'width': '256px'
                })
                setTimeout(function(){
                    $('.jwppp-video-details-' + number).show();                
                }, 300)

            } 
        })

        /*More options button*/
        $('.more-options-' + number).click(function() {
            $('.jwppp-more-options-' + number).toggle('fast');

            $(this).text(function(i, text) {
                return text == 'Show options' ? 'Hide options' : 'Show options';
            });

            var method = $('.jwppp-video-toggles.' + number + ' li.active');

            if($(method).data('video-type') == 'add-url' || method.length == 0) {

                setTimeout(function(){
                    var url_field = $('#_jwppp-video-url-' + number + '.jwppp-url');
                    var n_sources = $('#_jwppp-sources-number-' + number).val();

                    if($('.more-options-' + number).text() == 'Less options') {

                        $('.jwppp-video-details-' + number).hide();
                        $(url_field).animate({'width': '507px'})
                        if(n_sources >= 2) {
                            $('#_jwppp-' + number +'-main-source-label').show();                    
                        }

                    } else {

                        $(url_field).animate({'width': '256px'})
                        $('#_jwppp-' + number +'-main-source-label').hide();

                         /*Self-hosted video*/
                        var title = $('.jwppp-more-options-' + number + ' #_jwppp-video-title-' + number).val();
                        var description = $('#_jwppp-video-description-' + number).val();

                        $('.jwppp-video-details-' + number).html(
                            (title ? '<span>Title</span>: ' + title + '<br>' : '') +
                            (description ? '<span>Description</span>: ' + description + '<br>' : '')
                        );

                        setTimeout(function(){
                            $('.jwppp-video-details-' + number).show();
                        }, 300)

                    }
                }, 400)

            }

        });
        
        /*Media type*/
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

        /*Poster image preview*/
        $(document).on('change', '#_jwppp-video-image-' + number, function(){
            if($(this).val()) {
                $('.poster-image-preview.' + number).remove();

                /*Small class if the player is self-hosted*/
                var small = $(wrap).hasClass('self') ? ' small' : '';
                
                $('.jwppp-' + number + ' .jwppp-input-wrap').prepend('<img class="poster-image-preview ' + number + small + '" src="' + $(this).val() + '" style="display: none;">');
                $('.poster-image-preview.' + number).fadeIn(300);
            } else {
                $('.poster-image-preview.' + number).fadeOut(300, function(){
                    $(this).remove();
                });
            }
        })
        
        /*Chapters*/
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

            /*If subtitles are activated, manual/ load option is shown*/
            if($('#_jwppp-chapters-subtitles-' + number).val() == 'subtitles') {
                $('#_jwppp-subtitles-method-' + number).show();
                $('label[for="_jwppp-subtitles-write-default-' + number + '"]').show();
                $('label[for="_jwppp-subtitles-load-default-' + number + '"]').show();
            }

            /*If subtitle method is set to "load", the elements change*/
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

        /*Hide/ show contents based on the main flag*/
        $('#_jwppp-add-chapters-' + number).on('change',function() {
            if($('#_jwppp-add-chapters-' + number).prop('checked')) {
                $('span.add-chapters.' + number).text('Add');
                $('#_jwppp-chapters-subtitles-' + number).show();
                $('#_jwppp-chapters-number-' + number).show();
                

                /*If subtitles are activated, manual/ load option is shown*/
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

                $('li#video-' + number + '-subtitle').each(function(i,el) {
                    var numb = $(el).data('number');
                    if(numb <= n_chapters) {
                        $(el).show();
                    }
                })

            } else {
                $('span.add-chapters.' + number).text('Add Chapters, Subtitles or Preview Thumbnails');
                $('#_jwppp-chapters-subtitles-' + number).hide();
                $('#_jwppp-chapters-number-' + number).hide();
                $('li#video-' + number + '-chapter').hide();
                $('#_jwppp-subtitles-method-' + number).hide();
                $('.load-subtitles-' + number).hide();

            }
        });

        /*Set different placeholder for differents element types*/ 
        function placeholder() {
            var selector = $('#_jwppp-chapters-subtitles-' + number);
            if($(selector).val() == 'thumbnails') {
                var placeholder = 'Thumbnail url';                  
            } else if($(selector).val() == 'subtitles') {
                var placeholder = 'Subtitle';
            } else {
                var placeholder = 'Chapter title';
            }
            $('ul.chapters-subtitles-' + number + ' li input[type=text]').attr('placeholder', placeholder);
        }

        /*Change contents based on the tool selected*/
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

        /*Change element type based on subtitles methos*/
        $('#_jwppp-subtitles-method-' + number).on('change', function(){
            if($(this).val() == 'load') {
                $('.load-subtitles-' + number).show();
                $('.chapters-subtitles-' + number).hide();
            } else {
                $('.load-subtitles-' + number).hide();
                $('.chapters-subtitles-' + number).show();
            }
        })
        
        /*Change the elements number base on the number tool*/
        $('#_jwppp-sources-number-' + number).on('change',function() {
            var n_sources          = $(this).val();
            var n_current_sources  = $('li#video-' + number + '-source').length;

            /*Show labels if alternatives source exist*/
            if(n_sources > 1) {
                $('.source-label-' + number).show('slow');
            } else {
                $('.source-label-' + number).hide();
            }

            if(n_sources > n_current_sources) {
                for(n=n_current_sources+1; n == n_sources; n++) {
                    var element = '<li id="video-' + number + '-source" data-number="' + n + '">' + 
                                  '<input type="text" style="margin-right:1rem;" name="_jwppp-' + number + '-source-' + n + '-url" value="" placeholder="Source url" size="60" />' +
                                  '<input type="text" name="_jwppp-' + number + '-source-' + n + '-label" class="source-label-' + number + '" style="margin-right:1rem;" value="" placeholder="Label (HD, 720p, 360p)" size="30" />' +
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

        /*Chapter number*/
        $('#_jwppp-chapters-number-' + number).on('change',function() {

            var n_chapters          = $(this).val();
            var n_current           = $('li#video-' + number + '-chapter').length;
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
                            'placeholder="Subtitles file url (VTT, SRT, DFXP)"' +
                            'size="60" />' +
                            '<input type="text" name="_jwppp-' + number + '-subtitle-' + n + '-label" style="margin-right:1rem;" value="" placeholder="Label (EN, IT, FR )" size="30" />';
                           '</li>';
                    $('ul.load-subtitles-' + number).append(element);
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
    })
}