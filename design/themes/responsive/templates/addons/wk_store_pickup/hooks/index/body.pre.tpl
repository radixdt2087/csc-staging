{if $order_info && $runtime.controller == 'orders' && $runtime.mode =='details'}
{foreach from=$order_info.products item=product key=key name=name}
    {if $product.extra.wk_store_id && $product.extra.wk_store_pickup_info}
    {include file="addons/wk_store_pickup/views/wk_store_pickup/components/store_info.tpl" wk_store_info=$product.extra.wk_store_pickup_info content_id=$key}
    {/if}
{/foreach}
{/if}
