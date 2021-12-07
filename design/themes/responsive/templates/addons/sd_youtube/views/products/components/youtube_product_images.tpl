{if $product_labels_view}
    <div class="sd-product-labels-images-wrapper">
{/if}

{if $product_labels_view && $sd_labels_on_product_page_position == "over_product_image"}
    {include file="addons/sd_labels/components/product_labels.tpl" sd_labels=$sd_labels product=$product}
{/if}

<div class="ty-product-img cm-preview-wrapper sd-product-video" id="product_images_{$preview_id}">
    {if $image_pair_var.pair_id == $product.youtube_link && ($details_page || $quick_view)}
        {include file="addons/sd_youtube/common/image.tpl"
            obj_id="{$preview_id}_{$image_id}"
            images=$image_pair_var
            link_class="cm-image-previewer"
            image_width=$image_width
            image_height=$image_height
            video="true"
            image_id="preview[product_images_{$preview_id}]"
        }
    {else}
        {include file="addons/sd_youtube/common/image.tpl"
            obj_id="{$preview_id}_{$image_id}"
            images=$image_pair_var
            link_class="cm-image-previewer"
            image_width=$image_width
            image_height=$image_height
            image_id="preview[product_images_{$preview_id}]"
        }
    {/if}

    {foreach $product.image_pairs as $image_pair}
        {if $image_pair && $image_pair.pair_id !== $image_pair_var.pair_id}
            {if $image_pair.image_id}
                {$img_id = $image_pair.image_id}
            {else}
                {$img_id = $image_pair.detailed_id}
            {/if}
            {if $image_pair.pair_id == $product.youtube_link && ($details_page || $quick_view)}
                {include file="addons/sd_youtube/common/image.tpl"
                    images=$image_pair
                    link_class="cm-image-previewer hidden"
                    obj_id="{$preview_id}_{$img_id}"
                    image_width=$image_width
                    image_height=$image_height
                    video="true"
                    image_id="preview[product_images_{$preview_id}]"
                }
            {else}
                {include file="addons/sd_youtube/common/image.tpl"
                    images=$image_pair
                    link_class="cm-image-previewer hidden"
                    obj_id="{$preview_id}_{$img_id}"
                    image_width=$image_width
                    image_height=$image_height
                    image_id="preview[product_images_{$preview_id}]"
                }
            {/if}
        {/if}
    {/foreach}
</div>

{if $product_labels_view}
    </div>
{/if}

{if $product.image_pairs}
    {if $settings.Appearance.thumbnails_gallery == "Y"}
        {$image_counter = 0}
        <input type="hidden" name="no_cache" value="1" />
        {strip}
        <div class="ty-center ty-product-bigpicture-thumbnails_gallery">
            <div class="cm-image-gallery-wrapper ty-thumbnails_gallery ty-inline-block">
                {strip}
                <div class="ty-product-thumbnails owl-carousel cm-image-gallery" id="images_preview_{$preview_id}">
                    {if $image_pair_var}
                        <div class="cm-item-gallery ty-float-left">
                            <a data-ca-gallery-large-id="det_img_link_{$preview_id}_{$image_id}"
                               class="cm-gallery-item cm-thumbnails-mini active ty-product-thumbnails__item"
                               style="width: {$th_size}px; height: {$th_size}px"
                               data-ca-image-order="{$image_counter}"
                               data-ca-parent="#product_images_{$preview_id}"
                            >
                                {if $image_pair_var.detailed.object_type != "product"}
                                    {$image_class = "sd-youtube-pict"}
                                {else}
                                    {$image_class = false}
                                {/if}
                                {include file="addons/sd_youtube/common/image.tpl"
                                    images=$image_pair_var
                                    image_width=$th_size
                                    image_height=$th_size
                                    show_detailed_link=false
                                    obj_id="{$preview_id}_{$image_id}_mini"
                                    class=$image_class
                                }
                            </a>
                        </div>
                    {/if}
                    {if $product.image_pairs}
                        {foreach $product.image_pairs as $image_pair}
                            {$image_counter = $image_counter + 1}
                            {if $image_pair}
                                <div class="cm-item-gallery ty-float-left">
                                    {if $image_pair.detailed.object_type != "product"}
                                        {$image_class = "sd-youtube-pict"}
                                    {else}
                                        {$image_class = false}
                                    {/if}
                                    {if $image_pair.image_id}
                                        {$img_id = $image_pair.image_id}
                                    {else}
                                        {$img_id = $image_pair.detailed_id}
                                    {/if}
                                    <a class="cm-gallery-item cm-thumbnails-mini ty-product-thumbnails__item"
                                        data-ca-gallery-large-id="det_img_link_{$preview_id}_{$img_id}"
                                        style="width: {$th_size}px; height: {$th_size}px"
                                        data-ca-image-order="{$image_counter}"
                                        data-ca-parent="#product_images_{$preview_id}"
                                    >
                                        {include file="addons/sd_youtube/common/image.tpl"
                                            images=$image_pair
                                            image_width=$th_size
                                            image_height=$th_size
                                            show_detailed_link=false
                                            obj_id="{$preview_id}_{$img_id}_mini"
                                            class=$image_class
                                        }
                                    </a>
                                </div>
                            {/if}
                        {/foreach}
                    {/if}
                </div>
                {/strip}
            </div>
        </div>
        {/strip}
    {else}
        {$image_counter = 0}
        <div class="ty-product-thumbnails ty-center cm-image-gallery" id="images_preview_{$preview_id}">
            {strip}
                {if $image_pair_var}
                    <a class="cm-thumbnails-mini active ty-product-thumbnails__item"
                        data-ca-gallery-large-id="det_img_link_{$preview_id}_{$image_id}"
                        data-ca-image-order="{$image_counter}"
                        data-ca-parent="#product_images_{$preview_id}"
                        style="width: {$th_size}px; height: {$th_size}px" 
                    >
                        {if $image_pair_var.detailed.object_type != "product"}
                            {$image_class = "sd-youtube-pict"}
                        {else}
                            {$image_class = false}
                        {/if}
                        {include file="common/image.tpl"
                            images=$image_pair_var
                            image_width=$th_size
                            image_height=$th_size
                            show_detailed_link=false
                            obj_id="{$preview_id}_{$image_id}_mini"
                            class=$image_class
                        }
                    </a>
                {/if}

                {if $product.image_pairs}
                    {foreach $product.image_pairs as $image_pair}
                        {$image_counter = $image_counter + 1}
                        {if $image_pair}
                            {if $image_pair.detailed.object_type != "product"}
                                {$image_class = "sd-youtube-pict"}
                            {else}
                                {$image_class = false}
                            {/if}
                            {if $image_pair.image_id == 0}
                                {$img_id = $image_pair.detailed_id}
                            {else}
                                {$img_id = $image_pair.image_id}
                            {/if}
                            <a class="cm-thumbnails-mini ty-product-thumbnails__item"
                                data-ca-gallery-large-id="det_img_link_{$preview_id}_{$img_id}"
                                data-ca-image-order="{$image_counter}"
                                data-ca-parent="#product_images_{$preview_id}"
                                style="width: {$th_size}px; height: {$th_size}px"
                            >
                                {include file="common/image.tpl"
                                    images=$image_pair
                                    image_width=$th_size
                                    image_height=$th_size
                                    show_detailed_link=false
                                    obj_id="{$preview_id}_{$img_id}_mini"
                                    class=$image_class
                                }
                            </a>
                        {/if}
                    {/foreach}
                {/if}
            {/strip}
        </div>
    {/if}
{/if}
