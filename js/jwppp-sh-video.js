/**
 * Tools available with every self-hosted video
 * @param  {int} number the video's number in the post/ page
 */
var sh_video_script = function(number) {
    jQuery(function($){
        //SHOW SOURCES LABELS IF THEY ARE MORE THAN TWO
        if($('#_jwppp-sources-number-' + number).val() > 1) {
            $('.source-label-' + number).show('slow');
        } else {
            $('.source-label-' + number).hide();
        }

        $('.more-options-' + number).click(function() {
            $('.jwppp-more-options-' + number).toggle('fast');
            // $('.more-options').text('Less options');
            $(this).text(function(i, text) {
                return text == 'More options' ? 'Less options' : 'More options';
            });
        });
        
        //MEDIA TYPE
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

        //POSTER IMAGE PREVIW
        $(document).on('change', '#_jwppp-video-image-' + number, function(){
            if($(this).val()) {
                $('.poster-image-preview.' + number).remove();
                $('.poster-image-container-' + number).append('<img class="poster-image-preview ' + number + '" src="' + $(this).val() + '" style="display: none;">');
                $('.poster-image-preview.' + number).fadeIn(300);
            } else {
                $('.poster-image-preview.' + number).fadeOut(300, function(){
                    $(this).remove();
                });
            }
        })
        
        //CHAPTERS
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

            //IF SUBTITLES ARE ACTIVATED, SELECT MANUAL/ LOAD IS SHOWN
            if($('#_jwppp-chapters-subtitles-' + number).val() == 'subtitles') {
                $('#_jwppp-subtitles-method-' + number).show();
                $('label[for="_jwppp-subtitles-write-default-' + number + '"]').show();
                $('label[for="_jwppp-subtitles-load-default-' + number + '"]').show();
            }

            //IF SUBTITLE METHOD IS SET TO "LOAD", THE ELEMENTS CHANGE
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

        //HIDE/ SHOW CONTENTS BASED ON THE MAIN FLAG
        $('#_jwppp-add-chapters-' + number).on('change',function() {
            if($('#_jwppp-add-chapters-' + number).prop('checked')) {
                $('span.add-chapters.' + number).text('Add');
                $('#_jwppp-chapters-subtitles-' + number).show();
                $('#_jwppp-chapters-number-' + number).show();
                

                //IF SUBTITLES ARE ACTIVATED, SELECT MANUAL/ LOAD IS SHOWN
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

                // $('li#video-' + number + '-subtitle').hide();
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


        //SET DIFFERENT PLACEHOLDER FOR DIFFERENTS ELEMENT TYPES 
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


        //CHANGE CONTENTS BASED ON THE TOOL SELECTED
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


        //CHANGE ELEMENT TYPE BASED ON SUBTITLES METHOS
        $('#_jwppp-subtitles-method-' + number).on('change', function(){
            if($(this).val() == 'load') {
                $('.load-subtitles-' + number).show();
                $('.chapters-subtitles-' + number).hide();
            } else {
                $('.load-subtitles-' + number).hide();
                $('.chapters-subtitles-' + number).show();
            }
        })
        
        //CHANGE THE ELEMENTS NUMBER BASE ON THE NUMBER TOOL
        $('#_jwppp-sources-number-' + number).on('change',function() {
            var n_sources          = $(this).val();
            var n_current_sources  = $('li#video-' + number + '-source').length;

            //SHOW LABELs IF ALTERNATIVES SOURCE EXIST
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

        //CHAPTER NUMBER
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
                    // placeholder();
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

