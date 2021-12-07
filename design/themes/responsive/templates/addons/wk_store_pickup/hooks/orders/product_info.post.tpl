{if $product.extra.wk_store_id && $product.extra.wk_store_pickup_info}
    <div class="ty-orders-detail__table-store_pickup_point"><b>{__("wk_store_pickup_points")}</b>:&nbsp;
    <span class="wk_show_store_info">
        <a class="cm-dialog-opener cm-dialog-auto-size" data-ca-target-id="wk_single_store_info{$key}"> {$product.extra.wk_store_pickup_info.title}<a/></span>
    </div>
    {* {include file="addons/wk_store_pickup/views/wk_store_pickup/components/store_info.tpl" wk_store_info=$product.extra.wk_store_pickup_info content_id=$key} *}
{/if}