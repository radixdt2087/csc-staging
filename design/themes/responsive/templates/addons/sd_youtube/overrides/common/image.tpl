<!--this file override in addon sd_youtube-->
{strip}
    {if $capture_image}
        {capture "image"}
    {/if}

    {if !$obj_id}
        {$obj_id = rand()}
    {/if}

    {$image_data = $images|fn_image_to_display:$image_width:$image_height}
    {$generate_image = $image_data.generate_image && !$external}
    {$show_no_image = $show_no_image|default:true}
    {$image_additional_attrs = $image_additional_attrs|default:[]}
    {$image_link_additional_attrs = $image_link_additional_attrs|default:[]}

    {if $image_data}
        {$image_additional_attrs["alt"] = $image_data.alt}
        {$image_additional_attrs["title"] = $image_data.alt}
        {$image_link_additional_attrs["title"] = $images.detailed.alt}
    {/if}

    {hook name="common:image"}
        {if $show_detailed_link}
            <a id="det_img_link_{$obj_id}"
                class="{$link_class} {if $image_data.detailed_image_path}{if $video}js-detach-image-zoom{/if} cm-previewer ty-previewer{/if}"
                {if $image_data.detailed_image_path && $image_id}data-ca-image-id="{$image_id}"{/if}
                data-ca-image-width="{$images.detailed.image_x}"
                data-ca-image-height="{$images.detailed.image_y}"
                {if $image_data.detailed_image_path}
                    href="{$image_data.detailed_image_path}"
                    {$image_link_additional_attrs|render_tag_attrs nofilter}
                {/if}
            >
        {/if}

        {if $image_data.image_path}
            {if !empty($product.product_option) || $runtime.controller == "index"}
                <img {if $obj_id && !$no_ids}id="det_img_{$obj_id}"{/if}
                    class="{if $product.youtube_link}sd-youtube-pict {/if}ty-pict sd-youtube-object-fit {$valign} {$class}{if $lazy_load} lazyOwl{/if}{if $generate_image} ty-spinner{/if}"
                    {if $image_onclick}onclick="{$image_onclick}"{/if}
                    {if $generate_image}data-ca-image-path="{$image_data.image_path}"{/if}
                    {if $lazy_load}data-{/if}src="{if $generate_image}{$images_dir}/icons/spacer.gif{else}{$image_data.image_path}{/if}"
                    {$image_additional_attrs|render_tag_attrs nofilter}
                    {if $image_height}
                        height="{$image_height}"
                        style="height: {$image_height}px;"
                    {/if}
                />
            {else}
                {hook name="products:product_image_object"}
                    <img {if $obj_id && !$no_ids}id="det_img_{$obj_id}"{/if}
                        class="{if $product.youtube_link}sd-youtube-pict {/if}ty-pict sd-youtube-object-fit {$valign} {$class}{if $lazy_load} lazyOwl{/if}{if $generate_image} ty-spinner{/if}"
                        {if $generate_image}data-ca-image-path="{$image_data.image_path}"{/if}
                        {if $lazy_load}data-{/if}src="{if $generate_image}{$images_dir}/icons/spacer.gif{else}{$image_data.image_path}{/if}"
                        {if $image_onclick}onclick="{$image_onclick}"{/if}
                        {$image_additional_attrs|render_tag_attrs nofilter}
                        {if $image_height}
                            height="{$image_height}"
                            style="height: {$image_height}px;"
                        {/if}
                    />

                    {if $show_detailed_link}
                        <svg class="ty-pict__container" aria-hidden="true" width="{$image_data.width}" height="{$image_data.height}" viewBox="0 0 {$image_data.width} {$image_data.height}" style="max-height: 100%; max-width: 100%; position: absolute; top: 0; left: 50%; transform: translateX(-50%); z-index: -1;">
                            <rect fill="transparent" width="{$image_data.width}" height="{$image_data.height}"></rect>
                        </svg>
                    {/if}
                {/hook}
            {/if}
        {else}
            <span class="ty-no-image"
                style="min-width: {$image_width|default:$image_height}px;
                       min-height: {$image_height|default:$image_width}px;"
            >
                <i class="ty-no-image__icon ty-icon-image" title="{__("no_image")}"></i>
            </span>
        {/if}

        {if $show_detailed_link}
            {if $images.detailed_id}
                <span class="ty-previewer__icon hidden-phone"></span>
            {/if}
            </a>
        {/if}
    {/hook}

    {if $capture_image}
        {/capture}
        {capture name="icon_image_path"}
            {$image_data.image_path}
        {/capture}
        {capture name="detailed_image_path"}
            {$image_data.detailed_image_path}
        {/capture}
    {/if}
{/strip}
