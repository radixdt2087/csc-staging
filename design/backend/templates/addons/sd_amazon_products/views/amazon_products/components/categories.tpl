<div class="control-group" id="box_ebay_category">
    <label class="control-label" for="elm_ebay_category">{__("sd_amz_category")}:</label>
    <div class="controls">
    <select {if !$ebay_categories}disabled="disabled"{/if} id="elm_ebay_{$data_id}" name="template_data[{$data_id}]"{if $data_id == 'category'} onchange="Tygh.$.ceAjax('request', fn_url('ebay.get_category_features?data_id={$data_id}&site_id={$template_data.site_id}&template_id={$template_data.template_id}&category_id=' + this.value), {$ldelim}result_ids: 'box_ebay_cf_{$data_id}', caching: true{$rdelim});"{/if}>
        <option value="">{__('select')}</option>
        {foreach from=$ebay_categories item="item"}
            <option {if $selected_ebay_category == $item.category_id}selected="selected"{/if} value="{$item.category_id}">{$item.full_name}</option>
        {/foreach}
    </select>
    </div>
<!--box_ebay_category--></div>