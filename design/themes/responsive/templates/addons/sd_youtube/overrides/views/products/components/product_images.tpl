<!--this file is overridden in sd_youtube addon-->

{$th_size = $thumbnails_size|default:35}

{if $product.main_pair.icon || $product.main_pair.detailed}
    {$image_pair_var = $product.main_pair}
{elseif $product.option_image_pairs}
    {$image_pair_var = $product.option_image_pairs|reset}
{/if}

{if $image_pair_var.image_id}
    {$image_id = $image_pair_var.image_id}
{else}
    {$image_id = $image_pair_var.detailed_id}
{/if}

{if !$preview_id}
    {$preview_id = $product.product_id|uniqid}
{/if}

{include "addons/sd_youtube/views/products/components/youtube_product_images.tpl"}

{include "common/previewer.tpl"}
{script src="js/tygh/product_image_gallery.js"}

{hook name="products:product_images"}{/hook}
