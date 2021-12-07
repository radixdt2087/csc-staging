{*** This file is similar to "overrides/views/products/components/product_images.tpl" ***}
<!--product_label_images hook override in addon sd_youtube-->

{if ($runtime.controller == "products" && $runtime.mode == "quick_view")}
    {$quick_view = 1}
{else}
    {$quick_view = 0}
{/if}

{include file="addons/sd_youtube/views/products/components/youtube_product_images.tpl" product_labels_view=true}
