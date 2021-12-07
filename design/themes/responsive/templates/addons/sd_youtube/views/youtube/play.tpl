{if $addons.sd_youtube.show_player_controls == "N"}
    {$player_controls = "&amp;controls=0"}
{/if}

<div class="sd-stores-popup-container cm-central-dialog" id="youtube_play_{$youtube_link}">
    <div class="sd-stores-popup clearfix">
        <iframe class="sd-youtube-video sd-max-full-width js-youtube-video"
            src="https://www.youtube-nocookie.com/embed/{$youtube_link}?rel=0&amp;enablejsapi=1{$player_controls}"
            width="1280"
            height="720"
            frameborder="0"
            allowfullscreen
        ></iframe>
    </div>
<!--youtube_play_{$youtube_link}--></div>
