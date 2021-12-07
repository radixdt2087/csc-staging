{if $affiliate_merchant}
    {if $auth.user_id > 0}
        {if $show_name}
            {if $hide_links}<strong>{else}<a href="{"products.view&product_id=`$product.product_id`&id=`$product.affiliate_product`"|fn_url}" class="product-title" title="{$product.product|strip_tags}" {live_edit name="product:product:{$product.product_id}" phrase=$product.product}>{/if}{$product.product nofilter}{if $hide_links}</strong>{else}</a>{/if}
        {elseif $show_trunc_name}
            {if $hide_links}<strong>{else}<a href="{"products.view&product_id=`$product.product_id`&id=`$product.affiliate_product`"|fn_url}" class="product-title" title="{$product.product|strip_tags}" {live_edit name="product:product:{$product.product_id}" phrase=$product.product}>{/if}{$product.product|truncate:44:"...":true nofilter}{if $hide_links}</strong>{else}</a>{/if}
        {/if}
    {else}
        {if $show_name}
            {if $hide_links}<strong>{else}<a href="javascript:void(0)" class="product-title product-affiliate-redirect" title="{$product.product|strip_tags}" {live_edit name="product:product:{$product.product_id}" phrase=$product.product}>{/if}{$product.product nofilter}{if $hide_links}</strong>{else}</a>{/if}
        {elseif $show_trunc_name}
            {if $hide_links}<strong>{else}<a href="javascript:void(0)" class="product-title product-affiliate-redirect" title="{$product.product|strip_tags}" {live_edit name="product:product:{$product.product_id}" phrase=$product.product}>{/if}{$product.product|truncate:44:"...":true nofilter}{if $hide_links}</strong>{else}</a>{/if}
        {/if}
    {/if}
{else}
    {if $show_name}
        {if $hide_links}<strong>{else}<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product-title" title="{$product.product|strip_tags}" {live_edit name="product:product:{$product.product_id}" phrase=$product.product}>{/if}{$product.product nofilter}{if $hide_links}</strong>{else}</a>{/if}
    {elseif $show_trunc_name}
        {if $hide_links}<strong>{else}<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product-title" title="{$product.product|strip_tags}" {live_edit name="product:product:{$product.product_id}" phrase=$product.product}>{/if}{$product.product|truncate:44:"...":true nofilter}{if $hide_links}</strong>{else}</a>{/if}
    {/if}
{/if}