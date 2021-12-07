<div class="table-responsive-wrapper">
    <table class="table table-middle table-responsive" width="100%">
    <thead class="cm-first-sibling">
    <tr>
        <th width="5%">{__("wk_store_name")}</th>
        <th width="20%">{__("wk_store_stock")}</th>
        <th width="15%">&nbsp;</th>
    </tr>
    </thead>
        <tbody>
            {foreach from=$product_data.pickup_stores item="prev_data" key="_key" name="pickup_stores"}
            <tr class="cm-row-item">
                <td width="35%" data-th="{__("wk_store_name")}">
                <input type="hidden" name="product_data[pickup_stores][{$_key}][product_id]" value="{$product_data.product_id}">
                <select id="store_ids{$_key}" name="product_data[pickup_stores][{$_key}][store_id]" class="span3">
                    <option value="">{__("none")}</option>
                    {foreach from=$company_stores item="wk_store_name" key="store_id"}
                        <option value="{$store_id}"{if $prev_data.store_id == $store_id}selected{/if}>{$wk_store_name}</option>
                    {/foreach}
                </select>
                </td>

                <td width="5%" data-th="{__("wk_store_stock")}">
                    <input type="text" name="product_data[pickup_stores][{$_key}][stock]" value="{$prev_data.stock}" class="input-micro cm-value-integer" />
                    </td>
                <td width="15%" class="nowrap {$no_hide_input_if_shared_product} right">
                    {include file="buttons/clone_delete.tpl" dummy_href=true microformats="cm-delete-row" no_confirm=true}
                </td>
            </tr>
            {/foreach}
            {math equation="x+1" x=$_key|default:0 assign="new_key"}
            <tr class="{cycle values="table-row , " reset=1}{$no_hide_input_if_shared_product}" id="box_add_product_pickup_store">
                <input type="hidden" name="product_data[pickup_stores][{$new_key}][product_id]" value="{$product_data.product_id}">
                <td width="35%" data-th="{__("wk_store_name")}">
                    <select id="store_ids{$_key}" name="product_data[pickup_stores][{$new_key}][store_id]" class="span3">
                        <option value="">{__("none")}</option>
                        {foreach from=$company_stores item="wk_store_name" key="store_id"}
                        <option value="{$store_id}">{$wk_store_name}</option>
                        {/foreach}
                    </select>
                </td>
                <td width="5%" data-th="{__("wk_store_stock")}">
                    <input type="text" name="product_data[pickup_stores][{$new_key}][stock]" value="" class="input-micro cm-value-integer" /></td>
                <td width="15%" class="right">
                    {include file="buttons/multiple_buttons.tpl" item_id="add_product_pickup_store"}
                </td>
            </tr>
        </tbody>
    </table>
</div>