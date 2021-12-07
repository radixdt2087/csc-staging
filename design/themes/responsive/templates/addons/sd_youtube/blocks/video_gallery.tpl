{** block-description:video_gallery **}
{$gallery_video_limit = $block.properties.video_limit|default:6}

{if $items}
    {$videos = $items}
{else}
    {$videos = $gallery_video_limit|fn_sd_youtube_get_products_videos:$runtime.company_id}
{/if}

{if $videos}
    {$main_video = $videos|array_rand:1}
    {$th_size = $thumbnails_size|default:150}
    {$show_player_controls = 0}

    {if $addons.sd_youtube.show_player_controls == "Y"}
        {$show_player_controls = 1}
    {/if}

    <div class="sd-video-gallery-block ty-product-img" style="{if $block.properties.play_video_block == "N"}display: none;{/if}">
        <iframe id="youtube_gallery_block"
            class="sd-youtube-video sd-max-full-width js-youtube-video"
            src="https://www.youtube-nocookie.com/embed/{$videos.$main_video}?rel=0&iv_load_policy=3&controls={$show_player_controls}"
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
                        {include file="addons/sd_youtube/common/image.tpl"
                            images=$video_ico_arr
                            show_detailed_link=false
                        }
                    </a>
                </div>
            {/foreach}
        </div>
    {/if}

    <div class="ty-right sd-video-gallery-block__link">
        <a href="{fn_url("youtube_gallery.view")}">{__("see_all")}</a>
    </div>
{/if}
