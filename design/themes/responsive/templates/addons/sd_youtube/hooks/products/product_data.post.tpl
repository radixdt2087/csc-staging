{capture name="product_labels_{$obj_prefix}{$obj_id}"}
    {$capture_name = "product_labels_{$obj_prefix}{$obj_id}"}
    {$smarty.capture.$capture_name nofilter}

    {$runtime_flag = false}

    {if $runtime.controller == "categories" || $runtime.controller == "companies" || $runtime.mode == "search"}
        {$runtime_flag=true}
    {/if}

    {if $product.youtube_link && $runtime_flag && $addons.sd_youtube.show_video_on_category_page == "Y"}
        <div class="sd-youtube-play-label cm-reload-{$obj_prefix}{$obj_id}"
            id="youtube_play_label_update_{$obj_prefix}{$obj_id}">

            <div class="sd-youtube-play-label__value">
                {include file="addons/sd_youtube/common/youtube_play.tpl"
                    name=$product.product
                    youtube_link=$product.youtube_link
                }
            </div>

        <!--youtube_play_label_update_{$obj_prefix}{$obj_id}--></div>
    {/if}
{/capture}
