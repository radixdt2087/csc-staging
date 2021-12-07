<div class="control-group">
    <label class="control-label" for="amazon_variant_{$id}">{__("addon.sd_amazon_products.amazon_variant")}:</label>
    <div class="controls">
    	<input type="text" id="amazon_variant_{$id}" name="option_data[variants][{$num}][amazon_variant]" value="{$vr.amazon_variant|default:""}" class="input-large" />
    </div>
</div>