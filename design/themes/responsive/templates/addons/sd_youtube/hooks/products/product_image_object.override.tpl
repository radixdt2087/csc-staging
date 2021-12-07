{if $runtime.controller !== "categories"}
    {if !empty($product.youtube_link)}
    <!--this file override in addon sd_youtube-->
        {script src="js/addons/sd_youtube/func.js"}
        {if $video == true && $hide_youtube_content != true}
            {if $product.product_id|fn_get_product_details_view == "blocks/product_templates/bigpicture_template.tpl"}
                {if !empty($addons.sd_youtube.big_picture_page_video_width)}
                    {$image_width = "`$addons.sd_youtube.big_picture_page_video_width`px"}
                {else}
                    {$image_width = "100%"}
                {/if}
            {else}
                {$image_width = "100%"}
            {/if}
            <div style="width: {$image_width}" class="sd-aspect-ratio-wrapper">
                <div class="sd-aspect-ratio">
                    <iframe
                        class="sd-max-full-width"
                        {strip}
                        src="https://www.youtube-nocookie.com/embed/
                            {$product.youtube_link}?rel=0&amp;
                            {if $addons.sd_youtube.show_player_controls == "N"}controls=0{/if}
                            {if $addons.sd_youtube.autoplay == "Y" && $product.replace_main_image == "Y"}&amp;autoplay=1&amp;mute=1{/if}
                            &amp;enablejsapi=1;"
                        {/strip}
                        frameborder="0"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        {elseif $show_video_ico && $hide_youtube_content != true && !$var.variant_id}
            <div id="video_iframe_{$product.product_id}" data-product="{$product.product_id}">
                <img {if $obj_id && !$no_ids}id="det_img_{$obj_id}"{/if}
                    class="ty-pict sd-video-pict {$valign} {$class} {if $lazy_load}lazyOwl{/if} {if $generate_image}ty-spinner{/if} cm-image"
                    {if $generate_image}data-ca-image-path="{$image_data.image_path}"{/if}
                    {if $lazy_load}data-{/if}src="http://img.youtube.com/vi/{$product.youtube_link}/0.jpg"
                    {if $image_onclick}onclick="{$image_onclick}"{/if}
                    {$image_additional_attrs|render_tag_attrs nofilter}
                />
            </div>
        {else}
            {if $video_ico == true}
                <img {if $obj_id && !$no_ids}id="det_img_{$obj_id}"{/if}
                    class="ty-pict {$valign} {$class} {if $lazy_load}lazyOwl{/if} {if $generate_image}ty-spinner{/if} cm-image"
                    {if $generate_image}data-ca-image-path="{$image_data.image_path}"{/if}
                    {if $lazy_load}data-{/if}src="{if $generate_image}{$images_dir}/icons/spacer.gif{else}{$image_data.image_path}{/if}"
                    {if $image_onclick}onclick="{$image_onclick}"{/if}
                    {$image_additional_attrs|render_tag_attrs nofilter}
                />
            {else}
                <img {if $obj_id && !$no_ids}id="det_img_{$obj_id}"{/if}
                    class="ty-pict sd-youtube-object-fit {$valign} {$class} {if $lazy_load}lazyOwl{/if} {if $generate_image}ty-spinner{/if} cm-image"
                    {if $generate_image}data-ca-image-path="{$image_data.image_path}"{/if}
                    {if $lazy_load}data-{/if}src="{if $generate_image}{$images_dir}/icons/spacer.gif{else}{$image_data.image_path}{/if}"
                    {if $image_onclick}onclick="{$image_onclick}"{/if}
                    {$image_additional_attrs|render_tag_attrs nofilter}
                    {if $image_height}
                        height="{$image_height}"
                        style="height: {$image_height}px;"
                    {/if}
                />
            {/if}
        {/if}
    {/if}
{/if}
