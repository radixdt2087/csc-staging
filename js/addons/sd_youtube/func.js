function height_youtube_video(class_name) {
    class_name.each(function() {
        var youtube_height = $(this).width() / 16 * 9;
        $(this).height(youtube_height);
    });
}

(function(_, $) {
    $.ceEvent('on', 'ce.tab.show', function () {
        height_youtube_video($('.js-youtube-video'));
    });

    // Detailed product: Accordion
    $(document).on( 'click', '#youtube_video', function() {
        if ($(this).closest('.ty-accordion')) {
            height_youtube_video($(this).closest('.ty-accordion').find($('.js-youtube-video')));
        }
    });

    $('#youtube_gallery_block').load(function() {
        height_youtube_video($('.js-youtube-video'));
    });

    $(window).resize(function() {
        height_youtube_video($('.js-youtube-video'));
    })

    $.ceEvent('on', 'ce.commoninit', function(context) {
        $('.js-youtube-carousel').owlCarousel({
            items : 3,
            itemsMobile : [479, 2]
        });
    });

    $.ceEvent('on', 'ce.dialogshow', function(d) {
        if ($(d).find($('.js-youtube-video')).length) {
            height_youtube_video($(d).find($('.js-youtube-video')));
        }

        $(d).on('dialogclose', function(event) {
            toggleVideo(d, 'hide');
        });
    });

    $.ceEvent('on', 'ce.commoninit', function($context) {
        const $youtubeBlock = $('.js-detach-image-zoom', $context);

        if (!$youtubeBlock.length) {
            return;
        }

        $youtubeBlock.closest('.ty-image-zoom__wrapper').children(':first-child').unwrap();
    });
}(Tygh, Tygh.$));

function toggleVideo(element, state) {
    var iframes = element.find("iframe");
    iframes.each(function() {
        func = state == 'hide' ? 'pauseVideo' : 'playVideo';
        this.contentWindow.postMessage('{"event":"command","func":"' + func + '","args":""}', '*');
    });
}

function gallery_show_video(video_image, show_player_controls) {
    $('#youtube_gallery_block').attr('src', 'https://www.youtube-nocookie.com/embed/' + video_image + '?rel=0&amp;controls=' + show_player_controls);
    $('.sd-video-gallery-block').show();
    height_youtube_video($('.js-youtube-video'));
}
