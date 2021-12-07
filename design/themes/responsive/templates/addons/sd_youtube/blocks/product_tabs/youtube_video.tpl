{** block-description:youtube_video **}
{$videos = $product.product_id|fn_sd_youtube_get_product_videos:$runtime.company_id}

{if $videos}
    {$th_size = $thumbnails_size|default:150}
    {$main_video = $videos|array_rand:1}
    {$show_player_controls = 0}

    {if $addons.sd_youtube.show_player_controls == "Y"}
        {$show_player_controls = 1}
    {/if}

    <div class="sd-video-gallery-block">
        <iframe id="youtube_gallery_block"
            class="sd-youtube-video sd-max-full-width js-youtube-video"
            src="https://www.youtube-nocookie.com/embed/{$videos.$main_video}?rel=0&controls={$show_player_controls}"
            width="100%"
            frameborder="0"
            allowfullscreen
        ></iframe>
    </div>

    {if $videos|count > 1}
        <div class="owl-carousel js-youtube-carousel sd-youtube-carousel">
            {foreach $videos as $product_id => $video_image}
                {$video_ico_arr = $video_image|fn_sd_youtube_get_video_ico:$product_id}

                <div class="sd-youtube-carousel__item">
                    <a onclick="gallery_show_video('{$video_image}', {$show_player_controls})"
                        style="width: {$th_size}px;"
                    >
                        {include file="common/image.tpl"
                            images=$video_ico_arr
                            show_detailed_link=false
                        }
                    </a>
                </div>
            {/foreach}
        </div>
    {/if}
{/if}
