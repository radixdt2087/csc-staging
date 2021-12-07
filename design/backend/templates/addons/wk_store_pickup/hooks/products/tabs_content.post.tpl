<div id="content_wk_store_pickup" class="hidden">
    <div class="control-group">
        <label class="control-label" for="wk_enable_store_pickup">{__("wk_enable_store_pickup")}{include file="common/tooltip.tpl" tooltip=__("wk_enable_store_pickup_help") text=__("wk_enable_store_pickup_help")}</label>
        <div class="controls">
            <input type="hidden" value="N" name="product_data[enable_store_pickup]">
            <input type="checkbox" value="Y" name="product_data[enable_store_pickup]" id="wk_enable_store_pickup" {if $product_data.enable_store_pickup == 'Y'}checked{/if}/>
        </div>
    </div>
    <div class="control-group {if $product_data.enable_store_pickup == 'N'}hidden{/if}" id="wk_allow_store_pickup_only" >
        <label class="control-label" for="store_pickup_only">{__("wk_allow_store_pickup_only")}{include file="common/tooltip.tpl" tooltip=__("wk_allow_store_pickup_only_help") text=__("wk_allow_store_pickup_only_help")}</label>
        <div class="controls">
            <input type="hidden" value="N" name="product_data[store_pickup_only]">
            <input type="checkbox" value="Y" name="product_data[store_pickup_only]" id="store_pickup_only" {if $product_data.store_pickup_only == 'Y'}checked{/if}/>
        </div>
    </div>
    <div id="wk_product_store_selector"  class="{if $product_data.enable_store_pickup == 'N'}hidden{/if}">
        {include file="addons/wk_store_pickup/views/wk_store_pickup/components/product_store_selector.tpl"}
    </div>
<!--content_wk_store_pickup--></div>
<script>
$(document).ready(function(){
    $('#wk_enable_store_pickup').on('change',function(){
        $('#wk_product_store_selector').toggle();
        $('#wk_allow_store_pickup_only').toggle();
    });
});
</script>