{if $oi.extra.wk_store_id && $oi.extra.wk_store_pickup_info}
    <div class="ty-orders-detail__table-store_pickup_point"><b>{__("wk_store_pickup_points")}</b>:&nbsp;
        <span class="wk_show_store_info">
            <a class="cm-dialog-opener cm-dialog-auto-size" data-ca-target-id="wk_single_store_info{$key}"> {$oi.extra.wk_store_pickup_info.title}</a>
        </span>
        {$store_lists = Fn_Get_Product_Store_Pickup_Points_Short_info($oi.product_id, $oi.quantity)}
        {if $store_lists && !$runtime.company_id}
        <span><a class="cm-dialog-opener cm-dialog-auto-size" data-ca-target-id="wk_all_store_info{$key}"><i class="icon-pencil"></i>{__("change")}</a></span>
        <div class="hidden" id="wk_all_store_info{$key}" title="{__("wk_change_store_pickup_point")}">
        <p>
            <select name="wk_store_point[{$key}]">
                <option value="">------</option>
                {foreach from=$store_lists item=store_info key=store_id}
                    <option value="{$store_id}" {if $store_id == $oi.extra.wk_store_id}selected{/if}>{$store_info.title}</option>
                {/foreach}
            </select>
            <br/>
        </p>
        <p class="center">
            {include file="buttons/button.tpl" but_name="dispatch[wk_store_pickup.wk_change_store.`$key`]" but_text=__("change") but_meta="btn btn-primary cm-ajax cm-force-ajax cm-dialog-closer" but_role="submit"}
        </p>
        </div>
        {/if}
    </div>
    {include file="addons/wk_store_pickup/views/wk_store_pickup/components/store_info.tpl" wk_store_info=$oi.extra.wk_store_pickup_info content_id=$key}
{/if}