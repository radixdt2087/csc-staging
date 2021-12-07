<!--this file is overridden in sd_youtube addon-->
{$product_detail_view_url = "products.view?product_id={$product.product_id}"}

{capture name="product_detail_view_url"}
    {** Sets product detail view link *}
    {hook name="products:product_detail_view_url"}
        {$product_detail_view_url}
    {/hook}
{/capture}

{$product_detail_view_url = $smarty.capture.product_detail_view_url|trim}

{capture name="main_icon"}
    <a class="sd-product-list-link{if $video_gallery} sd-product-list-link--youtube{/if}"
        {if $video_gallery}
            href="{fn_url("youtube_gallery.view&product_id={$product.product_id}")}"
        {else}
            href="{fn_url($product_detail_view_url)}"
        {/if}
    >
        {include "common/image.tpl"
            obj_id=$obj_id_prefix
            images=$product.main_pair
            image_width=$settings.Thumbnails.product_lists_thumbnail_width
            image_height=$settings.Thumbnails.product_lists_thumbnail_height
        }
    </a>
{/capture}

{if $product.image_pairs && $show_gallery}
    <div class="ty-center-block">
        <div id="icons_{$obj_id_prefix}"
            class="ty-thumbs-wrapper owl-carousel cm-image-gallery"
            data-ca-items-count="1"
            data-ca-items-responsive="true"
        >
            {if $product.main_pair}
                <div class="cm-gallery-item cm-item-gallery">
                    {$smarty.capture.main_icon nofilter}
                </div>
            {/if}
            {foreach $product.image_pairs as $image_pair}
                {if $image_pair}
                    <div class="cm-gallery-item cm-item-gallery">
                        <a href="{fn_url($product_detail_view_url)}">
                            {include "common/image.tpl"
                                no_ids=true
                                images=$image_pair
                                image_width=$settings.Thumbnails.product_lists_thumbnail_width
                                image_height=$settings.Thumbnails.product_lists_thumbnail_height
                                lazy_load=true
                            }
                        </a>
                    </div>
                {/if}
            {/foreach}
        </div>
    </div>
{else}
    {$smarty.capture.main_icon nofilter}
{/if}
